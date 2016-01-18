<?php

class ApiController extends \BaseController {

	protected $statusCode = 200;

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
		return $this;
	}

	public function respondNotFound($message = 'message.not-found') {
		return $this->setStatusCode(404)->respondWithError(trans($message));
	}

	public function respond($data, $headers = []) {
		return Response::json([
								  'response' => count($data) > 0 ? $data : new \stdClass,
								  'cur_time' => time()
							  ], $this->getStatusCode(), $headers);
	}

	public function respondNoContent($headers = []) {
		return $this->setStatusCode(204)->respond(array(), $headers);
	}

	public function respondInvalidApi($message = 'message.unauthorized') {
		return $this->setStatusCode(401)->respondWithError(trans($message));
	}

	public function respondInsufficientPrivileges($message = 'message.insufficient-privileges') {
		if (is_array($message)) {
			return $this->setStatusCode(403)->respondWithError($message);
		}
		return $this->setStatusCode(403)->respondWithError(trans($message));
	}

	public function respondServerError($message = 'message.server-error') {
		return $this->setStatusCode(500)->respondWithError(trans($message));
	}

	public function respondWithError($message, $headers = []) {
		return Response::json([
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]], $this->getStatusCode(), $headers
		);
	}

	public function respondWithCustomStatusCode($message, $errorCode, $statusCode, $headers = []){
		return Response::json([
			'error' => [
				'message' => trans($message),
				'status_code' => $statusCode
			]
		], $errorCode, $headers);
	}

	public function validateId($id) {
		return Validator::make(array('id' => $id), array('id' => 'numeric'));
	}

}