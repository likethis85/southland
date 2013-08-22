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
    /** @brief retrieve issues belongs to current project
     *
     */
    public function getIssues() {
        $projId = spClass('spSession')->getUser()->getCurrentProject();
        if(!empty($projId)) {
            $condition=array(
                'prj' => "$projId",
            );
            return $this->findAll($condition);
        } else {
            return array();
        }
    }
    /** @brief update status of the issue 
     *
     */
    public function updateStatus($iid, $status){
        if(empty($iid) || empty($status))
            return false;
            
        return $this->update(array('id' => $iid), array('status' => $status));
    }
    /** @brief convert string to status
     *
     */
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
    /** @brief detect doe user has privalege to access the issue 
     *
     */
    public function allow($iid, $uid) {
        if(empty($iid))
            return false;

        $issue = $this->find(array('id' => $iid));
        if(empty($issue))
            return false;

        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($issue['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        if($issue['acl']==$allow_protected)
            return spClass('userorgModel')->isMemberOfProject($issue['prj'], $uid);
        else
            return spClass('userorgModel')->isMemberOfIssue($iid, $uid);
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
        return true;
    }
}
