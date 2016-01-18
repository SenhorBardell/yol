<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Comment
 *
 * @property-read \User $user
 */
class Comment extends \Eloquent {
	use SoftDeletingTrait;
	protected $fillable = ['text', 'user_id'];

	public static function validate($input) {
		$rules = array(
			'text' => 'required'
		);

		return Validator::make($input, $rules);
	}

	public function user() {
		return $this->belongsTo('User')->withTrashed();
	}

	public function likes() {
		return $this->morphMany('Like', 'likeable');
	}

	public function post() {
		return $this->belongsTo('Post');
	}

	public function liked() {
		$user = Auth::user();
		return $this->likes->filter(function($like) use ($user) {
			return $like->user_id == $user->id;
		})->count() > 0;
	}

	public function scopeByLastAndPost($query, $id, $post_id) {
		return $query->where('id', '<', $id)->byPost($post_id)->wth();
	}

	public function scopeByTimestampAndPost($query, $timestamp, $post_id) {
		return $query->where('updated_at', '>', $timestamp)->byPost($post_id)->wth();
	}

	public function scopeByPost($query, $id) {
		return $query->where('post_id', $id)->wth();
	}

	public function scopeWth($query) {
		return $query->with('user', 'likes', 'geos', 'images', 'cars', 'cars.images');
	}

	public function getCreatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getUpdatedAtAttribute($value) {
		return strtotime($value);
	}

	public function cars() {
		return $this->morphedByMany('CarHistory', 'attachable', 'comments_attachables', 'postable_id');
	}

	public function carsWithNumbers() {
		return $this->morphedByMany('CarNumber', 'attachable', 'comments_attachables', 'postable_id');
	}

	public function images() {
		return $this->morphMany('Image', 'imageable');
	}

	public function geos() {
		return $this->morphedByMany('Geo', 'attachable', 'comments_attachables', 'postable_id');
	}

	public function getOwnerToken() {
		$token = null;

		if(isset($this->user_id) && (int)$this->user_id > 0) {
			$device = Device::whereIn('user_id', $this->user_id)
							->get()
							->first();
			if($device) {
				$token = $device->auth_token;
			}
		}

		return $token;
	}
}