<?php

if (!defined('SOUTHLAND')) { exit(1);}

class timelineModel extends spModel
{
	var $pk = "id";			 // 按ID排序
	var $table = "timeline"; // 数据表的名称

    var $scope_project = 1;
    var $scope_task = 2;

    public function updateTimeline($id,$start,$end,$brief,$content){
        return $this->Update(array('id'=> $id),
                             array( 'brief' => $brief,
                                    'content'=>$content,
                                    'stime' => $start,
                                    'etime' => $end==null ? 0:$end));
    }
    /** @brief create timeline event for project
     *
     */
    public function createForProject($pid, $uid, $start, $end, $brief,$content) {
        $data = array(
            'uid' => $uid,
            'prj' => $pid,
            'scope' => $this->scope_project,
            'sid' => $pid,
            'brief' => $brief,
            'content'=>$content,
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
    public function dropTask($tid, $tm) {
        if(empty($tid))
            return true;

        if(empty($tm))
            $tm = date('Y-m-d H:i:s');

        return $this->update(array('scope'=>$this->scope_task, 'sid'=>$tid), array('droptime' => $tm));
    }
    /** @brief 把任务转移到新的项目
     *
     */
    public function postTask($tid, $newPid){
        if(empty($newPid) || empty($tid))
            return false;

        return $this->update(array('scope'=>$this->scope_task, 'sid'=>$tid),array('prj'=>$newPid));
    }
    /** @brief get all timeline events for the project
     *
     */
    public function getProject($pid,$uid) {
        $events = $this->findAll(array('prj' => $pid, 'droptime'=>0));
        foreach($events as $event){
            if(!isset($scopes[$event['scope']]))
                $scopes[$event['scope']] = array();
           array_push($scopes[$event['scope']],$event['sid']);
        }
        $timelines = array();
        foreach($scopes as $key => $scope) {
            $items = array_intersect(spClass('userroleModel')->getItemsBy($key,$scope,$uid),$scope);
            foreach($events as $event){
                if($event['scope']==$key && in_array($event['sid'],$items))
                    array_push($timelines,$event);
            }
        }
        return $timelines;
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
