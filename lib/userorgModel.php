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

    /************************************************************************************************
	 * project related
     ***********************************************************************************************/

    /** @brief add user as project creator
     *
     */
    public function addProjectCreator($pid,$uid) {
        $this->create(array('uid' => $uid, 'pid' => $pid,  'sid' => $pid, 'scope' => $this->scope_project, 'role' => $this->role_project_creator));
    }
    /** @brief retreives all users for the project 
     *
     */
	public function getUsersByProject($pid) {
        if(!is_numeric($pid))
            return false;

        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select a.role,b.nick,b.id from $prefix"."userorg as a,$prefix"."user as b where a.uid=b.id and a.pid=$pid and a.role<9";
        return $this->findSql($sql);
	}
    /** @brief retreive all projects the user joined
     *
     */
	public function getProjectsByUser($uid) {
        if(empty($uid))
            return false;

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
    public function isMemberOfProject($pid,$uid) {
        $rcd = $this->find(array('uid' => $uid, 'sid' => $pid, 'scope' => $this->scope_project));
        return !empty($rcd);
    }
	public function getUserRole($pid,$uid) {
        $roles = $this->findAll(array('uid' => $uid, 'pid' => $pid, 'scope' => $this->scope_project), null, 'role');
        if(empty($roles))
            return array();

        $ur = array();
        foreach($roles as $value) {
            foreach($value as $role) {
                $ur.array_push($ur, $role);
            }
        }

        return $ur;
    }
    /************************************************************************************************
	 * task related
     ***********************************************************************************************/

    /** @brief retreive all uses the task related
     *
     */
	public function getUsersByTask($tid) {
	    return $this->find(array('scope' => $scope_task, 'sid' => $tid));
	}
    /** @brif retreives tasks the user related
     *
     */
    public function getTasksByUser($uid) {
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select o.role,u.nick,p.title from $prefix"."userorg as o,$prefix"."user as u,$prefix"."project as p where o.pid=p.id and o.uid=$uid and o.scope=".$this->scope_task;
        return $this->findSql($sql);
    }
    /** @brief add use as member of task
     *
     */
	public function addTaskMember($tid, $uid) {
	    $this->create(array('uid' => $uid, 'sid' => $tid, 'scope' => $this->scope_task, 'role' => $this->role_member));
	}
    /** @brief is the user a member of the task
     *
     */
    public function isMemberOfTask($tdi,$uid) {
        $rcd = $this->find(array( 'uid' => $uid, 'sid' => $tid, 'scope' => $this->scope_task));
        return !empty($rcd);
    }

    /************************************************************************************************
	 * issue related
     ***********************************************************************************************/

	/** @brief retreive all uses issue related
	 *
	 */
	public function getUsersByIssue($pid) {
        if(!is_numeric($pid))
            return false;
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
    	$role_bug_owner         = 9;
        $role_bug_reporter      = 10;
        $role_bug_assigner      = 11;
	    $sql = "select a.role,b.nick,b.id from $prefix"."userorg as a,$prefix"."user as b where a.pid=$pid and".
	            " scope=".$this->scope_issue.
	            " and a.role in($role_bug_owner,$role_bug_reporter,$role_bug_assigner)".
	            " and a.uid=b.id";
	    return $this->findSql($sql);
	}
    /** @brief retreive issues the user related 
     *
     */
    public function getIssuesByUser($uid){
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select o.role,u.nick,p.title from $prefix"."userorg as o,$prefix"."user as u,$prefix"."project as p where o.pid=p.id and o.uid=$uid and o.scope=".$this->scope_issue;
        return $this->findSql($sql);
    }
    /** @brief user who report the issue 
     *
     */
	public function addIssueReporter($iid,$uid){
	    $this->create(array('uid' => $uid,
	                        'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_reporter)
                    	    );
	}
    /** @brief user who assign issue to others
     *
     */
	public function addIssueAssigner($iid,$uid){
	    $this->create(array('uid' => $uid, 
                    	    'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_assigner)
                            );
	}
    /** @brief user who own the issue 
     *
     */
	public function addIssueOwner($iid,$uid){
	    $this->create(array('uid' => $uid, 
                    	    'pid' => spClass('spSession')->getUser()->getCurrentProject(),
                    	    'sid' => $iid, 
                    	    'scope' => $this->scope_issue, 
                    	    'role' => $this->role_bug_owner)
                    	    );
	}
    /** @brief is user a member of the issue 
     *
     */
    public function isMemberOfIssue($iid,$uid) {
        $rcd = $this->find(array('uid' => $uid,
                                 'sid' => $iid,
                                 'scope' => $this->scope_issue));
        return !empty($rcd);
    }

    /************************************************************************************************
	 * topic related
     ***********************************************************************************************/

    public function  isMemberOfTopic($tid, $uid) {
        return false;
    }
}
