<?php
if (!defined('SOUTHLAND')) { exit(1);}
class commentModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "comment"; // 数据表的名称

    var $scope_task  = 1;
    var $scope_issue = 2;
    var $scope_forum = 3;

    /** @brief 任务相关的注释
     *
     */
    public function createForTask($uid, $pid, $tid, $content){
        if(empty($tid) || empty($pid) || empty($content))
            return false;

        return $this->create(array(
                        'uid' => $uid,
                        'prj' => $pid,
                        'scope' => $this->scope_task,
                        'sid' => $tid,
                        'content' => $content
                        ));
    }
    /** @brief 获取任务相关的条目
     *
     */
    public function getTaskComments($tid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'id',
                'enabled' => 'true'
            )
        );

        if(empty($tid))
            return array();
        $condition = array(
            'scope' => $this->scope_task,
            'sid' => $tid
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }
    /** @brief 删除任务相关的条目
     *
     */
    public function dropTask($tid, $dt){
        if(empty($tid))
            return true;

        if(empty($dt))
            $dt = date('Y-m-d H:i:s');

        return $this->update(array('scope'=>$this->scope_task, 'sid'=>$tid), array('droptime'=>$dt));
    }
    /** @brief 转移任务相关的注释到新的项目
     *
     */
    public function postTask($tid, $pid) {
        if(empty($tid) || empty($pid))
            return false;

        return $this->update(array('sid'=>$tid,'scope' => $this->scope_task), array('prj' => $pid));
    }
    /** @brief 问题相关的注释
     *
     */
    public function createForIssue($uid, $pid, $iid, $content){
        if(empty($tid) || empty($pid) || empty($content))
            return false;

        return $this->create(array(
                        'uid' => $uid,
                        'prj' => $pid,
                        'scope' => $this->scope_issue,
                        'sid' => $iid,
                        'content' => $content
                        ));
    }
    /** @brief 转移Bug相关的注释到新的项目
     *
     */
    public function postIssue($iid, $pid) {
        if(empty($iid) || empty($pid))
            return false;

        return $this->update(array('sid'=>$iid,'scope' => $this->scope_issue), array('prj' => $pid));
    }
    public function getIssueComments($iid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'id',
                'enabled' => 'true'
            )
        );

        if(empty($iid))
            return array();
        $condition = array(
            'scope' => $this->scope_issue,
            'sid' => $iid
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }
    /** @brief 话题相关的注释
     *
     */
    public function createForForum($uid, $pid, $fid, $content){
        if(empty($tid) || empty($pid) || empty($content))
            return false;

        return $this->create(array(
                        'uid' => $uid,
                        'prj' => $pid,
                        'scope' => $this->scope_forum,
                        'sid' => $tid,
                        'content' => $content
                        ));
    }
    public function getForumComments($tid) {

        $this->linker = array(
            array(
                'type' => 'hasone',
                'map'  => 'user',
                'mapkey' => 'uid',
                'fclass' => 'userModel',
                'fkey'   => 'id',
                'enabled' => 'true'
            )
        );

        if(empty($tid))
            return array();
        $condition = array(
            'scope' => $this->scope_forum,
            'sid' => $tid,
        );
        $fields = array(
            'content',
            'addtime'
        );
        return $this->spLinker()->findAll($condition);
    }
}
