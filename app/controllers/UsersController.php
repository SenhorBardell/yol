<?php

use Helpers\Transformers\CollectionTransformer;
use Intervention\Image\Facades\Image;

class UsersController extends ApiController {

	/**
	* @var Helpers\Transformers\CollectionTransformer
	*/
	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Display a listing of the resource.
	 * GET /users
	 *
	 * @return Response
	 */
	public function index() {
		$user = Auth::user();

		$post = Post::find(1);

//		if ($user->can('User.view'));
//			return $this->respondInsufficientPrivileges();

		$users = User::all();

		if ($users->isEmpty())
			return $this->respondNotFound('user.no-users');

		return $this->respond($this->collectionTransformer->transformUsers($users, Request::header('Locale')));
	}

	public function passwordReset() {
		//TODO make new push token
		$token = Input::get('token');
		$udid = Input::get('udid');

		if (!$udid)
			return $this->respondInsufficientPrivileges('user.no-device');

		$smsEntry = SMS::where('token', $token)->orderBy('id')->first();

		if (!Input::has('password'))
			return $this->respondInsufficientPrivileges('user.wrong-password');

		$validator = Validator::make(Input::only('password'), ['password' => 'required|between:6,12']);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		if (!$smsEntry)
			return $this->respondInsufficientPrivileges('user.invalid-token');

		if (!$smsEntry->verified)
			return $this->respondInsufficientPrivileges('user.phone-not-verified');

		$phone = Phone::where('number', $smsEntry->phone)->first();

		$user = $phone->user;
		$user->password = Input::get('password');

		if ($user->save()) {
			//TODO create new auth token

			$user->devices->each(function (Device $device) {
				$device->pushTokens()->delete();
			});

			$user->devices()->delete();

			$smsEntry->delete();

			$device = Device::create([
				'udid' => $udid,
				'auth_token' => base64_encode(openssl_random_pseudo_bytes(32)),
				'phone' => 0 //FIXME phone is in separate table
			]);

			$user->devices()->save($device);

			$response['user'] = $this->collectionTransformer->transformUserToSmall($user);
			$response['auth_token'] = $device->auth_token;


			return $this->respond($response);
		}

		return $this->respondServerError();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store() {
		$validator = User::validate(Input::all());
		$token = Input::get('token');

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$smsEntry = SMS::where('token', $token)->orderBy('id')->first();

		if (!$smsEntry)
			return $this->respondInsufficientPrivileges('user.invalid-token');

		if (!$smsEntry->verified)
			return Response::json(['error' => ['message' => 'Your number is not yet verified. Please re-register.', 'status' => 1]], 403);

		$device = Device::create([
			'udid' => $smsEntry->device,
			'auth_token' => base64_encode(openssl_random_pseudo_bytes(32)),
			'phone' => $smsEntry->phone
		]);

		$phone = Phone::create([
			'number' => $smsEntry->phone
		]);

		$user = new User(Input::all());

		if (Input::hasFile('img'))
			$user->setAvatar(Input::file('img'));
		else {
			//FIXME cant get default postgres value to work
//			$user->img_origin = S3_PUBLIC.'placeholder_128.png';
//			$user->img_middle = S3_PUBLIC.'placeholder_128.png';
//			$user->img_small = S3_PUBLIC.'placeholder_64.png';
		}

		$user->phone_id = $phone->id;
		$user->password = Input::get('password');
		$user->urgent_calls = 2;
        $user->save();
		$user->roles()->attach(3); // user role

		$user->devices()->save($device);
		$user->phones()->save($phone);
		$user->subscriptions()->attach([
			2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,
			17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28,
			30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41,
			42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53,
			56, 57, 58
		]);
//		Category::updateSubsCounts();

		$smsEntry->delete();

		$response['user'] = $this->collectionTransformer->transformUserToSmall($user);
		$response['auth_token'] = $device->auth_token;

		return $this->respond($response);

//		Mail::send('emails.verify', ['token' => $user->verification_code], function($message) use ($user) {
//			$message->to($user->email, $user->name.' '.$user->family_name)->subject('Verify your account');
//		});
	}


	/**
	 * Show yourself
	 * GET /users/self
	 *
	 * @return Response
	 */
	public function showAuthenticated() {
		$user = Auth::user();

		$user->load('cars', 'phones');

		return $this->respond($this->collectionTransformer->transformUser($user, Request::header('Locale')));
	}

	/**
	 * Update (basically create new one but with provided id) mine phone
	 * This functions requires token - user must validate phone number via sms
	 * @param $id
	 *
	 * @return Response
	 */
	public function selfUpdatePhone($id) {
		$token = Input::get('token');

		$smsEntry = SMS::where('token', $token)->orderBy('id')->first();

		if (!$smsEntry)
			return $this->respondInsufficientPrivileges('user.invalid-token');

		if (!$smsEntry->verified)
			return Response::json(['error' => ['message' => 'Your number is not yet verified. Please re-register', 'status' => 1]], 403);

		$phone = Phone::find($id);

		if (!$phone)
			return $this->respondNotFound('user.phone-not-found');

		$phone->fill(['number' => $smsEntry->phone]);

		if ($phone->save()) {
			$smsEntry->delete();
//			$user = Auth::user();
			return $this->respond($phone);
//			return $user->phones;
		}

		return $this->respondNotFound('user.phone-not-found');
	}

	/**
	 * Store new phone on myself
	 * Requires token - user must validate phone number
	 * POST /api/users/self/phones
	 *
	 * @return Response
	 */
	public function selfStorePhone() {
		$token = Input::get('token');

		$smsEntry = SMS::where('token', $token)->orderBy('id')->first();

		if (!$smsEntry)
			return $this->respondInsufficientPrivileges('user.invalid-token');

		if (!$smsEntry->verified)
			return Response::json(['error' => ['message' => 'Your numer is not ye verified. Please re-register', 'status' => 1]]);

		$phone = Phone::create([
			'number' => $smsEntry->phone
		]);

		$user = Auth::user();

		$user->phones()->save($phone);
		$smsEntry->delete();

		if ($phone)
			return $this->respond($phone);
		//TODO transformer no?

		return $this->respondServerError('message.server-error');
	}

	public function deleteSelfPhone($id) {
		$user = Auth::user();

		$phone = $user->phones()->find($id);

		if (!$phone)
			return $this->respondNotFound('user.phone-not-found');

		if ($phone->id == $user->phone->id)
			return $this->respondInsufficientPrivileges();

		if ($phone->delete())
			return $this->respondNoContent();

		return $this->respondServerError('message.server-error');
	}

	public function selfAssignPhone($id) {
		$user = Auth::user();

		$phone = $user->phones()->find($id);

		if (!$phone)
			return $this->respondNotFound('user.phone-not-found');

		$user->phone_id = $phone->id;

		if ($user->save())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	/**
	 * Update yourself
	 * POST /users/self
	 * 
	 * @return Response
	 */
	public function selfUpdate() {
		$user = Auth::user();

		$validator = Validator::make(Input::only(['email']), ['email' => 'email|yol_unique']);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$user->fill(Input::except(['img', 'password', 'email']));

		$user->email = strlen(Input::get('email')) == 0 ? null : Input::get('email');

		if (Input::hasFile('img')) {
			$img = Image::make(Input::file('img'));
			if ($img->width() <= 128 || $img->height() <= 128)
				return $this->respondWithCustomStatusCode('user.image-resolution', 403, 1004);

			try {
				$user->setAvatar(Input::file('img'));
			} catch (Exception $e) {
				return $this->respondServerError($e->getMessage());
			}
		}

		if ($user->save()) {

			$user->load('cars', 'phones');

			return $this->respond($this->collectionTransformer->transformUser($user, Request::header('Locale')));
		}

		return $this->respondServerError();
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id) {
		$user = Auth::user();

		//FIXME Ineffiecent code, double query
		$otherUser = User::withTrashed()->find($id);

		if (!$otherUser) return $this->respondNotFound('user.not-found');

		if ($otherUser->deleted_at)
			return Response::json([
				'response' => [
					'id' => $otherUser->id,
					'name' => $otherUser->name,
					'status' => 'deleted',
					'img' => [
						'thumb' => $otherUser->img_small,
						'middle' => $otherUser->img_middle,
						'origin' => $otherUser->img_origin
					]
				]
			]);

		if ($user->isBlocked($id))
			return Response::json([
				'response' => [
					'id' => $otherUser->id,
					'name' => $otherUser->name,
					'status' => 'blocked',
					'reason' => 'User blocked you',
					'img' => [
						'thumb' => $otherUser->img_smal,
						'middle' => $otherUser->img_middle,
						'origin' => $otherUser->img_origin
					],
					'in_blacklist' => $user->inBlackList($otherUser->id) ? 1 : 0
				]
			], 403);

		if (!$otherUser)
			return $this->respondNotFound('user.not-found');

		$otherUser->load('cars', 'phones');

		return $this->respond($this->collectionTransformer->transformOtherUser($otherUser, Request::header('Locale')));
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$validator = Validator::make(Input::all(), ['email' => 'email|unique:users']);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$user = User::find($id);

		if (!$user)
			$this->respondNotFound('user.not-found');

		$user->fill(Input::all());

		if ($user->save()) 
			return $this->respond($this->collectionTransformer->transformUser($user, Request::header('Locale')));

		return $this->respondServerError();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$validator = $this->validateId($id);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$user = User::find($id);

		if (!$user)
			$this->respondNotFound('user.not-found');

		if ($user->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	public function selfDestroy() {
		$user = Auth::user();

		if ($user->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	/** 
	 * Get all users posts
	 * GET /api/users/self/posts
	 * 
	 * @return Response
	 */
	public function selfPosts() {
		$user = Auth::user();
		$max = 25;

		if (Input::has('size'))
			$max = Input::get('size') > 61 ? $max : Input::get('size');

		if (Input::has('last'))
			$posts = $user->posts()
				->where('id', '<', Input::get('last'))
				->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
				->orderBy('id', 'desc')
				->take($max)
				->get();
		else
			$posts = $user->posts()
				->with('user', 'likes', 'comments', 'images', 'geos', 'cars', 'cars.images', 'category')
				->orderBy('id', 'desc')
				->take($max)
				->get();

		return $this->respond($this->collectionTransformer->transformPosts($posts));
	}

	/**
	 * Get all users comments
	 * /api/users/self/comments
	 * 
	 * @return Response
	 */
	public function selfComments() {
		$user = Auth::user();

		if (Input::has('last'))
			$comments = $user->comments()->where('id', '>', Input::get('last'))->take(10)->get();
		else
			$comments = $user->comments()->take(10)->get();

		return $this->respond($comments);
	}

	public function blacklist() {
		$user = Auth::user();

		return $this->respond($user->blockedUsers->map(function($user) {
			return $this->collectionTransformer->transformUserToSmall($user);
		}));
	}

	public function block() {
		$id = Input::get('id');
		$user = Auth::user();

		$otherUser = User::find($id);

		if (!$otherUser)
			return $this->respondNotFound('user.not-found');

		$user->block($id);

		return $this->respondNoContent();
	}

	public function unblock() {
		$id = Input::get('id');
		$user = Auth::user();

		$otherUser = User::find($id);

		if (!$otherUser)
			return $this->respondNotFound('user.not-found');

		$user->unblock($id);

		return $this->respondNoContent();
	}

	public function changePassword() {
		$user = Auth::user();
		$oldPassword = Input::get('oldPassword');
		$newPassword = Input::get('newPassword');

		if ($user->checkPasswordAttribute($oldPassword)) {
			$user->password = $newPassword;

			// Delete others devices auth
			$header = Request::header('Authorization');
			$token = explode(' ', $header)[1];

			if ($token) {
				$user->devices()->where('auth_token', '!=', $token)->get()->each(function ($token) {
					PushToken::where('device_id', $token->id)->delete();
					$token->delete();
				});
			}
			if ($user->save())
				return $this->respondNoContent();

		} else {
			return $this->respondInsufficientPrivileges('user.wrong-old-pass');
		}

		return $this->respondServerError();
	}
}