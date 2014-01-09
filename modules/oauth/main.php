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
        //require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/contrib/Google_Oauth2Service.php';
        require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/contrib/Google_PlusService.php';

        $client = new Google_Client();
        /* local testing
        $client->setClientId('844466437028.apps.googleusercontent.com');
        $client->setClientSecret('yTwOn8pO-OzOgnmsMKvce_Cz');
        $client->setRedirectUri('http://www.eon.com/oauth.php?a=google');
        */
        $client->setClientId('289588472225.apps.googleusercontent.com');
        $client->setClientSecret('M1cPjAhYntLUxcbL3xBOwGwQ');
        $client->setRedirectUri('http://aeon.tk/oauth.php?a=google');
        
        $plus = new Google_PlusService($client);
        $code = $this->spArgs('code');
        if(!empty($code)) {
            $client->authenticate();
            $token = $client->getAccessToken();
            $client->setAccessToken($token);
            $gplus = $plus->people->get('me');
            //Array ( [kind] => plus#person [etag] => "RVZ_f1bhF-B19rh4H4M0uhzoFng/f-aojrUFdYRuqP5wIZORu_GScvo" [emails] => Array ( [0] => Array ( [value] => issac.hong@zoom.us [type] => account ) ) [objectType] => person [id] => 101272517671095294156 [displayName] => [name] => Array ( [familyName] => Hong [givenName] => Issac ) [image] => Array ( [url] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50 ) [isPlusUser] => [language] => en [verified] => [domain] => zoom.us )
            $userinfo = array(
                'uname' => $gplus['id'],
                'email' => $gplus['emails'][0]['value'],
                'nick'  => $gplus['name']['givenName'].' '.$gplus['name']['familyName'],
                'oauth' => 'google+',
                'avatar'=> $gplus['image']['url'],
            );
            $login = spClass('userModel')->userlogin($userinfo['uname'], '', $userinfo['oauth']);
            if(false === $login) 
                $login = spClass('userModel')->signon_google($userinfo);

            if($login)
                $this->jumpFirstPage();

        } else {
           spClass('keeper')->speak(T('Error Google User Reject'), '/index.php?c=user&a=login');
        }
/*
        if (isset($_GET['code'])) {
            $client->authenticate();
            $token = $client->getAccessToken();
            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?a=google';
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        if ($client->getAccessToken()) {
            $userinfo = $plus->userinfo->get();
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
