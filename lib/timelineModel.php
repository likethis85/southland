<?php

if (!defined('SOUTHLAND')) { exit(1);}

class timelineModel extends spModel
{
	var $pk = "id";			 // 按ID排序
	var $table = "timeline"; // 数据表的名称

    var $scope_project = 1;
    var $scope_task = 2;

    public function updateTimeline($id,$start,$end,$brief){
        return $this->Update(array('id'=> $id),
                             array( 'brief' => $brief,
                                    'stime' => $start,
                                    'etime' => $end==null ? 0:$end));
    }
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

    /** @brief create timeline event for task
     *
     */
    public function createForTask($pid,$tid,$uid,$start,$end,$brief) {
        if(empty($uid) || empty($tid))
            return;

        $data = array(
                'uid' => $uid,
                'prj' => $pid,
                'scope' => $this->scope_task,
                'sid' => $tid,
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
        return $this->findAll(array('prj' => $pid));
    }

    /** @brief 把scope值从数值转化为字符串
     *
     */
    public function scope2string($scope){
        switch($scope){
        case $this->scope_task:
            return 'task';
        case $this->scope_project:
        default:
            return 'project';
        }
    }
}
