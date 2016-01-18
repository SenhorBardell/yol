<?php

Route::filter('token', function() {
	$token = AccessToken::where('token', Input::get('token'))->first();
	if (!$token)
		return Response::json(array('status' => 'Unauthorized'));
});

Route::filter('auth', function() {
	if (Auth::onceBasic())
		return Response::json(['error' => ['message' => 'Invalid credentials', 'status_code' => 401]], 401);
});


Route::filter('bearer', function() {
	$header = Request::header('Authorization');
	if (!$header)
		return Response::json(['error' => ['message' => 'Unauthorized', 'status_code' => 401]], 401);

	$token = explode(' ', $header)[1];

	if($token) {
		$device = Device::where('auth_token', $token)
						->orderBy('id', 'desc')->first();

		if(!is_null($device)) {
			try {
				Auth::onceUsingId($device->user_id);
			} catch(Exception $e) {
				return Response::json(['error' => ['message' => $e->getMessage(), 'status_code' => 500]], 500);
			}
		} else {
			return Response::json(['error' => ['message' => 'Invalid token', 'status_code' => 401]], 401);
		}
	} else {
		return Response::json(['error' => ['message' => 'Unauthorized', 'status_code' => 401]], 401);
	}

	// TODO User is online logic
});
/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

 App::before(function($request) {
 	App::setLocale($request->header('Locale'));
	 //TODO check for and send 452
	//	 iOS 1.0.100.39e13d1
	//Android 1.0.100.hash_commit
 });


 App::after(function($request, $response)
 {
	 $response->headers->set('Access-Control-Allow-Origin', '*');
	 $response->headers->set('Access-Control-Allow-Headers', 'Authorization');
 });