<?php
if (!defined('SOUTHLAND')) { exit(1);}
class forumModel extends spModel
{
    var $pk = "id";				// 按id排序
    var $table = "forum"; // 数据表的名称
    
    public function allow($tid, $uid, $operation) {
        if(empty($tid) || !is_numeric($tid))
            return false;
            
        $topic = $this->find(array('id' => $tid));
        if(empty($topic)) return false;
        
        if(empty($operation))$operation='Default';
        $op = "allow{$operation}";
        if(!method_exists($this,$op))
            $op = 'allowDefault';

        return $this->{$op}($topic,$uid);
    }    
    private function allowDefault($topic,$uid) {
        return spClass('userroleModel')->isMemberOfProject($topic['prj'], $uid);
    }   
    private function allowView($topic,$uid){
        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($topic['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;
 
        if($topic['acl']==$allow_protected)
            return spClass('userroleModel')->isMemberOfProject($topic['prj'], $uid);
        else
            return spClass('userroleModel')->isMemberOfTopic($topic['id'], $uid);
    }
    /** @brief 添加Topic */
    public function addTopic($pid,$uid,$subject,$content,$acl){
        $tid = $this->create(array(
			                'prj' => $pid,
            			    'author' => $uid,
            				'subject'=> $subject,
            				'content'=> $content,
                            'acl'    => $acl)
            			   );
        if($tid === false)
            return false;
            
        if(false == spClass('userroleModel')->addTopicCreator($pid,$tid,$uid))
            return false;
            
        return $tid;
    }
    /** @brief 删除Topic */
    public function drop($tid) {
        $droptime=date('Y-m-d H:i:s');
        return $this->update(array('id' => $tid), array('droptime' => $droptime));
    }
}
