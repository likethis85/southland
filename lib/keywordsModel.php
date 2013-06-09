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
        $sql = "select id,content from {$prefix}keywords where content in (";
        foreach($kwds as $kwd){
            $sql .= "\"$kwd\",";
        }
        $sql .= '"")';
        $exists = $this->findSql($sql);
        $finds = array();
        foreach($exists as $value) {
            $finds[$value['content']] = $value['id'];
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
            $sql = "INSERT INTO {$prefix}keywords_ref(prj,scope,sid,ref) VALUES($pid,$this->scope_wiki,$wid,$value)";
            if($this->runSql($sql)){
                array_push($refs, $value);
            }
        }

        $sql = "UPDATE {$prefix}keywords set ref_count=ref_count+1 WHERE id in(";
        foreach($refs as $value){
            $sql .= "$value,";
        }
        $sql .= "0)";

        $this->runSql($sql);
    }
    public function findForProject($pid){
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "SELECT content FROM {$prefix}keywords WHERE id in (SELECT id FROM {$prefix}keywords_ref WHERE prj=$pid or prj=0)";
        return $this->findSql($sql);
    }
    public function findForWiki($wid) {
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "SELECT b.content as keyword FROM {$prefix}keywords as b,{$prefix}keywords_ref as c WHERE $wid=c.sid  AND b.id=c.ref AND c.scope=".$this->scope_wiki;
        $wiki = $this->findSql($sql);
        return $wiki;
    }
    
    public function findWikis($keyword){
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "SELECT * FROM {$prefix}wiki WHERE id IN(SELECT sid FROM {$prefix}keywords as a,{$prefix}keywords_ref as b WHERE a.id=b.ref and b.scope=".$this->scope_wiki.")";
        return $this->findSql($sql);
    }
}
