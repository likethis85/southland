<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 导航管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class content extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Content");
		$this->navigation_current = 'content';
		
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
		
		$this->display("admin/content_index.html");
	}
	
	public function add(){
		$objNavigation = spClass("navigationModel");
		$this->tNavigationPosition = $objNavigation->navigationPosition();
		$this->tNavigationEnabled = $objNavigation->navigationEnabled();
		$this->action = 'add';
		$this->display("admin/content_form.html");
	}
	
	public function edit(){
		$intCid = $this->spArgs("cid");
		$objNavigation = spClass("navigationModel");
		$this->tNavigationDetail = $objNavigation->navigationDetail($intCid);
		$objModules = spClass("moduleModel");
		$this->tModules = $objModules->itemlist($this->getLang());
		$this->tNavigationEnabled = $objNavigation->navigationEnabled();
		$this->action = 'edit';
		$this->display("admin/content_form.html");
	}
	
	public function preview(){
		$intCid = $this->spArgs("cid");
		$objNavigation = spClass("navigationModel");
		$this->tNavigationDetail = $objNavigation->navigationDetail($intCid);
		$objModules = spClass("moduleModel");
		$this->tModules = $objModules->itemlist($this->getLang());
		$this->tNavigationEnabled = $objNavigation->navigationEnabled();
		$this->display("admin/content_preview.html");
	}
	
	public function post(){
		$strAction = $this->spArgs("action");
		$data = array(
			'title'		=>	$this->spArgs("title"),
			'content'	=>	$this->spArgs("content"),
			'module'	=>	$this->spArgs("module"),
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
		$this->success(T('Successfully ' . $strAction . 'ed!' ), spUrl("content","index"));
	}
}
