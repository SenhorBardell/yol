<?php

use Carbon\Carbon;

class ChatsController extends ApiController {

    /**
     * Display a limited listing of chats.
     *
     * @param int $size Response size
     * @return Response
     */
    public function getListLimited($size) {
        return $this->getList(null, $size);
    }

    /**
     * Display a listing of chats.
     *
     * @param int|null $lastChatId Last chat ID
     * @param int $size Response size
     * @return Response
     */
    public function getList($lastChatId = null, $size = 20) {
        $list = array();

        $ids = array();
        ChatMember::where('user_id', Auth::user()->id)
                  ->get()
                  ->each(function ($member) use (&$ids) {
                      $ids[] = $member->chat_id;
                  });

        if(count($ids) > 0) {
            $size = (int)$size > 0 ? (int)$size : 20;

            $unread = array();
            MessageUnread::whereRaw('user_id = ? and message_id not in (select b.message_id from messages_removed b where b.chat_id = chat_id)',
                                    array(Auth::user()->id))
                         ->get()
                         ->each(function ($message) use (&$unread) {
                             $unread[] = $message->chat_id;
                         });

            $query = 'select * from chats a where a.id in (' . implode(', ', $ids) . ') ' .
                     (($lastChatId = (int)$lastChatId) > 0 ? 'and a.id < ' . $lastChatId . ' ' : '') .
                     'and (select count(b.id) from messages b where b.chat_id=a.id ' .
                     'and b.id not in (select c.message_id from messages_removed c where c.chat_id = a.id) ' .
                     'and b.id > coalesce((select d.message_id from chats_cleared d where d.user_id = ' . Auth::user()->id . ' and d.chat_id = a.id), 0)) > 0 ' .
                     'order by a.timestamp desc limit ' . $size;

            $result = DB::select($query);

            foreach($result as $array) {
                $chat = new Chat((array)$array);

                $data = array(
                    'chat_id' => $chat->id,
                    'owner_id' => $chat->owner_id,
                    'topic' => $chat->topic,
                    'timestamp' => $chat->getTimestamp(),
                    'users' => $chat->getUsersArray()
                );

                if(in_array($chat->id, $unread)) {
                    $data['unread'] = true;
                }

                if(!is_null($lastMessage = $chat->getLastMessage())) {
                    $data['last_message'] = $lastMessage->getAsArray();
                }

                $list[] = $data;
            }
        }

        return $this->respond($list);
    }

