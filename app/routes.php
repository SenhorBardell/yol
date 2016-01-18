<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::pattern('id', '[0-9]+');
Route::pattern('car_id', '[0-9]+');
Route::pattern('category_id', '[0-9]+');
Route::pattern('permission_id', '[0-9]+');

/* Views */

Route::get('/', function () {
	return Redirect::to('admin');
});

Route::group(['prefix' => 'admin', 'before' => 'admin.auth'], function() {

	Route::get('/', function() {
		return Redirect::action('MarkRefsController@index');
	});

	Route::resource('users', 'AdminUsersController');
	Route::get('users/{id}/cars', 'AdminUsersController@cars');
	Route::get('users/{id}/chats', 'AdminUsersController@chats');
	Route::get('users/{id}/black-list', 'AdminUsersController@blackList');
	Route::get('users/{id}/posts', 'AdminUsersController@posts');
	Route::get('users/{id}/comment', 'AdminUsersController@comments');
	Route::get('users/{id}/devices', 'AdminUsersController@devices');
	Route::get('users/{id}/phones', 'AdminUsersController@phones');
	Route::get('users/{id}/images', 'AdminUsersController@images');

	Route::resource('marks', 'MarkRefsController');

	Route::get('models', 'ModelRefsController@listAll');
	Route::get('models/create', 'ModelRefsController@create');
	Route::post('models/store', 'ModelRefsController@store');

	Route::resource('marks.models', 'ModelRefsController');

	Route::resource('vehicle-types', 'VehicleTypeRefsController');

	Route::resource('body-types', 'BodyTypeRefsController');

	Route::get('categories', 'CategoriesController@index');
	Route::get('categories/create', 'CategoriesController@create');
	Route::post('categories', 'CategoriesController@store');
	Route::get('categories/{id}/edit', 'CategoriesController@edit');
	Route::get('categories/{id}/posts', 'CategoriesController@posts');
	Route::get('categories/{id}/delete', 'CategoriesController@delete');
	Route::delete('categories/{id}/destroy', 'CategoriesController@destroy');
	Route::patch('categories/{id}', 'CategoriesController@update');
	Route::get('categories/{id}/up', 'CategoriesController@up');
	Route::get('categories/{id}/down', 'CategoriesController@down');

	Route::get('posts', 'PostsController@adminIndexAll');
	Route::get('posts/{id}/edit', 'PostsController@edit');
	Route::patch('posts/{id}', 'PostsController@adminUpdate');
	Route::get('posts/{id}/comments', 'PostsController@comments');

	Route::get('comments', 'CommentsController@adminIndex');
	Route::get('comments/{id}/edit', 'CommentsController@adminEdit');
	Route::patch('comments/{id}/update', 'CommentsController@adminUpdate');
	Route::get('comments/{id}/delete', 'CommentsController@delete');
	Route::delete('comments/{id}/destroy', 'CommentsController@adminDestroy');

	Route::get('generate', function() {
		Artisan::call('refs:compile', ['--with_categories']);
		return Redirect::to('admin');
	});

});


Route::get('/api', function() {
	//need actualization
	$url = URL::to('/api/');

	return Response::json(array(
		'users' => $url . 'users',
		'user' => $url . 'users/{id}',
		'user_self' => $url . 'users/self',
		'categories' => $url . 'categories',
		'category_posts' => $url . 'category/{id}/posts',
		'post' => $url . 'posts/{id}',
		'post_comments' => $url . 'posts/{id}/comments'
	));
});

$noAuthApis = function() {

	/* Authentication */

	Route::post('auth', 'AuthController@auth'); // Auth by token

	Route::post('devices/auth', 'AuthController@devicesAuth'); // Auth device

	/* Verification */

	Route::post('verify', 'AuthController@verify');

	Route::post('validate', 'AuthController@validate');

	Route::post('users/register', 'UsersController@store');

	Route::post('password-reset', 'UsersController@passwordReset');

	/* References */

	Route::get('references', 'ReferencesController@all2');

	Route::get('cities', 'ReferencesController@cities');

	/* license */

	Route::get('license', function () {
		return Response::view('license');
	});

};

