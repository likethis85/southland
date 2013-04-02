<?php
if (!defined('SOUTHLAND')) { exit(1);}
class forumModel extends spModel
{
		var $pk = "id";				// 按id排序
		var $table = "forum"; // 数据表的名称
		
		public function getTopics() {
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
?>
