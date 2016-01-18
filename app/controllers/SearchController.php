<?php

use Helpers\Transformers\CollectionTransformer;

class SearchController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	public function search() {

		//TODO Rate limit

		$query = Input::get('query');
		$max = Input::has('size') && Input::get('size') < 61 ? Input::get('size') : 25;
		$last = Input::has('last') ? Input::get('last') : Post::orderBy('id', 'desc')->first()->id;

		$posts = Post::where('text', 'ilike', '%'.$query.'%')
			->where('id', '<', $last)
			->orderBy('id', 'desc')
			->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
			->take($max)
			->get();

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	public function searchUsers($id) {
		$user = User::find($id);

		if (!$user)
			return $this->respond([]);

		return $this->respond($this->collectionTransformer->transformOtherUser($user));
	}
}