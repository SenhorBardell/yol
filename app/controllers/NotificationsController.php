<?php
use Helpers\Transformers\CollectionTransformer;

class NotificationsController extends ApiController {
    const AMOUNT_LIMIT = 100;
    const TIME_LIMIT = 2592000; //per seconds

    /**
     * @var Helpers\Transformers\CollectionTransformer
     */
    protected $collectionTransformer;

    function __construct(CollectionTransformer $collectionTransformer) {
        $this->collectionTransformer = $collectionTransformer;
    }

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
     * Display a listing of notifications.
     *
     * @param int|null $lastNotificationId Last chat ID
     * @param int $size Response size
     * @return Response
     */
    public function getList($lastNotificationId = null, $size = 20) {
        $result = array();

        $size = (int)$size > 0 ? (int)$size : 20;

        $query = sprintf('select max(x.id) as "before" from (select id from notifications n where n.owner_id = %d order by n.timestamp desc offset %d) x;', Auth::user()->id,
                         self::AMOUNT_LIMIT);
        if(($data = DB::select($query)) && !is_null($idBeforeRemove = $data[0]->before)) {
            Notification::whereRaw('owner_id = ? and id < ?', array(Auth::user()->id, $idBeforeRemove))
                        ->update(array('is_removed' => 1));
        }

        Notification::whereRaw('owner_id = ? and is_removed = 0 and timestamp > (CURRENT_TIMESTAMP - INTERVAL \'' . self::TIME_LIMIT . ' second\')'
                               . (is_null($lastNotificationId) ? '' : ' and id < ' . (int)$lastNotificationId),
                               array(Auth::user()->id))
                    ->orderBy('timestamp', 'desc')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->take($size)
                    ->each(function ($notification) use (&$result) {
                        $notificationUsers = NotificationUser::whereRaw('notification_id = ? and is_removed = 0', array($notification->id))
                                                             ->orderBy('timestamp', 'desc')
                                                             ->get();

                        $usersCount = $notificationUsers->count();

                        $type = $notification->object;
                        $event = $notification->event;

                        $data = array(
                            'id' => $notification->id,
                            'type' => $type,
                            'amount' => $usersCount,
							'timestamp' => $notification->getTimeStamp()
                        );

//						var_dump($type.' '.$event);

                        $self = $this;

                        if($type == 'post') {
                            $post = Post::find($notification->object_id);

                            $data['post_id'] = $post->id;
                            $data['post_title'] = $post->text;
                            if($event == 'commented') {
                                $notificationUsers->take(2)
                                                  ->each(function ($user) use (&$data, $self) {
                                                      $comment = Comment::find($user->subject_id);
													  if ($comment) {
														  $data['comments'][] = $self->collectionTransformer->transformComment($comment);
													  } else {
														  unset($data['id']);
														  unset($data['type']);
														  unset($data['amount']);
														  unset($data['timestamp']);
														  unset($data['post_id']);
														  unset($data['post_title']);
													  }
                                                  });
                            } else if($event == 'liked') {
                                $notificationUsers->take(2)
                                                  ->each(function ($user) use (&$data, $self) {
                                                      $user = User::withTrashed()->find($user->user_id);
                                                      $data['likes'][] = $self->collectionTransformer->transformUserToSmall($user);
                                                  });
                            } else {
								unset($data['id']);
								unset($data['type']);
								unset($data['amount']);
								unset($data['timestamp']);
								unset($data['post_id']);
								unset($data['post_title']);
							}
                        } else if($type == 'comment') {
                            $comment = Comment::find($notification->object_id);

							if ($comment) {
								$post = Post::find($comment->post_id);

								if ($post) {
									$data['post_id'] = $post->id;
									$data['post_title'] = $post->text;
									$data['comment_id'] = $comment->id;
								}

								if($event == 'liked') {
									$notificationUsers->take(2)
										->each(function ($user) use (&$data, $self) {
											$user = User::withTrashed()->find($user->user_id);
											$data['likes'][] = $self->collectionTransformer->transformUserToSmall($user);
										});
								}
							} else {
								unset($data['id']);
								unset($data['type']);
								unset($data['amount']);
								unset($data['timestamp']);
								unset($data['post_id']);
								unset($data['post_title']);
							}
                        } else {
							unset($data['id']);
							unset($data['type']);
							unset($data['amount']);
							unset($data['timestamp']);
							unset($data['post_id']);
							unset($data['post_title']);
						}


//						$filter = function ($data) {
//							return array_filter($data) != [];
//						};
//                        $result['notifications'][] = array_filter($data, $filter);
						if (!empty($data))
							$result['notifications'][] = $data;
                    });

        if(is_null($lastNotificationId)) {
            foreach(Auth::user()->getTokens() as $token) {
                $state = new StateSender($token);

                $state->setAllPostsAsRead();
                $state->setAllCommentsAsRead();

                $state->send();
            }
        }

        return $this->respond($result);
    }
}