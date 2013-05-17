<?php

if (!defined('SOUTHLAND')) { exit(1);}

class timelineModel extends spModel
{
	var $pk = "id";			 // 按ID排序
	var $table = "timeline"; // 数据表的名称

    var $scope_project = 1;

    public function createForProject($pid, $uid, $timestamp, $brief) {
        $data = array(
            'uid' => $uid,
            'prj' => $pid,
            'scope' => $this->scope_project,
            'sid' => $pid,
            'brief' => $brief,
            'etime' => $timestamp
        );

        return $this->Create($data);
    }

    public function getProject($pid) {
        return $this->findAll(array('scope' => $this->scope_project, 'sid' => $pid, 'droptime' => 0), 'etime');
    }
}
