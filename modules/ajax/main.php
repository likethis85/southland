<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}

    function utp() { // update_task_priority
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
	
    function uts() { // update_task_priority
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

    /** @brief Add user to the project 
     *
     */
    function pau() {
        $obj = spClass('userorgModel');
        $sid = $this->spArgs('pid');
        $uid = spClass('userModel')->getUserByEmail($this->spArgs('u'));
        if(false === $uid)
            return 'No user found';
        $uid = $uid['uid'];
        if(empty($sid)) return;
        if(empty($uid)) return;
        echo "$uid";exit;
        $role = $this->spArgs('to');
        if($role=='Developer') {
            $role = $obj->role_dev_member;
            $obj->AddDevMember($sid, $uid);
        }
        else if($role == 'QA') {
            $role = $obj->role_qa_member;
            $obj->AddQAMember($sid, $uid);
        }
        else if($role == 'Observer') {
            $role = $obj->role_member;
            $obj->AddProjectMember($sid, $uid);
        }
        else
            return;
    }

	function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
