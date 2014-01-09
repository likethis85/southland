<?php
if (!defined('SOUTHLAND')) { exit(1);}

/** @brief OAuth相关的逻辑管理 */
class oauth2 {
    
    private function getGoogleOauthClient($pro='www.eon.com') {
        require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/Google_Client.php';
        require_once APP_PATH.'/vendors/goauth/google-api-php-client/src/contrib/Google_PlusService.php';
        $client = new Google_Client();
        if($pro=='aeon.tk') {
            $client->setClientId('289588472225.apps.googleusercontent.com');
            $client->setClientSecret('M1cPjAhYntLUxcbL3xBOwGwQ');
            $client->setRedirectUri('http://aeon.tk/oauth.php?a=google');
        } else {
           $client->setClientId('844466437028.apps.googleusercontent.com');
           $client->setClientSecret('yTwOn8pO-OzOgnmsMKvce_Cz');
           $client->setRedirectUri('http://www.eon.com/oauth.php?a=google');
        }
         
        $client->setScopes(array('https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/userinfo.email'));
        return $client;
    }
    public function getGoogleOauthUrl($pro) {
        $client = $this->getGoogleOauthClient($pro);
        return $client->createAuthUrl();
    }

    public function getGooglePlusUserInfo($code) {
        if(empty($code))
            return array();

        $client = $this->getGoogleOauthClient($pro);
        $plus = new Google_PlusService($client);
        $client->authenticate();
        $token = $client->getAccessToken();
        $client->setAccessToken($token);
        $gplus = $plus->people->get('me');
        /*  google+ user info structure
        Array ( 
            [kind] => plus#person 
            [etag] => "RVZ_f1bhF-B19rh4H4M0uhzoFng/f-aojrUFdYRuqP5wIZORu_GScvo" 
            [emails] => Array ( 
                [0] => Array ( 
                    [value] => issac.hong@zoom.us [type] => account 
                ) 
            ) 
            [objectType] => person 
            [id] => 101272517671095294156 
            [displayName] => 
            [name] => Array ( 
                [familyName] => Hong 
                [givenName] => Issac 
            ) 
            [image] => Array ( 
                [url] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50 
            ) 
            [isPlusUser] => 
            [language] => en 
            [verified] => 
            [domain] => zoom.us 
        )
        */
        $userinfo = array(
            'uname' => $gplus['id'],
            'email' => $gplus['emails'][0]['value'],
            'nick'  => $gplus['name']['givenName'].' '.$gplus['name']['familyName'],
            'oauth' => 'google+',
            'avatar'=> $gplus['image']['url'],
        );
        return $userinfo;

    }
}
