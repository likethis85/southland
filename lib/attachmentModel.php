<?php
if (!defined('SOUTHLAND')) { exit(1);}
class attachmentModel extends spModel {
    var $pk = 'id';		 // 按id排序
    var $table = 'attachment'; // 数据表的名称
    
    var $scope_project = 1;
    var $scope_task = 2;
    var $scope_issue = 3;
    
    /** @brief 添加任务的附件信息
     *
     */
    public function createForTask($uid, $pid, $tid, $files){
        if(empty($files))
            return false;
            
        foreach($files as $file){
            $this->create(array(
                'uid' => $uid,
                'prj' => $pid,
                'scope' => $this->scope_task,
                'sid' => $tid,
                'oname' => $file['origin'],
                'path' => $file['save']));
        }
        
        return true;
    }
    /** @brief 获取任务相关的附件信息
     *
     */
    public function getTaskAttachment($tid){
        return $this->findAll(array('scope'=>$this->scope_task, 'sid'=>$tid));
    }
    /** @brief 添加问题的附件信息
     *
     */
    public function createForIssue($uid, $pid, $iid, $files) {
        if(empty($files))
            return false;
            
        foreach($files as $file){
            $this->create(array(
                'uid' => $uid,
                'prj' => $pid,
                'scope' => $this->scope_issue,
                'sid' => $iid,
                'oname' => $file['origin'],
                'path' => $file['save']));
        }
        return true;
    }
    /** @brief 获取问题的附件信息
     *
     */
    public function getIssueAttachment($iid){
        return $this->findAll(array('scope'=>$this->scope_issue, 'sid' => $iid));    
    }
}
