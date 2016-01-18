<?php

use Aws\S3\Exception\S3Exception as S3Exception;
use Carbon\Carbon;

class MessagesController extends ApiController {

    /**
     * Display a limited listing of messages.
     *
     * @param int $chatId Chat ID
     * @param int $size Reponse size
     * @return Response
     */
    public function getListLimited($chatId, $size) {
        return $this->getList($chatId, null, $size);
    }

    /**
     * Display a listing of messages.
     *
     * @param int $chatId Chat ID
     * @param int|null $lastShownMessageId Last shown message ID
     * @param int $size Reponse size
     * @return Response
     */
    public function getList($chatId, $lastShownMessageId = null, $size = 50) {
        if((int)$chatId > 0) {
            if(!is_null($chat = Chat::find((int)$chatId))
                && !ChatMember::whereRaw('chat_id = ? and user_id = ?', array($chatId, Auth::user()->id))
                    ->get()->isEmpty()
            ) {
                $result = array();

                $size = (int)$size > 0 ? (int)$size : 50;

                $unread = array();
                if(is_null($lastShownMessageId)) {
                    MessageUnread::whereRaw('chat_id = ? and user_id = ? and message_id not in (select b.message_id from messages_removed b where b.chat_id = chat_id and user_id = '.Auth::user()->id.')',
                        array($chat->id, Auth::user()->id))
                        ->get()
                        ->each(function ($message) use (&$unread) {
                            $unread[] = $message->message_id;
                        });
                }

                $cleared = ChatCleared::whereRaw('chat_id = ? and user_id = ?', array($chat->id, Auth::user()->id))
                    ->orderBy('message_id', 'desc')
                    ->get()
                    ->first();

                $query
                    = 'select a.* from messages a where a.chat_id = ' . $chat->id . ' and a.id not in (select b.message_id from messages_removed b where b.chat_id = a.chat_id and b.user_id = '. Auth::user()->id.')';
                if(count($unread) > 0) {
                    $query .= ' and a.id not in (' . implode(',', $unread) . ')';
                }

                if($cleared) {
                    $query .= ' and a.id > ' . $cleared->message_id;
                }

                if(!is_null($lastShownMessageId) && (int)$lastShownMessageId > 0) {
                    $query .= ' and a.id < ' . $lastShownMessageId;
                }

                $query .= ' order by a.timestamp desc limit ' . $size;

                $messages = DB::select($query);

                if(count($unread) > 0) {
                    $unreadMessages = DB::table('messages')
                        ->whereRaw('chat_id = ? and id in (' . implode(',', $unread) . ')', array($chat->id))
                        ->orderBy('timestamp', 'desc');

                    $messages = array_merge($unreadMessages->get(), $messages);
                }

                foreach($messages as $message) {
                    $data = (new Message((array)$message))->getAsArray();

                    if(in_array($message->id, $unread)) {
                        $data['unread'] = true;
                    }

                    $result['messages'][] = $data;
                }


                return $this->respond($result);
            } else {
                return $this->respondWithError('Chat doesn\'t exist');
            }
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Store a newly message.
     *
     * @return Response
     */
    public function send() {
        $chatId = Input::get('chat_id');
        $user = Auth::user();

        if(($content = Input::get('content'))
            && (int)$chatId > 0
            && !is_null($chat = Chat::find((int)$chatId))
            && $chat->hasMember($user->id)
        ) {
            foreach ($chat->getUsersIds() as $id) {
                if ($user->isBlocked($id))
                    return Response::json(['error' => [
                        'message' => 'Cant send to group with users which blocked',
                        'status_code' => 1002
                    ]], 403);
            }
            if((isset($content['text'])
                || (isset($content['image_id'])
                    && MessageAttachment::whereRaw('id = ? and chat_id = ?', array((int)$content['image_id'], $chat->id))
                        ->get()
                        ->count() > 0)
                || (isset($content['car']) && ($car = Car::find((int)$content['car']['id'])))
                || (isset($content['geo']) && isset($content['geo']['lat']) && isset($content['geo']['long']) && isset($content['geo']['location'])))
            ) {
                if (isset($content['text']) && strlen($content['text']) > 2500)
                    return $this->respondInsufficientPrivileges('Слишком длинный текст');

                $message = new Message();
                $message->chat_id = $chat->id;
                $message->user_id = $user->id;

                if(isset($content['text'])) {
                    $message->text = $content['text'];
//                } else if(isset($content['image_id'])) {
//                    $message->image_id = (int)$content['image_id'];
                } else if(isset($content['car'])) {
                    $message->car_id = $car->id;

                    if((boolean)$content['car']['car_with_number']) {
                        $message->car_number = $car->number;
                    }
                } else if(isset($content['geo'])) {
                    $message->lat = $content['geo']['lat'];
                    $message->lng = $content['geo']['long'];
                    $message->location = $content['geo']['location'];
                }

                if (isset($content['image_id']))
                    $message->image_id = (int)$content['image_id'];

                $message->save();

                $chat->timestamp = DB::raw('NOW()');
                $chat->save();

                foreach($chat->getUsers() as $user) {
                    $unread = new MessageUnread();
                    $unread->message_id = $message->id;
                    $unread->user_id = $user->id;
                    $unread->chat_id = $chat->id;
                    $unread->save();
                }

                $message = Message::find($message->id);

                foreach($chat->getMembersTokens() as $token) {
                    $state = new StateSender($token);
                    $state->setMessageAsAdded($message->getAsArray(true));
                    $state->send();
                }
                return $this->respond($message->getAsArray());
            } else {
                return $this->setStatusCode(403)->respondWithError('Unset necessary parameter in correct format');
            }
        } else {
            return $this->setStatusCode(500)->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Attach a image to chating
     *
     * @return Response
     */
    public function attach() {
        $chatId = (int)Input::get('chat_id');

        if((int)$chatId > 0
            && !is_null($chat = Chat::find((int)$chatId))
            && $chat->hasMember(Auth::user()->id)
        ) {
            if(Input::hasFile('image')) {
                $file = Input::file('image');

                if(in_array(strtolower($file->guessExtension()), array('png', 'gif', 'jpeg', 'jpg'))) {

                    $attachment = new MessageAttachment();
                    $attachment->chat_id = $chat->id;
					$attachment->origin = '';
					$attachment->thumb = '';
					$attachment->save();

					$this->log('Init images');
					$this->log(memory_get_usage(true));
					$origin = new ImageUtil($file);
					$attachment->origin = $this->upload($attachment->id, 'origin', $origin->getImage());
					$attachment->gallery = $this->upload($attachment->id, 'gallery', $origin->resize2('gallery')->getImage());
					$origin->getImage()->destroy();
					$origin = null;
					$this->log('Gallery and origin images should be destroyed');
					$this->log(memory_get_usage(true));
					$this->log('Init thumbnail');
					$thumb = (new ImageUtil($file))->resize2('thumbnail');
					$this->log(memory_get_usage(true));
					$this->log('Uploading');
					$attachment->thumb = $this->upload($attachment->id, 'thumb', $thumb->getImage());
					$this->log(memory_get_usage(true));
					$attachment->width = $thumb->getWidth();
					$attachment->height = $thumb->getHeight();
					$thumb->getImage()->destroy();
					$thumb = null;
					$this->log('Thumbnail destroyed');
					$this->log(memory_get_usage(true));
                    $attachment->save();

                    $domain = 'http' . (Request::server('HTTPS') ? '' : 's') . '://' . Request::server('HTTP_HOST');

                    return $this->respond(array(
                        'id' => (int)$attachment->id,
                        'thumb' => $domain . '/api/messages/attach/thumb/' . $attachment->id,
                        'origin' => $domain . '/api/messages/attach/gallery/' . $attachment->id,
                        'width' => $attachment->width,
                        'height' => $attachment->height
                    ));
                } else {
                    return $this->respondWithError('Image has unsupported extension (sent file extension' . strtolower($file->guessExtension()) . ')');
                }
            } else {
                return $this->respondWithError('Image isn\'t found');
            }
        } else {
            return $this->respondWithError('Chat isn\'t found');
        }
    }

    /**
     * Display attached image by ID.
     *
     * @param int $imageId Attached image ID
     * @return Response
     */
    public function getAttach($type, $imageId) {
        if(in_array($type, array('thumb', 'origin', 'gallery'))
            && (int)$imageId > 0
        ) {
            $result = DB::select('select b.user_id from chats_members b where b.chat_id = (select a.chat_id from messages_attachments a where a.id = ?) and b.user_id = ?',
                array($imageId, Auth::user()->id));

            if(count($result) > 0) {
                try {
                    $s3 = AWS::get('s3');
                    $fileName = $imageId . '-' . $type;

                    $image = $s3->getObject(array(
                        'Bucket' => S3_PRIVATE_BUCKET_NAME,
                        'Key' => $fileName
                    ));

                    return Response::make($image['Body'], 200, array('Content-Type' => $image['ContentType']));
                } catch(S3Exception $e) {
                    return $this->respondWithError('Attachment isn\'t exist');
                }
            } else {
                return $this->respondWithError('Attachment isn\'t exist in yours chats');
            }
        } else {
            return $this->respondWithError('Unset `type` parameter in correct format');
        }
    }

    /**
     * Remove the message.
     *
     * @param $messageId Message ID
     * @return Response
     */
    public function deleteMessage($messageId) {
        $user = Auth::user();
        $message = Message::find($messageId);

        if (!$message) return $this->respondNotFound('Message not found');

        $member = $message->chat->members->filter(function ($member) use($user) {
            return $member->id = $user->id;
        });

        if (!$member) return $this->respondNotFound('Message not found');

        if(MessageRemoved::where('message_id', $message->id)->where('user_id', $user->id)
                ->get()
                ->count() == 0
        ) {
            $removed = new MessageRemoved();
            $removed->message_id = $message->id;
            $removed->user_id = Auth::user()->id;
            $removed->chat_id = $message->chat_id;
            $removed->save();

//			$chat = Chat::find($message->chat_id);
//			foreach($chat->getMembersTokens(false) as $token) {
//				$state = new StateSender($token);
//				$state->setMessageAsRemoved($chat->id, $message->id);
//				$state->send();
//			}
        }

        return $this->respondNoContent();
    }

    /**
     * Clear the chat.
     *
     * @param $chatId Chat ID
     * @return Response
     */
    public function clearChatHistory($chatId) {
        if(($chat = Chat::find($chatId))
            && $chat->hasMember(Auth::user()->id)
        ) {
            $lastMessage = Message::where('chat_id', $chat->id)
                ->orderBy('id', 'desc')
                ->take(1)
                ->get()
                ->first();

            ChatCleared::whereRaw('chat_id = ? and user_id = ?', array($chat->id, Auth::user()->id))
                ->delete();

            $cleared = new ChatCleared();
            $cleared->chat_id = $chat->id;
            $cleared->user_id = Auth::user()->id;
            $cleared->message_id = $lastMessage->id;
            $cleared->save();

            return $this->respondNoContent();
        } else {
            return $this->respondWithError('Chat isn\'t found');
        }
    }

    /**
     * Update delivered timestamp
     *
     * @return Response
     */
    public function deliver() {
        $message = Message::find(Input::get('id'));
        $user = Auth::user();

        $member = $message->chat->members->filter(function ($member) use($user) {
            return $member->id == $user->id;
        });

        if (!$member) return $this->respondNotFound('Chat member not found');

        if (!$message) return $this->respondNotFound('Message not found');

        $message->delivered_at = Carbon::now()->timestamp;

        if ($message->save()) {
            $message->chat->members->each(function ($member) use($message){
                $member->devices->each(function ($device) use($message){
                    $state = new StateSender($device->auth_token);
                    $state->setMessageAsDelivered($message->chat->id, $message->id, $message->delivered_at);
                    $state->send();
                });
            });
            return $this->respondNoContent();
        }

        return $this->respondServerError();
    }

    public function newMessages($chatId, $messageId, $size = 20) {
        $chat = Chat::find($chatId);

        if (!$chat)
            return $this->respondNotFound();

        if (!Message::find($messageId))
            return $this->respondNotFound();

        return $this->respond(['messages' => $chat->messages()->where('id', '>', $messageId)->take($size)->get()->transform(function ($message) {
            return $message->getAsArray();
        })]);
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