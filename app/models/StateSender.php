<?php

class StateSender {
    private $data;
    private $token;
    private $redis;
	public $withCar;

    function __construct($token, $withCar = false) {
        $this->token = $token;
		$this->withCar = $withCar;

        $this->redis = Redis::connection();

        $conn = parse_url(getenv('REDISCLOUD_URL'));
        if(isset($conn['pass'])) {
            $this->redis->auth($conn['pass']);
        }

        $this->data = json_decode($this->redis->get($this->token), true)
            ?: [
                'new' => [
                    'chats' => [],
					'carChats' => [],
                    'posts' => [],
                    'comments' => [],
                    'emergencies' => []
                ]
            ];
    }

	private function getChatType() {
		return $this->withCar ? 'carChats' : 'chats';
	}

	public function getData() {
		return $this->data;
	}

    public function setChatAsTyping($chatId, $userId, $state = true) {
        $this->data['chats']['typing'] = array(
            'chat_id' => $chatId,
            'user_id' => $userId,
            'state' => $state
        );

        return $this;
    }

	public function setCarChatAsTyping($chatId, $userId = null, $carId = null, $state = true) {
		if (!$userId && !$carId) return $this;

		$this->data['carChats']['typing'] = [
			'chat_id' => $chatId,
			'state' => $state
		];

		if ($userId) $this->data['carChats']['typing']['user_id'] = $userId;
		else $this->data['carChats']['typing']['car_id'] = $carId;

		return $this;
	}

    public function setChatAsRead($chatId, $userId) {
        $this->data['new']['chats'] = array_diff($this->data['new']['chats'], array($chatId));

        $this->data['chats']['marked_as_read'] = array(
            'chat_id' => $chatId,
            'user_id' => $userId
        );

        return $this;
    }

	public function setCarChatAsRead($chatId, $userId = null, $carId = null) {
		if (!$userId && !$carId) return $this;

		$this->data['carChats']['marked_as_read']['chat_id'] = $chatId;

		if ($userId) $this->data['carChats']['marked_as_read']['user_id'] = $userId;
		elseif ($carId) $this->data['carChats']['marked_as_read']['car_id'] = $carId;

		return $this;
	}

	public function setChatAsDelivered($chatId, $userId) {
		$this->data['chats']['delivered'] = [
			'chat_id' => $chatId,
			'user_id' => $userId
		];

		return $this;
	}

	public function setCarChatAsDelivered($chatId, $userId = null, $carId = null) {
		if (!$userId && !$carId) return $this;

		$this->data['carChats']['delivered'] = [
			'chat_id' => $chatId,
		];

		if ($userId) $this->data['carChats']['delivered']['user_id'] = $userId;
		elseif ($carId) $this->data['carChats']['delivered']['car_id'] = $carId;

		return $this;
	}

	public function updateCounts($type, $id) {
		$this->data['new'][$type] = array_diff($this->data['new'][$type], [$id]);
	}

	public function resetCounts($type) {
		$this->data['new'][$type] = [];
	}

	public function setEmergencyAsDelivered($emergencyId, $deliveredAt, $viaSMS = false) {
		$this->data['emergencies']['delivered'] = [
			'emergency_id' => $emergencyId,
			'via_sms' => $viaSMS,
			'delivered_at' => $deliveredAt
		];
		return $this;
	}

	public function setEmergencyAdded(Emergency $emergency, $tries) {
		$this->data['new']['emergencies'][] = $emergency->id;
		$this->data['emergencies']['added'] = [
			'id' => $emergency->id,
			'sender' => $emergency->sender,
			'created_at' => $emergency->created_at,
			'number' => $emergency->number,
			'phone_number' => $emergency->phone_number,
			'delivered_at' => $emergency->delivered_at,
			'status' => $emergency->status,
			'via_sms' => false,
			'complained_at' => $emergency->complained_at
		];
		$this->data['emergencies']['tries'] = $tries;

		return $this;
	}

	public function setEmergencyAsComplained($id, $complained_at) {
		$this->data['emergencies']['complained'] = [
			'emergency_id' => $id,
			'complained_at' => strtotime($complained_at)
		];
    }

    public function setChatAsUpdated($chatId, $userId) {
        $this->data['chats']['updated'] = array(
            'chat_id' => $chatId,
            'user_id' => $userId
        );

        return $this;
    }

    public function setMessageAsAdded($messageAsArray) {
        $this->data['new'][$this->getChatType()][] = $messageAsArray['chat_id'];

        $this->data[$this->getChatType()]['messages']['added'] = $messageAsArray;

        return $this;
    }

    public function setMessageAsRemoved($chatId, $messageId) {
        $this->data[$this->getChatType()]['messages']['removed'] = array(
            'chat_id' => $chatId,
            'message_id' => $messageId
        );

        return $this;
    }

	public function setMessageAsDelivered($chatId, $messageId, $delivered_at) {
		$this->data[$this->getChatType()]['messages']['delivered'] = [
			'chat_id' => $chatId,
			'message_id' => $messageId,
			'delivered_at' => $delivered_at
		];
	}

	public function setMessageAsViewed($chatId, $messageId, $viewed_at) {
		$this->data[$this->getChatType()]['messages']['viewed'] = [
			'chat_id' => $chatId,
			'message_id' => $messageId,
			'viewed_at' => $viewed_at
		];
	}

