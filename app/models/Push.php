<?php

class Push {
    public static function send($platform, $token, $message) {
        if($platform == 'ios') {
            self::sendToIOS($token, $message);
        } else if($platform == 'android') {
            self::sendToAndroid($token, $message);
        }
    }

    private static function sendToIOS($token, $message) {
        /*$push = new ApnsPHP_Push(ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION, 'com.appkode.yol');*/
        $push = new ApnsPHP_Push(ApnsPHP_Abstract::ENVIRONMENT_SANDBOX, ROOT.'/app/config/apns-dev.pem');
		$disabledLogger = new DisabledLogger();
        $push->setProviderCertificatePassphrase('test123');
		$push->setLogger($disabledLogger);

//        $push->setRootCertificationAuthority(ROOT . '/app/config/apns-dev.pem');

        $push->connect();

        $apnsMessage = new ApnsPHP_Message();
        $apnsMessage->setText($message);
		$apnsMessage->addRecipient($token);
        $push->add($apnsMessage);
        $push->send();
        $push->disconnect();

        $error = $push->getErrors();
        if(is_array($error) && count($error) > 0) {
            self::log('iOS error: ' . "\n" . json_encode($error));
        } else {
            self::log('Push is sent');
        }
    }

    private static function sendToAndroid($token, $message) {
        $sender = new CodeMonkeysRu\GCM\Sender('AIzaSyBgiyWjX2JSzVWsSz8GcMgMnCs7F5ZEgjo');
        $message = new CodeMonkeysRu\GCM\Message([$token], array('message' => $message));

        /*
        $message
            ->setCollapseKey("collapse_key")
            ->setDelayWhileIdle(false)
            ->setTtl(20)
            ->setRestrictedPackageName("ru.appkode.yol")
            ->setDryRun(true)
        ;
        */

        try {
            $response = $sender->send($message);

            if($response->getFailureCount() > 0) {
                self::log(sprintf('Sending of android token %s returns error', $token));
            } else {
                self::log('Push is sent');
            }
        } catch(CodeMonkeysRu\GCM\Exception $e) {
            if($e->getCode() == CodeMonkeysRu\GCM\Exception::ILLEGAL_API_KEY) {
                self::log('Illegal android api key');
            } else if($e->getCode() == CodeMonkeysRu\GCM\Exception::AUTHENTICATION_ERROR) {
                self::log('Authentication android error');
            } else if($e->getCode() == CodeMonkeysRu\GCM\Exception::MALFORMED_REQUEST) {
                self::log('Malformed android request');
            } else if($e->getCode() == CodeMonkeysRu\GCM\Exception::MALFORMED_RESPONSE) {
                self::log('Malformed android response');
            } else {
                self::log('Android unknown error');
            }
        }
    }

    private static function log($text, $level = ['push']) {
        Log::debug($text, $level);
    }
}

class DisabledLogger implements ApnsPHP_Log_Interface {
    public function log($message) {

    }
}
