<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public $logNid = 0;
	public $logModule = 0;
	public $logModuleAction = '';
	public $logModuleXid = 0;
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function index(){ // 这里是首页
		$this->contents = "主体模块首页内容";
		$this->index = 1;
		
		$objNews = spClass("newsModel");
		$this->tHomeNews = $objNews->getList(4);
		$this->display("index.html");
	}
	
	function page(){ // 其他内容
		$intNid = $this->spArgs("nid");
		$this->logNid = $intNid;
		$objNavigation = spClass("navigationModel");
		$arrNavigationDetail = $objNavigation->navigationDetail($intNid);
		$this->tNavigationDetail = $arrNavigationDetail;
		$this->tNid = $intNid;
		
		// 模块的模版载入
		if($arrNavigationDetail['module']!=0){
			$objModule = spClass("moduleModel");
			$arrModule = $objModule->find(array('mid'=>$arrNavigationDetail['module']));
			$this->tModule = $arrModule['module'];
			eval('$this->_'.$arrModule['module'].'();');
			// log
			$this->logModule = $arrNavigationDetail['module'];
		}
		$this->display("page.html");
	}
	
	function project() {// 选择项目
        $nid = $this->spArgs('id');
        $this->setUsingProject($nid);
        $this->jumpProjectPage();
	}
	// module news
	function _news(){
		$intNid = $this->spArgs("nid");
		$intNewsid = $this->spArgs("news",0);
		if ($intNewsid!=0){
			$objNews = spClass("newsModel");
			$this->tNews = $objNews->getDetail($intNewsid);
			$this->moduleaction='detail';
			// log
			$this->logModuleAction = 'detail';
			$this->logModuleXid = $intNewsid;
		}else{
			$objNews = spClass("newsModel");
			$this->tNews = $objNews->getList();
			// log
			$this->logModuleAction = 'list';
			$this->logModuleXid = $intNewsid;
		}
	}

	function _order(){
		$submitsoporder = $this->spArgs('submitsoporder');
		// log
		$this->logModuleAction = 'dispaly';
		$this->logModuleXid = 0;
		
		if($submitsoporder == 1){
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
			
			$objOrderform = spClass("orderformModel");
			$objOrderform->create($data);
			$this->moduleaction = 'submit';
			// log
			$this->logModuleAction = 'submit';
			$this->logModuleXid = $intNewsid;
		}
	}
	function _project() {
		$objProj = spClass('projectModel');
		$this->tProject = $objProj->getCurrentInfo();
        $this->tMembers = $objProj->getProjectMembers();
	}
	function _forum(){
		$objForum = spClass("forumModel");
		$this->tSubjects = $objForum->getTopics();
	}

    function _task() {
        $objTask = spClass('taskModel');
        $this->tTasks = $objTask->getTasks();
        $this->tCurrTask = $this->spArgs('id');
        $this->tComments = spClass('commentModel')->getTaskComments($this->tCurrTask);
    }
    function _issue() {
        $this->tIssues = spClass('issueModel')->getIssues();
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
		//$objLog = spClass("logModel");
		//$objLog->add($this->logNid,$this->logModule,$this->logModuleAction,$this->logModuleXid);
	}
}	
