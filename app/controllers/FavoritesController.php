<?php

use Helpers\Transformers\CollectionTransformer;
use Illuminate\Database\Eloquent\Collection;

class FavoritesController extends ApiController {

	protected $collectionTransformer;

	public function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	public function likes() {
		$user = Auth::user();
		$size = Input::has('size') && Input::get('size') < 25 ? Input::get('size') : 25;
		$last = Input::has('last') ? Input::get('last') * $size: 0;

//		if ($last)
//			$likes = $user->likes()->orderBy('id', 'desc')->where('likeable_type', 'Post')->take($size)->get();

		//TODO pagination
//		$likes = $user->likes()->orderBy('id', 'desc')->take($size)->get()->filter(function($like) {
//			return $like->likeable_type == 'Post';
//		});

		$likes = $user->likes()->where('likeable_type', 'Post')->take($size)->skip($last)->orderBy('id')->get();

//		echo 'Likes';
//		var_dump($likes->map(function ($like) {
//			return $like->likeable_id;
//		}));

		if ($likes->count() == 0)
			return $this->respond([]);


		$posts = Post::whereIn('id', $likes->map(function ($like) {
			return $like->likeable_id;
		})->toArray())
			->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'carsWithNumbers', 'cars.images', 'category')
			->orderBy('id', 'desc')
			->get();

//		echo 'Posts';
//		var_dump($posts->map(function ($post) {
//			return $post->id;
//		}));

		$posts = $this->sortByLikes($posts, $likes);

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	private function sortByLikes(Collection $collection,Collection $orderArray) {
		$sorted = new Collection();
		$orderArray->each(function ($orderElem) use ($collection, $sorted) {
			if ($collection->contains($orderElem->likeable_id))
				$sorted->push($collection->find($orderElem->likeable_id));
		});
		return $sorted;
	}

	public function store() {

		if (!Input::has('id'))
			return $this->respondNotFound('Post id has not been provided');

		$user = Auth::user();
		$id = Input::get('id');
		$favorite = $this->getByPost($id, $user->id);

		if ($favorite)
			return $this->respondNoContent();

		Favorite::create(['post_id' => $id, 'user_id' => $user->id, 'created_at' => Carbon\Carbon::now()]);

		return $this->respondNoContent();
	}

	public function unfavorite() {
		if (!Input::has('id'))
			return $this->respondNotFound('Post id has not been provided');

		$id = Input::get('id');
		$user = Auth::user();
		$favorite = $this->getByPost($id, $user->id);

		if (!$favorite)
			return $this->respondNotFound('Favorite post not found');

		if ($favorite->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	private function getByPost($id, $user_id) {
		return Favorite::where('post_id', $id)->where('user_id', $user_id)->first();
	}

	public function index() {
		$user = Auth::user();
		$size = Input::has('size') && Input::get('size') < 40 ? Input::get('size') : 25;
		$last = Input::has('last') ? Input::get('last') * $size : 0;

		$posts = $user->favorites()->orderBy('pivot_created_at', 'desc')->take($size)->skip($last)->get();

		$posts->load('user', 'likes', 'comments', 'images', 'geos', 'cars', 'carsWithNumbers', 'cars.images', 'category');

		return $this->respond($posts->map(function ($post) use($user) {
			return [
				'id' => $post->id,
				'text' => $post->text,
				'created_at' => $post->created_at,
				'updated_at' => $post->updated_at,
				'added_at' => strtotime($post->pivot->created_at),
				'user' => $this->collectionTransformer->transformUserToSmall($post->user),
				'attachments' => $this->collectionTransformer->transformAttachments($post),
				'comments_count' => $post->comments->count(),
				'likes' => $post->likes->count(),
				'commented' => $post->commented(),
				'liked' => $post->liked() ? 1 : 0,
				'is_fav' => 1,
				'category' => [
					'id' => $post->category->id,
					'title' => $post->category->title,
					'subscribed' => $user->subscriptions->filter(function($subscription) use ($post) {
						return $subscription->id == $post->category->id;
					})->count() == 1 ? 1 : 0
				]
			];
		}));
	}
}