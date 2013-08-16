<?php
if (!defined('SOUTHLAND')) { exit(1);}
import('general');
/**
 * 用户管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class user extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Users");
		$this->navigation_current = 'user';
	}
	
	public function index(){ // 这里是首页
		$current_page = $this->spArgs("page",1);
		$page_size = $this->spArgs("size",10);
		
		$objUser = spClass("userModel");
		$arrUser = $objUser->spPager($current_page, $page_size)->findAll(null,'uid desc');
		$arrBar = $objUser->spPager()->getPager();
		$this->tPageBar = $arrBar;
		$this->tUsers = $arrUser;
		$this->display("admin/user_index.html");
	}
	
	public function add(){
		$intNid = $this->spArgs("nid");
		$objUser = spClass("userModel");
		$this->tUserEnabled = $objUser->userEnabled();
		$objUsergroup = spClass("usergroupModel");
		$this->tUserGroup = $objUsergroup->glist($this->getLang());
		$this->action = 'add';
		$this->display("admin/user_form.html");
	}
	
	public function edit(){
		$uid = $this->spArgs("uid");
		$objUser = spClass("userModel");
		$this->user = $objUser->userDetail($uid);
		$this->tUserEnabled = $objUser->userEnabled();
		$objUsergroup = spClass("usergroupModel");
		$this->tUserGroup = $objUsergroup->glist($this->getLang());
		$this->action = 'edit';
		$this->display("admin/user_form.html");
	}
	
	public function profile(){
		$uid = $this->spArgs("uid");
		$objUser = spClass("userModel");
		$this->user = $objUser->userDetail($uid);
		$this->tUserEnabled = $objUser->userEnabled();
		$objUsergroup = spClass("usergroupModel");
		$this->tUserGroup = $objUsergroup->glist($this->getLang());
		$this->display("admin/user_profile.html");
	}
	
	public function post(){
		$uid = $this->spArgs("uid");
		$strAction = $this->spArgs("action");
		$data = array(
			'uname'		=>	$this->spArgs("uname"),
			'firstname'	=>	$this->spArgs("firstname"),
			'lastname'	=>	$this->spArgs("lastname"),
			'email'	=>	$this->spArgs("email"),
			'street'	=>	$this->spArgs("street"),
			'city'	=>	$this->spArgs("city"),
			'country'	=>	$this->spArgs("country"),
			'state'	=>	$this->spArgs("state"),
			'zip'	=>	$this->spArgs("zip"),
			'tel'	=>	$this->spArgs("tel"),
			'enabled'	=>	$this->spArgs("enabled"),
		);
		$password = $this->spArgs("upass");
		$confirmpassword = $this->spArgs("confirmpassword");
		if($password!='' && $password=$confirmpassword){
			$data['upass'] = md5($password);
		}
		
		$objUser = spClass("userModel");
		$userExist = $objUser->userExist($data['uname'], $uid);
		if($userExist){
			$this->jsonerror("'uname': '".T('Username occupied.')."'");
		}
		
		$emailExist = $objUser->emailExist($data['email'], $uid);
		if($emailExist){
			$this->jsonerror("'email': '".T('Email occupied.')."'");
		}
		if ($strAction == 'add'){
			
			$objUser->create($data);
		}elseif($strAction == 'edit'){
			$conditions = array('uid'=>$uid);
			$objUser->update($conditions, $data);
		}
		$this->jsonsuccess(T('Successfully ' . $strAction . 'ed!' ), spUrl("user","index"));
	}
	
	public function delete(){
		$uid = $this->spArgs("uid");
		$objUser = spClass("userModel");
		$conditions = array('uid' => $uid);
		$objUser->delete($conditions); // 删除记录
		$this->success(T('Successfully  deleted!' ), spUrl("user","index"));
	}
	
	// 退出登录
	public function logout(){
		// 这里是PHP.net关于删除SESSION的方法
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time()-42000, '/');}
		session_destroy();
		$userObj = spClass("userModel"); // 实例化userModel类
		// 跳转回首页
		$this->success(T("You are now signed out."), spUrl("user","login"));// 已退出，返回首页！
	}
	
	// 显示用户登录框以及验证用户登录情况
	public function login(){
		if( $uname = $this->spArgs("uname") ){ // 已经提交，这里开始进行登录验证
			$upass = $this->spArgs("upass"); // 通过acl的upass获取提交的密码
		
		    if($GLOBALS['G_SP']['mode']!='debug' && !spClass('spVerifyCode')->verify($this->spArgs('gcc'))) {
    	        $this->jump(spUrl('user','login'));
    	        return;
	        }
	    	
	    	$userObj = spClass("userModel"); // 实例化userModel类
			// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
			if( false == $userObj->userlogin($uname, $upass) ){
				// 登录失败，提示后跳转回登录页面
				$this->error(T("The username address or password you provided does not match our records."), spUrl("user","login"));//"用户名/密码错误，请重新输入！"
				
			}else{
			    // 登录成功，跳转到首页
				$this->jump(spUrl("main","index"));
			}

		}
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单
		$this->tTitle = T('Login');
		$this->tView = array(
		    'require' => array(
		        'user_sign' => true,
		        'form'  => true
		    )
		);
		$this->display("user/login.html");
	}
	
	// 用户注册
	public function signon(){
	    if($this->spArgs('submit') != 1) {
	        $this->display('user/signon.html');
	    } else {
            $uname=$this->spArgs('uname');
            $upass=$this->spArgs('upass');
            if(!preg_match( '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $uname )){
                spClass('keeper')->speak(T('Error user name should be a valid email').": $uname");
            } else {
                if(!spClass('spVerifyCode')->verify($this->spArgs('gcc'))) {
        	        spClass('keeper')->speak(T('Error Invalid Certification Code'));
        	        return;
	            }
                $data = array(
                    'uname' => $uname,
                    'upass' => $upass,
                    'email' => $uname,
                    'nick'  => $this->spArgs('nick'),
                    //'avatar'=> $this->spArgs('avatar'),
                    'enabled' => 1,
                );
                if(spClass("userModel")->create($data)==false) {
                    $this->tErrorMsg = 'Failed for user name or email duplicated';
                    $this->display("user/signon.html");
                }else {
                    $this->login();
                }
            }
        }
	}

}	
