<?php
/////////////////////////////////////////////////////////////////
// SpeedPHP中文PHP框架, Copyright (C) 2008 - 2010 SpeedPHP.com //
/////////////////////////////////////////////////////////////////

define("SPANONYMOUS","SPANONYMOUS"); // 无权限设置的角色名称


/**
 *	抽象用户类，用于描述当前用户的属性
 */
class spUser
{
	private $userInfo = null;
    private $usingProj = 0;
    private $currNid = 0;

	public function __construct() {
        $this->userInfo = array(
            'acl' => 'ANONYMOUS'
        );
	}
    public function setUserInfo($uinfo) {
        $this->userInfo = $uinfo;
    }
    public function getUserInfo() {
        return $this->userInfo;
    }
	public function is_guest() {
        return $this->userInfo['acl'] === 'ANONYMOUS';
	}
	public function is_admin() {
        return !$this->is_guest() && $this->userInfo['acl']=='WEBMASTER';
	}
	public function is_user() {
        return !$this->is_guest() && $this->userInfo['acl']!='WEBMASTER';
	}
    public function getAcl() {
        return $this->userInfo['acl'];
    }
	public function getUserAccount() {
		if(!empty($this->userInfo)) {
			return $this->userInfo['uname'];
		}
	}
	public function getUserName() {
		if(!empty($this->userInfo)) {
			return $this->userInfo['firstname'].' '.$this->userInfo['lastname'];
		}
        return null;
	}
	public function getUserId() {
		if(!empty($this->userInfo)) {
			return $this->userInfo['id'];
		}
	}
    public function setCurrentNid($nid) {
        $this->currNid = $nid;
    }
    public function getCurrentNid() {
        return $this->currNid;
    }
	public function setCurrentProject($pid) {
	    $this->usingProj = $pid;
    }
    public function getCurrentProject() {
        return $this->usingProj;
    }
    public function setRole($role) {
        $this->userInfo['role'] = $role;
    }
    public function getRole() {
        return $this->userInfo['role'];
    }
}


/**
 * 基于组的用户权限判断机制
 * 要使用该权限控制程序，需要在应用程序配置中做以下配置：
 * 有限控制的情况，在配置中使用	'launch' => array( 'router_prefilter' => array( array('spAcl','mincheck'), ), )
 * 强制控制的情况，在配置中使用	'launch' => array( 'router_prefilter' => array( array('spAcl','maxcheck'), ), )
 */
class spAcl
{
	/**
	 * 默认权限检查的处理程序设置，可以是函数名或是数组（array(类名,方法)的形式）
	 */
	public $checker = array('spAclModel','check');
	
	/**
	 * 默认提示无权限提示，可以是函数名或是数组（array(类名,方法)的形式）
	 */
	public $prompt = array('spAcl','def_prompt');
	/**
	 * 构造函数，设置权限检查程序与提示程序
	 */
	public function __construct()
	{	
		$params = spExt("spAcl");
		if( !empty($params["prompt"]) )$this->prompt = $params["prompt"];
		if( !empty($params["checker"]) )$this->checker = $params["checker"];
	}

	/**
	 * 获取当前会话的用户标识
	 */
	public function get()
	{
        return spClass('spSession')->getUser()->getAcl();
	}

    /**
     *
     */
    public function maxcheck() {
        return TRUE;
    }
	/**
	 * 无权限提示跳转
	 */
	public function prompt($url='')
	{
		$prompt = $this->prompt;
		if( is_array($prompt) ){
			return spClass($prompt[0])->{$prompt[1]}($url);
		}else{
			return call_user_func_array($prompt,array($url));
		}
	}
	
	/**
	 * 默认的无权限提示跳转
	 */
	public function def_prompt()
	{
		$url = spUrl(); // 跳转到首页，在强制权限的情况下，请将该页面设置成可以进入。
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"Access Failed!\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	/**
	 * 设置当前用户，内部使用SESSION记录
	 * 
	 * @param acl_name    用户标识：可以是组名或用户名
	 */
	public function set($acl_name)
	{
		$_SESSION["SpAclSession"] = $acl_name;
	}
}

 /**
 * ACL操作类，通过数据表确定用户权限
 * 表结构：
 * CREATE TABLE acl
 * (
 * 	aclid int NOT NULL AUTO_INCREMENT,
 * 	name VARCHAR(200) NOT NULL,
 * 	controller VARCHAR(50) NOT NULL,
 * 	action VARCHAR(50) NOT NULL,
 * 	acl_name VARCHAR(50) NOT NULL,
 * 	PRIMARY KEY (aclid)
 * ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
 */
class spAclModel extends spModel
{

	public $pk = 'aclid';
	/**
	 * 表名
	 */
	public $table = 'acl';

	public function check_black($acl_name, $controller, $action)
	{
		$rows = array('controller' => $controller, 'action' => $action);
        $items = $this->findAll($rows);
        foreach($items as $item) {
            if(($item['workspace']==null || $item['workspace']==WORKSPACE) && ($item['acl_name']==$acl_name))
                return FALSE;
        }
        return TRUE;
	}

    public function check_white($acl_name, $controller, $action)
	{
		$rows = array('controller' => $controller, 'action' => $action);
        $items = $this->findAll($rows);
        foreach($items as $item) {
            if(($item['workspace']==null || $item['workspace']==WORKSPACE) && ($item['acl_name']==$acl_name || $item['acl_name']=='ANONYMOUS'))
                return TRUE;
        }
        return FALSE;
	}
}
