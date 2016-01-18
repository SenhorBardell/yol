<?php

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

class smsSender {

	private $endpoint = 'http://cab.websms.ru/http_in6.asp';
	protected $login;
	protected $pass;
	public $number;
	public $message = 'Message';
	public $test = true;

	/**
	 * Construct function if class needs to be
	 * instantiated as 'new ...'
	 *
	 * @param string $number
	 * @param null $message
	 */
	function __construct($number = null, $message = null) {
		$this->number = $number;
		if ($message) $this->message = $message;
		$this->login = getenv('SMS_LOGIN');
		$this->pass = getenv('SMS_PASS');
	}

	public function send($callback) {
		$client = new Client();
		$url = $this->endpoint;
		$url .= '?http_username='.$this->login;
		$url .= '&http_password='.$this->pass;
		$url .= '&phone_list='.$this->number;
//		$url .= '&phone_list=7921104033';
		$url .= '&message='.$this->message;

//		if ($this->test)
//			$url .= '&test=-1';

		$url .= '&format=xml';
		$res = $client->get($url);
//		dd($res->getQuery());
		$callback($res->send());
	}


	/**
	 * Function that gets called on a job
	 * Push::queue('smsSender', $emergency);
	 *
	 * @param $job
	 * @param Emergency $emergency
	 */
	public function fire($job, $emergency) {
		$user = User::find($emergency['receiver']);

		$emergencyModel = Emergency::find($emergency['id']);

		if ($emergencyModel->delivered_at) return $job->delete();

		if (!$user) return $job->delete();

		$this->number = fetchNumber($user->phone->number);

		$this->message = "+0{$emergency['sender_phone']} просит подойти к машине {$emergency['number']} [Сервис 'YOL']";
		$this->send(function(Response $response) use ($emergency, $job) {
//			var_dump($response->xml());
			Log::debug('SMS Sender', ['sms']);
			Log::debug($response->getBody(true));
			$emergency = Emergency::find($emergency['id']);

			if ($emergency) {
				$emergency->message_id = (string)$response->xml()->httpIn->sms['message_id'];
				$emergency->save();
			}

			$job->delete();
		});
	}

}

