<?php

use Helpers\Transformers\CollectionTransformer;

class LikesController extends ApiController {

    /**
     * @var Helpers\Transformers\CollectionTransformer
     */
    protected $collectionTransformer;

    function __construct(CollectionTransformer $collectionTransformer) {
        $this->collectionTransformer = $collectionTransformer;
    }

    /**
     * Transform posts and comments to
     * Post and Comment. Because we have Post and Comment in our db
     *
     * @param $string
     * @return bool|string
     */
    private function str($string) {
        switch($string) {
            case 'comments':
                return 'Comment';
            case 'posts':
                return 'Post';
        }
        return false;
    }

    public function like($type, $id) {
        $this->storeOrGet($this->str($type), $id);

        return $this->respondNoContent();
    }

    private function storeOrGet($type, $id) {
        $user = Auth::user();

        $like = $this->get($type, $id, $user->id);

        if(!$like) {
            $like = Like::create([
				'likeable_id' => $id,
				'likeable_type' => $type,
				'user_id' => $user->id,
				'created_at' => Carbon\Carbon::now()]);

            if(in_array($type, array('Post', 'Comment'))) {
                $object = $type::find($id);

                if($object) {
                    if($user->id != $object->user_id
                       && ($device = Device::where('user_id', $object->user_id)
                                           ->get()
                                           ->first())
                    ) {
                        $method = "set{$type}AsLiked";

                        $state = new StateSender($device->auth_token);
                        $state->$method($object, Auth::user());
                    }
                }
            }
        }

        return $like;
    }

    private function get($type, $id, $userId) {
        return Like::where('likeable_type', $type)
                   ->where('user_id', $userId)
                   ->where('likeable_id', $id)
                   ->first();
    }

    public function unlike($type, $id) {
        $type = $this->str($type);
        $user = Auth::user();

        $like = $this->get($type, $id, $user->id);

        if($like) {
            if($like->delete()) {
                $object = strtolower($type);

                State::whereRaw('object=? and object_id=? and event=\'liked\'', array($object, $id))
                     ->delete();

                Notification::whereRaw('object=? and object_id=? and event=\'liked\'', array($object, $id))
                            ->get()->each(function ($notification) use ($user) {
                        NotificationUser::whereRaw('notification_id=? and user_id=?', array($notification->id, $user->id))
                                        ->get()->each(function ($notificationsUser) {
                                $notificationsUser->is_removed = 1;
                                $notificationsUser->save();
                            });

                        $notification->is_removed = 1;
                        $notification->save();
                    });

                Notification::whereRaw('object=? and event=\'liked\' and is_removed=0 and ' .
                                       '(select count(nu.id) from notifications_users nu where nu.notification_id=notifications.id and nu.is_removed=0)=0', array($object))
                            ->get()->each(function ($notification) {
                        $notification->is_removed = 1;
                        $notification->save();
                    });

                return $this->respondNoContent();
            }
        } else {
            return $this->respondNotFound('Like not found');
        }

        return $this->respondServerError();
    }

    public function users($type, $id) {
        $usersIDs = Like::usersIDs($this->str($type), $id);

        if($usersIDs->count() == 0) {
            return $this->respond([]);
        }

        $users = User::whereIn('id', $usersIDs->toArray())
                     ->with('cars')
                     ->get();

        if(!$users) {
            return $this->respond([]);
        }

        $response = $users->map(function ($user) {
            $rsp = $this->collectionTransformer->transformUserToSmall($user);
            $rsp['cars'] = $this->collectionTransformer->transformCars($user->cars);
            return $rsp;
        });

        return $this->respond($response);
    }
}