$apis = function() {

	Route::get('status', function () {
		return Response::json(array('status' => 'Api is running, you are authenticated'));
	});

	Route::post('upload', 'UploaderController@upload');

	/* User */

	Route::get('users', 'UsersController@index');

	Route::get('users/{id}', 'UsersController@show');

	Route::patch('users/{id}', 'UsersController@update');

	Route::delete('users/{id}', 'UsersController@destroy');

	Route::get('users/{id}/posts', 'PostsController@userPosts');

	Route::get('users/{id}/comments', 'CommentsController@userComments'); //@TODO implement

	/* Contacts */

	Route::get('contacts', 'ContactsController@index');

	Route::post('contacts', 'ContactsController@store');

	Route::delete('contacts/{id}', 'ContactsController@destroy');

	/* Self */

	Route::post('users/self', 'UsersController@selfUpdate');

	Route::delete('users/self', 'UsersController@selfDestroy');

	Route::get('users/self/cars', 'CarsController@selfCars');

	Route::get('users/self', 'UsersController@showAuthenticated');

	Route::post('users/self/phones/{id}', 'UsersController@selfUpdatePhone');

//	Route::post('users/self/phones', 'UsersController@selfStorePhone'); deprecated

//	Route::delete('users/self/phones/{id}', 'UsersController@deleteSelfPhone'); deprecated

//	Route::get('users/self/phones/assign/{id}', 'UsersController@selfAssignPhone'); deprecated

	Route::get('users/self/posts', 'UsersController@selfPosts');

	Route::get('users/self/comments', 'UsersController@selfComments');

	Route::post('users/self/password', 'UsersController@changePassword');

	/* Car */

	Route::get('users/{id}/cars', 'CarsController@index');

	Route::get('users/self/cars', 'CarsController@selfIndex');

	Route::get('users/self/cars/change-car/{id}', 'CarsController@makePrimary');

	Route::post('users/self/cars', 'CarsController@selfStore');

	Route::post('users/{id}/cars', 'CarsController@store');

	Route::get('users/{id}/cars/{car_id}', 'CarsController@show');

	Route::patch('users/{id}/cars/{car_id}', 'CarsController@update');

	Route::patch('users/self/cars/{id}', 'CarsController@selfUpdate');

	Route::delete('users/{id}/cars/{car_id}', 'CarsController@destroy');

	Route::delete('users/self/cars/{id}', 'CarsController@selfDestroy');

	Route::delete('cars/{id}/images/{image_id}', 'CarsController@destroyimage');

	/* Categories */

	Route::get('categories', 'CategoriesController@all');

	Route::get('categories/{id}', 'CategoriesController@single');

//	Route::post('categories', 'CategoriesController@store');

//	Route::patch('categories/{id}', 'CategoriesController@update');

//	Route::delete('categories/{id}', 'CategoriesController@destroy');

	/* Sub-categories */

	Route::get('categories/{id}/categories', 'CategoriesController@sub');

	Route::post('categories/{id}/categories', 'CategoriesController@subStore');

	/* Subscriptions */

	Route::post('subscriptions', 'SubscriptionsController@subscribe');

	Route::delete('subscriptions/{id}', 'SubscriptionsController@unsubscribe');

	Route::get('subscriptions', 'SubscriptionsController@subscriptions');

	/* Post */

	Route::get('categories/{id}/posts', 'PostsController@index');

	Route::post('categories/{id}/posts', 'PostsController@store');

	Route::get('posts', 'PostsController@all');

	Route::get('posts/{id}', 'PostsController@show');

	Route::patch('posts/{id}', 'PostsController@update');

	Route::delete('posts/{id}', 'PostsController@destroy');

	Route::post('posts/{postId}/set_as_read', 'PostsController@setAsRead');

	/* Search */

	Route::get('posts/search', 'SearchController@search');

	Route::get('users/search/{id}', 'SearchController@searchUsers');

	/* Comment */

	Route::get('posts/{id}/comments', 'CommentsController@index');

	Route::post('posts/{id}/comments', 'CommentsController@store');

	Route::patch('comments/{id}', 'CommentsController@update');

	Route::delete('comments/{id}', 'CommentsController@destroy');

	Route::post('comments/{commentId}/set_as_read', 'CommentsController@setAsRead');

	/* Like */

	Route::get('{type}/{id}/likes', 'LikesController@users');

	Route::post('{type}/{id}/likes', 'LikesController@like');

	Route::delete('{type}/{id}/likes', 'LikesController@unlike');

	/* Feed */

	Route::get('feed', 'CategoriesController@feed');

	/* Attachments */

//	Route::delete('{postable}/{postable_id}/attachments/{id}', 'AttachmentsController@destroy');

	/* Favorite */

	Route::get('favorites', 'FavoritesController@index');

	Route::post('favorites', 'FavoritesController@store');

	Route::delete('favorites', 'FavoritesController@unfavorite');

	/* Settings */

	Route::get('settings', 'SettingsController@index');

	Route::patch('settings', 'SettingsController@toggle');

	Route::post('settings', 'SettingsController@update');

	/* Black list */

	Route::get('black-list', 'UsersController@blackList');

	Route::post('black-list', 'UsersController@block');

	Route::delete('black-list', 'UsersController@unblock');

	/* User related Permissions */
/*
	Route::get('users/{id}/grant/{role_id}', 'RolesController@grant');

	Route::get('users/{id}/permissions', 'PermissionsController@show');

	Route::get('users/{id}/roles', 'PermissionsController@showRole');

	Route::get('users/{id}/roles/grant/{role_id}', 'PermissionsController@grantRole');
*/
	/* Roles */
/*
	Route::get('roles', 'RolesController@index');

	Route::post('roles', 'RolesController@store');

	Route::patch('roles/{id}', 'RolesController@update');

	Route::delete('roles/{id}', 'RolesController@destroy');

	Route::get('roles/{id}/permissions', 'RolesController@show');

	Route::post('roles/{id}/permissions', 'RolesController@store');
*/
	/* Permissions */

	Route::get('permissions/{id}/assign/{role_id}', 'PermissionsController@assign');

	Route::get('permissions', 'PermissionsController@index');

	Route::post('permissions', 'PermissionsController@store');

	Route::patch('permissions/{id}', 'PermissionsController@update');

	Route::delete('permissions/{id}', 'PermissionsController@destroy');

	/* Car Chats */

	Route::get('carchats', 'CarChatsController@index');

	Route::post('carnumbers', 'CarChatsController@validateNumber');

	Route::post('carchats', 'CarChatsController@store');

	Route::patch('carchats/{id}', 'CarChatsController@update');

	Route::delete('carchats/{id}', 'CarChatsController@destroy');

	/* Car Messages */

	Route::get('carchats/{id}/messages', 'CarMessagesController@index');

	Route::post('carchats/{id}/messages', 'CarMessagesController@store');

	Route::patch('carchats/{id}/messages/{message_id}', 'CarMessagesController@deliver');

	Route::post('carchats/{id}/attachments', 'CarMessagesController@attach');

	Route::get('carchats/{id}/attachments/{attachment_id}', 'CarMessagesController@getAttach');

	Route::get('carchats/{id}/attachments/{attachment_id}/{type}', 'CarMessagesController@getAttach');

	Route::delete('carchats/{id}/messages/{message_id}', 'CarMessagesController@destroy');

	/* Chats API */

	Route::get('chats/{chatId}/users', 'ChatsController@getUsers');

	Route::get('chats/{chatId}', 'ChatsController@getChat');

	Route::get('chats/chat_id/{lastChatId}/size/{size}', 'ChatsController@getList');

	Route::get('chats/size/{size}', 'ChatsController@getListLimited');

	Route::get('chats/chat_id/{lastChatId}', 'ChatsController@getList');

	Route::get('chats/user_id/{userId}/size/{size}', 'ChatsController@getChatByUser');

	Route::get('chats/user_id/{userId}', 'ChatsController@getChatByUser');

	Route::get('chats', 'ChatsController@getList');

	Route::post('chats', 'ChatsController@create');

	Route::post('chats/{chatId}/users', 'ChatsController@includeUser');

	Route::patch('chats/{chatId}', 'ChatsController@update');

	Route::delete('chats/{chatId}/user_id/{userId}', 'ChatsController@exceptUser');

	/* Messages API */

	Route::get('messages/{chatId}/message_id/{lastShownMessageId}/size/{size}', 'MessagesController@getList');

	Route::get('messages/{chatId}/last_shown_message_id/{messageId}/size/{size}', 'MessagesController@newMessages');

	Route::get('messages/{chatId}/last_shown_message_id/{messageId}', 'MessagesController@newMessages');

	Route::get('messages/{chatId}/size/{size}', 'MessagesController@getListLimited');

	Route::get('messages/{chatId}/message_id/{lastShownMessageId}', 'MessagesController@getList');

	Route::get('messages/{chatId}', 'MessagesController@getList');

	Route::post('messages', 'MessagesController@send');

	Route::post('delivered-messages', 'MessagesController@deliver');

	Route::get('messages/attach/{type}/{imageId}', 'MessagesController@getAttach');

	Route::post('messages/attach', 'MessagesController@attach');

	Route::delete('messages/message_id/{messageId}', 'MessagesController@deleteMessage');

	Route::delete('messages/chat_id/{chatId}', 'MessagesController@clearChatHistory');

	/* Complains API */

	Route::filter('ComplaintsSaveFilter', 'ComplaintsController@saveFilter');
	Route::post('complaints', array(
		'before' => 'ComplaintsSaveFilter',
		'uses' => 'ComplaintsController@save'
	));

	/* Notifications API */

	Route::get('notifications/notification_id/{lastNotificationId}/size/{size}', 'NotificationsController@getList');

	Route::get('notifications/size/{size}', 'NotificationsController@getListLimited');

	Route::get('notifications/notification_id/{lastNotificationId}', 'NotificationsController@getList');

	Route::get('notifications', 'NotificationsController@getList');

	/* Push API */

	Route::filter('PushControllerFilter', 'PushController@filter');

	Route::post('push/bind', array(
		'before' => 'PushControllerFilter',
		'uses' => 'PushController@bind'
	));

	Route::post('push/unbind', array(
		'before' => 'PushControllerFilter',
		'uses' => 'PushController@unbind'
	));

	Route::post('push/test', 'PushController@test');

	/* Emergencies */

	Route::get('emergencies', 'EmergenciesController@index');

	Route::delete('emergencies/{id}', 'EmergenciesController@destroy');

	Route::post('emergencies', 'EmergenciesController@store');

	Route::patch('emergencies', 'EmergenciesController@resetCounts');

	Route::patch('emergencies/{id}', 'EmergenciesController@deliver');
};

/* Main API  resolver func */

Route::group(['prefix' => 'api'], function() use($apis, $noAuthApis) {
	Route::group(['before' => 'bearer'], $apis);
	Route::group([], $noAuthApis);

	Route::group(['prefix' => 'v1'], function() use($apis, $noAuthApis) {
		Route::group([], $noAuthApis);
		Route::group(['before' => 'bearer'], $apis);
	});
});
