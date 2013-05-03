<?php
if (!defined('SOUTHLAND')) { exit(1);}

class userorgModel extends spModel
{
	var $pk = "id";
	var $table = "userorg"; // 数据表的名称
	
	var $scope_project = 1;
	var $scope_task = 2;
	var $scope_issue = 3;
	
	var $role_member            = 0;
	var $role_dev_owner         = 1;
	var $role_qa_owner          = 2;
	var $role_project_manager   = 3;
	var $role_dev_manager       = 4;
	var $role_qa_manager        = 5;
    var $role_dev_member        = 6;
    var $role_qa_member         = 7;
    var $role_project_creator   = 8;
    var $role_bug_owner         = 9;
    var $role_bug_reporter      = 10;
    var $role_bug_assigner      = 11;

	// project related
    public function addProjectCreator($pid,$uid) {
        $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_project_creator));
    }
	public function getUsersByProject() {
	    $pid = spClass('spSession')->getUser()->getCurrentProject();
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        
        $role_member            = 0;    
        $role_dev_owner         = 1;    
        $role_qa_owner          = 2;    
        $role_project_manager   = 3;    
        $role_dev_manager       = 4;    
        $role_qa_manager        = 5;    
        $role_dev_member        = 6;    
        $role_qa_member         = 7;    
        $role_project_creator   = 8;      

        $sql = "select a.role,b.nick,b.uid from $prefix"."userorg as a,$prefix"."user as b where a.uid=b.uid and a.pid=$pid and a.role<9";
        return $this->findSql($sql);
	}
	public function getProjectsByUser($uid) {
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $sql = "select a.* from ".$prefix."project as a ,".$prefix."userorg as b where a.droptime=0 and a.id=b.sid and b.uid=$uid and b.scope=".$this->scope_project;
	    return $this->findSql($sql);
	}
	public function addDevMember($pid,$uid) {
	    return $this->create(array('uid' => $uid, 'pid' => $pid, 'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_dev_member));
	}
	public function addQAMember($pid,$uid) {
	    $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_qa_member));
	}
	public function addProjectMember($pid, $uid) {
	    $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_member));
	}
	public function addProjectManager($pid, $uid) {
	    $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_project_manager));
	}
	public function removeProject($pid) {
	    return $this->delete(array('sid' => $pid, 'scope' => $this->scope_project));
	}
	
	// task related
	public function getUsersByTask($tid) {
	    return $this->find(array('scope' => $scope_task, 'sid' => $tid));
	}
	public function addTaskMember($tid, $uid) {
	    $this->create(array('uid' => $uid, 'sid' => $tid, 'scope' => $this->scope_task, 'role' => $this->role_member));
	}
	public function removeTask($tid) {
	    return $this->delete(array('sid' => $tid, 'scope' => $this->scope_task));
	}
	
	// issue related
	/** @brief retreive all uses issue related
	 *
	 */
	public function getIssueUsers(){
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
    	$role_bug_owner         = 9;
        $role_bug_reporter      = 10;
        $role_bug_assigner      = 11;
	    $pid = spClass('spSession')->getUser()->getCurrentProject();
	    $sql = "select a.role,b.nick,b.uid from $prefix"."userorg as a,$prefix"."user as b where a.pid=$pid and".
	            " scope=".$this->scope_issue.
	            " and a.role in($role_bug_owner,$role_bug_reporter,$role_bug_assigner)".
	            " and a.uid=b.uid";
	    return $this->findSql($sql);
	}
	public function addIssueReporter($iid,$uid){
	    $this->create(array('uid' => $uid,
	                        'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_reporter)
                    	    );
	}
	public function addIssueAssigner($iid,$uid){
	    $this->create(array('uid' => $uid, 
                    	    'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_assigner)
                            );
	}
	public function addIssueOwner($iid,$uid){
	    $this->create(array('uid' => $uid, 
                    	    'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_owner)
                    	    );
	}
}
