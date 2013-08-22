<?php
if (!defined('SOUTHLAND')) { exit(1);}
class projectModel extends spModel
{
	var $pk = "id";			// 按ID排序
	var $table = "project"; // 数据表的名称
    var $linker = null;

    var $status_open = 0;
    var $status_close = 1;

    /** @brief 创建项目 */
    public function createProject($uid,$title,$desc,$acl){
        if(empty($uid) || empty($title) || empty($acl))
            return false;
            
        $data = array(
            'uid' => $uid,
            'title' => $title,
            'description' => $desc,
            'acl' => $acl
        );
        $pid = $this->create($data);
        if($pid===false)
            return false;
            
        if(false === spClass('userroleModel')->addProjectCreator($pid,$uid))
            return false;
           
        return $pid;
    }
    
    public function getUserProjects($uid) {
        $items = array();
        $projects = spClass('userroleModel')->getProjectsByUser($uid);
        $items = array_values($projects);
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

        return spClass('userroleModel')->isMemberOfProject($pid, $uid);
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
    public function getProjectMembers($pid) {
        $members = spClass('userroleModel')->getUsersByProject($pid);
        return $members;
    }
    public function closeProject($pid) {
        if(false == $this->update(array('id' => $pid), array('status' => $this->status_close)))
            return false;

        return true;
    }
    public function deleteProject($pid) {
        $this->update(array('id' => $pid), array('droptime' => null));
    }
}
