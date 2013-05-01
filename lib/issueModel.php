<?php
if (!defined('SOUTHLAND')) { exit(1);}
class issueModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "issue"; // 数据表的名称

    var $linker = array(
        array (
            'type' => 'hasone',
            'map' => 'owner',
            'mapkey' => 'owner',
            'fclass' => 'userModel',
            'fkey' => 'uid',
            'enabled' => 'true'
        )
    );
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
}
