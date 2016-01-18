<?php

use Carbon\Carbon;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Illuminate\Console\Command;

class smsChecker {

	private $endpoint = 'http://cab.websms.ru/http_out5.asp';
	protected $login;
	protected $pass;

	public function checkResults($emergency, $callback) {
		$client = new Client();
		$url = $this->endpoint;
		$url .= '?http_username='.$this->login;
		$url .= '&http_password='.$this->pass;
		$url .= '&message_id='.$emergency['message_id'];
		$url .= '&format=xml';
//		$url .= '&test=-1';
		$res = $client->get($url);
		$callback($res->send());
	}

	public function fire($job, $emergencyId) {
		Log::debug($emergencyId);

		$this->login = getenv('SMS_LOGIN');
		$this->pass = getenv('SMS_PASS');

		$emergency = Emergency::find($emergencyId);
		if (!$emergency) return $job->delete();
		if ($emergency->delivered_at) return $job->delete();


		$createdAt = Carbon::createFromTimestamp($emergency->created_at);

		// Check feedback only if 30 seconds passed
		if ($createdAt->diffInSeconds(Carbon::now()) > 30) {
			$this->checkResults($emergency, function (Response $response) use ($emergency, $job) {
//				var_dump($response->xml());
				Log::debug('SMS Checker', ['sms']);
				Log::debug($response->getBody(true), ['sms']);

				$payload = $response->xml()->httpOut->sms;
				if ($payload['delivered_date'] && (int)$payload['result_id'] != 7) {
					$emergency->delivered_at = Carbon::parse((string)$payload['delivered_date']);
					$emergency->via_sms = true;
					$emergency->getMembersTokens()->each(function ($token) use ($emergency) {
						$state = new StateSender($token->auth_token);
						$state->updateCounts('emergencies', $emergency->id);
						$state->setEmergencyAsDelivered($emergency->id, $emergency->delivered_at, true);
						$state->send();
					});
				}
				if (!in_array((int)$payload['result_id'], [2, 3, 4, 6, 8])) {
					$emergency->failed = true;
					$emergency->delivered_at = null;
				}
				$job->delete();
				return $emergency->save();
			});
		} else {
			$job->release(10);
		}
	}
}