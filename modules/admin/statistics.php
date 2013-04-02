<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 统计管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-07-17
 */
class statistics extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Statistics");
		$this->navigation_current = 'statistics';
		
	}
	
	public function index(){ // 这里是首页
	}
}
