<?php
if (!defined('SOUTHLAND')) { exit(1);}

class subscriberModel extends spModel
{
    var $pk = 'id';		 // 按id排序
    var $table = 'subscriber'; // 数据表的名称
 
    var $scope_project = 1;
    var $scope_task = 2;
    var $scope_issue = 3;

    var $level_plain = 1;
    var $level_hover = 2;
    var $level_ontop = 3;

    public function scribeProject($uid,$pid,$level){
        if(empty($uid) || empty($pid))
            return false;

        $data = array(
            'uid' => $uid,
            'scope' => $scope_project,
            'sid'   => $pid,
            'deptch' => $level
        );

        return $this->create($data);
    }
}
