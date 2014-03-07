<?php
if (!defined('SOUTHLAND')) { exit(1);}
class issueModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "issue"; // 数据表的名称
    var $STATUS_PENDING = 0;
    var $STATUS_WORKING = 1;
    var $STATUS_FIXED = 2;
    var $STATUS_VERIFIED = 3;
    var $STATUS_CLOSED = 4;
    var $STATUS_DUPLICATED = 5;
    var $STATUS_KNOWISSUE = 6;
    var $STATUS_DEFER = 7;            
    var $STATUS_IGNORED = 8;

    /** @brief 创建Issue */
    public function createIssue($pid,$uid,$tid,$prio,$brief,$detail,$acl) {
        $iid = $this->create(array(
    			    'uid' => $uid,
    			    'prj' => $pid,
                    'tid' => $tid,
    				'priority' => $prio,
    				'brief'    => $brief,
                    'detail'   => $detail,
                    'acl'      => $acl
    			    )
	           );
	    if(false===$iid)
	        return false;
	        
	    if(false === spClass('userroleModel')->addIssueCreator($pid,$iid,$uid))
	        return false;
	        
	    return $iid;
    }
    public function updateIssue($iid,$prio,$brief,$detail,$acl) {
        return $iid = $this->update(array('id'=>$iid), 
                                    array(
                                        'priority' => $prio,
                                        'brief'    => $brief,
                                        'detail'   => $detail,
                                        'acl'      => $acl
                                        )
                                   );
    }
    /** @brief  获取Issue详细信息 */
    public function getIssueDetail($iid) {
        if(empty($iid)) 
            return false;

        $issue = $this->find(array('id' => $iid));
        if(empty($issue))
            return false;
        if(!empty($issue['tid']))
            $task = spClass('taskModel')->find(array('id'=>$issue['tid']));
        $attachments = spClass('attachmentModel')->getIssueAttachment($iid);
        $members = spClass('userroleModel')->getUsersByProject($issue['prj']);
        $owner = spClass('userroleModel')->getIssueOwner($iid);
        $issue['owner'] = $owner[0]['id'];
        $issue['task'] = $task;
        $issue['attachment'] = $attachments;
        $issue['member'] = $members;
        return $issue;
    }
    /** @brief update status of the issue  */
    public function updateStatus($iid, $status){
        if(empty($iid) || empty($status))
            return false;
            
        return $this->update(array('id' => $iid), array('status' => $status));
    }
    /** @brief convert string to status */
    public function str2status($str){
        $status = array(
            'pending'   => $this->STATUS_PENDING,
            'working'   => $this->STATUS_WORKING,
            'fixed'     => $this->STATUS_FIXED,
            'verified'  => $this->STATUS_VERIFIED,
            'closed'    => $this->STATUS_CLOSED,
            'duplicated'=> $this->STATUS_DUPLICATED,
            'knowissue' => $this->STATUS_KNOWISSUE,
            'defer'     => $this->STATUS_DEFER
        );
        
        return $status[$str];
    }
    /** @brief Issue的ACL控制 */
    public function allow($iid, $uid, $operation) {
        if(empty($iid) || !is_numeric($iid))
            return false;

        $issue = $this->find(array('id' => $iid));
        if(empty($issue)) return false;
        
        if(empty($operation))$operation='Default';
        $op = 'allow{$operation}';
        if(!method_exists($this, $op))
            $op='allowDefault';
            
        return $this->{$op}($issue,$uid);
    }
    private function allowDefault($issue,$uid) {
        return spClass('userroleModel')->isMemberOfProject($issue['prj'], $uid);
    }
    private function allowView($issue,$uid) {
        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($issue['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        if($issue['acl']==$allow_protected)
            return spClass('userroleModel')->isMemberOfProject($issue['prj'], $uid);
        else
            return spClass('userroleModel')->isMemberOfIssue($iid, $uid);
    }

    /** @brief 推迟bug到指定的项目 
     *
     *  @Detail
     *      把Bug相关的timeline事件和comments都更新到指定的pid下
     */
    public function post($iid, $pid) {
        if(false == $this->update(array('id' => $iid), array('prj' => $pid)))
            return false;

        spClass('commentModel')->postIssue($iid, $pid);
        spClass('userroleModel')->postIssue($iid, $pid);
        return true;
    }
}
