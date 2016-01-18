<?php namespace Helpers\Transformers;

use CarChat;
use EmergencyChat;
use EmergencyMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;
use User;

class ChatsCollectionTransformer {

	public function transformChat(CarChat $chat, $userId) {
		$resp = [
			'chat_id' => $chat->id,
			'owner_id' => $chat->owner_id,
			'last_message' => $chat->lastMessage ? $this->transformMessage($chat->lastMessage) : null,
			'timestamp' => $chat->updated_at,
			'isCarChat' => true
		];

		$chat->isOwner($userId) ? $resp['car'] = $this->transformUser($chat->receiver, $chat->receiverCar) : $resp['user'] = $this->transformUser($chat->owner);

		if ($chat->isUnread($userId)) $resp['unread'] = true;

		return $resp;
	}

	public function transformChats(Collection $chats, $userId) {
		return $chats->transform(function ($chat) use ($userId) {
			return $this->transformChat($chat, $userId);
		});
	}

	public function transformMessage(\CarMessage $message, $redundantly = false) {
		$content = [];

		$resp = [
			'message_id' => $message->id,
			'chat_id' => $message->chat_id,
			'timestamp' => $message->created_at,
			'delivered_at' => $message->delivered_at,
			'viewed_at' => $message->viewed_at
		];

		if (!is_null($message->text))
			$content['text'] = $message->text;

		if (!is_null($message->long))
			$content['geo'] = [
				'lat' => $message->lat,
				'long' => $message->long,
				'location' => $message->location
			];

		if (!is_null($message->image_id)) {
			$domain = 'http' . (Request::server('HTTPS') ? '' : 's') . '://' . Request::server('HTTP_HOST');

			$content['image'] = [
				'id' => $message->attachmentImage->id,
				'thumb' => "{$domain}/api/carchats/{$message->chat_id}/attachments/{$message->attachmentImage->id}",
				'origin' => "{$domain}/api/carchats/{$message->chat_id}/attachments/{$message->attachmentImage->id}/origin",
				'width' => $message->attachmentImage->width,
				'height' => $message->attachmentImage->height
			];
		}

			if (!is_null($message->car_id)) {
				$content['car'] = [
					'id' => $message->attachmentCar->id,
					'mark' => $message->attachmentCar->mark,
					'model' => $message->attachmentCar->model,
					'year' => $message->attachmentCar->year,
					'color' => $message->attachmentCar->color,
					'vehicle_type' => $message->attachmentCar->vehicle_type,
					'body_type' => $message->attachmentCar->body_type
				];
				if(!is_null($message->car_number)) {
					$content['car']['number'] = $message->car_number;
				}
			}
			if ($redundantly) {

				if (!$message->via_car) {
					$user = User::find($message->user_id);

					$resp['user'] = [
							'id' => $user->id,
							'name' => $user->name,
							'img' => [
								'middle' => $user->img_middle
							]
					];
				} else {
					$chat = CarChat::find($message->chat_id);

					$resp['car'] = [
						'id' => $message->user_car_id,
						'number' => $chat->number
					];
				}

			} else {

				if ($message->via_car) {
					$resp['car_id'] = $message->user_car_id;
				} else {
					$resp['user_id'] = $message->user_id;
				}
			}

		$resp['content'] = $content;


		if ($message->isUnread()) $resp['unread'] = true;

//		dd($resp);
		return $resp;
	}

	public function transformMessages(Collection $messages, $redundantly = false) {
		return $messages->transform(function($message) use($redundantly){
			return $this->transformMessage($message, $redundantly);
		});
	}

	public function transformUser(\User $user, $car = false) {
		if ($car) {
			$resp['id'] = $car->id;
			$resp['number'] = $car->number;
		} else {
			$resp = [
				'id' => $user->id,
				'name' => $user->name,
				'img' => [
					'middle' => $user->img_middle
				]
			];
		}
		return $resp;
	}

}