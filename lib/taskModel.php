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
        
    /** @brief 创建Task */
    public function createTask($pid,$uid,$prio,$subject,$detail,$acl) {
        $tid = $this->create(array(
                			    'pid' => 0,
                			    'prj' => $pid,
                                'assigner' => $uid,
                				'owner'=> $uid,
                				'priority'=> $prio,
                				'subject'=> $subject,
                                'detail'=> $detail,
                                'acl'   => $acl)
			                );
        if(false === $tid)
            return false;
        if(spClass('userroleModel')->addTaskCreator($pid,$tid,$uid)===false)
            return false;
        return $tid;
    }
    public function getTasks($pid) {
        if(empty($pid))
            return array();
        
        $tasks = $this->findAll(array('prj' => $pid, 'droptime'=>0));
        if(false==$tasks)
            return array();
            
        return $tasks;
    }
    /** @brief 基于Task层面的ACL 
     *
     *  @Detail
     *      对于基于Task的状态修改，如：删除，修改状态，添加用户等
     *  由该函数控制
     */
    public function allow($tid, $uid) {
        if(empty($tid) || !is_numeric($tid))
            return false;
            
        $task = $this->find(array('id' => $tid));
        if(empty($task)) return false;
        
        if(empty($operaton)) $operation = 'Default';
        $op = "allow{$operation}";
        if(!method_exists($this,$op)) return $op='allowDefault';

        return $this->{$op}($task,$uid);
    }
    private function allowDefault($task,$uid){
        return spClass('userroleModel')->isMemberOfProject($task['prj'], $uid);
    }
    private function allowView($task, $uid) {
        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($task['acl']==$allow_public)
            return true;

        if(empty($uid)) return false;

        return spClass('userroleModel')->isMemberOfProject($task['prj'], $uid);
    }

    public function drop($tid) {
        $droptime=date('Y-m-d H:i:s');
        //spClass('timelineModel')->dropTask($tid, $droptime);
        //spClass('commentModel')->dropTask($tid, $droptime);
        return $this->update(array('id'=>$tid), array('droptime'=>$droptime));
    }

    public function post($tid, $pid) {
        if(false == $this->update(array('id' => $tid), array('prj' => $pid)))
            return false;

        spClass('timelineModel')->postTask($tid, $pid);
        spClass('commentModel')->postTask($tid, $pid);
        return true;
    }
}
