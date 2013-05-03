<?php
if (!defined('SOUTHLAND')) { exit(1);}
class commentModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "comment"; // 数据表的名称

    public function getTaskComments($tid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'uid',
                'enabled' => 'true'
            )
        );

        if(empty($tid))
            return array();
        $condition = array(
            'owner' => 'task',
            'rid' => $tid
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }

    public function getIssueComments($iid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'uid',
                'enabled' => 'true'
            )
        );

        if(empty($tid))
            return array();
        $condition = array(
            'owner' => 'issue',
            'rid' => $iid
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }

    public function getForumComments($tid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'uid',
                'enabled' => 'true'
            )
        );

        if(empty($tid))
            return array();
        $condition = array(
            'owner' => 'forum',
            'rid' => $tid,
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }
}
