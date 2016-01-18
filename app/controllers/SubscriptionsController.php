<?php

use Helpers\Transformers\CollectionTransformer;

class SubscriptionsController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Subscribe to category.
	 *
	 * @return Response
	 */
	public function subscribe() {
		if (!Input::has('id'))
			return $this->respondInvalidApi('Please provide category id');

		$user = Auth::user();

		$id = Input::get('id');

		if (is_array($id)) {
			$categories = Category::findMany($id)->filter(function($category) {
				return $category->parent_id != 0;
			});

			if ($categories->count() > 0)
				$user->subscriptions()->sync($id);

			return $this->respondNoContent();
		}

		$category = Category::find(Input::get('id'));

		if (!$category)
			return $this->respondNotFound('Post not found');

		// if already in subscriptions
		if ($user->subscriptions->filter(function ($subscription) use ($category) {
			return $category->id == $subscription->id;
		})->count() >= 1)
			return $this->respondNoContent();

		if ($category->parent_id == 0)
			return $this->respondInvalidApi('Cant subscribe to High level categories');

		$user->subscriptions()->attach($category->id);
		$user->save();

		$category->updateCount('subscriptions');

		return $this->respondNoContent();
	}

	public function unsubscribe($id) {
		$user = Auth::user();

		$category = Category::find($id);

		if (!$category)
			return $this->respondNotFound('Category Not found');

		if ($user->subscriptions()->detach($id)) {
			$category->updateCount('subscriptions');
			return $this->respondNoContent();
		}

		return $this->respondServerError();
	}

	/**
	 * Show list of subscri
	 *
	 * @return Response
	 */
	public function subscriptions() {
		$user = Auth::user();

		return $this->respond($this->collectionTransformer->transformSubscriptions($user->subscriptions));
	}

}