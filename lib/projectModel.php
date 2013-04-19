<?php
if (!defined('SOUTHLAND')) { exit(1);}
class projectModel extends spModel
{
	var $pk = "id";					// 按ID排序
	var $table = "project"; // 数据表的名称
    var $linker = null;
	
    public function getProjects() {
        $uid = spClass('spSession')->getUser()->GetUserId();
        $condition ="uid=$uid AND status!=255";
        foreach($this->findAll($condition) as $item)
            $items[$item['id']] = $item;
        return $items;
    }
	public function getCurrentInfo() {
	    $linker = array(
            array (
                'type' => 'hasone',
                'map' => 'uid',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey' => 'uid',
                'enabled' => 'true'
            )
        );
        $this->linker = $linker;
        $info = $this->spLinker()->find(array( 'id' => spClass('spSession')->getUser()->getCurrentProject()));
        if($info === false) return array();
        else return $info;
    }
    public function getProjectMembers() {
        $members = spClass('userorgModel')->getUsersByProject(spClass('spSession')->getUser()->getCurrentProject(), true);
        return $members;
    }
    public function deleteProject($pid) {
        $this->update(array('id' => $pid), array('status' => 255));
    }
}
