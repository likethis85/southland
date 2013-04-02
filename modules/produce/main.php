<?php
class main extends spController
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = "产品模块|主题部分";
	}
	
	function index(){ // 这里是首页
		$this->contents = "产品模块首页内容";
		$this->display("produce/main_index.html");
	}
	
	function page(){ // 其他内容
		$this->contents = "产品模块其他内容";
		$this->display("produce/main_page.html");
	}
}	