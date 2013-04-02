<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 导航管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class settings extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Settings");
		$this->navigation_current = 'settings';
		
	}
	
	public function index(){
		$action = $this->spArgs("action");
		$objSettings = spClass("settingsModel");
		
		if ($action=='edit'){
			$id = $this->spArgs("id");
			$this->tDetail = $objSettings->getDetail($id);
		}
		
		if($action=='post'){
			$data = array(
				'skey'		=>	$this->spArgs("skey"),
				'stype'		=>	$this->spArgs("stype"),
				'svalue'	=>	$this->spArgs("svalue"),
			);
			$id = $this->spArgs("id");
			$type = $this->spArgs("type");
			if($type == 'edit'){
				$conditions = array('skey'=>$id);
				$objSettings->update($conditions, $data);
			}elseif($type == 'add'){
				$objSettings->create($data);
			}
			
		}
		
		if ($action=='delete'){
			$id = $this->spArgs("id");
			$conditions = array('skey'=>$id);
			$objSettings->delete($conditions);
		}
		
		$current_page = $this->spArgs("page",1);
		$page_size = $this->spArgs("size",10);
		
		$arrOrder = $objSettings->spPager($current_page, $page_size)->findAll();
		$arrBar = $objSettings->spPager()->getPager();
		$this->tPageBar = $arrBar;
		$this->tList = $arrOrder;
		$this->action = $action;
		$this->display("admin/settings_index.html");
	}
}
