<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 管理员系统管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class main extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	public function index(){ // 这里是首页
        $uid = $this->tUser['id'];
        if($uid != 1)
            spError();

        $this->tTitle = T('AdminPage');

        $this->tSiteUsers = spClass('userModel')->getAllUsers();

        $this->tView = array(
            'require' => array(
                'timelinr' => true
            )
        );

		$this->display("admin/index.html");
	}
}
