<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use \Aws\S3\Exception\S3Exception as S3Exception;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Intervention\Image\Facades\Image;

/**
 * Class User
 * @property integer $id
 * @property string $name
 * @property string $img_small
 * @property string $img_middle
 * @property string $img_origin
 * @property-read \Illuminate\Database\Eloquent\Collection|\Category[] $subscriptions
 */
class User extends Eloquent implements UserInterface {

	use UserTrait, SoftDeletingTrait;

    protected $hidden = ['created_at', 'updated_at', 'password'];

	protected $fillable = [
        'name',
        'birthday',
        'city',
        'sex',
        'about',
        'email',
    ];

	public function getCityAttribute($value) {
		return (int)$value;
	}

	public function getCompletedAttribute($value) {
		return (int)$value;
	}

	public function getImgSmallAttribute($value) {
		if ($this->deleted_at)
			return S3_PUBLIC."default_avatar_deleted.png";
		return S3_PUBLIC.$value;
	}

	public function getImgMiddleAttribute($value) {
		if ($this->deleted_at)
			return S3_PUBLIC."default_avatar_deleted.png";
		return S3_PUBLIC.$value;
	}

	public function getImgLargeAttribute($value) {
		if (!$value) return S3_PUBLIC."placeholder_128.png"; //FIXME quick workaround, need to do it properly

		if ($this->deleted_at) return S3_PUBLIC."default_avatar_deleted.png";

		return S3_PUBLIC.$value;
	}

	public function images() {
		return $this->belongsToMany('Image');
	}

	public function cars() {
		return $this->hasMany('Car');
	}

	public function cityRef() {
		return $this->belongsTo('CityRef', 'city');
	}

	public function carsHistory() {
		return $this->hasMany('CarHistory');
	}

	public function car() {
		return $this->belongsTo('Car');
	}

	public function phones() {
		return $this->hasMany('Phone');
	}

	public function phone() {
		return $this->belongsTo('Phone');
	}

	public function post() {
		return $this->belongsTo('Post');
	}

	public function posts() {
		return $this->hasMany('Post');
	}

	public function comments() {
		return $this->hasManyThrough('Comment', 'Post');
	}

	public function chats() {
		return $this->belongsToMany('Chat', 'chats_members');
	}

	public function chatsCleared() {
		return $this->belongsToMany('Chat', 'chats_cleared');
	}

	public function chatsHistory() {
		return $this->belongsToMany('Chat', 'chats_history');
	}

	public function roles() {
		return $this->belongstoMany('Role');
	}

	/**
	 * List of users that i blocked. My black list.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function blockedUsers() {
		return $this->belongsToMany('User', 'black_list', 'user_id', 'blocked_user_id');
	}

	/**
	 * List of users who blocked me.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function blockedMeUsers() {
		return $this->belongsToMany('User', 'black_list', 'blocked_user_id', 'user_id');
	}

	/**
	 * Did the OTHER user blocked me?
	 *
	 * @param $id User id
	 * @return bool
	 */
	public function isBlocked($id) { //blockedUsers or BlockedMeUsers???
		return $this->blockedMeUsers->filter(function($user) use($id) {
			return $user->id == $id;
		})->count() > 0;
	}

	/**
	 * Did I blocked this user
	 *
	 * @param $id
	 * @return bool
	 */
	public function inBlackList($id) {
		return $this->blockedUsers->filter(function($user) use($id) {
			return $user->id == $id;
		})->count() > 0;
	}
	/**
	 * Block someone.
	 *
	 * @param $id User id
	 */
	public function block($id) {
		$this->blockedUsers()->attach($id);
	}

	public function unblock($id) {
		return $this->blockedUsers()->detach($id);
	}

	public function devices() {
		return $this->hasMany('Device');
	}

	public function emergencies() {
		return $this->hasMany('Emergency', 'sender');
	}

	public function receivedEmergencies() {
		return $this->hasMany('Emergency', 'receiver');
	}

	public function myCarChats() {
		return $this->hasMany('CarChat', 'owner_id');
	}

	public function contacts() {
		return $this->belongsToMany('User', 'contact_user', 'user_id', 'contact_id');
	}

	public function subscriptions() {
		return $this->belongsToMany('Category', 'subscriptions');
	}

	public function likes() {
		return $this->hasMany('Like');
	}

	public function favorites() {
		return $this->belongsToMany('Post', 'favorites', 'user_id', 'post_id')->withPivot('created_at');
	}

	public function complaints() {
		return $this->belongsTo('Complaint', 'owner_id');
	}

