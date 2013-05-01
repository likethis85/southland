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
    function update() {
        $submit = $this->spArgs('submitedit');
        if($submit == 1) {
            $condition = array(
                'id' => $this->spArgs('id')
            );
            $data = array(
                'subject' => $this->spArgs('subject'),
				'detail'=> $this->spArgs('TaskDesc')
            );
            spClass('taskModel')->update($condition, $data);
			$this->jumpTaskPage();
        } else {
            $condition = array(
                'id' => $this->spArgs('id')
            );
            $this->tTask = spClass('taskModel')->find($condition);
            $this->display('task/update.html');
        }
    }
    function get_status($tid){
        if(empty($tid))
            return false;

        $task = spClass('taskModel')->find(array('id'=>$tid));
        if(empty($task))
            return false;
        return $task['status'];
    }
    function update_status($status){
        $tid = $this->spArgs('id');
        if(!empty($tid)) {
            $condition = array(
                'id' => $tid
            );
            $task = spClass('taskModel')->find($condition);
            if(!empty($task)) {
                $newStatus = $task['status'];
                $data = array(
                    'status' => $newStatus|$status
                );
                spClass('taskModel')->update($condition, $data);
            }
        }
        $this->jumpTaskPage();
    }
    function complete() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status|$this->COMPLETED);
    }
    function incomplete() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status&~$this->COMPLETED);
    }
    function veri(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status|$this->VERIFIED);
    }
    function unveri() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status&~$this->VERIFIED);
    }
    function cc(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status|$this->CODECOMPLETED);
    }
    function uncc() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status&~$this->CODECOMPLETED);
    }
    function working(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status|$this->WORKING);
    }
    function unworking() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status($status&~$this->WORKING);
    }
    function del() {
        $nid = $this->spArgs('id');
        if(empty($nid)) 
            return;

        $condition = array(
            'id' => $nid
        );

        spClass('taskModel')->delete($condition);
        $condition = array(
            'rid' => $nid,
            'owner' => 'task'
        );
        spClass('commentModel')->delete($condition);

        $this->jumpTaskPage();
    }

    function cmt() {
        $id = $this->spArgs('id');
        if(empty($id)) return;
        $comment = $this->spArgs('reply');
        if(empty($comment)) return;

        $sess = spClass('spSession');
        $data = array(
            'uid' => $sess->getUser()->getUserId(),
            'prj' => $sess->getUser()->getCurrentProject(),
            'owner' => 'task',
            'rid' => $id,
            'content' => $comment
        );
        spClass('commentModel')->create($data);

        $nid = 0;
        foreach($this->tNavigation as $nav) {
            if($nav['name'] == 'Task'){
                $nid = $nav['nid'];
                break;
            }
        }
        if(empty($nid)) {
            $this->jumpTaskPage();
        } else {
            $this->navi("/task.php?c=main&a=view&id=$id");
        }
    }
}
