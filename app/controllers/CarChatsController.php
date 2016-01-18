<?php

use Carbon\Carbon;
use Helpers\Transformers\ChatsCollectionTransformer;

class CarChatsController extends ApiController {

	public $user;
	public $size = 25;
	private $collectionTransformer;

	function __construct(ChatsCollectionTransformer $transformer) {
		$this->user = Auth::user();
		$this->collectionTransformer = $transformer;
	}

	public function index() {
//		$chats = CarChat::byUserId($this->user->id)->with(['owner', 'receiver', 'receiverCar']);

		$chats = CarChat::with(['owner', 'receiver', 'receiverCar', 'lastMessage'])->where('owner_id', $this->user->id)->where('deleted_by_owner', false);


		if ($lastId = Input::get('last_id')) {
			$chats->byLastId($lastId);
			$chats->orWhere('receiver_id', $this->user->id)->where('deleted_by_receiver', false);
			$chats->byLastId($lastId);
		} else {
			$chats->orWhere('receiver_id', $this->user->id)->where('deleted_by_receiver', false);
		}

		if (Input::has('size')) {
			$this->size = Input::get('size');
			$chats->limit($this->size);
		}

		$chats->orderBy('updated_at', 'desc');

//		dd($chats->toSql());
		return $this->respond($this->collectionTransformer->transformChats($chats->get(), $this->user->id));
	}

	public function validateNumber() {
		$car = Car::where('number', Input::get('number'))->first();

		if (!$car) return $this->respondNotFound('user.car-not-found');

		if (!$car->user) return $this->respondNotFound('user.not-found');

		if ($car->user->id == $this->user->id) return $this->respondInsufficientPrivileges('user.self-car-talk');

		if ($car->user->delete_at) return $this->respondNotFound('user.not-associated-with-car');

		if ($this->user->isBlocked($car->user->id)) return $this->respondInsufficientPrivileges('user.blocked');

		if ($this->user->sex == $car->user->sex) return $this->respondInsufficientPrivileges('chat.same-sex');

		if (!$car->user->enable_carchats) return $this->respondInsufficientPrivileges('chat.carchats-not-accepting');

		return $this->respondNoContent();
	}

	/**
	 * Create new chat
	 * @return CarChat|\Illuminate\Http\JsonResponse
	 */
	public function store() {
		$number = Input::get('number');

		$car = Car::where('number', Input::get('number'))->first();

		if (!$car) return $this->respondNotFound('user.car-not-found');

		if (!$car->user) return $this->respondNotFound('user.not-found');

		if ($car->user->id == $this->user->id) return $this->respondInsufficientPrivileges('user.self-car-talk');

		if ($car->user->delete_at) return $this->respondNotFound('user.not-associated-with-car');

		if ($this->user->isBlocked($car->user->id)) return $this->respondInsufficientPrivileges('user.blocked');

		if ($this->user->sex == $car->user->sex) return $this->respondInsufficientPrivileges('chat.same-sex');

		if (!$car->user->enable_carchats) return $this->respondInsufficientPrivileges('chat.carchats-not-accepting');

		$chat = CarChat::byNumber($car->number)->where('owner_id', $this->user->id)->first();

		//TODO if was deleted by all side remove deleted flag

		if ($chat) {
			$chat->load(['owner', 'receiver', 'receiverCar']);
			if ($chat->isOwner($this->user->id))
				$chat->deleted_by_owner = false;
			else
				$chat->deleted_by_receiver = false;

			if ($chat->save())
				return $this->respond($this->collectionTransformer->transformChat($chat, $this->user->id));
		}

		$chat = new CarChat([
			'number' => $car->number,
			'receiver_id' => $car->user_id,
			'receiver_car_id' => $car->id,
		]);

		$chat->updated_at = Carbon::now();

		if ($this->user->myCarChats()->save($chat))
			return $this->respond($this->collectionTransformer->transformChat($chat, $this->user->id));

		return $this->respondServerError();
	}

	public function update($id) {
		$chat = CarChat::find($id);
		$actions = Input::all();

		if (!$chat) return $this->respondNotFound('chat.not-found');

		if ($chat->isOwner($this->user->id)) {
			$tokens = $chat->owner->devices;
		} else {
			$tokens = $chat->receiver->devices;
		}
		$tokens->each(function ($token) use($actions, $chat) {
			$state = new StateSender($token->auth_token, true);

			if (array_key_exists('mark_as_read', $actions)) {
				$this->read($state, $chat);
				$this->updateCounts($state, $chat);
			}
			if (array_key_exists('delivered', $actions)) $this->deliver($state, $chat);
			if (array_key_exists('typing', $actions)) $this->type($state, $chat);

			$state->send();
		});

		return $this->respondNoContent();
	}

	public function destroy($id) {
		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound(trans('chat.not-found'));

		$chat->messages->each(function ($message) use($chat) {
			if ($chat->isOwner($this->user->id)) {
				$message->deleted_by_owner = true;
			} else {
				$message->deleted_by_receiver = true;
			}
			$message->save();
		});

		if ($chat->isOwner($this->user->id)) {
			$chat->deleted_by_owner = true;
		} else {
			$chat->deleted_by_receiver = true;
		}

		$chat->save();

		$this->user->devices->each(function($token) use ($chat) {
//		$chat->getMembersTokens()->each(function($token) use($chat) {
			$state = new StateSender($token->auth_token, true);
			$this->remove($state, $chat);
			$state->send();
		});

		return $this->respondNoContent();
	}

	private function read(StateSender $state, $chat) {
		$chat->messages()->whereNull('viewed_at')->where('user_id', '!=', $this->user->id)->get()->each(function ($message) {
			if (!$message->delivered_at) $message->delivered_at = Carbon::now();
			if (!$message->viewed_at) $message->viewed_at = Carbon::now();
			$message->viewed_at = Carbon::now();
			$message->save();
		});
		if ($chat->isOwner($this->user->id)) {
			$state->setCarChatAsRead($chat->id, $this->user->id);
		} else {
			$state->setCarChatAsRead($chat->id, null, $chat->receiver_car_id);
		}
	}

	private function deliver(StateSender $state, $chat) {
		$chat->messages()->whereNull('delivered_at')->where('user_id', '!=', $this->user->id)->get()->each(function($message) {
			if (!$message->delivered_at)  $message->delivered_at = Carbon::now();
//			if (!$message->viewed_at) $message->viewed_at = Carbon::now();
			$message->save();
		});
		// Set $state->setChatAsRead($chatId, $userId);
		if ($chat->isOwner($this->user->id))
			$state->setCarChatAsDelivered($chat->id, $this->user->id);
		else
			$state->setCarChatAsDelivered($chat->id, null, $chat->receiver_car_id);
	}

	private function type(StateSender $state, $chat) {
		if ($chat->isOwner($this->user->id))
			$state->setCarChatAsTyping($chat->id, $this->user->id);
		else
			$state->setCarChatAsTyping($chat->id, null, $chat->receiver_car_id);
	}

	private function remove(StateSender $state, $chat) {
		$state->updateCounts('carChats', $chat->id);
	}

	private function updateCounts(StateSender $state, $chat) {
		$state->updateCounts('carChats', $chat->id);
	}
}
