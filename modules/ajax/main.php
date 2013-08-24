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
        $tid = $this->spArgs('id');
        $val = $this->spArgs('val');
        if(is_null($tid) || is_null($val)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Invalid Parameters');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        $uid = spClass('spSession')->getUser()->getUserId();
        if(!spClass('taskModel')->allow($tid, $uid)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

        if(spClass('taskModel')->updateField(array('id'=>$tid), 'priority', $val) === TRUE) {
            echo spClass('Services_JSON')->encode($this->ajaxResult);
        }
        exit;
    }
	
    /** @brief update task status
     *
     */
    function uts() { 
        $tid = $this->spArgs('id');
        $val = $this->spArgs('val');
        if(is_null($tid) || is_null($val)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        $uid = spClass('spSession')->getUser()->getUserId();
        if(!spClass('taskModel')->allow($tid, $uid)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

        $condition = array(
            'id' => $tid
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
        if(empty($uid)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

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
            echo "{'url':'" . "/mass/$uid/origin/".$uf->file_name . "','title':'" . $title . "','original':'" . $_FILES['photo']['name'] . "','state':'" . 'SUCCESS' . "'}";
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
    /** @brief 为项目添加用户
     *
     */
    function AddProjectUser() {
        $user = spClass('userModel')->getUserByUname($this->spArgs('name'));
        if(false === $user) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error no this user');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        
        $pid = $this->spArgs('pid');
        $uid = $this->tUser['id'];
        if(!spClass('projectModel')->allow($pid, $uid)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

        $uid = $user['id'];
        if(empty($pid) || empty($uid)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        spClass('userroleModel')->addProjectMember($pid,$uid);
        echo spClass('Services_JSON')->encode($this->ajaxResult);
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
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;    
        }

        if(!$obj->allow($iid, $uid)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

        $this->ajaxResult->error = $obj->updateStatus($iid, $status)===false ? $this->constAjaxErr->ERROR_FAIL:$this->constAjaxErr->ERROR_OK;
        if($this->ajaxResult->error===$this->constAjaxErr->ERROR_FAIL)
            $this->ajaxResult->msg = T('Error DB operation failed');
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

    /** @brief 删除时间线事件
     *
     */
    function dtm(){
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $tid = $this->spArgs('id');

        if($tid==0) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Invalid Parameters');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;    
        }

        if(!spClass('projectModel')->allow($pid, $uid)) {
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Operation not permit');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }

        if(spClass('timelineModel')->delete(array('id' => $tid))===false){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error DB operation failed');
        }
        echo spClass('Services_JSON')->encode($this->ajaxResult);
        exit;
    }

    /** @brief 收消息
     *
     */
    function rmsg(){
        $uid = $this->tUser['id'];
        if(empty($uid)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Invalid Parameters');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        
        $msgs = spClass('messageModel')->receiver_message($uid);
        if(false == $msgs){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error DB operation failed');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        
        
        $this->ajaxResult->msg = $msgs;
        echo spClass('Services_JSON')->encode($this->ajaxResult);
        exit;
    }
    /** @brief 获取当前用户的所有项目
     *
     */
	function GetAllProjects(){
	    $uid = $this->tUser['id'];
	    if(empty($uid)){
            $this->ajaxResult->error = $this->constAjaxErr->ERROR_FAIL;
            $this->ajaxResult->msg = T('Error Invalid Parameters');
            echo spClass('Services_JSON')->encode($this->ajaxResult);
            exit;
        }
        $names = array();
        $projects = spClass('userorgModel')->getProjectsByUser($uid);
        foreach($projects as $project){
            if($project['status']==0)
                array_push($names, array('title'=>$project['title'], 'id'=>$project['id']));
        }
        $this->ajaxResult->data = $names;
        echo spClass('Services_JSON')->encode($this->ajaxResult);
        exit;
	}
    function pdf_task() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $tid = $this->spArgs('tid');
        define('FPDF_FONTPATH',APP_PATH.'/font/');
        import('html2fpdf.php');
        $pdf=new HTML2FPDF();
        $pdf->AddPage();
        $pdf->SetFont('courier','B',8);
        $pdf->Cell(40,10,'Hello World!');
        $pdf->Output('example.pdf','D');
        exit;
    }
	function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
