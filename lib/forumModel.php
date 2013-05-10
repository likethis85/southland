<?php
if (!defined('SOUTHLAND')) { exit(1);}
class forumModel extends spModel
{
    var $pk = "id";				// 按id排序
    var $table = "forum"; // 数据表的名称
    
    public function getTopics() {
        $projId = spClass('spSession')->getUser()->getCurrentProject();
        $topics = $this->findAll(array('prj' => $projId));
        if(false == $topics) $topics = array();
        return $topics;
    }

    public function allow($tid, $uid) {
        if(empty($tid))
            return false;
            
        $topic = $this->find(array('id' => $tid));
        if(empty($task))
            return false;
            
        $allow_public = 1;
        $allow_protected = 2;
        $allow_private = 3;
        if($task['protection']==$this->allow_public)
            return true;

        if(empty($uid))
            return false;
        
        return spClass('userorgModel')->isMemberOfTopic($tid, $uid);
    }
}
