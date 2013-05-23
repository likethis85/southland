<?php
if (!defined('SOUTHLAND')) { exit(1);}
class taskModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "task"; // 数据表的名称

    public function getTasks() {
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
}
