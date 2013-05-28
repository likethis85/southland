<?php

if (!defined('SOUTHLAND')) { exit(1);}

class timelineModel extends spModel
{
	var $pk = "id";			 // 按ID排序
	var $table = "timeline"; // 数据表的名称

    var $scope_project = 1;

    /** @brief create timeline event for project
     *
     */
    public function createForProject($pid, $uid, $start, $end, $brief) {
        $data = array(
            'uid' => $uid,
            'prj' => $pid,
            'scope' => $this->scope_project,
            'sid' => $pid,
            'brief' => $brief,
            'stime' => $start,
            'etime' => $end==null ? 0:$end
        );
        return $this->Create($data);
    }

    /** @brief get all timeline events for the project
     *
     */
    public function getProject($pid) {
        return $this->findAll(array('scope' => $this->scope_project, 'sid' => $pid, 'droptime' => 0),'etime');
    }
}
