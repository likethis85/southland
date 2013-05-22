<?php
if (!defined('SOUTHLAND')) { exit(1);}
class projectModel extends spModel
{
	var $pk = "id";					// 按ID排序
	var $table = "project"; // 数据表的名称
    var $linker = null;

    public function getProjects() {
        $uid = spClass('spSession')->getUser()->GetUserId();
        $items = array();
        $projects = spClass('userorgModel')->getProjectsByUser($uid);
        if(!empty($projects)) {
            foreach($projects as $item)
                $items[$item['id']] = $item;
        }

        $projects = $this->findAll(array('acl' => 0, 'droptime' => 0));
        if(!empty($projects)) {
            foreach($projects as $item)
                $items[$item['id']] = $item;
        }
        return $items;
    }
    /** @brief detect does current user is permit to view the project
     *
     */
    public function allow($pid, $uid) {
        if(empty($pid))
            return false;

        $proj = $this->find(array('id' => $pid));
        if(empty($proj))
            return false;

        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($proj['acl']==$allow_public)
            return true;
    
        if(empty($uid))
            return false;

        return spClass('userorgModel')->isMemberOfProject($pid, $uid);
    }
	public function getCurrentInfo() {
	    $linker = array(
            array (
                'type' => 'hasone',
                'map' => 'uid',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey' => 'id',
                'enabled' => 'true'
            )
        );
        $this->linker = $linker;
        $info = $this->spLinker()->find(array( 'id' => spClass('spSession')->getUser()->getCurrentProject()));
        if($info === false) return array();
        else return $info;
    }
    public function getProjectMembers() {
        $members = spClass('userorgModel')->getUsersByProject(spClass('spSession')->getUser()->getCurrentProject());
        return $members;
    }
    public function deleteProject($pid) {
        $this->update(array('id' => $pid), array('droptime' => null));
    }
}
