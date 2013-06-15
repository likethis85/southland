<?php
/** @brief 用于session的用户信息类型
 *
 *  @Detail
 *      用于描述当前登录用户的信息,与session一起持久化
 */
class spUser {
	private $userInfo = null;
    private $usingProj = 0;
    private $currNid = 0;

	public function __construct() {
        $this->userInfo = array('id' => 0);
	}
    public function setUserInfo($uinfo) {
        $this->userInfo = $uinfo;
    }
    public function getUserInfo() {
        return $this->userInfo;
    }
	public function is_guest() {
        return $this->userInfo['id']==0;
	}
	public function is_admin() {
        return false;
	}
	public function is_user() {
        return $this->userInfo['id']!=0;
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
 * Session扩展类
 *
 */
class spSession {

        function  __construct() {
        	if(empty($_SESSION['user'])) {
                $_SESSION['user'] = spClass('spUser');
            }
        }

        function getUser() {
            return $_SESSION['user'];
        }

        /** @brief 支持以path形式访问Session值
         * 
         * @param string $key
         * @return mixed
         */
        function get($key = NULL) {
        		return $_SESSION[$key];
                //return $this->path_array($this->session, $key);
        }

        /**
         * 将数据存入SESSION, 支持path形式访问
         * @param string $key
         * @param mixed $value
         * @return bool
         */
        function put($key, $value) {
        		$_SESSION[$key] = $value;
        		/*
                $array =& $this->path_array($this->session, $key);
                $array = $value;
                return TRUE;
            */
        }

        /**
         * Path形式访问数组
         * @param minxed &$array
         * @param string $path
         * @return mixed
         */
        private function &path_array(&$array, $path = NULL) {
                if(empty($path) || !is_array($array)) {
                        return $array;
                }else{
                        $arr_path = explode('/', $path);
                        $path = NULL;
                        foreach($arr_path as $v){
                                $path .= '[\''.addslashes($v).'\']';
                        }
                        eval('$value =& $array'.$path.';');
                        return $value;
                }
        }
}
/* End of this file */
