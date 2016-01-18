<?php

use Carbon\Carbon;
use Helpers\Transformers\CollectionTransformer;

class PostsController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	/**
	 * Max posts per page
	 * @var integer
	 */
	private $max = 10;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Display all post of all categories
	 * GET /posts
	 *
	 * @return  Response
	 */
	public function all() {
		if (!Auth::user()->can('Post.view')) {
			return $this->respondInsufficientPrivileges('Unauthorized');
		}

		if (Input::has('last')) {
			$posts = Post::last(Input::get('last'))->take($this->max)->get();
		} else {

			$posts = Post::take($this->max)->get();
		}

		if ($posts->isEmpty()) {
			return $this->respondNotFound('No posts');
		}

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	public function adminIndexAll() {
		$posts = Post::orderBy('updated_at', 'desc')->paginate();
		return View::make('admin.dicts.list', [
				'columns' => ['ID', 'Пользователь', 'Текст', 'Рубрика', 'Лайки',
					'Комментарии', 'Время', '', ''],
				'data' => $posts->transform(function ($post) {
						return [
							'id'       => $post->id,
							'user'     => link_to("admin/users{$post->user_id}", $post->user->name),
							'text'     => link_to("admin/posts/{$post->id}/edit", $post->text),
							'category' => link_to("/admin/categories/{$post->category_id}/edit", $post->category->title),
							'likes'    => link_to("admin/posts/{$post->id}/likes", $post->likes->count()),
							'comments' => link_to("admin/posts/{$post->id}/comments", $post->comments->count()),
							'time'     => Carbon::createFromTimestamp($post->created_at),
							'edit'     => link_to("/admin/posts/{$post->id}/edit", 'редактировать'),
							'delete'   => link_to("/admin/posts/{$post->id}/delete", 'удалить')
						];
					}),
				'actions' => [
					['link'  => 'admin/posts/delete', 'text'  => 'Удалить выбранное']
				],
				'links' => $posts->links(),
				'title' => 'Посты'
			]);
	}

	/**
	 * Display a listing of the resource.
	 * GET categories/{id}/posts
	 *
	 * @param int $id
	 * @return Response
	 */
	public function index($id) {
		$category = Category::find($id);

		if (!$category) {
			return $this->respondNotFound('Category not found');
		}

		if (Input::has('size')) {
			$this->max = Input::get('size') > 61?$this->max:Input::get('size');
		}

		if (Input::has('last')) {
			$posts = $category
				->posts()
				->where('id', '<', Input::get('last'))
				->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
				->take($this->max)
				->orderBy('id', 'desc')
				->get();
		} else {

			$posts = $category
				->posts()
				->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
				->take($this->max)
				->orderBy('id', 'desc')
				->get();
		}

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST categories/{id}/posts
	 *
	 * @param int $id
	 * @return Response
	 */
	public function store($id) {
		$user     = Auth::user();
		$category = Category::find($id);

		if (!$category)
			return $this->respondNotFound('category not found');

		if (strlen(Input::get('text')) > 10000)
			return $this->respondInsufficientPrivileges('Слишком длинный текст');

		$post = new Post(Input::all());
		$post->user()->associate($user);

		if ($category->posts()->save($post)) {
			//			$category->updateCount('posts');

			if (Input::has('attachments')) {
				$attachments = Input::get('attachments');
				foreach ($attachments as $attachment) {
					$carHelper = new Helpers\carHelper();

					if ($attachment['type'] == 'Geo') {
						$geo = Geo::create(['long' => $attachment['long'], 'lat' => $attachment['lat'], 'location' => $attachment['location']]);
						$post->geos()->save($geo);
					}

					if ($attachment['type'] == 'Car') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$post->cars()->attach($car->id);
					}

					if ($attachment['type'] == 'CarNumber') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$post->carsWithNumbers()->attach($car->id);
					}

					if ($attachment['type'] == 'Image') {
						$image = Image::find($attachment['id']);
						if ($image) {
							$post->images()->save($image);
						}
					}
				}
			}

			$post->load('cars', 'geos', 'images');

			return $this->respond($this->collectionTransformer->transformPost($post, $user->favorites, $user->subscriptions));
		}

		return $this->respondServerError();
	}

	/**
	 * Display the specified resource.
	 * GET /posts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$user = Auth::user();
		$post = Post::find($id);

		if (Input::has('size')) {
			$size = Input::get('size') <= 25?Input::get('size'):$this->max;
		} else {

			$size = $this->max;
		}

		if (!$post) {
			return $this->respondNotFound();
		}

		$comments = $post->comments()->orderBy('id', 'desc')->take($size)->get();

		if (!$comments->isEmpty()) {
			$post     = $this->collectionTransformer->transformPost($post, $user->favorites, $user->subscriptions);
			$comments = $this->collectionTransformer->transformComments($comments);
			return $this->respond(['post' => $post, 'comments' => $comments]);
		} else {
			return $this->respond(['post' => $this->collectionTransformer->transformPost($post, $user->favorites, $user->subscriptions), 'comments' => []]);
		}

		return $this->respondServerError();
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /posts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$user = Auth::user();
		//		$validator = Post::validate(Input::all());
		//
		//		if ($validator->fails())
		//			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$post = Post::find($id);

		if (!$post) {
			return $this->respondNotFound('Post not found');
		}

		if (!$user->can('Post.update', $post)) {
			return $this->respondInsufficientPrivileges('Unauthorized action');
		}

		if (strlen(Input::get('text')) > 10000)
			return $this->respondInsufficientPrivileges('Слишком длинный текст');

		$post->fill(Input::all());

		if ($post->save()) {
			if (Input::has('attachments') && !empty(Input::get('attachments'))) {

				$post->cars()->detach();
				$post->geos()->detach();
				$post->carsWithNumbers()->detach();

				$attachments = Input::get('attachments');
				foreach ($attachments as $attachment) {
					$carHelper = new Helpers\carHelper();

					if ($attachment['type'] == 'Geo') {
						$geo = Geo::create(['long' => $attachment['long'], 'lat' => $attachment['lat'], 'location' => $attachment['location']]);
						$post->geos()->save($geo);
					}

					if ($attachment['type'] == 'Car') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$post->cars()->attach($car->id);
					}

					if ($attachment['type'] == 'CarNumber') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$post->carsWithNumbers()->attach($car->id);
					}

					if ($attachment['type'] == 'Image') {
						$image = Image::find($attachment['id']);

						if ($image && !$post->images()->find($attachment['id'])) {
							$post->images()->save($image);
						}

						if ($post->images()->find($attachment['id']))
							$images[] = $image->id;


					}
				}

				if (isset($images)) {
					$post->images()->whereNotIn('id', $images)->delete();
				} else {
					$post->images()->delete();
				}

			} else {
				$post->images()->delete();
				$post->cars()->detach();
				$post->geos()->detach();
				$post->carsWithNumbers()->detach();
			}
			$post->load('cars', 'geos', 'images');

			return $this->respond($this->collectionTransformer->transformPost($post, $user->favorites, $user->subscriptions));
		}

		return $this->respondServerError();
	}

	public function adminUpdate($id) {
//		dd(Input::all());
		$post = Post::find($id);

		if (!$post) App::abort(404);

		$post->fill(Input::all());

		if (Input::has('geo')) {
			$post->geos()->detach();
			$post->geos()->save(Geo::create([
				'long' => Input::get('geo')['long'],
				'lat' => Input::get('geo')['lat'],
				'location' => Input::get('geo')['location']
			]));
		}

		if (Input::has('car')) {
			$post->cars()->detach();
			$user = User::find($post->user_id);
			$carHistory = $user->carsHistory()->create([
				'mark' => Input::get('car')['mark'] != 0 ?: null,
				'model' => Input::get('car')['model'] != 0 ?: null,
				'year' => Input::get('car')['year'] != 0 ?: null,
				'color' => Input::get('car')['color'] != 0 ?: null,
				'body_type' => Input::get('car')['body_type'] != 0 ?: null,
			]);
			$post->cars()->attach($carHistory->id);
		}

		if (Input::has('carNumber')) {
			$post->carsWithNumbers()->detach();
			$user = User::find($post->user_id);
			$carNumber = $user->carsHistory()->create([
				'mark' => Input::get('carNumber')['mark'] != 0 ?: null,
				'model' => Input::get('carNumber')['model'] != 0 ?: null,
				'year' => Input::get('carNumber')['year'] != 0 ?: null,
				'color' => Input::get('carNumber')['color'] != 0 ?: null,
				'body_type' => Input::get('carNumber')['body_type'] != 0 ?: null
			]);
			$post->carsWithNumbers()->attach($carNumber->id);
		}

		if (Input::has('images')) {
			foreach (Input::get('images') as $image) {
				dd($image);
			}
		}

		if ($post->save())
			return Redirect::to('/admin/posts');

		App::abort(500);
	}

	public function userPosts($id) {
		$user = User::find($id);

		if (!$user) {
			return $this->respondNotFound('User not found');
		}

		$max = 25;

		if (Input::has('size')) {
			$max = Input::get('size') > 61?$max:Input::get('size');
		}

		if (Input::has('last')) {
			$posts = $user->posts()
			              ->where('id', '<', Input::get('last'))
			              ->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
			              ->orderBy('id', 'desc')
			              ->take($max)
			              ->get();
		} else {

			$posts = $user->posts()
			              ->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
			              ->orderBy('id', 'desc')
			              ->take($max)
			              ->get();
		}

		$posts->reverse();

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /posts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		$post = Post::find($id);

		if (!$post) {
			return $this->respondNotFound();
		}

		$user = Auth::user();

		if (!$user->can('Post.delete', $post)) {
			return $this->respondInsufficientPrivileges();
		}

		if ($post->delete()) {
			$category = $post->category;
			$category->updateCount('posts');

			$commentsIds = array();
			Comment::where('post_id', $id)
				->get()	->each(function ($comment) use ($commentsIds) {
					$commentsIds[] = $comment->id;
				});

			if (count($commentsIds) > 0) {
				State::whereRaw('object=\'comment\'')->whereIn('object_id', $commentsIds)
				                                     ->delete();
			}

			$post->comments()->delete();
			$category->updateCount('comments');

			State::whereRaw('object=\'post\' and object_id=?', array($id))
				->delete();

			Notification::whereRaw('object=\'post\' and object_id=? and is_removed=0', array($id))
				->get()	->each(function ($notification) {
					NotificationUser::where('notification_id', $notification->id)
						->get()->each(function ($notificationsUser) {
							$notificationsUser->is_removed = 1;
							$notificationsUser->save();
						});

					$notification->is_removed = 1;
					$notification->save();
				});

			return $this->respondNoContent();
		}

		return $this->respondServerError();
	}

	public function setAsRead($postId) {
		if ((int) $postId > 0
			 && !is_null($post = Post::find((int) $postId))
		) {
			if (($token = $post->getOwnerToken())) {
				$state = new StateSender($token);
				$state->setPostAsRead($postId);

				Comment::where('post_id', $postId)
					->get()	->each(function ($comment) use ($state) {
						$state->setCommentAsRead($comment->id);
					});

				$state->send();
			}

			return $this->respondNoContent();
		} else {
			return $this->respondWithError('Post doesn\'t exist');
		}
	}

	//TODO Move duplicate method to its according controller
	public function comments($id) {
		$post     = Post::find($id);
		$comments = $post->comments()->with('post', 'likes', 'user')->paginate();
		return View::make('admin.dicts.list', [
				'columns' => ['ID', 'Пользователь', 'Текст', 'Комментарий', 'Лайки', '', ''],
				'data'    => $comments->transform(function ($comment) {
						return [
							'id'      => $comment->id,
							'user'    => link_to("admin/users/{$comment->user->id}", $comment->user->name),
							'text'    => link_to("admin/comments/{$comment->id}/edit", $comment->text),
							'comment' => link_to("admin/posts/{$comment->post_id}", $comment->post->text),
							'likes'   => $comment->likes->count(),
							'edit'    => link_to("admin/comments/{$comment->id}/edit", 'редактировать'),
							'delete'  => link_to("admin/comments/{$comment->id}/delete", 'удалить')
						];
					}),
				'actions' => [
					['link'  => 'admin/comments/delete', 'text'  => 'Удалить выбранное']
				],
				'title' => 'Комментарии',
				'links' => $comments->links()
			]);
	}

	public function edit($id) {
		$post = Post::with('cars')->find($id);
		return View::make('admin.posts.edit', compact('post'));
	}

}