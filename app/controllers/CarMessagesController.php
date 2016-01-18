<?php

use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
use Helpers\Transformers\ChatsCollectionTransformer;

class CarMessagesController extends ApiController {

	public $user;
	protected $collectionTransformer;

	function __construct(ChatsCollectionTransformer $transformer) {
		$this->user = Auth::user();
		$this->collectionTransformer = $transformer;
	}

	public function index($id) {
		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound('chat.not-found');

		$messages = $chat->messages();

		$messages->orderBy('id', 'desc');

		if (Input::has('last_id'))
			$messages->byLastId(Input::get('last_id'));

		if (Input::has('from_id'))
			$messages->newById(Input::get('from_id'));

		if (Input::has('size')) {
			$size = Input::get('size');
			$messages->limit($size >= 40 ? 25 : $size);
		}

		if ($chat->isOwner($this->user->id)) {
			$messages->whereNull('deleted_by_owner');
		} else {
			$messages->whereNull('deleted_by_receiver');
		}

		// If user is chat owner then we need to show car for him
		// if not we need to display actual user

		return $this->respond(['messages' => $this->collectionTransformer->transformMessages($messages->get())]);
	}

	public function store($id) {

		$chat = CarChat::find($id);

		if ($chat->isOwner($this->user->id)) {
			if (!$chat->receiver->enable_carchats) return $this->respondInsufficientPrivileges('chat.carchats-not-accepting');
		} else {
			if (!$chat->owner->enable_carchats) return $this->respondInsufficientPrivileges('chat.carchats-not-accepting');
		}
		if ($chat->owner->sex == $chat->receiver->sex) return $this->respondInsufficientPrivileges('chat.same-sex');

//		if (!$chat->isOwner($this->user->id)) {
//			if ($this->user->enable_carchats) return $this->respondInsufficientPrivileges('chat.carchats-not-accepting');
//		}

		if (!$chat) return $this->respondNotFound('chat.not-found');

		/*if (!$chat->isOwner($this->user->id) && $chat->byNumber == true) {
			$chat->byNumber = false;
			$chat->save();
			$chat->getMembersTokens()->each(function($token) use($chat) {
				$state = new StateSender($token->auth_token, true);
				$state->setCarAsUser($chat, $this->user);
				$state->send();
			});
		}*/

		$content = Input::get('content');

		$message = new CarMessage([
			'user_id' => $this->user->id,
		]);

		$message->created_at = Carbon::now();

		if (isset($content['text'])) $message->text = $content['text'];

		if (isset($content['car'])) {
			$car = $this->user->cars()->find($content['car']['id']);

			if ($car) {
				$message->car_id = $car->id;
				if ((boolean)($content['car']['car_with_number']))
					$message->car_number = $car->number;
			}
		}

		if (isset($content['geo'])) {
			$message->lat = $content['geo']['lat'];
			$message->long = $content['geo']['long'];
			$message->location = $content['geo']['location'];
		}

		if (isset($content['image_id'])) {
			$message->image_id = $content['image_id'];
		}

		if (!$chat->isOwner($this->user->id)) {
			$message->user_car_id = $chat->receiver_car_id;
			//TODO check if user still have this car&number
			$message->via_car = true;
			$chat->deleted_by_owner = false;
		} else {
			$chat->deleted_by_receiver = false;
		}

		if ($chat->messages()->save($message)) {
			$chat->last_message_id = $message->id;
			$chat->updated_at = Carbon::now();
			$chat->save();
			$chat->getMembersTokens($this->user->id)->each(function($token) use($message) {
				$state = new StateSender($token->auth_token, true);
				$state->setMessageAsAdded($this->collectionTransformer->transformMessage($message, true));
				$state->send();
			});
			return $this->respond($this->collectionTransformer->transformMessage($message));
		}

		return $this->respondServerError();
	}

