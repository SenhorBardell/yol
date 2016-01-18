<?php 

return [
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Memory',

	/**
	 * Consumers
	 */
	'consumers' => [

		/**
		 * Facebook
		 */
        'Facebook' => [
            'client_id'     => getenv('FB_ID'),
            'client_secret' => getenv('FB_SECRET'),
            'scope'         => ['email','user_online_presence'],
        ],

        /**
         * Odnoklassniki
         */
        'Odnoklassniki' => [
            'client_id' => getenv('OK_ID'),
            'client_secret' => getenv('OK_SECRET'),
            'client_public' => getenv('OK_PUBLIC'),
            'scope' => []
        ],

        /**
         * Mail
         */
        'Mail' => [
            'client_id' => getenv('MAIL_ID'),
            'client_secret' => getenv('MAIL_SECRET'),
            'client_private' => getenv('MAIL_PRIVATE'),
            'scope' => []
        ]

	]

];