	public function complaintsToMe() {
		return $this->belongsTo('Complaint', 'user_id');
	}

	public static function validate($input) {
		$rules = array(
			'name' => 'required',
			'email' => 'email|unique:users',
			'password' => 'required|between:6,12'
		);

		return Validator::make($input, $rules);
	}

	/**
	 * Check permission user
	 *
	 * @param Eloquent
	 * @param bool $object
	 *
	 * @return bool
	 */
	public function can($action, $object = false) {
        if($action == 'User.view') {
            return true;
        }

		if (is_object($object)) {
			$params = explode('.', $action);

			// As for default user can edit his/her comment and post
			if ($params[0] == 'Post' || $params[0] == 'Comment')
				if ($params[1] == 'delete' || $params[1] == 'update')
					if ($object->user->id == $this->id)
						return true;
		}

		// check if user have permission
		foreach ($this->roles()->with('permissions')->get() as $role)
			foreach ($role->permissions as $permission)
				if ($action == $permission->action)
					return true;

		return false;
	}

	private function check($permission, $role) {
		return $permission->action == $role;
	}

	public function setPasswordAttribute($password){
		$this->attributes['password'] = Hash::make($password);
	}

	public function checkPasswordAttribute($password) {
		return Hash::check($password, $this->attributes['password']);
	}

	public function getAuthIdentifier() {
		return $this->getKey();
	}

	public function getUpdatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getAuthPassword() {
		return $this->password;
	}

	public static function createApiKey() {
		return Str::random(32);
	}

	public function setAvatar($img) {
		$s3 = AWS::get('s3');
		$thumbnailPlaceholder = S3_PUBLIC.'placeholder_128.png';
		$profilePlaceholder = S3_PUBLIC.'placeholder_64.png';

		$imageUtil = new ImageUtil($img);
		$this->img_origin = $this->uploadImage($s3, $imageUtil->getImage());
		$imageUtil->getImage()->destroy();
		$imageUtil = null;
		preventMemoryLeak();

		$imageUtil2 = new ImageUtil($img);
		$this->img_large = $this->uploadImage($s3, $imageUtil2->resize2('avatar.gallery')->getImage());
		$imageUtil2->getImage()->destroy();
		$imageUtil = null;
		preventMemoryLeak();

		$imageUtil3 = new ImageUtil($img);
		$this->img_middle = $this->uploadImage($s3, $imageUtil3->resize2('avatar.profile')->getImage()) ?: $profilePlaceholder;
		$imageUtil3->getImage()->destroy();
		$imageUtil3 = null;
		preventMemoryLeak();

		$imageUtil4 = new ImageUtil($img);
		$this->img_small = $this->uploadImage($s3, $imageUtil4->resize2('avatar.thumbnail')->getImage()) ?: $thumbnailPlaceholder;
		$imageUtil4->getImage()->destroy();
		$imageUtil4 = null;
		preventMemoryLeak();
	}


	/**
	 * Accessors
	 */
	public function getIdAttribute($value) {
		return (int)$value;
	}

    public function getChatWithUser($userId) {
        $result = null;

        if(isset($this->id) && (int)$this->id > 0) {
            $data = DB::select('select c.chat_id from chats_members c where c.chat_id in (select b.chat_id from chats_members b where b.chat_id in (select a.chat_id from chats_members a where a.user_id = ?) group by b.chat_id having count(b.user_id) = 2) and c.user_id = ?;', array($this->id, $userId));

            if(count($data) > 0) {
                $result = Chat::find($data[0]->chat_id);
            }
        }

        return $result;
    }

	public function getTokens() {
		$tokens = array();

		if(isset($this->id) && (int)$this->id > 0) {
			Device::where('user_id', $this->id)->get()->each(function ($device) use (&$tokens) {
				$tokens[] = $device->auth_token;
			});
		}

		return $tokens;
	}

	public function messages() {
		return $this->belongsTo('Message');
	}

	// TODO Must be just one method not 3 identical
	private function uploadImage($s3, $image) {
		$name = str_random(40).'.jpg';
		try {
			$uploadedImage = $s3->putObject([
				'ContentType' => $image->mime(),
				'ACL' => 'public-read',
				'Bucket' => S3_PUBLIC_BUCKET_NAME,
				'Key' => $name,
				'Body' => $image->encode('jpg', IMAGE_COMPRESSING_QUALITY)
			]);
		} catch (\Aws\S3\Exception\S3Exception $e) {
			return false;
		}

		preventMemoryLeak(); // TODO no need to enable garbage collection, but need a benchmark to proof
		return $name;
	}

}