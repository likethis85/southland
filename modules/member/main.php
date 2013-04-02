<?php
if (!defined('SPEEDCMS')) { exit(1);}
class main extends spController
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = "用户模块|主题部分";
	}
	
	function index(){ // 这里是首页
		$this->contents = "用户模块首页内容";
		$this->display("member/main_index.html");
	}
	
	function page(){ // 其他内容
		$this->contents = "用户模块其他内容";
		$this->display("member/main_page.html");
	}
}	