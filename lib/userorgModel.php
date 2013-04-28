<?php
if (!defined('SOUTHLAND')) { exit(1);}

class userorgModel extends spModel
{
	var $pk = "id";
	var $table = "userorg"; // 数据表的名称
	
	var $scope_project = 1;
	var $scope_task = 2;
	var $scope_issue = 3;
	
	var $role_member = 0;
	var $role_dev_owner = 1;
	var $role_qa_owner = 2;
	var $role_project_manager = 3;
	var $role_dev_manager = 4;
	var $role_qa_manager = 5;
    var $role_dev_member = 6;
    var $role_qa_member = 7;
    var $role_project_creator = 8;

	// project related
    public function addProjectCreator($pid,$uid) {
        $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_project_creator));
    }
	public function getUsersByProject($pid, $withuser) {
	    if(true === $withuser) {
            $prefix = $GLOBALS['G_SP']['db']['prefix'];
	        $sql = "select a.role,b.nick,b.uid from ".$prefix."userorg as a,".$prefix."user as b where a.uid=b.uid and a.sid=$pid and a.scope=".$this->scope_project;
	        return $this->findSql($sql);
	    } else {
	        return $this->find(array('scope' => $scope_project, 'sid' => $pid));
	    }
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
	
	// task project
	public function getUsersByTask($tid) {
	    return $this->find(array('scope' => $scope_task, 'sid' => $tid));
	}
	public function addTaskMember($tid, $uid) {
	    $this->create(array('uid' => $uid, 'sid' => $tid, 'scope' => $this->scope_task, 'role' => $this->role_member));
	}
	public function removeTask($tid) {
	    return $this->delete(array('sid' => $tid, 'scope' => $this->scope_task));
	}
	
}
