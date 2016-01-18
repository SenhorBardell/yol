<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useErrorLog();

if (App::environment('local')) {
	Log::useDailyFiles(storage_path().'/logs/laravel.log');
	Event::listen('illuminate.query', function($query, $binginds, $time) use($path) {
		$now = (new DateTime)->format('Y-m-d H:i:s');
		$log = PHP_EOL;
		$log .= $now.' | '.$query.' | ' . $time.' ms'.PHP_EOL;
		File::append(storage_path().'/logs/sql.log', $log);
	});
}

if (App::environment('production')) {

	Event::listen('illuminate.query', function($query, $binding, $time, $connections) {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		//TODO make function output with trace($query)
		foreach ($backtrace as $trace) {
			if (array_key_exists('file', $trace) && array_key_exists('line', $trace)) {
				if (strpos($trace['file'], base_path() . '/app') !== false) {
					$loglines = PHP_EOL;
					$loglines .= date('Y-m-d H:i:s').' ';
					$loglines .= ' [sql] ';
					$loglines .= 'query: '.$query;
					$loglines .= ' connection: '.$connections;
					$loglines .= ' file: ' . $trace['file'];
					$loglines .= ' line: ' . $trace['line'];
					$loglines .= PHP_EOL;
				}
			}
		}
		if (isset($loglines)) Log::debug($loglines, ['sql']);
	});

}



/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

//if (App::environment('local')) {
//
//	App::error(function(Exception $exception, $code) {
//		return Response::json(['error' => [
//			'message' => $exception->getMessage(),
//			'line' => $exception->getLine(),
//			'file' => $exception->getFile(),
//			'status_code' => $code
//		]], $code);
//	});
//
//}

//if (App::environment('production')) {
//
//	App::error(function(Exception $exception, $code) {
//		return Response::json(['error' => [
//			'message' => $exception->getMessage(),
//			'status_code' => $code
//		]], $code);
//	});
//
//}
//
App::missing(function($exception) {
	return Response::json(['error' => [
		'message' => 'Not found',
		'status_code' => 404
	]], 404);
});



/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
 * Custom validator
 */
Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^[\pL\s]+$/u', $value);
});

Validator::extend('yol_unique', function ($attribute, $value) {
	$user = User::where($attribute, $value)->first();

	if ($user) {
		if ($user == Auth::user()) return true;
		return false;
	}

	return true;
});

function fetchNumber($number) {
	$prefix = 994;
	$vips = [
		['fake' => '991234567', 'origin' => '79211040339'],
		['fake' => '991000013', 'origin' => '79216188661'],
		['fake' => '991234568', 'origin' => '79110703637'],
		['fake' => '991234566', 'origin' => '79527934163'],
		['fake' => '991234500', 'origin' => '79097753393'],
		['fake' => '997654321', 'origin' => '79527929736'],
		['fake' => '994444444', 'origin' => '79003526195'],
		['fake' => '993334444', 'origin' => '79003526195']
	];

	foreach ($vips as $vip) {
		if ($vip['fake'] == $number) {
			return $vip['origin'];
		}
	}

	return $prefix.$number;
}

function preventMemoryLeak() {
	gc_enable();
	gc_collect_cycles();
	gc_disable();
}

//define('S3_PUBLIC', 'https://yolanothertest.s3-us-west-2.amazonaws.com/');
//define('S3_PRIVATE', 'https://privatyol.s3-us-west-2.amazonaws.com/');
define('S3_PUBLIC', 'https://s3-eu-west-1.amazonaws.com/bucket-yol-public/');
define('S3_PRIVATE', 'https://s3-eu-west-1.amazonaws.com/bucket-yol-private/');
define('S3_PUBLIC_BUCKET_NAME', 'bucket-yol-public');
define('S3_PRIVATE_BUCKET_NAME', 'bucket-yol-private');
define('IMAGE_COMPRESSING_QUALITY', 95);