	public function attach($id) {
		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound();

		if (!$chat->isMember($this->user->id)) return $this->respondInsufficientPrivileges('User is not the member of chat');

		if (!Input::hasFile('image')) return $this->respondInsufficientPrivileges('No image');

		$file = Input::file('image');

		if (!in_array($file->guessExtension(), ['png', 'gif', 'jpeg', 'jpg'])) return $this->respondInsufficientPrivileges('Unsupported file extension');

		$attachment = new MessageAttachment();
		$attachment->id = DB::select("select nextval('messages_attachments_id_seq')")[0]->nextval;
		$this->log('Init images');
		$this->log(memory_get_usage(true));
		$origin = new ImageUtil($file);
		$attachment->origin = $this->upload($attachment->id, 'origin', $origin->getImage());
		$attachment->gallery = $this->upload($attachment->id, 'gallery', $origin->resize2('gallery')->getImage());
		$origin->getImage()->destroy();
		$origin = null;
		$this->log('Images should be destroyed');
		$this->log(memory_get_usage(true));
		$this->log('Init thumbnail');
		$thumb = (new ImageUtil($file))->resize2('thumbnail');
		$attachment->thumb = $this->upload($attachment->id, 'thumb', $thumb->getImage());
		$attachment->width = $thumb->getWidth();
		$attachment->height = $thumb->getHeight();
		$thumb->getImage()->destroy();
		$thumb = null;
		$this->log('Thumbnail should be destroyed');
		$this->log(memory_get_usage(true));

		if ($chat->attachments()->save($attachment)) {
			$domain = 'http' . (Request::server('HTTPS') ? '' : 's') . '://' . Request::server('HTTP_HOST');
			return $this->respond([
				'id' => $attachment->id,
				'thumb' => $domain . '/api/carchats/'.$chat->id.'/attachments/' . $attachment->id,
				'origin' => $domain . '/api/carchats/'.$chat->id.'/attachments/'.$attachment->id.'/gallery',
				'width' => $attachment->width,
				'height' => $attachment->height
			]);
		}

		$this->log(memory_get_usage(true));
		return $this->respondServerError();
	}

	public function getAttach($id, $attachment_id, $type = 'thumb') {

		if (!in_array($type, ['thumb', 'origin', 'gallery'])) return $this->respondInsufficientPrivileges('Unknown type');

		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound('chat.not-found');

		$attachment = $chat->attachments()->find($attachment_id);

		if (!$attachment) return $this->respondNotFound('Attachment not found');

		try {
			$s3 = AWS::get('s3');
			$fileName = $attachment_id . '-' . $type;

			$image = $s3->getObject(array(
				'Bucket' => S3_PRIVATE_BUCKET_NAME,
				'Key' => $fileName
			));

			return Response::make($image['Body'], 200, array('Content-Type' => $image['ContentType']));
		} catch(S3Exception $e) {
			return $this->respondWithError('Attachment not found');
		}
	}

	public function destroy($id, $message_id) {
		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound('chat.not-found');

		$message = $chat->messages()->find($message_id);

		if (!$message) return $this->respondNotFound('Message not found');

		if ($chat->isOwner($this->user->id))
			$message->deleted_by_owner = true;
		else
			$message->deleted_by_receiver = true;

		//TODO add a removed state
		if ($message->save())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	public function deliver($id, $message_id) {
		$chat = CarChat::find($id);

		if (!$chat) return $this->respondNotFound('chat.not-found');

		$message = $chat->messages()->find($message_id);

		if (!$message) return $this->respondNotFound('Message not found');

		$message->delivered_at = Carbon::now();

		$message->save();

		$chat->getMembersTokens()->each(function ($token) use($chat, $message){
			$state = new StateSender($token->auth_token, true);
			$state->setMessageAsDelivered($chat->id, $message->id, $message->delivered_at);
			$state->send();
		});

		return $this->respondNoContent();
	}

	private function upload($id, $type, $image) {
		$s3 = AWS::get('s3');
		try {
			$fileName = $id . '-' . $type;

			$uploadedImage = $s3->putObject(array(
				'ContentType' => 'image/jpg',
				'ACL' => 'bucket-owner-read',
				'Bucket' => S3_PRIVATE_BUCKET_NAME,
				'Key' => $fileName,
				'Body' => $image->encode('jpg', IMAGE_COMPRESSING_QUALITY)
			));
			return $fileName;

		} catch(S3Exception $e) {
//			dd($e);
			return null;
		}
	}

	private function log($msg) {
		Log::debug($msg, ['upld']);
	}
}