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
	    if(spClass('spSession')->getUser()->is_user()) {
		    $this->tProjects = spClass('projectModel')->getProjects();
		}
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
        $nid = $this->spArgs('pid');
        $this->setUsingProject($nid);
        $this->jumpProjectPage();
	}
	function _project() {
		$pid = $this->spArgs('pid');
		if(!empty($pid) && $pid!=$this->tCurrProj && spClass('projectModel')->allow($pid)) {
		    spClass('spSession')->getUser()->setCurrentProject($pid);
		    $this->tCurrProj = spClass('spSession')->getUser()->getCurrentProject();
		    $this->tProject = spClass('projectModel')->getCurrentInfo();
		}
	}
	function _forum(){
	    $this->_project();
    	$objForum = spClass("forumModel");
        $this->tSubjects = $objForum->getTopics();
	}

    function _task() {
        $this->_project();
        $objTask = spClass('taskModel');
        $this->tTasks = $objTask->getTasks();
    }
    function _issue() {
        $this->_project();
        $this->tIssues = spClass('issueModel')->getIssues();
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
		//$objLog = spClass("logModel");
		//$objLog->add($this->logNid,$this->logModule,$this->logModuleAction,$this->logModuleXid);
	}
}	
