<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}

    /** @brief google login via OAuth */
	function google() {
        $code = $this->spArgs('code');
        if(!empty($code)) {
            $userinfo = spClass('oauth2')->getGooglePlusUserInfo($code);
            $login = spClass('userModel')->userlogin($userinfo['uname'], '', $userinfo['oauth']);
            if(false === $login) 
                $login = spClass('userModel')->signon_google($userinfo);

            if($login)
                $this->jumpFirstPage();

        } else {
           spClass('keeper')->speak(T('Error Google User Reject'), '/index.php?c=user&a=login');
        }
    }

	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
