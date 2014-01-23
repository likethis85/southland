<?php
if (!defined('SOUTHLAND')) { exit(1);}

class main extends general
{
    var $COMPLETED = 128;
    var $VERIFIED = 64;
    var $CODECOMPLETE = 32;
    var $WORKING = 16;

	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tView = array(
		    'require' => array(
		        'editor' => true
		    )
		);
	}
	
    /** @brief 添加Issue */
	function add() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid,$uid,'AddIssue')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }
		$submit = $this->spArgs("submit");
		if($submit == 1) {
		    $files = $this->saveFile($uid, $_FILES, 'attachments for issue '.$this->spArgs('IssueBrief'));
			$iid = spClass('issueModel')->createIssue(  $pid,
			                                            $uid,
			                                            $this->spArgs('tid'),
			                                            $this->spArgs('IssuePri'),
			                                            $this->spArgs('IssueBrief'),
			                                            $this->spArgs('IssueDesc'),
			                                            $this->spArgs('acl')
			                                         );
            if(false != $iid) {
                spClass('attachmentModel')->createForIssue($uid, $pid, $iid, $files);
                spClass('userroleModel')->addIssueOwner($pid,$iid,$this->spArgs('oid'));
            }
			$this->jumpIssuePage();
		} else {
		    $this->tTitle = $this->tProject['title'].'-'.T('CreateNewIssue');
		    $tid = $this->spArgs('tid');
		    $this->tTid = $tid===null ? 0:$tid;  
		    $this->tTasks = spClass('taskModel')->getTasks($pid);
		    $this->tMembers = spClass('userroleModel')->getUsersByProject($pid);
			$this->display("issue/add.html");
		}
	}
    /** @brief 编辑Issue */
    function update() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('id');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        if(!spClass('projectModel')->allow($pid,$uid,'Update')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }
		$submit = $this->spArgs("submit");
        if($submit == 1) {
            $files = $this->saveFile($uid, $_FILES, 'attachments for issue '.$this->spArgs('IssueBrief'));
			spClass('issueModel')->updateIssue( $iid,
                                                $this->spArgs('IssuePri'),
                                                $this->spArgs('IssueBrief'),
                                                $this->spArgs('IssueDesc'),
                                                $this->spArgs('acl')
                                             );
            spClass('attachmentModel')->createForIssue($uid, $pid, $iid, $files);
            $this->navi('/issue.php?a=view&id='.$iid);
        } else {
            $issue = spClass('issueModel')->getIssueDetail($iid);
            if(empty($issue))
                $this->jumpIssuePage();
            else{
                $this->tIssue = $issue;
                $this->display("issue/update.html");
            }
        }
    }
    /** @brief 查看bug的详细信息 */
    function view() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('id');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        
        if(!spClass('projectModel')->allow($pid,$uid,'View')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }
        
        $this->tIssue = spClass('issueModel')->find(array('id' => $iid));
        $this->tTitle = $this->tProject['title'].'-'.$this->tIssue['brief'];
        $this->tComments = spClass('commentModel')->getIssueComments($iid);
        $this->tAttachments = spClass('attachmentModel')->getIssueAttachment($iid);
        $this->display('issue/view.html');
    }
    /** @brief 标记为Open */
    function open(){
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('iid');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
       
        $model = spClass('issueModel');
        if(!$model->allow($iid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $model->updateStatus($iid, $model->STATUS_WORKING);
        $this->jumpIssuePage();
    }
    /** @brief 标记为Fixed */
    function fixed(){
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('iid');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
       
        $model = spClass('issueModel');
        if(!$model->allow($iid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $model->updateStatus($iid, $model->STATUS_FIXED);
        $this->jumpIssuePage();
    }
    /** @brief 标记为Verified */
    function verified(){
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('iid');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
       
        $model = spClass('issueModel');
        if(!$model->allow($iid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $model->updateStatus($iid, $model->STATUS_VERIFIED);
        $this->jumpIssuePage();
    }
    /** @brief 推迟bug */
    function post() {
        $uid = $this->tUser['id'];
        $iid = $this->spArgs('id');
        $pid = $this->spArgs('prj');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }
        if(empty($uid) || !spClass('issueModel')->allow($iid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(!spClass('projectModel')->allow($pid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        spClass('issueModel')->post($iid, $pid);
        $this->jumpIssuePage();
    }
    /* @brief 忽略 */
    function ignore() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('iid');

        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }
        if(empty($uid) || !spClass('issueModel')->allow($iid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(!spClass('projectModel')->allow($pid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $model = spClass('issueModel');
        $model->updateStatus($iid, $model->STATUS_IGNORED);
        $this->jumpIssuePage();

    }
    function cmt() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $iid = $this->spArgs('id');
        $comment = $this->spArgs('reply');
        if(empty($iid) || empty($comment)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        spClass('commentModel')->createForIssue($uid,$pid,$iid,$comment);
        $this->navi("/issue.php?a=view&id=$iid");
    }
}
