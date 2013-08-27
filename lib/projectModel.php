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
        if(empty($uid) || empty($title))
            return false;
            
        $pid = $this->create(array(
                                'uid' => $uid,
                                'title' => $title,
                                'description' => $desc,
                                'acl' => $acl
                            )
                      );
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
    /** @brief 用户在项目层面的访问权限控制 
     *
     *  @Detail
     *      用户项目层面上的访问控制，如Task，Issue等页面是否允许浏览，
     *  是否允许添加Task，Issue等对项目本身的修改
     *  
     *  @Parameters
     *      @param  pid 项目id
     *      @param  uid 用户id
     *      @operation 操作类型 Default(默认),View(浏览),AddTask(添加Task),etc
     */
    public function allow($pid,$uid, $operation){
        if(empty($pid) || !is_numeric($pid))
            return false;
            
        $proj = $this->find(array('id' => $pid));
        if(empty($proj)) return false;
            
        $op = "allow{$operation}";
        if(!method_exists($this,$op))
            $op = "allowDefault";

        return $this->{$op}($proj,$uid);
    }
    private function allowDefault($proj,$uid){
        return spClass('userroleModel')->isMemberOfProject($proj['id'],$uid);
    }
    public function allowView($proj, $uid) {
        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($proj['acl']==$allow_public)
            return true;
    
        if(empty($uid))
            return false;

        return spClass('userroleModel')->isMemberOfProject($proj['id'], $uid);
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
    public function closeProject($pid) {
        if(false == $this->update(array('id' => $pid), array('status' => $this->status_close)))
            return false;

        return true;
    }
    public function deleteProject($pid) {
        $this->update(array('id' => $pid), array('droptime' => null));
    }
}
