<?php
if (!defined('SOUTHLAND')) { exit(1);}
class issueModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "issue"; // 数据表的名称

    var $linker = array(
        array (
            'type' => 'hasone',
            'map' => 'owner',
            'mapkey' => 'owner',
            'fclass' => 'userModel',
            'fkey' => 'uid',
            'enabled' => 'true'
        )
    );
    /** @brief retrieve issues belongs to current project
     *
     */
    public function getIssues() {
        $projId = spClass('spSession')->getUser()->getCurrentProject();
        if(!empty($projId)) {
            $condition=array(
                'prj' => "$projId",
            );
            return $this->findAll($condition);
        } else {
            return array();
        }
    }
    /** @brief update status of the issue 
     *
     */
    public function updateStatus($iid, $status){
        if(empty($iid) || empty($status))
            return false;
            
        return $this->update(array('id' => $iid), array('status' => $status));
    }
    /** @brief convert string to status
     *
     */
    public function str2status($str){
        $status = array(
            'pending'   => 0,
            'working'   => 1,
            'fixed'     => 2,
            'verified'  => 3,
            'closed'    => 4
        );
        
        return $status[$str];
    }
}
