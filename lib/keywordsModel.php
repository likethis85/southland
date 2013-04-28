<?php
if (!defined('SOUTHLAND')) { exit(1);}

class keywordsModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "keywords"; // 数据表的名称

    var $scope_wiki = 1;

    public function createForWiki($wid, $kwd) {
        if(empty($wid) || empty($kwd))
            return false;

        $data = array(
            'prj' => spClass('spSession')->getUser()->getCurrentProject(),
            'scope' => $this->scope_wiki,
            'sid' => $wid,
            'kwd' => $kwd
        );

        return $this->Create($data);
    }
}
