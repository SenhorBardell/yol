<?php

use Helpers\Transformers\CollectionTransformer;

class CommentsController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	private $max = 25;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Display a listing of the resource.
	 * GET /posts/{id}/comments
	 *
     * @param int $id
	 * @return Response
	 */
	public function index($id) {

		if (Input::has('size') || Input::get('size') > 61)
			$max = Input::get('size');
		else
			$max = 25;

		if (Input::has('timestamp'))
			$comments = Comment::byTimestampAndPost(Input::get('timestamp'), $id)->orderBy('id', 'desc')->take($max)->get();

		elseif (Input::has('last'))
			$comments = Comment::byLastAndPost(Input::get('last'), $id)->take($max)->orderBy('id', 'desc')->get();

		else
			$comments = Comment::byPost($id)->orderBy('id', 'desc')->take($max)->get();

		if ($comments->isEmpty())
			return $this->respond([]);

		return $this->respond($this->collectionTransformer->transformComments($comments));
	}

	/**
	 * Comments index for admin page
	 * @return mixed
	 */
	public function adminIndex() {
		$comments = Comment::with('user', 'likes', 'user')->orderBy('updated_at', 'desc')->paginate();
		return View::make('admin.dicts.list', [
			'columns' => ['ID', 'Пользователь', 'Текст', 'Пост', 'Лайки', '', ''],
			'data' => $comments->transform(function ($comment) {
				return [
					'id' => $comment->id,
					'user' => link_to("admin/users/{$comment->user->id}", $comment->user->name),
					'text' => link_to("admin/comments/{$comment->id}/edit", $comment->text),
					'post_text' => link_to("admin/posts/{$comment->post_id}/edit", $comment->post->text),
					'likes' => $comment->likes->count(),
					'edit' => link_to("admin/comments/{$comment->id}/edit", 'редактировать'),
					'delete' => link_to("admin/comments/{$comment->id}/delete", 'удалить')
				];
			}),
			'actions' => [
				['link' => 'admin/comments/delete', 'text' => 'Удалить выбранное']
			],
			'title' => 'Комментарии',
			'links' => $comments->links()
		]);
	}

	public function adminEdit($id) {
		$comment = Comment::with('cars', 'carsWithNumbers')->find($id);
		if ($comment)
			return View::make('admin.comments.edit', compact('comment'));
		App::abort(404);
	}

	public function adminUpdate($id) {
		$comment = Comment::find($id);

		if (!$comment) App::abort(404);

		$comment->fill(Input::all());

		if (Input::has('car')) {
			$comment->cars()->detach();
			$user = User::find($comment->user_id);
			$carHistory = $user->carsHistory()->create([
				'mark' => Input::get('car')['mark'] != 0 ?: null,
				'model' => Input::get('car')['model'] != 0 ?: null,
				'year' => Input::get('car')['year'] != 0 ?: null,
				'color' => Input::get('car')['color'] != 0 ?: null,
				'body_type' => Input::get('car')['body_type'] != 0 ?: null
			]);
			$comment->cars()->attach($carHistory->id);
		}

		if (Input::has('carNumber')) {
			$comment->carsWithNumbers()->detach();
			$user = User::find($comment->user_id);
			$carNumber = $user->carsHistory()->create([
				'mark' => Input::get('carNumber')['mark'] != 0 ?: null,
				'model' => Input::get('carNumber')['model'] != 0 ?: null,
				'year' => Input::get('carNumber')['year'] != 0 ?: null,
				'color' => Input::get('carNumber')['color'] != 0 ?: null,
				'body_type' => Input::get('carNumber')['body_type'] != 0 ?: null
			]);
			$comment->carsWithNumbers()->attach($carNumber->id);
		}

		if (Input::has('images')) {
			foreach (Input::get('images') as $image) {
				dd($image);
			}
		}

		if ($comment->save())
			return Redirect::to('/admin/comments');

		App::abort(500);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST posts/{id}/comments
	 *
     * @param int $id
	 * @return Response
	 */
	public function store($id) {
		$user = Auth::user();

//		$validator = Comment::validate(Input::all());
//
//		if ($validator->fails())
//			return $this->respondInsufficientPrivileges($validator->messages()->all());

		if (Input::get('text') == '' & !Input::has('attachments'))
			return $this->respondInsufficientPrivileges('Send some text');

		if (strlen(Input::get('text')) > 2500)
			return $this->respondInsufficientPrivileges('Слишком длинный текст');

		$post = Post::find($id);

		if (!$post)
			return $this->respondNotFound('Post not found');

		$comment = new Comment(Input::all());
		$comment->user()->associate(Auth::user());

		$category = $post->category;

		if ($category && $post->comments()->save($comment)) {
			if($post->user_id != $comment->user_id
			   && ($device = Device::where('user_id', $comment->user_id)
								   ->first())
			) {
				$token = $device->auth_token;

				$state = new StateSender($token);
                $state->setPostAsCommented($post, $comment, Auth::user());
			}

//			$category->updateCount('comments');

			if (Input::has('attachments')) {
				$attachments = Input::get('attachments');
				foreach ($attachments as $attachment) {
					$carHelper = new Helpers\carHelper();

					if ($attachment['type'] == 'Geo') {
						$geo = Geo::create(['long' => $attachment['long'], 'lat' => $attachment['lat'], 'location' => $attachment['location']]);
						$comment->geos()->save($geo);
					}

					if ($attachment['type'] == 'Car') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$comment->cars()->attach($car->id);
					}

					if ($attachment['type'] == 'CarNumber') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$comment->carsWithNumbers()->attach($car->id);
					}

					if ($attachment['type'] == 'Image') {
						$image = Image::find($attachment['id']);
						if ($image)
							$comment->images()->save($image);
					}
				}
			}

			$comment->load('cars', 'geos', 'images');

			return $this->respond($this->collectionTransformer->transformComment($comment));
		}

		return $this->respondServerError();
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /comments/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
//		$validator = Comment::validate(Input::all());
		$user = Auth::user();

