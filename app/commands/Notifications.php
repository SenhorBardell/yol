<?php

use Illuminate\Console\Command;

class Notifications extends Command {
    const PERIOD = 10; // seconds

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'notifications:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge accumulated states and notifications';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        foreach($this->getProcessingUsers() as $owner) {
            $tokens = $this->getUserTokens($owner->owner_id);
			$this->comment('Token finished');

            $states = $this->getUserStates($owner->owner_id);
			$this->comment('States finished');

            $this->translateStatesToNotifications($states);
			$this->comment('Translation finished');

            $this->sendStatesToDevises($tokens, $states);
			$this->comment('Send to device finished');

            foreach($states as $state) {
                $state->delete();
            }
        }
    }

    private function getProcessingUsers() {
        $query = sprintf('select a.owner_id from states a where a.timestamp < (CURRENT_TIMESTAMP - INTERVAL \'%d second\') group by a.owner_id;', self::PERIOD);

        return DB::select($query);
    }

    private function getUserTokens($userId) {
        $tokens = array();

        Device::where('user_id', $userId)->get()->each(function ($device) use (&$tokens) {
			$this->info("Tokens {$device->auth_token}");
            $tokens[] = $device->auth_token;
        });

        return $tokens;
    }

    private function getUserStates($userId) {
        $states = array();

        State::whereRaw('owner_id = ?', array($userId))->orderBy('timestamp', 'asc')
             ->get()->each(function ($state) use (&$states) {
                $states[] = $state;
            });

        return $states;
    }

    private function translateStatesToNotifications($states) {
        $data = array();

        foreach($states as $state) {
            $key = implode(';', array($state->owner_id, $state->object, $state->object_id, $state->event));

            if(!isset($data[$key])) {
                $notification = new Notification();
                $notification->owner_id = $state->owner_id;
                $notification->object = $state->object;
                $notification->object_id = $state->object_id;
                $notification->event = $state->event;
                $notification->timestamp = $state->timestamp;

                $data[$key] = array(
                    'notification' => $notification,
                    'users' => array()
                );
            } else {
                $data[$key]['notification']->timestamp = $state->timestamp;
            }

            $notificationUser = new NotificationUser();
            $notificationUser->subject_id = $state->subject_id;
            $notificationUser->user_id = $state->user_id;
            $notificationUser->timestamp = $state->timestamp;

            if($state->object == 'post') {
                if($state->event == 'commented') {
                    $notificationUser->subject = 'comment';
                } else if($state->event == 'liked') {
                    $notificationUser->subject = 'like';
                }
            } else if($state->object == 'comment') {
                if($state->event == 'liked') {
                    $notificationUser->subject = 'like';
                }
            }

            $data[$key]['users'][] = $notificationUser;
			$this->info("Notification:");
			$this->info("Notification Subject: {$notificationUser->subject_id}");
			$this->info("Notification User: {$notificationUser->user_id}");
			$this->info("Notification Timestamp: {$notificationUser->timestamp}");
        }

        foreach($data as $notification) {
            $notification['notification']->save();

            foreach($notification['users'] as $notificationUser) {
                $notificationUser->notification_id = $notification['notification']->id;
                $notificationUser->save();
            }
        }
    }

    private function sendStatesToDevises($tokens, $states) {
        foreach($tokens as $token) {
            $stateSender = new StateSender($token);

            foreach($states as $state) {
                if($state->object == 'post') {
                    $post = Post::find($state->object_id);
					if (!$post) $this->error('Post not found');

                    $comment = Comment::find($state->subject_id);
					if (!$comment) $this->error('Comment not found');

                    $user = User::find($state->user_id);
					if (!$user) $this->error('User not found');

                    if($post && $user) {
                        if($state->event == 'commented' && $comment) {
                            $stateSender->setPostAsCommented($post, $comment, $user, true);
                        } else if($state->event == 'liked') {
                            $stateSender->setPostAsLiked($post, $user, true);
                        }
                    }
                } else if($state->object == 'comment'
                          && $state->event == 'liked'
                ) {
                    $comment = Comment::find($state->object_id);
					if (!$comment) $this->error('Comment not found');
                    $user = User::find($state->user_id);
					if (!$user) $this->error('User not found');

					if ($user && $comment)
                    $stateSender->setCommentAsLiked($comment, $user, true);
                }
            }

            $stateSender->send();
        }
    }
}
