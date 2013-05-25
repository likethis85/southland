<?php
if (!defined('SOUTHLAND')) { exit(1);}

class keywordsModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "keywords"; // 数据表的名称

    var $scope_wiki = 1;

    public function createForWiki($pid, $wid, $kwds) {
        if(empty($wid) || empty($kwds))
            return false;

        if($pid==null) $pid = 0;

        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select id,content from $prefix"."keywords where content in (";
        foreach($kwds as $kwd){
            $sql .= "\"$kwd\",";
        }
        $sql .= '"")';
        $exists = $this->findSql($sql);
        $finds = array();
        foreach($exists as $value) {
            list($k,$v) = each($value);
            $finds["$k"] = $v;
        }
        foreach($kwds as $value) {
            if(empty($finds["$value"]))
                $finds["$value"] = 0;
        }
        foreach($finds as $key=>$value){
            if(empty($key))
                continue;

            if(empty($value)){
                $finds["$key"] = $this->Create(array('content' => "$key"));
            }
        }

        $this->table = 'keywords_ref';
        $refs = array();
        foreach($finds as $value){
            if(empty($value))
                continue;

            $data = array(
                'prj' => $pid,
                'scope' => $this->scope_wiki,
                'sid' => $wid,
                'ref' => $value
            );
            $sql = "INSERT INTO $prefix"."keywords_ref(prj,scope,sid,ref) VALUES($pid,$this->scope_wiki,$wid,$value)";
            if($this->runSql($sql)){
                array_push($refs, $value);
            }
        }
        $this->table = 'keywords';

        foreach($refs as $value){
            $this->update(array('id' => $value), array('ref_count' => 'ref_count+1'));
        }
    }
    
    public function findForWiki($wid) {
        return false;
    }
}
