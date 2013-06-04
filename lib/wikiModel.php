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

        spClass('keywordsModel')->createForWiki($date['prj'], $wid, $kwds);

        return $wid;
    }

    public function getWikis() {
        return $this->findAll(array('prj' => spClass('spSession')->getUser()->getCurrentProject()));
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

    public function allow($wid, $uid) {
        if(empty($wid))
            return false;

        $wiki = $this->find(array('id' => $wid));
        if(empty($wiki))
            return false;

        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($wiki['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        if($wiki['acl']==$allow_protected)
            return spClass('userorgModel')->isMemberOfProject($wiki['prj'], $uid);
        else
            return spClass('userorgModel')->isMemberOfProject($wid, $uid);
    }
}
