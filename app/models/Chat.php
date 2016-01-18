<?php

class Chat extends \Eloquent {
    protected $table = 'chats';
    protected $fillable = ['id', 'owner_id', 'topic', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }

	public function messages() {
		return $this->hasMany('Message');
	}

	public function members() {
		return $this->belongsToMany('User', 'chats_members');
	}
    public function getUsersIds() {
        $ids = array();

        if(isset($this->id) && (int)$this->id > 0) {
            ChatMember::where('chat_id', $this->id)
                      ->get()
                      ->each(function ($member) use (&$ids) {
                          $ids[] = $member->user_id;
                      });
        }

        return $ids;
    }

    public function getUsers($withMe = false) {
        $list = array();

        if(isset($this->id) && (int)$this->id > 0) {
            $ids = $this->getUsersIds();

            if(count($ids) > 0) {
                User::whereIn('id', $ids)
                    ->get()
                    ->each(function ($user) use (&$list, $withMe) {
                        if($withMe || $user->id != Auth::user()->id) {
                            $list[] = $user;
                        }
                    });
            }
        }

        return $list;
    }

    public function getUsersArray($withMe = false) {
        $list = array();

        if(isset($this->id) && (int)$this->id > 0) {
            $ids = $this->getUsersIds();;

            if(count($ids) > 0) {
                $chat = $this;

                User::withTrashed()->whereIn('id', $ids)
                    ->get()
                    ->each(function ($user) use (&$list, $chat, $withMe) {
                        if($withMe || $user->id != Auth::user()->id) {
                            $list[] = array(
                                'id' => $user->id,
                                'name' => $user->name,
                                'img' => array(
                                    'middle' => $user->img_middle
                                )
                            );
                        }
                    });
            }
        }

        return $list;
    }

    public function hasMember($userId) {
        if(isset($this->id) && (int)$this->id > 0) {
            return
                ChatMember::whereRaw('chat_id = ? and user_id = ?', array($this->id, $userId))
                          ->get()
                          ->count()
                > 0;
        }

        return false;
    }

    public function isGroup() {
        if(isset($this->id) && (int)$this->id > 0) {
            return
                ChatMember::where('chat_id', $this->id)
                          ->get()
                          ->count()
                > 2;
        }

        return false;
    }

    public function getMembersTokens($withMe = false) {
        $tokens = array();

        if(isset($this->id) && (int)$this->id > 0) {
            $ids = array();
            ChatMember::where('chat_id', $this->id)
                      ->get()
                      ->each(function ($member) use (&$ids, $withMe) {
                          if($withMe || $member->user_id != Auth::user()->id) {
                              $ids[] = $member->user_id;
                          }
                      });

            if(count($ids) > 0) {
                Device::whereIn('user_id', $ids)
                      ->get()
                      ->each(function ($device) use (&$tokens) {
                          $tokens[] = $device->auth_token;
                      });
            }
        }

        return $tokens;
    }

    public function getLastMessage() {
        if(isset($this->id) && (int)$this->id > 0) {
            $message = Message::whereRaw('chat_id = ? and id not in (select b.message_id from messages_removed b where b.user_id = ?)', array($this->id, Auth::user()->id))
                              ->orderBy('timestamp', 'desc')
                              ->take(1)
                              ->get()
                              ->first();

            return $message ?: null;
        }

        return null;
    }

    public function lastMessage() {
        return $this->messages()->orderBy('timestamp', 'desc')->first();
    }
}