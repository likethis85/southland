<?php
if (!defined('SOUTHLAND')) { exit(1);}

class userroleModel extends spModel
{
	var $pk = "id";
	var $table = "userrole"; // 数据表的名称
	
	var $scope_project = 1;
	var $scope_task = 2;
	var $scope_issue = 3;

    var $role = array(
        'role_project_member' => 1,
        'role_project_creator'=> 2,
    	'role_project_owner' => 9,
    	
    	'role_task_member' => 11,
    	'role_task_creator'=> 12,
    	'role_task_owner'  => 19,
    	
    	'role_topic_member' => 21,
    	'role_topic_creator'=> 22,
    	
    	'role_issue_member' => 31,
    	'role_issue_creator'=> 32,
    	'role_issue_owner'  => 39
    );

    /************************************************************************************************
	 * project related
     ***********************************************************************************************/
    /** @brief 添加项目创建者 */
    public function addProjectCreator($pid,$uid) {
        return $this->create(array(
                                'uid' => $uid, 
                                'prj' => $pid, 
                                'sid' => $pid, 
                                'scope' => $this->scope_project,
                                'role' => $this->role['role_project_creator'], 
                                'title'=>'role_project_creator')
                            );
    }
    /** @brief 添加为项目成员 */
	public function addProjectMember($pid, $uid) {
	    return $this->create(array(
	                    'uid' => $uid, 
	                    'prj' => $pid,  
	                    'sid' => $pid, 
	                    'scope' => $this->scope_project, 
	                    'role' => $this->role['role_project_member'], 
	                    'title'=>'role_project_member')
	                 );
	}
    /** @brief 获取项目的所有成员 */
	public function getUsersByProject($pid) {
        if(!is_numeric($pid))
            return false;

        $scope=$this->scope_project;
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select b.avatar,a.role,b.nick,b.id from {$prefix}userrole as a,{$prefix}user as b where a.uid=b.id and a.sid=$pid and a.scope=$scope";
        return $this->findSql($sql);
	}
    /** @brief 获取该用户参与的所有项目 */
	public function getProjectsByUser($uid) {
        if(empty($uid))
            return false;

        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $scope = $this->scope_project;
	    $sql = "select * from {$prefix}project where droptime=0 and (acl=0 or  
	             id in(select sid from {$prefix}userrole where uid=$uid and scope=$scope))";
	    return $this->findSql($sql);
	}
	/** @brief 获取用户角色 */
	public function getUserRoleOnProject($pid, $uid) {
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $scope = $this->scope_project;
        $sql = "select role from {$prefix}userrole where sid=$pid and scope=$scope and uid=$uid";
        $items = $this->findSql($sql);
        $roles = array();
        foreach($items as $role) {
            array_push($roles, $role['role']);
        }
        return $roles;
	}
	/** @brief 判断用户是否为项目成员 */
    public function isMemberOfProject($pid,$uid) {
        $rcd = $this->find(array('uid' => $uid, 'sid' => $pid, 'scope' => $this->scope_project));
        return !empty($rcd);
    }
    
    /************************************************************************************************
	 * task related
     ***********************************************************************************************/
    /** @brief 添加Task创建人 */
    public function addTaskCreator($pid,$tid,$uid){
        return $this->create(array(
                                'uid' => $uid, 
                                'prj' => $pid, 
                                'sid' => $tid, 
                                'scope' => $this->scope_task,
                                'role' => $this->role['role_task_creator'], 
                                'title'=>'role_task_creator')
                            );
    }
    /** @brief 获取任务相关的所有人员 */
	public function getUsersByTask($tid) {
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $scope = $this->scope_task;
	    $sql = "select * from {$prefix}user where id in (select uid from {$prefix}userrole where sid=$tid and scope=$scope)";
	    return $this->runSql($sql);
	}
    /** @brief 获取用户相关的所有任务 */
    public function getTasksByUser($pid,$uid) {
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $scope = $this->scope_task;
        $sql = "select T.*,R.role from {$prefix}task as T left join {$prefix}userrole as R on R.uid=$uid and T.id=R.sid and scope=$scope and R.prj=$pid where T.droptime=0 and ( R.uid=$uid or T.acl=0 and T.prj=$pid)";
        $tasks = $this->findSql($sql);
        foreach($tasks as $task) {
            $id = $task['id'];
            if(isset($temp[$id])) {
                array_push($temp[$id]['role'],$task['role']);
            } else {
                $role = $task['role'];
                $task['role'] = array($role);
                $temp[$id] = $task;
            }
        }
        return $temp;
    }
    /** @brief 判断是否为Task相关用户 */
    public function isMemberOfTask($tid,$uid) {
        $rcd = $this->find(array( 'uid' => $uid, 'sid' => $tid, 'scope' => $this->scope_task));
        return !empty($rcd);
    }

    /************************************************************************************************
	 * issue related
     ***********************************************************************************************/
    /** @brief 添加Issue创建者 */
    public function addIssueCreator($pid,$iid,$uid) {
        return $this->create(array(
                                'uid' => $uid,
                                'prj' => $pid,
                                'sid' => $iid,
                                'scope' => $this->scope_issue,
                                'role' => $this->role['role_issue_creator'],
                                'title'=> 'role_issue_creator')
                            );
    }
    /** @brief 添加Issue的负责人 */
    public function addIssueOwner($pid,$iid,$uid) {
        return $this->create(array(
            'uid' => $uid,
            'prj' => $pid,
            'sid' => $iid,
            'scope' => $this->scope_issue,
            'role' => $this->role['role_issue_owner'],
            'title'=> 'role_issue_owner'
        ));
    }
	/** @brief 获取Issue相关的所有用户 */
	public function getUsersByIssue($iid) {
        if(!is_numeric($pid))
            return false;
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
    	$scope = $this->scope_issue;
	    $sql = "select * from {$prefix}user as I inner join {$prefix}userrole as R on I.id=R.uid and R.sid=$iid and R.scope=$scope";
	    return $this->findSql($sql);
	}
    /** @brief 获取用户相关的所有Issue */
    public function getIssuesByUser($pid,$uid){
	    $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $scope = $this->scope_issue;
        $sql = "select I.*,R.role from {$prefix}issue as I left join {$prefix}userrole as R 
                 on R.uid=$uid and I.id=R.sid and R.scope=$scope and R.prj=$pid where I.droptime=0 and (R.uid=$uid or I.acl=0 and I.prj=$pid)";
        $issues = $this->findSql($sql);
        foreach($issues as $issue) {
            $id = $issue['id'];
            if(isset($temp[$id])) {
                array_push($temp[$id]['role'],$issue['role']);
            } else {
                $role = $issue['role'];
                $issue['role'] = array($role);
                $temp[$id] = $issue;
            }
        }
        return $temp;
    }
    /** @brief 获取Issue的创建人信息 */
    public function getIssueCreator($iid) {
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $scope = $this->scope_issue;
	    $role = $this->role['role_issue_creator'];
	    $sql = "select * from {$prefix}user where id in 
	             (select uid from {$prefix}userrole where sid=$iid and scope=$scope and role=$role)";
	    $user = $this->findSql($sql);
	    if(!empty($user)) return $user[0];
	    else return array();
    }
    /** @brief 获取Issue的负责人信息 */
    public function getIssueOwner($iid) {
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
	    $scope = $this->scope_issue;
	    $role = $this->role['role_issue_owner'];
	    $sql = "select * from {$prefix}user where id in 
	             (select uid from {$prefix}userrole where sid=$iid and scope=$scope and role=$role)";
	    return $this->findSql($sql);
    }
    /************************************************************************************************
	 * topic related
     ***********************************************************************************************/

    public function  isMemberOfTopic($tid, $uid) {
        return false;
    }
}
