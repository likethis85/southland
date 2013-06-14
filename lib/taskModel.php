<?php
if (!defined('SOUTHLAND')) { exit(1);}
class taskModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "task"; // 数据表的名称
    
    var $STATUS_PENDING = 0;
    var $STATUS_WORKING = 1;
    var $STATUS_CODECOMPLETE = 2;
    var $STATUS_VERIFIED = 3;
    var $STATUS_COMPLETED = 4;
        
    public function getTasks($pid) {
        if(empty($pid))
            return array();
        
        $tasks = $this->findAll(array('prj' => $pid));
        if(false==$tasks)
            return array();
            
        return $tasks;
    }

    public function allow($tid, $uid) {
        if(empty($tid))
            return false;

        $task = $this->find(array('id' => $tid));
        if(empty($task))
            return false;

        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($task['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        if($task['acl']==$allow_protected)
            return spClass('userorgModel')->isMemberOfProject($task['prj'], $uid);
        else
            return spClass('userorgModel')->isMemberOfTask($tid, $uid);
    }

    public function drop($tid) {
        if(empty($tid))
            return true;

        $droptime=date('Y-m-d H:i:s');
        spClass('timelineModel')->dropTask($tid, $droptime);
        spClass('commentModel')->dropTask($tid, $droptime);
        return $this->update(array('id'=>$tid), array('droptime'=>$droptime));
    }
}
