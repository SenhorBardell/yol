<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

    'default' => $_ENV['DB_ADAPTER'],
	'connections' => array(

		'pgsql' => array(
			'driver'    => 'pgsql',
			'host'      => $_ENV['DB_HOST'],
			'database'  => $_ENV['DB_DATABASE'],
			'username'  => $_ENV['DB_USER'],
			'password'  => $_ENV['DB_PASS'],
            'port'      => getenv('DB_PORT') ?: 3306,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'mysql' => array(
			'driver'   => 'mysql',
			'host'     => $_ENV['DB_HOST'],
			'database' => $_ENV['DB_DATABASE'],
			'username' => $_ENV['DB_USER'],
			'password' => $_ENV['DB_PASS'],
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),

	),

);
