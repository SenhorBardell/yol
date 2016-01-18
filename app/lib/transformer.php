<?php namespace Helpers\Transformers;

use Auth;
use Illuminate\Database\Eloquent\Collection;

abstract class Transformer extends \Eloquent {

	private $response = [];

	/* User */

	public function transformUsers($users, $locale = 'ru') {
		foreach ($users as $user)
			$response[] = $this->transformUser($user, $locale);

		return $response;
	}

	public abstract function transformUser($user);

	/* Phone */

	public function transformPhones(Collection $phones) {
		return $phones->map(function ($phone) {return $this->transformPhone($phone);});
	}

	public abstract function transformPhone($phone);

	/* Car */

	public function transformCars($cars, $other = false) {
		$response = [];

		foreach ($cars as $car)
			array_push($response, $this->transformCar($car, $other));

		return $response;
	}

	public abstract function transformCar($car);

	/* Image */

	public abstract function transformImage($image);

	public function transformImages(Collection $images) {
		return $images->map(function($image) {
			return $this->transformImage($image);
		});
	}

	/* Category */

	public function transformCategories(Collection $categories, $sub = false, $userSubscriptions = []) {
		return $categories->map(function($category) use ($sub, $userSubscriptions) {
			return $this->transformCategory($category, $sub, $userSubscriptions);
		});
	}

	public abstract function transformCategory($category, $sub, $userSubscriptions);

	/* Post */

	public function transformPosts(Collection $posts) {
		$favorites = Auth::user()->favorites;
		$subscriptions = Auth::user()->subscriptions;
		return $posts->map(function($post) use ($favorites, $subscriptions) {
			return $this->transformPost($post, $favorites, $subscriptions);
		});
	}

	public abstract function transformPost(\Post $post, Collection $favorites, Collection $subscriptions);

	/* Comment */

	public function transformComments(Collection $comments) {
		return $comments->map(function($comment) {
			return $this->transformComment($comment);
		});
	}

	public abstract function transformComment($comment);

	/* Subscription */

	public function transformSubscriptions(Collection $subscriptions) {
		return $subscriptions->map(function($subscription) {return $this->transformSubscription($subscription); });
	}

	public abstract function transformSubscription($subscription);

	public function transformContacts(Collection $users) {
		return $users->transform(function($user) {return $this->transformContact($user);});
	}

	public abstract function transformContact($user);
}