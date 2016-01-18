<?php

class SettingsController extends ApiController {

	private $user;

	public function __construct() {
		$this->user = Auth::user();
	}

	public function update() {
		$user = Auth::user();
		// This code is fucking smells >.<

//		$user->update(Input::all());

		$show_phone = Input::has('show_phone') ? Input::get('show_phone') : null;
		$show_email = Input::has('show_email') ? Input::get('show_email') : null;
		$show_car_number = Input::has('show_car_number') ? Input::get('show_car_number') : null;

		$enableCarchats = Input::has('enable_carchats') ? Input::get('enable_carchats') : null;
		$pushPM = Input::has('push_pm') ? Input::get('push_pm') : null;
		$pushComments = Input::has('push_comments') ? Input::get('push_comments') : null;
		$pushCommentLikes = Input::has('push_comment_likes') ? Input::get('push_comment_likes') : null;
		$pushPostLikes = Input::has('push_post_likes') ? Input::get('push_post_likes') : null;

		if (isset($show_phone))
			$user->show_phone = $show_phone;

		if (isset($show_email))
			$user->show_email = $show_email;

		if (isset($show_car_number))
			$user->show_car_number = $show_car_number;

		if (isset($pushPM)) $user->push_pm = $pushPM;
		if (isset($pushComments)) $user->push_comments = $pushComments;
		if (isset($pushCommentLikes)) $user->push_comments = $pushCommentLikes;
		if (isset($pushPostLikes)) $user->push_post_likes = $pushPostLikes;
		if (isset($enableCarchats)) $user->enable_carchats = $enableCarchats;

		if ($user->save())
			return $this->respond([
				'show_phone' => (int)$user->show_phone,
				'show_email' => (int)$user->show_email,
				'show_car_number' => (int)$user->show_car_number,
				'enable_carchats' => (int)$user->enable_carchats,
				'push_pm' => (int)$user->push_pm,
				'push_comments' => (int)$user->push_comments,
				'push_comment_likes' => (int)$user->push_comment_likes,
				'push_post_likes' => (int)$user->push_post_likes
			]);
	}

	public function toggle() {
		$setting = Input::get('setting');

		$this->toggleParam($setting);

		if ($this->user->save())
			return $this->respond([
				$setting => (int)$this->user->$setting
			]);

		return $this->respondServerError();

	}

	private function toggleParam($paramString) {
		if ($this->user->$paramString == 0) $this->user->$paramString = 1;
		elseif ($this->user->$paramString == 1) $this->user->$paramString = 0;
	}

	public function index() {
		$user = Auth::user();

		$header = Request::header('User-Agent');

//		if ($header == 'android 1.0.1')
//			return $this->respondWithCustomStatusCode('Update', 452, 452);

		return $this->respond([
			'show_phone' => (int)$user->show_phone,
			'show_email' => (int)$user->show_email,
			'show_car_number' => (int)$user->show_car_number,
			'enable_carchats' => (int)$user->enable_carchats,
			'push_pm' => (int)$user->push_pm,
			'push_comments' => (int)$user->push_comments,
			'push_comment_likes' => (int)$user->push_comment_likes,
			'push_post_likes' => (int)$user->push_post_likes
		]);
	}

}