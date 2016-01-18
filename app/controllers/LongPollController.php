<?php

use Helpers\Transformers\CollectionTransformer;

class LongPollController extends ApiController {

	private $timeout = 5;
	private $maxTries = 6;
	private $count = 10;

	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	public function check() {
		$response = [];

		if (!Input::has('timestamp'))
			return $this->respondInsufficientPrivileges('No timestamp');

		$i = 0;

		while (true) {
			$i++; // set_time_limit issue
			if ($i > $this->maxTries) return $this->respondNoContent();

			array_push($response, ['promotions' => []]);
			array_push($response, ['notifications' => []]);

			$userPostComments = $this->getUserPostComments();

			if (Input::has('post'))
				$comments = $this->getComments();

			if (Input::has('category'))
				$posts = $this->getPosts();

			if (isset($posts) && $posts != false && !$posts->isEmpty()) 
				array_push($response, ['category' => 
					['id' => Input::get('category'), 
					 'posts' => $this->collectionTransformer->transformPosts($posts)]]);

			if (isset($comments) && $comments != false && !$comments->isEmpty())
				array_push($response, ['post' =>
					['id' => Input::get('post'),
					'comments' => $this->collectionTransformer->transformComments($comments)]]);

			if ($userPostComments)
				array_push($response, ['user-post-comments' => $userPostComments]);

			else {
				sleep($this->timeout);
				continue;
			}

			return $response;

		}

	}

	private function getPosts() {

		$category = Category::find(Input::get('category'));

		if (is_null($category))
			return false;

		return $category->posts()->where('created_at', '>', Input::get('timestamp'))->take($this->count)->get();

	}

	private function getComments() {

		$post = Post::find(Input::get('post'));

		if (is_null($post))
			return false;

		return $post->comments()->where('created_at', '>', Input::get('timestamp'))->take($this->count)->get();

	}

	private function getUserPostComments() {
		$response = [];

		$user = Auth::user();

		$posts = $user->posts()->with(['comments' => function($query) {
			$query->with('user')->where('created_at', '>', Input::get('timestamp'));
		}])->get();

		if ($posts->isEmpty())
			return false;

		foreach ($posts as $post) {
			if ($post->comments->isEmpty())
				continue;

			array_push($response, 
				['id' => $post->id,
				'created_at' => $post->created_at->toISO8601String(),
				'updated_at' => $post->updated_at->toISO8601String(),
				'comments' => $this->collectionTransformer->transformComments($post->comments)]);
		}

		return $response;

	}
}