    public function setPostAsLiked($post, $user, $send = false) {
        if($send) {
            $this->data['new']['posts'][] = $post->id;

            if(!isset($this->data['posts'][$post->id])) {
                $this->data['posts'][$post->id] = array(
                    'post_id' => $post->id
                );
            }

            if(isset($this->data['posts'][$post->id]['liked'])) {
                $count = isset($this->data['posts'][$post->id]['liked']['count']) ? $this->data['posts'][$post->id]['liked']['count'] : 1;
                $this->data['posts'][$post->id]['liked'] = ['count' => ++$count];
            } else {
                $this->data['posts'][$post->id]['liked'] = ['user' => $user->name];
            }
        } else {
            $state = new State();
            $state->object = 'post';
            $state->object_id = $post->id;
            $state->event = 'liked';
            $state->user_id = $user->id;
            $state->owner_id = $post->user_id;
            $state->save();

            $state->timestamp = DB::raw('NOW()');
            $state->save();
        }

        return $this;
    }

    public function setPostAsCommented($post, $comment, $user, $send = false) {
        if($send) {
            $this->data['new']['posts'][] = $post->id;

            if(!isset($this->data['posts'][$post->id])) {
                $this->data['posts'][$post->id] = array(
                    'post_id' => $post->id
                );
            }

            if(isset($this->data['posts'][$post->id]['commented'])) {
                $count = isset($this->data['posts'][$post->id]['commented']['count']) ? $this->data['posts'][$post->id]['commented']['count'] : 1;
                $this->data['posts'][$post->id]['commented'] = array('count' => ++$count);
            } else {
                $this->data['posts'][$post->id]['commented'] = array('user' => $user->name);
            }
        } else {
            $state = new State();
            $state->object = 'post';
            $state->object_id = $post->id;
            $state->subject_id = $comment->id;
            $state->event = 'commented';
            $state->user_id = $user->id;
            $state->owner_id = $post->user_id;
            $state->save();

            $state->timestamp = DB::raw('NOW()');
            $state->save();
        }

        return $this;
    }

    public function setPostAsRead($postId) {
        $this->data['new']['posts'] = array_diff($this->data['new']['posts'], array($postId));

        return $this;
    }

    public function setAllPostsAsRead() {
        $this->data['new']['posts'] = array();

        return $this;
    }

    public function setAllCommentsAsRead() {
        $this->data['new']['comments'] = array();

        return $this;
    }

    public function setCommentAsLiked($comment, $user, $send = false) {
        if($send) {
            $this->data['new']['comments'][] = $comment->id;

            if(!isset($this->data['posts'][$comment->post_id])) {
                $this->data['posts'][$comment->post_id] = array(
                    'post_id' => $comment->post_id
                );
            }

            if(!isset($this->data['posts'][$comment->post_id]['comments'][$comment->id])) {
                $this->data['posts'][$comment->post_id]['comments'][$comment->id] = array(
                    'comment_id' => $comment->id
                );
            }

            if(isset($this->data['posts'][$comment->post_id]['comments'][$comment->id]['liked'])) {
                $count = isset($this->data['posts'][$comment->post_id]['comments'][$comment->id]['liked']['count'])
                    ? $this->data['posts'][$comment->post_id]['comments'][$comment->id]['liked']['count'] : 1;
                $this->data['posts'][$comment->post_id]['comments'][$comment->id]['liked'] = array('count' => ++$count);
            } else {
                $this->data['posts'][$comment->post_id]['comments'][$comment->id]['liked'] = array('user' => $user->name);
            }
        } else {
            $state = new State();
            $state->object = 'comment';
            $state->object_id = $comment->id;
            $state->event = 'liked';
            $state->user_id = $user->id;
            $state->owner_id = $comment->user_id;
            $state->save();

            $state->timestamp = DB::raw('NOW()');
            $state->save();
        }

        return $this;
    }

    public function setCommentAsRead($commentId) {
        $this->data['new']['comments'] = array_diff($this->data['new']['comments'], array($commentId));

        return $this;
    }

    public function send() {
        $this->data['new']['chats'] = array_values(array_unique($this->data['new']['chats']));
        $this->data['new']['posts'] = array_values(array_unique($this->data['new']['posts']));
        $this->data['new']['comments'] = array_values(array_unique($this->data['new']['comments']));
        $this->data['new']['carChats'] = array_values(array_unique($this->data['new']['carChats']));
		$this->data['new']['emergencies'] = array_values(array_unique($this->data['new']['emergencies']));

        if(isset($this->data['posts'])) {
            $posts = array();

            foreach($this->data['posts'] as $post) {
                if(isset($post['comments'])) {
                    $comments = array();

                    foreach($post['comments'] as $comment) {
                        $comments[] = $comment;
                    }

                    $post['comments'] = $comments;
                }

                $posts[] = $post;
            }

            $this->data['posts'] = $posts;
        }

        if(isset($this->data['comments'])) {
            $comments = array();

            foreach($this->data['commented'] as $comment) {
                $comments[] = $comment;
            }

            $this->data['comments'] = $comments;
        }

        $this->redis->set($this->token, json_encode(array(
                                                        'new' => $this->data['new']
                                                    )));

        $json = json_encode($this->data);
        $this->redis->publish($this->token, $json);

        $device = Device::where('auth_token', $this->token)
                        ->first();
        $pushToken = PushToken::find((int)$device->id);

        if($pushToken) {
            Push::send($pushToken->platform, $pushToken->token, $json);
        }

        return $this;
    }

}

