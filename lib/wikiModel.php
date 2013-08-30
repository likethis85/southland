<?php
if (!defined('SOUTHLAND')) { exit(1);}

class wikiModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "wiki"; // 数据表的名称

    public function createWiki($data, $kwds) {
        if(empty($data))
            return false;

        $wid = $this->Create($data);
        if(false === $wid)
            return false;

        $uid = $data['uid'];
        $pid = $data['prj'];
        spClass('keywordsModel')->createForWiki($data['prj'], $wid, $kwds);
        spClass('userroleModel')->addWikiCreator($pid, $wid, $uid);

        return $wid;
    }
    
    public function getWikiDetail($wid) {
        $wiki = $this->find(array('id' => $wid, 'droptime' => 0));
        if(empty($wiki))
            return array();

        $keywords = spClass('keywordsModel')->findForWiki($wid);
        if(false == $wiki) 
            return array('wiki' => $wiki, 'keywords' => array());

        $tW = array('wiki' => $wiki, 'keywords' => array());
        foreach($keywords as $keyword){
            array_push($tW['keywords'], $keyword['keyword']);
        }
        array_unique($tW['keywords']);
        return $tW;
    }

    /** @brief acl控制函数 */
    public function allow($wid,$uid,$operation) {
        if(empty($wid) || !is_numeric($wid))
            return false;
            
        $wiki = $this->find(array('id' => $wid));
        if(empty($wiki))
            return false;
            
        if(empty($operation)) $operation='Default';
        $op="allow{$operation}";
        if(!method_exists($this,$op))
            $op = 'allowDefault';
            
        return $this->{$op}($wiki,$uid);
    }
    private function allowDefault($wiki,$uid) {
        return $this->isMemberOfProject($wiki['prj'],$uid);
    }
    private function allowView($wiki, $uid) {
        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($wiki['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        return spClass('userroleModel')->isMemberOfProject($wiki['prj'], $uid);
    }
}
