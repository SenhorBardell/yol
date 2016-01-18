<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Post[] $posts
 * @property \Illuminate\Database\Eloquent\Collection|\Category[] $categories
 * @property-read \City $city
 * @property string $title
 * @property string $description
 */
class Category extends \Eloquent {

	use SoftDeletingTrait;

	protected $fillable = ['title', 'description', 'parent_id'];

	public $timestamps = false;

	public static function validate($input) {
		$rules = array(
			'title' => 'alpha_spaces',
			'description' => 'alpha_spaces'
		);

		return Validator::make($input, $rules);
	}

	public function posts() {
		return $this->hasMany('Post');
	}

	public function comments() {
		return $this->hasManyThrough('Comment', 'Post');
	}

	public function categories() {
		return $this->hasMany('Category', 'parent_id');
	}

	public function subscriptions() {
		return $this->hasMany('Subscription');
	}

	public function city() {
		return $this->belongsTo('City');
	}

	public function parent() {
		return $this->belongsTo('Category', 'parent_id');
	}

	public function getSubscriptionsCountAttribute($value) {
		return (int)$value;
	}

	public function getPostsCountAttribute($value) {
		return (int)$value;
	}

	public function getCommentsCountAttribute($value) {
		return (int)$value;
	}

	public function updateCount($type) {
		switch ($type) {
			case 'subscriptions':
				$this->subscriptions_count = $this->subscriptions->count();
				break;
			case 'posts':
				$this->posts_count = $this->posts->count();
				break;
			case 'comments':
				$this->comments_count = $this->comments->count();
				break;
		}
		$this->save();
	}

    public static function updateCounts() {
        DB::statement('UPDATE categories SET subscriptions_count = (SELECT count(*) AS aggregate FROM subscriptions WHERE subscriptions.category_id = categories.id) WHERE categories.parent_id <> 0');
		DB::statement('UPDATE categories SET posts_count = (SELECT count(*) AS aggregate FROM posts WHERE posts.category_id = categories.id) WHERE categories.parent_id <> 0');
		DB::statement('UPDATE categories SET comments_count = (SELECT count(*) AS aggregate FROM comments INNER JOIN posts on posts.id = comments.post_id WHERE posts.category_id = categories.id) WHERE categories.parent_id <> 0');
    }

	public static function updateSubsCounts() {
		DB::statement('UPDATE categories SET subscriptions_count = (SELECT count(*) AS aggregate FROM subscriptions WHERE subscriptions.category_id = categories.id) WHERE categories.parent_id <> 0');
	}
}
