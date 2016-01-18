<?php namespace Helpers\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionTransformer extends Transformer {

	// @TODO Display by locale
	public function transformUser($user, $locale = 'ru') {
		return [
			'id' => $user->id,
			'name' => $user->name,
			'email' => $user->email,
			'phone' => $this->transformPhone($user->phone),
			'phones' => $this->transformPhones($user->phones->filter(function($phone) use ($user) {
						return $user->phone_id != $phone->id;
				})->values()),
			'birthday' => $user->birthday,
			'age' => Carbon::now()->diff(carbon::parse($user->birthday))->y,
			'city' => $user->city,
			'img' => [
				'thumb' => $user->img_small,
				'middle' => $user->img_middle,
				'origin' => $user->img_large
			],
			'sex' => $user->sex,
			'about' => $user->about,
			'completed' => $user->completed,
//			'car' => $this->transformCar(@$user->cars->filter(function ($car) use ($user) {
//					return $user->car_id == $car->id;
//				})->values()[0]),
			'cars' => $this->transformcars($user->cars->filter(function ($car) use ($user) {
					return $user->car_id != $car->id;
				})->values()),
			'updated_at' => $user->updated_at
		];
	}

	public function transformOtherUser($user, $locale = 'ru') {
		return [
			'id' => $user->id,
			'name' => $user->name,
			'email' => $user->show_email ? $user->email : null,
//			'phone' => $user->show_phone ? $this->transformnumber($user->phone) : null, //TODO privacy
			'phones' => $user->show_phone ? $user->phones->map(function($phone) {
					return $this->transformPhone($phone, true);
				}) : [],
//			'birthday' => carbon::parse($user->birthday)->todatestring(),
			'age' => Carbon::now()->diff(Carbon::parse($user->birthday))->y,
			'city' => $user->city,
			'img' => [
				'thumb' => $user->img_small,
				'middle' => $user->img_middle,
				'origin' => $user->img_large
			],
			'sex' => $user->sex,
			'about' => $user->about,
//			'car' => $this->transformCar(@$user->cars->filter(function ($car) use ($user) {
//					return $user->car_id == $car->id;
//				})->values()[0], !$user->show_car_number),
			'cars' => $this->transformCars($user->cars->filter(function ($car) use ($user) {
					return $user->car_id != $car->id;
				})->values(), !$user->show_car_number),
			'in_blacklist' => Auth::user()->inBlackList($user->id) ? 1 : 0
		];
	}

	public function transformPhone($phone, $other = false) {
		if ($other)
			return $phone ?: $phone->number;
		return $phone ? ['id' => $phone->id, 'number' => $phone->number] :  null;
	}

	public function transformCar($car, $other = false) {
		if ($car) {
			$response = [
				'id' => $car->id,
				'year' => $car->year,
				'mark' => $car->mark,
				'model' => $car->model,
				'color' => $car->color,
				'body_type' => $car->body_type,
				'vehicle_type' => $car->vehicle_type,
				'img' => $car->images ? $this->transformImages($car->images) : []
			];

			if (!$other)
				$response['number'] = $car->number;

			return $response;
		}

		return null;
	}

	public function transformImage($image) {
		return [
			'id' => $image->id,
			'thumb' => $image->thumbnail,
            'main' => $image->regular,
            'height' => $image->height,
            'width' => $image->width
		];
	}

	public function transformCategory($category, $sub = false, $userSubscriptions = false) {
		if ($sub)
			return [
				'id' => $category->id,
				'title' => $category->title,
				'categories' => $this->transformCategories($category->categories, false, $userSubscriptions),
			];

		$resp =  [
			'id' => $category->id,
			'title' => $category->title,
			'description' => $category->description,
			'users_count' => $category->subscriptions_count,
			'posts_count' => $category->posts_count,
			'comments_count' => $category->comments_count
		];

		if ($userSubscriptions)
			$resp['subscribed'] = $userSubscriptions->filter(function($subscription) use ($category) {
				return $subscription->id == $category->id;
			})->count();

		return $resp;
	}

	public function transformAttachments($postable) {
		$attachment = [];

		foreach ($postable->geos as $geo) {
			$attachment[] = [
				'long' => $geo->long,
				'lat' => $geo->lat,
				'location' => $geo->location,
				'id' => $geo->id,
				'type' => 'Geo'
			];
		};

		$postable->cars->each(function ($car) use($attachment) {
			array_push($attachment, ['Car' => $this->transformCar($car, true)]);
		});

		foreach ($postable->cars as $car) {
			$car = $this->transformCar($car, true);
			$car['type'] = 'Car';
			array_push($attachment, $car);
		};

		foreach ($postable->carsWithNumbers as $car) {
			$car = $this->transformCar($car);
			$car['type'] = 'CarNumber';
			array_push($attachment, $car);
		}

		foreach ($postable->images as $image) {
			$attachment[] = [
				'id' => $image->id,
				'type' => 'Image',
				'thumb' => $image->thumbnail,
				'main' => $image->regular,
				'height' => $image->height,
				'width' => $image->width
			];
		};

		return $attachment;
	}

	public function transformPost(\Post $post, Collection $favorites, Collection $subscriptions) {
		$response =  [
			'id' => $post->id,
			'text' => $post->text,
			'created_at' => $post->created_at,
			'updated_at' => $post->updated_at,
			'user' => $this->transformUserToSmall($post->user),
			'attachments' => $this->transformAttachments($post),
			'comments_count' => $post->comments->count(),
			'likes' => $post->likes->count(),
			'commented' => $post->commented(),
			'liked' => $post->liked() ? 1 : 0,
			'is_fav' => $favorites->filter(function($favorite) use($post) {
				return $favorite->id == $post->id;
			})->count() == 1 ? 1 : 0,
			'category' => [
				'id' => $post->category->id,
				'title' => $post->category->title,
				'subscribed' => $subscriptions->filter(function ($subscription) use ($post) {
					return $subscription->id == $post->category->id;
				})->count() == 1 ? 1 : 0
			]
		];

//		if ($comments)
//			$response['comments'] = $this->transformComments($post->comments);

		return $response;
	}

	public function transformComment($comment) {
		return [
			'id' => $comment->id,
			'text' => $comment->text,
			'created_at' => $comment->created_at,
			'updated_at' => $comment->updated_at,
			'user' => $this->transformUserToSmall($comment->user),
			'likes' => $comment->likes->count(),
			'attachments' => $this->transformAttachments($comment),
			'liked' => $comment->liked() ? 1 : 0
		];
	}

	public function transformUserToSmall($user) {
		return [
			"id" => $user->id,
			"name" => $user->name,
			"img" => [
				"thumb" => $user->img_small,
				"middle" => $user->img_middle,
				"origin" => $user->img_large
			]
		];
	}

	public function transformSubscription($subscription) {
		return [
			'id' => $subscription->id,
			'title' => $subscription->title
		];
	}

	public function transformContact($user) {
		$resp['user'] = $this->transformUserToSmall($user);
		$resp['car'] = $user->cars->count() > 0 ? $this->transformCar($user->cars->first()) : null;
		return $resp;
	}
}