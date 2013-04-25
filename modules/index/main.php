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
	    $nid = $this->spArgs('nid');
	    $module = $this->tNavigation[$nid-1];
	    if(empty($module)) {
	        $this->jumpFirstPage();
	    } else {
    		$this->tNid = $this->spArgs("nid");
    		$this->tModule = $this->tNavigation[$this->tNid-1]['module'];
    		eval('$this->_'.$this->tModule.'();');
    		$this->display("page.html");
    	}
	}
	function project() {// 选择项目
        $this->_project();
        $this->jumpTaskPage();
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
    function _wiki() {
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
		//$objLog = spClass("logModel");
		//$objLog->add($this->logNid,$this->logModule,$this->logModuleAction,$this->logModuleXid);
	}
}	
