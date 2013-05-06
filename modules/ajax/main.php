<?php
if (!defined('SOUTHLAND')) { exit(1);}

class AjaxErr {
    public $ERROR_FAIL = -1;
    public $ERROR_OK = 0;
}
class spAjaxMsg {
    var $error = null;
    var $msg = null;
    var $data = null;
}
class main extends general
{
    var $constAjaxErr;
    var $ajaxResult;

	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->constAjaxErr = new AjaxErr();
		$this->ajaxResult = new spAjaxMsg();
		$this->ajaxResult->error = $this->constAjaxErr->ERROR_OK;
	}

    /** @brief update task priority
     *
     */
    function utp() {
        $id = $this->spArgs('id');
        $val = $this->spArgs('val');
        if(is_null($id)) exit;
        if(is_null($val)) exit;
        $condition = array(
            'id' => $id
        );
        if(spClass('taskModel')->updateField($condition, 'priority', $val) === TRUE) {
            $result = spClass('Services_JSON')->encode('success');
            echo ($result);
        }
        exit;
    }
	
    /** @brief update task status
     *
     */
    function uts() { 
        $id = $this->spArgs('id');
        $val = $this->spArgs('val');
        if(is_null($id)) exit;
        if(is_null($val)) exit;
        $condition = array(
            'id' => $id
        );
        if(spClass('taskModel')->updateField($condition, 'status', $val) === TRUE) {
            $result = spClass('Services_JSON')->encode('success');
            echo $result;
        }
        exit;
    }

    /** @brief upload image 
     *
     **/
    function pushimg() {
        $uid = spClass('spSession')->getUser()->getUserId();
        $dir = APP_PATH."/mass/$uid";
        __mkdirs($dir, 0777);
        __mkdirs("$dir/origin", 0777);
        __mkdirs("$dir/thumbnail", 0777);
        __mkdirs("$dir/avatar", 0777);
        $uf = spClass('spUpload', array('save_path' => "$dir/origin"));
        if(TRUE === $uf->upload_file($_FILES['photo'])) {
            $recorder = array(
                'uid' => $uid,
                'bytes' => $_FILES['photo']['size'],
                'reason' => "upload file ".$_FILES['photo']['name'].' rename '.$uf->file_name
            );
            spClass('occupyModel')->create($recorder);
            echo "/mass/$uid/origin/".$uf->file_name;
        }
        else {

        }
        exit;
    }

    /** @brief Get current project's members
     *
     */
    function gpm() {
        $members = spClass('projectModel')->getProjectMembers();
        if(false == $members) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error DB operation failed'); 
        } else {
            $this->ajaxResult->data = $members;
        }
        
        echo spClass('Services_JSON')->encode($this->ajaxResult);
        exit;
    }
    /** @brief Add user to the project 
     *
     */
    function pau() {
        $obj = spClass('userorgModel');
        $sid = $this->spArgs('pid');
        $user = spClass('userModel')->getUserByEmail($this->spArgs('u'));
        if(false === $user) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error no this user');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        
        $uid = $user['uid'];
        if(empty($sid)) return;
        if(empty($uid)) return;
        $role = $this->spArgs('to');
        if($role=='Developer') {
            if(false === $obj->AddDevMember($sid, $uid)) {
                $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
                $this->ajaxResult->msg = T('Error DB operation failed');
                echo spClass('Services_JSON')->encode($this->ajaxResult);
            } else {
                $this->gpm();
            }
        }
        else if($role == 'QA') {
            if(false == $obj->AddQAMember($sid, $uid))
                echo 'error';
            else
                $this->gpm();
        }
        else if($role == 'Observer') {
            if(false === $obj->AddProjectMember($sid, $uid))
                echo 'error';
            else
                $this->gpm();
        }
        
        exit;
    }

    /** @brief update issue status
     *
     */
    function uis() {
        $obj = spClass('issueModel');
        $iid = $this->spArgs('id');
        $status = $obj->str2status($this->spArgs('to'));
        if(empty($iid) || empty($status)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Invalid Parameters');
        } else {
            $this->ajaxResult->error = $obj->updateStatus($iid, $status)===false ? $this->constAjaxErr->ERROR_FAIL:$this->constAjaxErr->ERROR_OK;
            if($this->ajaxResult->error===$this->constAjaxErr->ERROR_FAIL)
                $this->ajaxResult->msg = T('Error DB operation failed');
        }
        echo spClass('Services_JSON')->encode($this->ajaxResult);
        exit;    
    }

    /** @brief generate certification code
     *
     */
    function gcc() {
        spClass('spVerifyCode')->display();
        exit;
    }

	function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
