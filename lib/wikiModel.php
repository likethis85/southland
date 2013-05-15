<?php
if (!defined('SOUTHLAND')) { exit(1);}

class wikiModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "wiki"; // 数据表的名称

    public function createWiki($subject, $content, $kwds) {
        if(empty($subject) || empty($content))
            return false;

        $data = array(
            'uid' => spClass('spSession')->getUser()->getUserId(),
            'prj' => spClass('spSession')->getUser()->getCurrentProject(),
            'subject' => $subject,
            'content' => $content
        );

        $wid = $this->Create($data);
        if(false === $wid)
            return false;

        foreach($kwds as $kwd){
            spClass('keywordsModel')->CreateForWiki($wid, $kwd);
        }

        return $wid;
    }

    public function getWikis() {
        return $this->findAll(array('prj' => spClass('spSession')->getUser()->getCurrentProject()));
    }
    
    public function getWikiDetail($wid) {
        $wiki = $this->find(array('id' => $wid));
        $kwds = spClass('keywordsModel')->findForWiki($wid);
        if(empty($kwds))
            $kwds = array();
        return array('wiki' => $wiki, 'keywords' => $kwds);
    }

    public function allow($wid, $uid) {
        if(empty($wid))
            return false;

        $wiki = $this->find(array('id' => $wid));
        if(empty($task))
            return false;

        $allow_public = 0;
        $allow_protected = 1;
        $allow_private = 2;
        if($task['acl']==$allow_public)
            return true;

        if(empty($uid))
            return false;

        return spClass('userorgModel')->isMemberOfProject($tid, $uid);
    }
}
