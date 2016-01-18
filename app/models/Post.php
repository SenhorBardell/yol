<?php
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Post
 *
 * @property-read Collection|\Comment[] $comments
 * @property-read Collection|\Like[] $likes
 * @property-read \Category $category
 * @property-read \User $user
 * @method static \Post last($id)
 */
class Post extends Eloquent {

	use SoftDeletingTrait;
	protected $fillable = ['title', 'text'];

	public static function validate($input) {
		$rules = array(
			'text' => 'alpha_spaces',
			'title' => 'alpha_spaces'
		);

		return Validator::make($input, $rules);
	}

	public function comments() {
		return $this->hasMany('Comment');
	}

	public function category() {
		return $this->belongsTo('Category');
	}

	public function user() {
		return $this->belongsTo('User')->withTrashed();
	}

	public function likes() {
		return $this->morphMany('Like', 'likeable');
	}

	public function scopeLast($query, $id) {
		return $query->where('id', '>', $id);
	}

	public function commented() {
		$user = Auth::user();

		return $this->comments->filter(function($comment) use ($user) {
			return $comment->user_id == $user->id;
		})->count() > 0 ? 1 : 0;
	}

	public function getCreatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getUpdatedAtAttribute($value) {
		return strtotime($value);
	}

	public function cars() {
		return $this->morphedByMany('CarHistory', 'attachable', 'attachables', 'postable_id');
	}

	public function carsWithNumbers() {
		return $this->morphedByMany('CarNumber', 'attachable', 'attachables', 'postable_id');
	}

	public function images() {
		return $this->morphMany('Image', 'imageable');
	}

	public function geos() {
		return $this->morphedByMany('Geo', 'attachable', 'attachables', 'postable_id');
	}

	public function liked() {
		$user = Auth::user();
		return $this->likes->filter(function($like) use($user) {
			return $like->user_id == $user->id;
		})->count() > 0;
	}

	public function getOwnerToken() {
		$token = null;

		if(isset($this->user_id) && (int)$this->user_id > 0) {
			$device = Device::where('user_id', $this->user_id)
							->get()
							->first();
			if($device) {
				$token = $device->auth_token;
			}
		}

		return $token;
	}
}