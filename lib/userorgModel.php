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
	
	// project related
	public function getUsersByProject($pid) {
	    return $this->find(array('scope' => $scope_project, 'sid' => $pid));
	}
	public function addProjectMember($pid, $uid) {
	    $this->create(array('uid' => $uid, 'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_member));
	}
	public function addProjectManager($pid, $uid) {
	    $this->create(array('uid' => $uid, 'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_project_manager));
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
