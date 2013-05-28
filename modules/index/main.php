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
	    $this->tNid = 0;
        spClass('spSession')->getUser()->setCurrentNid($this->tNid);
        $this->tProjects = spClass('projectModel')->getProjects();
		$this->display("index.html");
	}
	
	function page(){ // 其他内容
	    $nid = $this->spArgs('nid');
	    $module = $this->tNavigation[$nid-1];
	    if(empty($module)) {
	        $this->jumpFirstPage();
	    } else {
    		$this->tNid = $this->spArgs("nid");
            spClass('spSession')->getUser()->setCurrentNid($this->tNid);
    		$this->tModule = $this->tNavigation[$this->tNid-1]['module'];
    		$this->_project();
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
        if(empty($pid))
            $pid = $this->tCurrProj;
        $uid = $this->tUser['id'];
        if(!spClass('projectModel')->allow($pid,$uid)) {
            spClass('keeper')->speak(T('Error Operation not permit'), '/index.php');
            exit;
        }

		if(!empty($pid) && $pid!=$this->tCurrProj) {
		    spClass('spSession')->getUser()->setCurrentProject($pid);
		    $this->tCurrProj = spClass('spSession')->getUser()->getCurrentProject();
		    $this->tProject  = spClass('projectModel')->getCurrentInfo();
		    $uom = spClass('userorgModel');
		    $ur = array('Manager' => false, 'Dev' => false, 'QA' => false);
            $roles = $uom->getUserRole($pid,$uid);
            foreach($roles as $role) {
                switch($role){
                case $uom->role_project_manager:
                    $ur['Manager'] = true;
                    break;
                case $uom->role_dev_owner:
                case $uom->role_dev_manager:
                    $ur['Dev'] = true;
                    break;
                case $uom->role_qa_owner:
                case $uom->role_qa_manager:
                    $ur['QA'] = true;
                    break;
                }
            }
            spClass('spSession')->getUser()->setRole($ur);
		}
		
		if($this->tNid==1) {
            $this->tMembers = spClass('projectModel')->getProjectMembers();
		    $tTimeline = array();
            $timelines = spClass('timelineModel')->getProject($this->tCurrProj);
            foreach($timelines as $timeline){
                array_push($tTimeline, array('title' => $timeline['brief'], 'start' => $timeline['stime'], 'end' => $timeline['etime'], 'id' => $timeline['id']));
            }
            $this->tTimeline = $tTimeline;
        }
	}
	function _forum(){
    	$objForum = spClass("forumModel");
        $this->tSubjects = $objForum->getTopics();
	}

    function _task() {
        $objTask = spClass('taskModel');
        $this->tTasks = $objTask->getTasks();
    }
    function _issue() {
        $uom = spClass('userorgModel');
        $pid = spClass('spSession')->getUser()->getCurrentProject();
        $uid = spClass('spSession')->getUser()->getUserId();
        $owners = $uom->getUsersByIssue($pid);
        $this->tIssues = spClass('issueModel')->getIssues();
        $issues = $this->tIssues;
        foreach($issues as &$issue){
            foreach($owners as $member){
                if($member['role']==$uom->role_bug_reporter)
                    $issue['reporter'] = $member;
                else if($member['role']==$uom->role_bug_assigner)
                    $issue['assigner'] = $member;
                else if($member['role']==$uom->role_bug_owner)
                    $issue['owner'] = $member;
            }
        }
        $this->tIssues = $issues;
        unset($issues);
        
        $tMembers = array();
        $members = $uom->getUsersByProject($pid);
        foreach($members as $member){
            $tMembers[$member['uid']] = $member;
        }
        $this->tMembers = $tMembers;
        unset($tMembers);
    }
    function _wiki() {
        $this->tWikis = spClass('wikiModel')->getWikis();
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
		//$objLog = spClass("logModel");
		//$objLog->add($this->logNid,$this->logModule,$this->logModuleAction,$this->logModuleXid);
	}
}	
