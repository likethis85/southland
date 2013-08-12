<?php
if (!defined('SOUTHLAND')) { exit(1);}

class subscriberModel extends spModel
{
    var $pk = 'uid';		 // 按id排序
    var $table = 'subscriber'; // 数据表的名称
 
    var $utype_site_user = 1;
    var $utype_group_user = 2;
    
    var $scope_project = 1;
    var $scope_task = 2;
    var $scope_issue = 3;

    var $level_plain = 1;
    var $level_hover = 2;
    var $level_ontop = 3;
    
    var $write_level_public = 1;
    var $write_level_protected = 2;
    var $write_level_private = 3;
    
    public function scribeProject($uid,$pid,$level,$write,$g){
        if(empty($uid) || empty($pid))
            return false;
        $data = array(
            'uid' => $uid,
            'utype' => $g===true ? $this->utype_group_user:$this->utype_site_user,
            'scope' => $scope_project,
            'sid'   => $pid,
            'depth' => $level,
            'writable' => $write
        );

        return $this->create($data);
    }
}