    /**
     * Display a listing of chats.
     *
     * @param int|null $chatId Chat ID
     * @return Response
     */
    public function getChat($chatId = null) {
        if((int)$chatId > 0
           && !is_null($chat = Chat::find((int)$chatId))
           && $chat->hasMember(Auth::user()->id)
        ) {
            $unread = array();
            MessageUnread::whereRaw('user_id = ? and message_id not in (select b.message_id from messages_removed b where b.chat_id = chat_id)',
                                    array(Auth::user()->id))
                         ->get()
                         ->each(function ($message) use (&$unread) {
                             $unread[] = $message->chat_id;
                         });

            $data = array(
                'chat_id' => $chatId,
                'topic' => $chat->topic,
                'timestamp' => $chat->getTimestamp(),
                'users' => $chat->getUsersArray()
            );

            if(in_array($chatId, $unread)) {
                $data['unread'] = true;
            }

            return $this->respond($data);
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Display p2p chat by user ID.
     *
     * @param int $userId User ID
     * @param int $size Response size
     * @return Response
     */
    public function getChatByUser($userId, $size = 20) {
        if((int)$userId > 0
           && !is_null($user = User::find((int)$userId))
        ) {
            if(!is_null($chat = $user->getChatWithUser(Auth::user()->id))) {
                $size = (int)$size > 0 ? (int)$size : 20;

                $unread = array(
                    'messages' => array(),
                    'chats' => array()
                );
                MessageUnread::whereRaw('chat_id = ? and user_id = ?', array($chat->id, Auth::user()->id))
                             ->get()
                             ->each(function ($message) use (&$unread) {
                                 $unread['messages'][] = $message->message_id;
                                 $unread['chats'][] = $message->chat_id;
                             });

                $cleared = ChatCleared::whereRaw('chat_id = ? and user_id = ?', array($chat->id, Auth::user()->id))
                                      ->get()
                                      ->first();

                $query = 'select a.* from messages a where a.chat_id = ' . $chat->id;
                if(count($unread['messages']) > 0) {
                    $query .= ' and a.id not in (' . implode(',', $unread['messages']) . ')';
                }

                if($cleared) {
                    $query .= ' and a.id > ' . $cleared->message_id;
                }

                $query .= ' and id not in (select b.message_id from messages_removed b where b.chat_id = chat_id)'
                          . ' order by a.timestamp desc limit ' . $size;

                $messages = DB::select($query);

                if(count($unread['messages']) > 0) {
                    $unreadMessages = DB::table('messages')
                                        ->whereRaw('chat_id = ? and id in (' . implode(',', $unread['messages']) . ')'
                                                   . ' and id not in (select b.message_id from messages_removed b where b.chat_id = chat_id)', array($chat->id))
                                        ->orderBy('timestamp', 'desc');

                    $messages = array_merge($messages, $unreadMessages->get());
                }

                $result = array();

                if(count($messages) > 0) {
                    $result = array(
                        'chat_id' => $chat->id,
                        'topic' => $chat->topic,
                        'timestamp' => $chat->getTimestamp(),
                        'users' => $chat->getUsersArray(),
                        'messages' => array()
                    );

                    foreach($messages as $message) {
                        $data = (new Message((array)$message))->getAsArray();

                        if(in_array($message->chat_id, $unread['chats'])) {
                            $data['unread'] = true;
                        }

                        $result['messages'][] = $data;
                    }
                }

                return $this->respond($result);
            } else {
				return $this->respondWithCustomStatusCode('Chat doesnt exist', 404, 1100);
            }
        } else {
            return $this->respondWithError('User doesn\'t exist');
        }
    }

    /**
     * Display a listing of chat's users
     *
     * @param int|null $chatId Chat ID
     * @return Response
     */
    public function getUsers($chatId = null) {
        if((int)$chatId > 0
           && !is_null($chat = Chat::find((int)$chatId))
           && $chat->hasMember(Auth::user()->id)
        ) {
            return $this->respond($chat->getUsersArray());
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Store a newly chat.
     *
     * @return Response
     */
    public function create() {
        if(!is_null($user = User::find((int)Input::get('user_id')))) {
            if(!Auth::user()
                   ->isBlocked($user->id)
            ) {
                $result = DB::select('select c.chat_id, count(c.chat_id) as count '
                                     . 'from chats_members c where c.chat_id in (select b.chat_id from chats_members b where b.chat_id in (select a.chat_id from chats_members a where a.user_id = ?) and b.user_id = ?) '
                                     . 'group by c.chat_id having c.count = 2 limit 1;', array(Auth::user()->id, $user->id));

                if(count($result) > 0) {
                    $chatId = $result[0]->chat_id;

                    $chat = Chat::find($chatId);
                } else {
                    $chat = new Chat();
                    $chat->owner_id = Auth::user()->id;
                    $chat->topic = '';
                    $chat->save();

                    $chatId = $chat->id;

                    $chatMember = new ChatMember();
                    $chatMember->chat_id = $chatId;
                    $chatMember->user_id = Auth::user()->id;
                    $chatMember->save();

                    $chatMember = new ChatMember();
                    $chatMember->chat_id = $chatId;
                    $chatMember->user_id = $user->id;
                    $chatMember->save();

                    $chatHystory = new ChatHistory();
                    $chatHystory->chat_id = $chatId;
                    $chatHystory->event = 'created';
                    $chatHystory->save();

                    $chat = Chat::find($chat->id);
                }

                return $this->respond(array(
                                          'chat_id' => $chat->id,
                                          'owner_id' => $chat->owner_id,
                                          'topic' => $chat->topic,
                                          'timestamp' => $chat->getTimestamp(),
                                          'users' => $chat->getUsersArray()
                                      ));
            } else {
                return $this->respondInsufficientPrivileges('Can\'t send message to the user');
            }
        } else {
            return $this->respondNotFound('User doesn\'t exist');
        }
    }

    /**
     * Include a new user to the chat
     *
     * @param int|null $chatId Chat ID
     * @return Response
     */
    public function includeUser($chatId = null) {
        if((int)$chatId > 0
           && !is_null($chat = Chat::find((int)$chatId))
           && $chat->hasMember(Auth::user()->id)
        ) {
            if(!is_null($user = User::find((int)Input::get('user_id')))) {
                if(!$chat->hasMember($user->id)) {
                    $chatMember = new ChatMember();
                    $chatMember->chat_id = $chatId;
                    $chatMember->user_id = $user->id;
                    $chatMember->save();

                    $this->setChatIsUpdated((int)$chatId);
                }

                return $this->respond(array(
                                          'chat_id' => $chatId,
                                          'owner_id' => $chat->owner_id,
                                          'topic' => $chat->topic,
                                          'timestamp' => $chat->getTimestamp(),
                                          'users' => $chat->getUsersArray()
                                      ));
            } else {
                return $this->respondWithError('User doesn\'t exist');
            }
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Except a new user to the chat
     *
     * @param int|null $chatId Chat ID
     * @return Response
     */
    public function exceptUser($chatId = null, $userId = null) {
        if((int)$chatId > 0
           && !is_null($chat = Chat::find((int)$chatId))
           && $chat->hasMember(Auth::user()->id)
        ) {
            if($chat->isGroup()) {
                if((int)$userId > 0
                   && !is_null($user = User::find((int)$userId))
                   && ChatMember::whereRaw('chat_id = ? and user_id = ?', array($chatId, $user->id))
                                ->get()
                                ->count() == 1
                ) {
                    if($chat->owner_id != $user->id) {
                        ChatMember::whereRaw('chat_id = ? and user_id = ?', array($chatId, $user->id))
                                  ->delete();

                        $this->setChatIsUpdated((int)$chatId);

                        return $this->respond(array(
                                                  'chat_id' => $chatId,
                                                  'owner_id' => $chat->owner_id,
                                                  'topic' => $chat->topic,
                                                  'timestamp' => $chat->getTimestamp(),
                                                  'users' => $chat->getUsersArray()
                                              ));
                    } else {
                        return $this->respondWithError('Can\'t exclude chat owner');
                    }
                } else {
                    return $this->respondWithError('User doesn\'t exist');
                }
            } else {
                return $this->respondWithError('Can\'t exclude user from private chat');
            }
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }

    /**
     * Update the chat info
     *
     * @param int|null $chatId Chat ID
     * @return Response
     */
    public function update($chatId = null) {
		$user = Auth::user();
        if((int)$chatId > 0
           && !is_null($chat = Chat::find((int)$chatId))
           && $chat->hasMember(Auth::user()->id)
        ) {
            if((bool)Input::get('mark_as_read')) {
				$this->markAsRead($chat, $user);
            }

            if(strlen($newTopic = (string)Input::get('topic')) > 0) {
                $chat->topic = $newTopic;
                $chat->save();

                foreach($chat->getMembersTokens() as $token) {
                    $state = new StateSender($token);
                    $state->setChatAsUpdated((int)$chatId, (int)Auth::user()->id);
                    $state->send();
                }
            }

            if(Input::has('typing')) {
                foreach($chat->getMembersTokens() as $token) {
                    $state = new StateSender($token);
                    $state->setChatAsTyping($chatId, Auth::user()->id, (bool)Input::get('typing'));
                    $state->send();
                }
            }

			if (Input::has('delivered')) $this->delivered($chat);

            return $this->respondNoContent();
        } else {
            return $this->respondWithError('Chat doesn\'t exist');
        }
    }


	private function delivered($chat) {
		$user = Auth::user();
		$member = $chat->members()->where('user_id', $user->id)->first();

		if (!$member) return $this->respondNotFound('Chat member not found');

		$chat->messages()->whereNull('delivered_at')->get()->each(function($message) {
			$message->delivered_at = Carbon::now();
			$message->save();
		});

		foreach ($chat->getMembersTokens(true) as $token) {
			$state = new StateSender($token);
			$state->setChatAsDelivered($chat->id, $user->id);
			$state->send();
		}

		return $this;
	}

	private function markAsRead($chat, $user) {
		$deliverState = false;
		foreach($chat->getMembersTokens(true) as $token) {
			MessageUnread::whereRaw('chat_id = '.$chat->id)
				->get()
				->each(function ($unread) use($chat, $user, $deliverState) {
					if ($unread->user_id == $user->id) {
						$unread->delete();

						Message::where('chat_id', $chat->id)->whereNull('delivered_at')->get()
							->map(function ($message) use ($chat, $user, $deliverState) {
								if (!$message->delivered_at)
									$message->delivered_at = Carbon::now();

								if (!$message->viewed_at)
									$message->viewed_at = Carbon::now();

								$message->save();
							});
					}
				});
			$state = new StateSender($token);
			if ($deliverState) $state->setChatAsDelivered($chat->id, $user->id);
			$state->setChatAsRead($chat->id, $user->id);
			$state->send();
		}

		$this->updateCounts($chat);
		return $this;
	}

	private function updateCounts($chat) {
		Auth::user()->devices->map(function ($device) use ($chat){
			$state = new StateSender($device->auth_token);
			$state->updateCounts('chats', $chat->id);
			$state->send();
		});
	}
}