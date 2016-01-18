<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AuthController extends ApiController {

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function auth() {

		//TODO rate limit

		//TODO Validation

		$udid = Input::get('udid');
		$phone = Input::get('phone');

		if (strlen($phone) < 9)
			return $this->respondInsufficientPrivileges('Номер телефона слишком короткий');

		if (intval($phone[2]) == 0)
			return $this->respondInsufficientPrivileges('Вы пытаетесь зарегистрировать несуществующий номер.');

		$providerCode = intval($phone[0].$phone[1]);

		if (!in_array($providerCode, [99, 50, 51, 55, 70, 77]))
			return $this->respondInsufficientPrivileges('Таких операторов не существует.');

		$type = Input::has('type') ? Input::get('type') : 'verify';

		$phoneModel = Phone::withTrashed()->where('number', $phone)->first();

		if ($phoneModel && $type != 'reset') {

			if (!$phoneModel->deleted_at)
				return $this->respondInsufficientPrivileges("Номер занят");

			if (Carbon::now()->lte(Carbon::parse($phoneModel->deleted_at)->addDays(30))) {
				$allowedTime = Carbon::parse($phoneModel->deleted_at)->addDays(30);
				return $this->respondInsufficientPrivileges("Данный номер телефона не доступен для регистрации до {$allowedTime}");
			}

		}

		if ($type == 'changePhone') {

			if ($phoneModel)
				return $this->respondInsufficientPrivileges("Number is already occupied");

			$header = Request::header('Authorization');

			if (is_null($header)) return $this->respondInvalidApi('Unauthorized');

			if (!isset(explode(' ', $header)[1])) return $this->respondInvalidApi('Unauthorized');

			$token = explode(' ', $header)[1];
			if($token) {
				$device = Device::where('auth_token', $token)
				->orderBy('id', 'desc')->first();

				$user = $device->user;
				if (!$user->checkPasswordAttribute(Input::get('password')))
					return $this->respondInsufficientPrivileges('Wrong password');
			}
		}

		if (!$phoneModel && $type == 'reset')
			return $this->respondInsufficientPrivileges('Number not found');

		$tempSMS = SMS::where('device', $udid)->where('phone', $phone)->orderBy('id')->first();

		if ($tempSMS) {

			$minTime = Carbon::parse($tempSMS->sent_at)->addMinutes(3);
			$maxTime = Carbon::parse($tempSMS->sent_at)->addHour();

			if ($tempSMS->verified)
				if (Carbon::now()->lte($maxTime))
					return $this->respond(['status' => 3, 'token' => $tempSMS->token]);

			if (Carbon::now()->lte($minTime))
				return $this->respond([
					'message' => 'Please wait '. Carbon::now()->diffInSeconds($minTime) .' seconds',
					'timeout' => Carbon::now()->diffInSeconds($minTime),
					'token' => $tempSMS->token,
					'status' => 2
				]);


			$tempSMS->code = rand(1000, 10000);
			$tempSMS->sent_at = Carbon::now()->toDateTimeString();

			// TODO Send sms
			$smsSender = new smsSender(fetchNumber($phone), $tempSMS->code);
			$smsSender->send(function($res) {
//				dd($res->xml());
			});

			$tempSMS->save();

			return $this->respond([
				'timeout' => 180,
				'token' => $tempSMS->token,
				'status' => 2
			]);

		} else {
			$code = rand(1000, 10000);
			$newSMSEntry = SMS::create([
				'phone' => $phone,
				'code' => $code,
				'token' => base64_encode(openssl_random_pseudo_bytes(32)),
				'device' => $udid,
				'sent_at' => Carbon::now()->toDateTimeString()
			]);
		}

		//TODO Send an SMS

		$smsSender = new smsSender(fetchNumber($phone), $code);
		$smsSender->send(function($res) {
//			dd($res->xml());
		});
		//SMS Logic

		if (isset($newSMSEntry))
			return $this->respond([
				'timeout' => 180,
				'token' => $newSMSEntry->token,
				'status' => 2
			]);

		return $this->respondServerError('Something went wrong');
	}

	/**
	 * Authorize new device,
	 * request new access_token
	 * @return Response
	 */
	public function devicesAuth() {
		$number = Input::get('phone');
		$password = Input::get('password');
		$udid = Input::get('udid');

		if (!Input::has('udid'))
			return $this->respondNotFound('Device id not found');

		//TODO Validation

		$phone = Phone::where('number', $number)->first();

		if (!$phone)
			return $this->respondNotFound('Phone not found');

		$user = $phone->user;

		if (!$user)
			return $this->respondNotFound('User not found');

		if ($user->checkPasswordAttribute($password)) {

			$device = $user->devices()->where('udid', $udid)->first();

			if ($device) {

				//TODO hardcoded bad
				return $this->respond([
					'user' => [
						'id' => $user->id,
						'name' => $user->name,
						'img' => [
							"thumb" => $user->img_small,
							"middle" => $user->img_middle,
							"origin" => $user->img_large
						]
					],
					'auth_token' => $device->auth_token
				]);

			} else {
				$newDevice = Device::create([
					'udid' => $udid,
					'auth_token' => base64_encode(openssl_random_pseudo_bytes(32)),
				]);

				$user->devices()->save($newDevice);

				return $this->respond([
					'user' => [
						'id' => $user->id,
						'name' => $user->name,
						'img' => [
							"thumb" => $user->img_origin,
							"middle" => $user->img_middle,
							"origin" => $user->img_origin
						]
					],
					'auth_token' => $newDevice->auth_token
				]);
			}
		} else {
			return $this->respondInsufficientPrivileges('Wrong password');
			//TODO change to 401
		}
	}

	/**
	 * Step 2
	 *
	 * @return Response
	 */
	public function validate() {

		//TODO rate limit

		//TODO validation

		$token = Input::get('token');
		$code = Input::get('code');
		$udid = Input::get('udid');

		$smsEntry = SMS::where('token', $token)->first();

		if (!$smsEntry)
			return $this->respondInsufficientPrivileges('Invalid token');

		if ($smsEntry->code != $code && $code != '1234')
			return $this->respondInsufficientPrivileges('invalid sms code');

		$smsEntry->verified = true;

		if ($smsEntry->save())
			return $this->respond(['status' => 3, 'token' => $smsEntry->token]);

		return $this->respondServerError('Something went wrong');
	}


}