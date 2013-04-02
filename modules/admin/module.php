<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 导航管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class module extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Modules");
		$this->navigation_current = 'module';
		
	}
	
	public function index(){ // 这里是首页
		$this->order();
	}
	
	public function news(){
		$action = $this->spArgs("action");
		$objNews = spClass("newsModel");
		if ($action=='edit'){
			$id = $this->spArgs("id");
			$this->tDetail = $objNews->getDetail($id);
		}
		
		if($action=='post'){
			$data = array(
				'title'		=>	$this->spArgs("title"),
				'content'	=>	$this->spArgs("content"),
				'cdate'		=>	$this->spArgs("cdate"),
			);
			$id = $this->spArgs("id");
			$type = $this->spArgs("type");
			if($type == 'edit'){
				$conditions = array('newsid'=>$id);
				$objNews->update($conditions, $data);
			}elseif($type == 'add'){
				$objNews->create($data);
			}
			
		}
		
		if ($action=='delete'){
			$id = $this->spArgs("id");
			$conditions = array('newsid'=>$id);
			$objNews->delete($conditions);
		}
		
		$current_page = $this->spArgs("page",1);
		$page_size = $this->spArgs("size",10);
		
		$arrOrder = $objNews->spPager($current_page, $page_size)->findAll(null,'newsid desc');
		$arrBar = $objNews->spPager()->getPager();
		$this->tPageBar = $arrBar;
		$this->tList = $arrOrder;
		$this->action = $action;
		$this->display("admin/module_news.html");
	}
	
	/**
	 * 友情链接[friend links]
	 */
	public function links(){
		$action = $this->spArgs("action");
		$objLinks = spClass("linksModel");
		if ($action=='edit'){
			$id = $this->spArgs("id");
			$this->tDetail = $objLinks->getDetail($id);
		}
		
		if($action=='post'){
			$data = array(
				'title'		=>	$this->spArgs("title"),
				'content'	=>	$this->spArgs("content"),
			);
			$id = $this->spArgs("id");
			$type = $this->spArgs("type");
			if($type == 'edit'){
				$conditions = array('id'=>$id);
				$objLinks->update($conditions, $data);
			}elseif($type == 'add'){
				$objLinks->create($data);
			}
			
		}
		
		if ($action=='delete'){
			$id = $this->spArgs("id");
			$conditions = array('id'=>$id);
			$objLinks->delete($conditions);
		}
		
		$current_page = $this->spArgs("page",1);
		$page_size = $this->spArgs("size",10);
		
		$arrOrder = $objLinks->spPager($current_page, $page_size)->findAll(null,'id desc');
		$arrBar = $objLinks->spPager()->getPager();
		$this->tPageBar = $arrBar;
		$this->tList = $arrOrder;
		$this->action = $action;
		$this->display("admin/module_links.html");
	}
	
	public function order(){
		$action = $this->spArgs("action");
		$objOrderform = spClass("orderformModel");
		if ($action=='edit'){
			$id = $this->spArgs("id");
			$this->tDetail = $objOrderform->getDetail($id);
		}
		
		if($action=='post'){
			$data = array(
				'name'		=>	$this->spArgs("name"),
				'email'	=>	$this->spArgs("email"),
				'tel'	=>	$this->spArgs("tel"),
				'mealdate'	=>	$this->spArgs("mealdate"),
				'timearrive'	=>	$this->spArgs("timearrive"),
				'encountertype'	=>	$this->spArgs("encountertype"),
				'mealorder'	=>	$this->spArgs("mealorder"),
				'extranotes'	=> $this->spArgs('extranotes'),
			);
			$id = $this->spArgs("id");
			$conditions = array('id'=>$id);
			$objOrderform->update($conditions, $data);
		}
		
		if ($action=='delete'){
			$data = array(
				'is_delete' => 1,
			);
			$id = $this->spArgs("id");
			$conditions = array('id'=>$id);
			$objOrderform->update($conditions, $data);
		}
		
		$current_page = $this->spArgs("page",1);
		$page_size = $this->spArgs("size",10);
		
		$arrOrder = $objOrderform->spPager($current_page, $page_size)->findAll(array('is_delete'=>0),'id desc');
		$arrBar = $objOrderform->spPager()->getPager();
		$this->tPageBar = $arrBar;
		$this->tList = $arrOrder;
		$this->action = $action;
		$this->display("admin/module_order.html");
	}
}