//		if ($validator->fails())
//			return $this->respondInsufficientPrivileges($validator->messages()->all());

		if (Input::get('text') == '' & !Input::has('attachments'))
			return $this->respondInsufficientPrivileges('Send some text');

		if (strlen(Input::get('text')) > 2500)
			return $this->respondInsufficientPrivileges('Слишком длинный текст');

		$comment = Comment::find($id);

		if (!$comment)
			return $this->respondNotFound('Comment not found');

		if (!$user->can('Comment.update', $comment))
			return $this->respondInsufficientPrivileges('Unauthorized action');

		$comment->fill(Input::all());

		if ($comment->save()) {

			if (Input::has('attachments') && !empty(Input::get('attachments'))) {

				$attachments = Input::get('attachments');

				$comment->cars()->detach();
				$comment->geos()->detach();
				$comment->carsWithNumbers()->detach();

				foreach ($attachments as $attachment) {
					$carHelper = new Helpers\carHelper();

					if ($attachment['type'] == 'Geo') {
						$geo = Geo::create(['long' => $attachment['long'], 'lat' => $attachment['lat'], 'location' => $attachment['location']]);
						$comment->geos()->save($geo);
					}

					if ($attachment['type'] == 'Car') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$comment->cars()->attach($car->id);
					}

					if ($attachment['type'] == 'CarNumber') {
						$car = $carHelper::fetchCar($user, $attachment['id']);
						if ($car)
							$comment->carsWithNumbers()->attach($car->id);
					}

					if ($attachment['type'] == 'Image') {
						$image = Image::find($attachment['id']);

						if ($image && !$comment->images()->find($attachment['id']))
							$comment->images()->save($image);

						if ($comment->images()->find($attachment['id']))
							$images[] = $image->id;
					}
				}

				if (isset($images)) {
					$comment->images()->whereNotIn('id', $images)->delete();
				} else {
					$comment->images()->delete();
				}

			} else {
				$comment->images()->delete();
				$comment->cars()->detach();
				$comment->geos()->detach();
				$comment->carsWithNumbers()->detach();
			}

			return $this->respond($this->collectionTransformer->transformComment($comment));
		}
		return $this->respondServerError();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /comments/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		$validator = $this->validateId($id);

		if($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$comment = Comment::find($id);

		if($comment) {
			$post = $comment->post;
			$category = $post->category;

			if($comment->delete()) {

				State::whereRaw('object=\'comment\' and object_id=?', array($id))
					 ->delete();

				NotificationUser::whereRaw('subject=\'comment\' and subject_id=? and is_removed=0', array($id))
								->get()->each(function ($notificationsUser) {
						$notificationsUser->is_removed = 1;
						$notificationsUser->save();
					});

				Notification::whereRaw('object=\'post\' and event=\'commented\' and is_removed=0 and ' .
									   '(select count(nu.id) from notifications_users nu where nu.notification_id=notifications.id and nu.is_removed=0)=0')
							->get()->each(function ($notification) {
						$notification->is_removed = 1;
						$notification->save();
					});

				return $this->respondNoContent();
			}
		} else {
			return $this->respondNotFound('Comment not found');
		}

		return $this->respondServerError();
	}

	public function setAsRead($commentId) {
		if((int)$commentId > 0
		   && !is_null($comment = Comment::find((int)$commentId))
		) {
			if(($token = $comment->getOwnerToken())) {
				$state = new StateSender($token);
				$state->setCommentAsRead($commentId);
				$state->send();
			}

			return $this->respondNoContent();
		} else {
			return $this->respondWithError('Comment doesn\'t exist');
		}
	}

	public function delete($id) {
		$comment = Comment::find($id);
		return View::make('admin.comments.delete', compact('comment'));
	}

	public function adminDestroy($id) {
		if (Comment::find($id)->delete())
			return Redirect::to('admin/comments');

		return App::abort();
	}

}
