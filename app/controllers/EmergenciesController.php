<?php

use Carbon\Carbon;

class EmergenciesController extends ApiController {

	private $user;

	public function __construct() {
		$this->user = Auth::user();
	}

	public function index() {
		$size = Input::has('size') && Input::get('size') < 50 ? Input::get('size') : 25;

		if (Input::has('last'))
			$emergencies = $this->user->emergencies()->where('id', '<', Input::get('last'))->orWhere('receiver', $this->user->id)->where('id', '<', Input::get('last'));
		else
			$emergencies = $this->user->emergencies()->orWhere('receiver', $this->user->id);

		$emergencies = $emergencies->take($size)->orderBy('id', 'desc')->get();


		$response['emergencies'] = $emergencies->transform(function($emergency) {
			$sender = User::with('phone')->find($emergency->sender);
			return [
				'id' => $emergency->id,
				'sender' => $emergency->sender,
				'created_at' => $emergency->created_at,
				'number' => $emergency->number,
				'phone_number' => $emergency->sender_phone,
				'delivered_at' => $emergency->delivered_at,
				'status' => $emergency->status,
				'via_sms' => $emergency->via_sms,
				'complained_at' => $emergency->complained_at,
				'failed' => $emergency->failed
			];
		});

		$response['tries'] = $this->user->urgent_calls;

		return $this->respond($response);
	}

	public function resetCounts() {
		$this->user->devices->each(function ($device) {
			$state = new StateSender($device->auth_token);
			$state->resetCounts('emergencies');
			$state->send();
		});
		return $this->respondNoContent();
	}

	public function store() {
		$limit = 2;
		if (!Input::has('number'))
			return $this->respondInsufficientPrivileges('No car number');

		$number = Input::get('number');

		$car = Car::where('number', $number)->first();

		if (!$car) return $this->respondNotFound('Car not found');

		$user = $car->user;

		if (!$user) return $this->respondNotFound('User not found');

		if ($user == $this->user) return $this->respondInsufficientPrivileges('Cant send to yourself');

		$emergency = new Emergency([
			'sender' => $this->user->id,
			'receiver' => $user->id,
			'number' => $car->number,
			'created_at' => Carbon::now(),
			'status' => 'отправлено'
		]);

		$emergency->sender_phone = $this->user->phone->number;

		//FIXME later add custom error to apicontroller
		if ($this->user->urgent_calls == 0)
			return Response::json([
				'error' => [
					'message' => 'Вы исчерпали лимит срочных вызовов                   ('.$limit.' в день)',
					'status_code' => 1003
				]
			], 403);


		$this->user->urgent_calls--;

		$this->user->emergencies()->save($emergency);

		$this->user->save();

		Queue::later(30, 'smsSender', $emergency);
		Queue::later(60, 'smsChecker', $emergency->id);

		$response['emergencies'] = [
			'id' => $emergency->id,
			'sender' => $emergency->sender,
			'created_at' => $emergency->created_at,
			'number' => $emergency->number,
			'phone_number' => $emergency->sender_phone,
			'delivered_at' => $emergency->delivered_at,
			'status' => $emergency->status,
			'via_sms' => false,
			'complained_at' => $emergency->complained_at,
			'failed' => $emergency->failed
		];
		$response['tries'] = $this->user->urgent_calls;
		$urgentCalls = $this->user->urgent_calls;
		$emergency->phone_number = (int) $this->user->phone->number;

		$emergency->receiverU->devices->each(function($device) use($emergency, $urgentCalls) {
			$stateSender = new StateSender($device->auth_token);
			$stateSender->setEmergencyAdded($emergency, $urgentCalls);
			$stateSender->send();
		});

		// Probably wiser to send state to self as well, and show only by response from state

//		$emergency->getMembersTokens()->each(function ($token) use($emergency, $urgentCalls) {
//			$stateSender = new StateSender($token->auth_token);
//			$stateSender->setEmergencyAdded($emergency, $this->user->urgent_calls);
//			$stateSender->send();
//		});

		return $this->respond($response);
	}

	public function deliver($id) {
		$emergency = Emergency::find($id);

		if (!$emergency) return $this->respondNotFound('Срочный вызов не найден');

		$emergency->delivered_at = Carbon::now();

		if ($emergency->save()) {
			$emergency->getMembersTokens()->each(function ($token) use($emergency) {
				$stateSender = new StateSender($token->auth_token);
				$stateSender->setEmergencyAsDelivered($emergency->id, $emergency->delivered_at);
				$stateSender->send();
			});

			return $this->respondNoContent();
		}

		return $this->respondServerError();
	}

	public function destroy($id) {
		$emergency = $this->user->emergencies()->find($id);

		if (!$emergency)
			return $this->respondNotFound('Emergency call not found');

		if ($emergency->delete())
			return $this->respondNoContent();

		return $this->respondNoContent();
	}

}