<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 导航管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class navigation extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Navigation");
		$this->navigation_current = 'navigation';
		
	}
	
	public function index(){ // 这里是首页
		$objNavigation = spClass("navigationModel");
		// save config
		if(isset($_POST)){
			foreach ($_POST as $v){
				if(isset($v['mlid'])){
					$data = array(
						'weight'	=>	$v['weight'],
						'parentid'	=>	$v['plid'],
						'enabled'	=>	$v['hidden']+0,
						'expanded'	=>	$v['expanded']+0,
					);
					$conditions = array('nid'=>($v['mlid']+0));
					$objNavigation->update($conditions, $data);
				}
			}
		}
		// navigation position list
		$arrPosition = $objNavigation->navigationPosition();
		// top navigation
		$this->tNavigationTop = $objNavigation->navigationTree(1);
		//bottom navigation
		$this->tNavigationBottom = $objNavigation->navigationTree(2);
		//naviation without position
		$this->tNavigationOther = $objNavigation->navigationTree(0);
		
		$this->display("admin/navigation_index.html");
	}
	
	public function add(){
		$intNid = $this->spArgs("nid");
		$objNavigation = spClass("navigationModel");
		$this->tNavigationPosition = $objNavigation->navigationPosition();
		$this->tNavigationEnabled = $objNavigation->navigationEnabled();
		$this->action = 'add';
		$this->display("admin/navigation_form.html");
	}
	
	public function edit(){
		$intNid = $this->spArgs("nid");
		$objNavigation = spClass("navigationModel");
		$this->tNavigationDetail = $objNavigation->navigationDetail($intNid);
		$this->tNavigationPosition = $objNavigation->navigationPosition();
		$this->tNavigationEnabled = $objNavigation->navigationEnabled();
		$this->action = 'edit';
		$this->display("admin/navigation_form.html");
	}
	
	public function post(){
		$strAction = $this->spArgs("action");
		$data = array(
			'name'		=>	$this->spArgs("name"),
			'position'	=>	$this->spArgs("position"),
			'enabled'	=>	$this->spArgs("enabled"),
		);
		
		$objNavigation = spClass("navigationModel");
		if ($strAction == 'add'){
			$objNavigation->create($data);
		}elseif($strAction == 'edit'){
			$intNid = $this->spArgs("nid");
			$conditions = array('nid'=>$intNid);
			$objNavigation->update($conditions, $data);
		}
		$this->success(T('Successfully ' . $strAction . 'ed!' ), spUrl("navigation","index"));
		
	}
	public function delete(){
		$intNid = $this->spArgs("nid");
		$objNavigation = spClass("navigationModel");
		$conditions = array('nid' => $intNid);
		$objNavigation->delete($conditions); // 删除记录
		$conditions = array('parentid' => $intNid);
		$objNavigation->delete($conditions); // 删除父导航为此id的记录
		$this->success(T('Successfully  deleted!' ), spUrl("navigation","index"));
	}
}
