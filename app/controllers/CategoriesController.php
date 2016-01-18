<?php

use Carbon\Carbon;
use Helpers\Transformers\CollectionTransformer;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;

class CategoriesController extends ApiController implements RemindableInterface {

	use RemindableTrait;

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Feed
	 */
	public function feed() {
//		$header = Request::header('User-Agent');

//		if ($header == 'yol 1.0.1')
//			return $this->respondWithCustomStatusCode('Update', 452, 452);
		$user = Auth::user();

		$subscriptions = $user->subscriptions->map(function ($subscription) {
				return $subscription->id;
			});

		if ($subscriptions->isEmpty()) {
			return $this->respondNotFound('forum.no-subscriptions');
		}

		$max = Input::has('size') && Input::get('size') < 61?Input::get('size'):25;

		if (Input::has('timestamp'))//might be unexpected behavior
		{ $posts = Post::whereIn('category_id', $subscriptions->toArray())
			                                                     ->where('updated_at', '>', Input::get('timestamp'))
			                                                     ->take($max)
			                                                     ->get();
		} elseif (Input::has('last')) {
			$posts = Post::whereIn('category_id', $subscriptions->toArray())
			                                                    ->where('id', '<', Input::get('last'))
			                                                    ->orderBy('id', 'desc')
			                                                    ->take($max)
			                                                    ->get();
		} else {

			$posts = Post::whereIn('category_id', $subscriptions->toArray())->orderBy('id', 'desc')->take($max)->get();
		}

		$posts->reverse();

		$posts->load('user', 'likes', 'comments', 'images', 'geos', 'cars', 'carsWithNumbers', 'cars.images', 'category');

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	/**
	 * Admin view
	 */
	public function index() {
		$initialCategories = Category::whereNull('parent_id')->with(['categories' => function ($query) {
			$query->orderBy('weight', 'desc')->orderBy('id');
		}])->orderBy('weight', 'desc')->orderBy('id')->paginate();
		$links = $initialCategories->links();
		$result = new \Illuminate\Database\Eloquent\Collection();
		$initialCategories->each(function ($category) use($result) {
			$result->push($category);
			$category->categories->each(function ($category) use ($result) {
				$result->push($category);
			});
		});
		$result->load('parent');
//		dd($result);
		return View::make('admin.dicts.list', [
				'title'   => 'Категории',
				'columns' => [
					'ID', 'Родитель', 'Вес', 'Заголовок', 'Описание', 'Под', 'Посты', 'Ком',
					'посты', '', ''
				],
				'data' => $result->transform(function ($category) use($links) {
						return [
							'id'            => $category->id,
							'parent_id'     => $category->parent_id?"{$category->parent->title} ({$category->parent_id})":'',
							'weight' => $category->weight.' '
								.link_to("admin/categories/{$category->id}/up", "&uarr;").' '
								.link_to("admin/categories/{$category->id}/down", "&darr;"),
							'title'         => $category->title,
							'description'   => $category->description,
							'subscriptions' => $category->subscriptions_count,
							'posts_count'   => $category->posts_count,
							'comments'      => $category->comments_count,
							'posts'         => link_to("/admin/categories/{$category->id}/posts", 'посты &rarr;'),
							'edit'          => link_to("/admin/categories/{$category->id}/edit", 'редактировать'),
							'delete'        => link_to("/admin/categories/{$category->id}/delete", 'удалить')
						];
					}),
				'links'   => $links,
				'actions' => [
					['link'  => '/admin/categories/create', 'text'  => 'Добавить']
				]
			]);
	}

	public function up($id) {
		$category = Category::find($id);
		$category->weight++;
		$category->save();
		return Redirect::to('admin/categories');
	}

	public function down($id) {
		$category = Category::find($id);
		$category->weight--;
		$category->save();
		return Redirect::to('admin/categories');
	}

	public function edit($id) {
		$category = Category::find($id);
		return View::make('admin.categories.edit', [
				'category' => Category::find($id),
				'data'     => [
					['title', $category->title, 'Название'],
					['parent_id', $category->parent_id, 'Родитель'],
					['description', $category->description, 'Описание']
				],
				'title' => "Редактировать категорию"
			]);
	}

	public function create() {
		return View::make('admin.categories.create', [
				'title' => 'Создать категорию',
				'data'  => [
					['title', null, 'Название'],
					['parent_id', null, 'Родитель'],
					['description', null, 'Описание']
				]
			]);
	}

	/**
	 * Admin posts from category
	 * @param $id
	 * @return View
	 */
	public function posts($id) {
		$category = Category::find($id);
		$posts    = $category->posts()->with('user', 'comments')->paginate();
		return View::make('admin.dicts.list', [
				'columns' => ['ID', 'Текст', 'Пользователь', 'Время', 'Кол. ком.',
					'', ''],
				'data' => $posts->transform(function ($post) {
						return [
							'id'             => $post->id,
							'text'           => link_to("admin/posts/{$post->id}", $post->text),
							'user'           => link_to("admin/users/{$post->user_id}", $post->user->name),
							'time'           => Carbon::createFromTimestamp($post->created_at),
							'comments_count' => link_to("admin/posts/{$post->id}/comments", $post->comments->count()),
							'edit'           => link_to("admin/posts/{$post->id}/edit", 'редактировать'),
							'delete'         => link_to("admin/posts/{$post->id}/delete", 'удалить')
						];
					}),
				'title'   => 'Все посты от категории',
				'links'   => $posts->links(),
				'actions' => [
					['link'  => 'admin/posts/delete', 'text'  => 'Удалить выбранное']
				]
			]);
	}

	public function delete($id) {
		$category = Category::find($id);
		if ($category) {
			return View::make('admin.categories.delete', compact('category'));
		}
		App::abort(404);
	}

	public function destroy($id) {
		$category = Category::find($id);
		$category->posts->each(function($post) {
			$post->comments()->delete();
		});
		$category->posts()->delete();
		$category->delete();
		return Redirect::to('admin/categories');
	}
	/**
	 * Display all categories of all cities
	 * GET /categories
	 *
	 * @return  Response
	 */
	public function all() {
		$baseCategories = [1, 58];

		$user = Auth::user();

		$subscriptions = $user->subscriptions;

		$categories = Category::whereBetween('id', $baseCategories)->whereNull('parent_id')->with(['categories' => function ($query) {
			$query->orderBy('weight', 'desc')->orderBy('id');
		}])->orderBy('weight', 'desc')->orderBy('id')->get();

		if ($categories->isEmpty()) {
			return $this->respondNotFound('forum.no-categories');
		}

		$computedCategories = $user->subscriptions()->whereNotBetween('id', $baseCategories)->get();

		if ($computedCategories->count() != 0) {
			$myCarCategory = new Category(['title' => 'Моя машина']);
			$myCarCategory->id = 0;
			$myCarCategory->categories = $computedCategories;

			$categories->prepend($myCarCategory);
		}


		return $this->respond($this->collectionTransformer->transformCategories($categories, true, $subscriptions));

	}

	/**
	 * Subscribe to category
	 * @param $id
	 *
	 * @return Response
	 */
	//	public function subscribe($id) {
	//		$category = Category::find($id);
	//
	//		if (!$category)
	//			return $this->respondNotFound('Category not found');
	//
	//		return $this->respondNoContent();
	//	}

	/**
	 * Unsubscribe from category
	 * @param $id
	 *
	 * @return Response
	 */
	//	public function unsubscribe($id) {
	//		$category = Category::find($id);
	//
	//		if (!$category)
	//			return $this->respondNotFound('Category not found');
	//
	//		return $this->respondNoContent();
	//	}

	/**
	 * Display category category by id
	 * GET /categories/{id}
	 *
	 * @param int id
	 * @return  Response
	 */
	public function single($id) {

		$category = Category::find($id);
//		dd($category);

		if (!$category) {
			return $this->respondNotFound('forum.category-not-found');
		} else {
			return $this->respond($this->collectionTransformer->transformCategory($category));
		}

		return $this->respondServerError();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST categories
	 *
	 * @return Response
	 */
	public function store() {
		$validator = Category::validate(Input::all());

		//		if ($validator->fails())
		//			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$category = Category::create(Input::all());

		if ($category) {
			return Redirect::to('/admin/categories');
		}

		return $this->respondServerError();
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH categories/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$category = Category::find($id);
		$category->fill(Input::all());

		if ($category->save()) {
			return Redirect::to('admin/categories');
		}

		return $this->respondServerError();
	}

	/**
	 * Sub-categories
	 */

	/**
	 * Display list of parent categories
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function sub($id) {
		$validator = $this->validateId($id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$category = Category::find($id);

		if (!$category) {
			return $this->respondNotFound('forum.category-not-found');
		}

		$categories = $category->categories;

		if (!$categories->count()) {
			return $this->respond(array());
		} else {

			return $this->respond($this->collectionTransformer->transformCategories($categories));
		}

		return $this->respondServerError();
	}

	/**
	 * Create a sub-category
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function subStore($id) {
		$validator = $this->validateId($id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$category = Category::find($id);

		if (!$category) {
			return $this->respondNotFound('forum.category-not-found');
		}

		$newCategory = new Category(Input::all());

		if ($category->categories()->save($newCategory)) {
			return $this->respond($this->collectionTransformer->transformCategory($newCategory));
		}

		return $this->respondServerError();
	}

}