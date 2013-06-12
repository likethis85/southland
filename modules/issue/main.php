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
	}
	
	function add() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(empty($uid) || !spClass('projectModel')->allow($pid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }
		$submit = $this->spArgs("submit");
		if($submit == 1) {
			$data = array(
			    'prj' => $pid,
                'tid' => $this->spArgs('tid'),
                'reporter' => $uid,
                'assigner' => $uid,
				'owner'    => $this->spArgs('oid'),
				'priority' => $this->spArgs('IssuePri'),
				'brief'    => $this->spArgs('IssueBrief'),
                'detail'   => $this->spArgs('IssueDesc'),
                'acl'      => $this->spArgs('acl')
			);
			$iid = spClass('issueModel')->create($data);
			if($iid !== false) {
			    spClass('userorgModel')->addIssueReporter($iid, spClass('spSession')->getUser()->getUserId());
			    spClass('userorgModel')->addIssueAssigner($iid, spClass('spSession')->getUser()->getUserId());
			    spClass('userorgModel')->addIssueOwner($iid, spClass('spSession')->getUser()->getUserId());
			}
			$this->jumpIssuePage();
		} else {
		    $this->tTitle = $this->tProject['title'].'-'.T('CreateNewIssue');
		    $tid = $this->spArgs('tid');
		    $this->tTid = $tid===null ? 0:$tid;  
		    $this->tTasks = spClass('taskModel')->getTasks($pid);
			$this->display("issue/add.html");
		}
	}
    function view() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $iid = $this->spArgs('id');
        if(empty($iid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        
        if(!spClass('projectModel')->allow($pid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }
        
        $this->tIssue = spClass('issueModel')->find(array('id' => $iid));
        $this->tTitle = $this->tProject['title'].'-'.$this->tIssue['brief'];
        $this->tComments = spClass('commentModel')->getIssueComments($iid);
        $this->display('issue/view.html');
    }
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

        if($this->spArgs('submit')==1) {
            $sess = spClass('spSession');
            $data = array(
                'uid' => $uid,
                'prj' => $pid,
                'owner' => 'issue',
                'rid' => $iid,
                'content' => $comment
            );
            spClass('commentModel')->create($data);
        }
        $this->navi("/issue.php?a=view&id=$iid");
    }
}
