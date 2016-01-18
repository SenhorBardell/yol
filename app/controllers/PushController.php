<?php

class PushController extends ApiController {
    public function bind() {
        if(($token = Input::get('token'))
           && ($platform = Input::get('platform'))
           && in_array($platform, array('android', 'ios', 'chrome', 'safari'))
        ) {
            $device = $this->getDevice();

            $pushToken = PushToken::find((int)$device->id);

            if(!$pushToken) {
				PushToken::where('device_id', $device->id)->delete();
                $pushToken = new PushToken();
                $pushToken->device_id = (int)$device->id;
            }

            $pushToken->token = $token;
            $pushToken->platform = $platform;

            $pushToken->save();

            return $this->respondNoContent();
        } else {
            return $this->respondWithError('Unset necessary parameters');
        }
    }

    public function unbind() {
        if(($token = Input::get('token'))) {
            $device = $this->getDevice();

            $pushToken = PushToken::find((int)$device->id);

            if($pushToken) {
                $pushToken->delete();
            }
        }

        return $this->respondNoContent();
    }

    public function test() {
        $device = $this->getDevice();

        $pushToken = PushToken::find((int)$device->id);

        if($pushToken) {
            Push::send($pushToken->platform, $pushToken->token, 'This is test message');

            return $this->respondNoContent();
        } else {
            return $this->respondWithError('Device isn\'t bound');
        }
    }

    private function getDevice() {
        $header = Request::header('Authorization');
        preg_match('#^Bearer\\s+(.*?)$#', $header, $matches);
        $authToken = $matches[1];

        $device = Device::where('auth_token', $authToken)
                        ->first();

        return $device;
    }

    public function filter() {
        if(!Input::has('token')) {
            return $this->respondWithError('Please provide token');
        }
    }
}
