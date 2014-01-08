<?php
if (!defined('SOUTHLAND')) { exit(1);}
class userModel extends spModel
{
	var $pk = "id"; // 每个留言唯一的标志，可以称为主键
	var $table = "user"; // 数据表的名称
	var $verifier = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行“格式验证”
		"rules" => array( // 规则
			'uname' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				//'minlength' => 3,  // uname长度不能小于3
				//'maxlength' => 12  // uname长度不能大于12
			),
		),
		"messages" => array( // 提示信息
			'uname' => array(
				'notnull' => "用户名不能为空",
				'minlength' => "用户名不能少于3个字符",
				'maxlength' => "用户名不能大于20个字符"
			),
		)
	);

    /** @brief 建立OAuth的Google用户并以google用户登录 */
    public function signon_google($userinfo) {
            //Array ( [id] => 101272517671095294156 [email] => issac.hong@zoom.us [verified_email] => 1 [name] => Issac Hong [given_name] => Issac [family_name] => Hong [locale] => en [hd] => zoom.us )
        $data = array(
                'uname' => $userinfo['email'],
                'upass' => '',
                'email' => $userinfo['email'],
                'oauth' => 'google',
                'nick'  => $userinfo['name'],
                'enabled' => 1
        );
        if($this->create($data)==false || false===$this->userlogin($data['uname'], '', 'google')) {
            spClass('keeper')->speak(T('Error Google User Reject'), '/index.php?c=user&a=login');
        } else {
            return true;
        }
    }
	/**
	 * 这里我们建立一个成员函数来进行用户登录验证
	 *
	 * @param uname    用户名
	 * @param upass    密码，请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
     * @param oauth    oauth login, default empty to site login
	 */
	public function userlogin($uname, $upass, $oauth=''){ 
		$conditions = array(
			'uname' => $uname,
			'upass' => $upass,
            'oauth' => $oauth,
			'enabled'=>1,
		);
		//dump($conditions);
		// 检查用户名/密码，由于$conditions是数组，所以SP会自动过滤SQL攻击字符以保证数据库安全。
		if( $result = $this->find($conditions) ){ 
		    if(WORKSPACE==='admin')
		        return false;
            spClass('spSession')->getUser()->setUserInfo($result);
			spClass('spSession')->getUser()->setCurrentProject(0);
			return true;
		}else{
			// 找不到匹配记录，用户名或密码错误，返回false
			return false;
		}
	}

    /** @brief 通过用户名查询用户
     *
     */
    public function getUserByUname($uname){
        if(empty($uname))
            return false;

        return $this->find(array('uname' => $uname));
    }
    /** @brief 通过登记的E-Mail查询用户
     *
     */
    public function getUserByEmail($email) {
        if(empty($email))
            return false;

        $condition = array(
            'email' => $email
        );
        return $this->find($condition);
    }
    /** @brief 获取用户基本信息 */
    public function getUserInfo($uid) {
        return $this->find(array('id' => $uid));
    }
	/**
	 * 无权限提示及跳转
	 */
	public function acljump($url=''){ 
		// 这里直接“借用”了spController.php的代码来进行无权限提示
		if($url=='')
			$url = spUrl("user","login");

		header('location:'.$url);
		exit(1);
	}
	
	/**
     *
     * 成功提示程序
     *
     * 应用程序的控制器类可以覆盖该函数以使用自定义的成功提示
	 *
     * @param $msg   成功提示需要的相关信息
     * @param $url   跳转地址
     */
    public function success($msg, $url){
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){document.write(\"{$msg}\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
    }
    
	public function userDetail($uid){
		$condition = array('id'=>$uid);
		$arr = $this->find($condition);
		return $arr;
	}
	
	public function userEnabled() {
		return array(
			0=>array('name'=>T('On'),'value'=>'1'),
			1=>array('name'=>T('Off'),'value'=>'0'),
		);
	}
	
	public function userExist($username, $uid){
		$userExist = $this->find(array('uname'=>$username));
		if($userExist==null){
			return false;
		}
		if($userExist['id']==$uid){
			return false;
		}
		return true;
	}
	
	public function emailExist($email, $uid){
		$userExist = $this->find(array('email'=>$email));
		if($userExist==null){
			return false;
		}
		if($userExist['id']==$uid){
			return false;
		}
		return true;
	}
}
