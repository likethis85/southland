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
}
