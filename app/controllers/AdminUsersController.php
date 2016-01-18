<?php

use Carbon\Carbon;

class AdminUsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /admin/users
	 *
	 * @return Response
	 */
	public function index() {
		$users = User::withTrashed();
		foreach (Input::all() as $key => $input) {
			if (in_array($key, ['vehicle_type', 'mark', 'model', 'body_type'])) {
				$users->whereHas('cars', function ($q) use ($key, $input) {
					$q->where($key, $input);
				});
			} elseif ($key == 'city')
				$users->where($key, $input);
		}
		$users = $users->with('cityRef')->paginate();
		return View::make('admin.users.index', [
			'title' => 'Пользователи',
			'columns' => ['ID', 'Аватар', 'Имя', 'Почта',
				'Номер телефона', 'Город', 'Возраст', 'Сообщений', 'Удален в', '', ''],
			'data' => $users->transform(function ($user) {
				return [
					'id' => $user->id,
					'img' => "<img src='{$user->img_small}'>",
					'name' => link_to_action('AdminUsersController@show', $user->name, [$user->id]),
					'email' => $user->email,
					'phone' => $this->returnIfPresent($user, 'phone', 'number'),
					'city' => $this->returnIfPresent($user, 'cityRef', 'ru'),
					'age' => $user->birthday,
					'messages' => $user->messages()->count(),
					'deleted' => $user->deleted_at,
					'edit' => link_to_action('AdminUsersController@edit', 'ред', [$user->id]),
					'delete' => link_to_action('AdminUsersController@edit', 'удал', [$user->id])
				];
			}),
			'links' => $users->links(),
			'actions' => [
				link_to('#modal1', 'Разослать', ['class' => 'btn btn-primary btn-md margin']),
				link_to('#modal1', 'Опубликовать в ленту', ['class' => 'btn btn-primary btn-md margin']),
				link_to('#model1', 'Удалить выбранное', ['class' => 'btn btn-primary btn-md margin'])
			],
			'filters' => [
				[
					'param' => 'city',
					'name' => 'Город',
					'data' => CityRef::all()->transform(function ($city) {
						return ['id' => $city->id, 'name' => $city->ru];
					}),
					'active' => Input::get('city')
				],
				[
					'param' => 'vehicle_type',
					'name' => 'Тип Авто',
					'data' => VehicleTypeRef::all()->transform(function ($v) {
						return ['id' => $v->id, 'name' => $v->ru];
					}),
					'active' => Input::has('vehicle_type')
				],
				[
					'param' => 'mark',
					'name' => 'Марка',
					'data' => MarkRef::all()->transform(function ($m) {
						return ['id' => $m->id, 'name' => $m->name];
					}),
					'active' => Input::has('mark')
				],
				[
					'param' => 'model',
					'name' => 'Модель',
					'data' => ModelRef::all()->transform(function ($m) {
						return ['id' => $m->id, 'name' =>$m->name];
					}),
					'active' => Input::Has('model')
				],
				[
					'param' => 'body_type',
					'name' => 'Кузов',
					'data' => BodyTypeRef::all()->transform(function ($b) {
						return ['id' => $b->id, 'name' => $b->ru];
					}),
					'active' => Input::has('body_type')
				]
			]
		]);
	}

	private function isActive($param) {
		return Input::has($param);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /adminusers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /adminusers
	 *
	 * @return Response
	 */
	public function store()
	{
	}

	private function makeLink($item, $options) {
		return [
			'link' => link_to_action(
				"{$options['controller']}@{$item['action']}",
				$item['text'],
				$options['params'],
				['class' => 'btn btn-primary btn-md margin']
			),
			'text' => $item['text']
		];
	}

	function makeLinks($list, $options) {
		return array_map(function ($item) use ($options) {
			return $this->makeLink($item, $options);
		}, $list);
	}

	function expandSingle($item) {
		return ['action' => $item[0], 'text' => $item[1]];
	}

	function expandList($list) {
		return array_map([$this, 'expandSingle'], $list);
	}

	/**
	 * Display the specified resource.
	 * GET /admin/users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$user = User::withTrashed()->find($id);
		return View::make('admin.users.show', [
			'title' => $user->name,
			'img' => $user->img_middle,
			'id' => $user->id,
			'user' => [
				['Имя', $user->name],
				['Пол', $user->sex],
				['День рождения', $user->birthday],
				['Город', $this->returnIfPresent(CityRef::find($user->city), 'ru')],
				['Телефон', $this->returnIfPresent($user->phone, 'number')],
				['Почта', $user->email],
				['О себе', $user->about]
			],
			'actions' => $this->makeLinks($this->expandList(
				[
					['show','Профиль'],
					['cars','Список машин'],
					['chats','Чаты'],
					['blackList','Блокированные пользователи'],
					['posts','Посты'],
					['comments','Комментарии'],
					['devices','Устройства'],
					['images', 'Изображения']
				]), [
						'controller' => 'AdminUsersController',
						'params' => [$user->id]
				]),
			'statistics' => [
				['string' => 'отправленных сообщений', 'value' => $user->messages()->count()],
				['string' => 'отправленных срочных вызовов', 'value' => $user->emergencies()->count()],
				['string' => 'сделанных пользователем первых контактов', 'value' => 12],
				['string' => 'постов', 'value' => $user->posts->count()],
				['string' => 'полученныз пользователем комментариев', 'value' => $user->comments->count()],
				['string' => 'полученных пользователем лайков', 'value' => $user->likes->count()],
				['string' => 'отправленных жалоб', 'value' => $user->complaints()->count()],
				['string' => 'жалоб на пользователя', 'value' => $user->complaintsToMe()->count()],
				['string' => 'блокированных пользователем', 'value' => $user->blockedUsers()->count()],
				['string' => 'пользователей заблокировавших данного пользователя', 'value' => $user->blockedMeUsers()->count()],
			]
		]);
	}

	public function cars($id) {
		$user = User::find($id);
		$cars = User::find($id)->cars()->paginate();

		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Изображение', 'Модель', 'Марка', 'Цвет', 'Тип кузова', 'Номер', '', ''],
			'data' => $cars->transform(function ($car) {
				return [
					'id' => $car->id,
					'img' => $car->images->first() ? HTML::image($car->images->first()->thumbnail, null, ['height' => '50px']) : '',
					'mark' => $this->returnIfPresent($car, 'markRef', 'name'),
					'model' => $this->returnIfPresent($car, 'modelRef', 'name'),
					'color' => $car->color,
					'body_type' => $this->returnIfPresent($car, 'bodyTypeRef', 'ru'),
					'number' => $car->number,
					'edit' => link_to_action('CarsController@show', 'ред', [$car->user_id, $car->id]),
					'delete' => link_to("admin/cars/{$car->id}/delete", 'удалить')
				];
			}),
			'actions' => $this->actions($user),
			'links' => $cars->links(),
			'title' => 'Машины пользователя'
		]);
	}

	public function chats($id) {
		$user = User::find($id);
		$chats = $user->chats()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Изображение', 'Пользователь', 'Сообщения', 'Время', ''],
			'data' => $chats->transform(function ($chat) {
				$user = User::find($chat->pivot->user_id);
				$message = $chat->lastMessage();
				$resp =  [
					'id' => $chat->id,
					'img' => HTML::image($user->img_small),
					'user' => link_to("admin/users/{$user->id}", $user->name),
					'message' => '',
					'time' => '',
					'delete' => link_to("admin/chats/{$chat->id}/delete", 'удалить')
				];
				if ($message) {
					$resp['message'] = link_to("admin/chats/{$chat->id}", $message->text);
					$resp['time'] = $message->timestamp;
				}
				return $resp;
			}),
			'links' => $chats->links(),
			'title' => 'Чаты',
			'actions' => $this->actions($user)
		]);
	}

	public function blackList($id) {
		$currentUser = User::find($id);
		$list = $currentUser->blockedUsers()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Изображение', 'Пользователь', ''],
			'data' => $list->transform(function($user) use($currentUser) {
				return [
					'id' => $user->id,
					'img' => HTML::image($user->img_small),
					'name' => link_to("admin/users/{$user->id}", $user->name),
					'delete' => link_to("admin/users/{$currentUser->id}/black-list/{$user->id}/delete")
				];
			}),
			'links' => $list->links(),
			'title' => 'Черный список',
			'actions' => $this->actions($currentUser)
		]);
	}

	public function posts($id) {
		$user = User::find($id);
		$posts = $user->posts()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Текст', 'Категория', 'Лайки', 'Комментарии', 'Время', '', ''],
			'data' => $posts->transform(function ($post) {
				return [
					'id' => $post->id,
					'text' => link_to("admin/posts/{$post->id}", $post->text),
					'category' => link_to("/admin/categories/{$post->category_id}/edit", $post->category->title),
					'likes' => link_to("admin/posts/{$post->id}/likes", $post->likes->count()),
					'comments' => link_to("admin/posts/{$post->id}/comments", $post->comments->count()),
					'time' => Carbon::createFromTimestamp($post->created_at),
					'edit' => link_to("admin/posts/{$post->id}/edit", 'редактировать'),
					'delete' => link_to("admin/posts/{$post->id}/delete", 'удалить')
				];
			}),
			'links' => $posts->links(),
			'actions' => $this->actions($user),
			'title' => 'Посты пользователя'
		]);
	}

	public function comments($id) {
		$user = User::find($id);
		$comments = $user->comments()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Пользователь', 'Текст', 'Пост', 'Лайки', 'Время', '', ''],
			'data' => $comments->transform(function ($comment) {
				return [
					'id' => $comment->id,
					'user' => link_to("users/{$comment->user_id}", $comment->user->name),
					'comment' => link_to("admin/posts/{$comment->id}", $comment->post->text),
					'post_text' => link_to("admin/posts/{$comment->post->id}", $comment->post->text),
					'likes' => link_to("admin/comment/{$comment->id}/likes", $comment->likes()->count()),
					'time' => Carbon::createFromTimestamp($comment->created_at),
					'edit' => link_to("admin/comments/{$comment->id}/edit", 'редактировать'),
					'delete' => link_to("admin/comments/{$comment->id}/delete", 'удалить')
				];
			}),
			'links' => $comments->links(),
			'title' => 'Комментарии',
			'actions' => $this->actions($user)
		]);
	}

	public function devices($id) {
		$user = User::find($id);
		$devices = $user->devices()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Устройство', 'Токен сессии', 'Номер', '', ''],
			'data' => $devices->transform(function ($device) {
				return [
					'id' => $device->id,
					'udid' => $device->udid,
					'auth_token' => $device->phone,
					'phone' => $device->phone,
					'edit' => link_to("admin/devices/{$device->id}/edit", 'редактировать'),
					'delete' => link_to("admin/devices/{$device->id}/delete", 'удалить')
				];
			}),
			'links' => $devices->links(),
			'title' => 'Устройства',
			'actions' => $this->actions($user)
		]);
	}

	public function phones($id) {
		$user = User::find($id);
		$phones = $user->phones()->paginate();
		return View::make('admin.dicts.admin-list', [
			'columns' => ['ID', 'Номер телефона', '', ''],
			'data' => $phones->transform(function ($phone) {
				return [
					'id' => $phone->id,
					'number' => $phone->number,
					'edit' => link_to("admin/phones/{$phone->number}/edit", 'редактировать'),
					'delete' => link_to("admin/phones/{$phone->number}/delete", 'удалить')
				];
			}),
			'links' => $phones->links(),
			'title' => 'Телефоны',
			'actions' => $this->actions($user)
		]);
	}

	public function images($id) {
		return User::find($id);
	}

	private function actions($user) {
		return $this->makeLinks($this->expandList(
			[
				['show','Профиль'],
				['cars','Список машин'],
				['chats','Чаты'],
				['blackList','Блокированные пользователи'],
				['posts','Посты'],
				['comments','Комментарии'],
				['devices','Устройства'],
				['phones','Телефоны']
			]), [
			'controller' => 'AdminUsersController',
			'params' => [$user->id]
		]);
	}

	private function returnIfPresent($object, $valueDefinition, $another = null) {
		if (!$object) return null;

		if (!$object->$valueDefinition) return null;

		if ($another) return $this->returnIfPresent($object->$valueDefinition, $another);

		return $object->$valueDefinition;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /adminusers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /adminusers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	public function delete($id) {
		return Redirect::to('/admin');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /adminusers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}