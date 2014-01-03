<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}

    /** @brief google login via OAuth */
	function google() {
        require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/Google_Client.php';
        require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/contrib/Google_Oauth2Service.php';

        $client = new Google_Client();
        $client->setApplicationName('Aeon for google OAuth2.0');
        $client->setClientId('844466437028.apps.googleusercontent.com');
        $client->setClientSecret('yTwOn8pO-OzOgnmsMKvce_Cz');
        $client->setRedirectUri('http://www.eon.com/oauth.php?a=google');
        $client->setScopes(array('https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email'));
        $plus = new Google_Oauth2Service($client);
        $code = $this->spArgs('code');
        if(!empty($code)) {
            $client->authenticate();
            $token = $client->getAccessToken();
            $client->setAccessToken($token);
            $userinfo = $plus->userinfo->get();
            print_r($userinfo);exit;
        }
/*
        if (isset($_GET['code'])) {
            $client->authenticate();
            $token = $client->getAccessToken();
            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        if ($client->getAccessToken()) {
            $userinfo = $plus->userinfo;
            $_SESSION['token'] = $client->getAccessToken();
            print_r($userinfo);exit;
        } else {
            $authUrl = $client->createAuthUrl();
            print "<a href='$authUrl'>Connect Me!</a>";
        }
*/
    }

	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
