<?php
if (!defined('SOUTHLAND')) { exit(1);}
class projectModel extends spModel
{
	var $pk = "id";					// 按ID排序
	var $table = "project"; // 数据表的名称
	var $linker = array(
        array (
            'type' => 'hasone',
            'map' => 'uid',
            'mapkey' => 'uid',
            'fclass' => 'userModel',
            'fkey' => 'uid',
            'enabled' => 'true'
        )
    );
    public function getProjects() {
        foreach($this->findAll('status != 255') as $item)
            $items[$item['id']] = $item;
        return $items;
    }
	public function getCurrentInfo(){
        $info = $this->spLinker()->find(array( 'id' => spClass('spSession')->getUser()->getCurrentProject()));
        if($info === false) return array();
        else return $info;
    }
    public function deleteProject($pid) {
        $this->update(array('id' => $pid), array('status' => 255));
    }
}
