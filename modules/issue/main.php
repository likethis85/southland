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
		$submit = $this->spArgs("submit");
		if($submit == 1) {
			$data = array(
			    'prj' => spClass('spSession')->getUser()->getCurrentProject(),
                'tid' => $this->spArgs('tid'),
                'reporter' => spClass('spSession')->getUser()->getUserId(),
                'assigner' => spClass('spSession')->getUser()->getUserId(),
				'owner'    => $this->spArgs('oid'),
				'priority' => $this->spArgs('IssuePri'),
				'brief'    => $this->spArgs('IssueBrief'),
                'detail'   => $this->spArgs('IssueDesc'),
                'category' => $this->spArgs('IssueCat')
			);
			$iid = spClass('issueModel')->create($data);
			if($iid !== false) {
			    spClass('userorgModel')->addIssueReporter($iid, spClass('spSession')->getUser()->getUserId());
			    spClass('userorgModel')->addIssueAssigner($iid, spClass('spSession')->getUser()->getUserId());
			    spClass('userorgModel')->addIssueOwner($iid, spClass('spSession')->getUser()->getUserId());
			}
			$this->jumpIssuePage();
		} else {
		    $tid = $this->spArgs('tid');
		    $this->tTid = $tid===null ? 0:$tid;  
		    $this->tTasks = spClass('taskModel')->getTasks();
			$this->display("issue/add.html");
		}
	}
    function view() {
        $iid = $this->spArgs('id');
        if(empty($iid))
            $this->jumpIssuePage();
        else {
            $condition = array(
                'id' => $iid
            );
            $this->tIssue = spClass('issueModel')->find($condition);
            $this->tComments = spClass('commentModel')->getIssueComments($iid);
            $this->display('issue/view.html');
        }
        
    }

    function cmt() {
        $id = $this->spArgs('id');
        $comment = $this->spArgs('reply');
        if(empty($id) || empty($comment)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }

        if($this->spArgs('submit')==1) {
            $sess = spClass('spSession');
            $data = array(
                'uid' => $sess->getUser()->getUserId(),
                'prj' => $sess->getUser()->getCurrentProject(),
                'owner' => 'issue',
                'rid' => $id,
                'content' => $comment
            );
            spClass('commentModel')->create($data);
        }
        $this->navi("/issue.php?a=view&id=$id");
    }
}
