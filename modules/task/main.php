<?php
if (!defined('SOUTHLAND')) { exit(1);}

class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function add() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(empty($uid) || !spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		$submit = $this->spArgs("submit");
		if($submit == 1) {
			$data = array(
			    'pid' => $this->spArgs('id'),
			    'prj' => $pid,
                'assigner' => $uid,
				'owner'=> $uid,
				'priority'=>$this->spArgs('TaskPri'),
				'subject'=>$this->spArgs('subject'),
                'detail'=>$this->spArgs('TaskDesc'),
                'acl'   =>$this->spArgs('acl')
			);
			$tid = spClass('taskModel')->create($data);
            if($tid == false){
                spClass('keeper')->speak(T('Error DB operation failed'));
                exit;
            }

            spClass('timelineModel')->createForTask($pid,$tid,$uid,date('y-m-d'),null,$this->spArgs('subject'));
			$this->jumpTaskPage();
		} else {
			$this->display("task/add.html");
		}
	}
    function view() {
        $uid = $this->tUser['id'];
        $tid = $this->spArgs('id');
        if(empty($tid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        if(!spClass('taskModel')->allow($tid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $this->tTask = spClass('taskModel')->find(array('id' => $tid));
        $this->tComments = spClass('commentModel')->getTaskComments($tid);
        $this->display('task/view.html');
    }
    function update() {
        $uid = $this->tUser['id'];
        $tid = $this->spArgs('id');
        if(empty($uid) || !spClass('taskModel')->allow($tid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(empty($tid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        $submit = $this->spArgs('submit');
        if($submit == 1) {
            $condition = array(
                'id' => $this->spArgs('id')
            );
            $data = array(
                'subject'  => $this->spArgs('subject'),
                'priority' => $this->spArgs('TaskPri'),
				'detail'   => $this->spArgs('TaskDesc'),
                'acl'      => $this->spArgs('acl')
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
        $tid = $this->spArgs('id');
        if(empty($tid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        $task = spClass('taskModel')->find(array('id'=>$tid));
        if(empty($task))
            return false;
        return $task['status'];
    }
    function update_status($status){
        $uid = $this->tUser['id'];
        $tid = $this->spArgs('id');
        if(empty($uid) || !spClass('taskModel')->allow($tid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(empty($tid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        if(!empty($tid)) {
            $condition = array('id' => $tid);
            $data = array('status' => $status);
            spClass('taskModel')->update($condition, $data);
        }
        $this->jumpTaskPage();
    }
    function pending() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status(spClass('taskModel')->STATUS_PENDING);
    }
    function complete() {
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status(spClass('taskModel')->STATUS_COMPLETED);
    }
    function veri(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status(spClass('taskModel')->STATUS_VERIFIED);
    }
    function cc(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status(spClass('taskModel')->STATUS_CODECOMPLETE);
    }
    function working(){
        $status = $this->get_status($this->spArgs('id'));
        if($status === false)
            $this->jumpTaskPage();
        else 
            $this->update_status(spClass('taskModel')->STATUS_WORKING);
    }
    function del() {
        $uid = $this->tUser['id'];
        $tid = $this->spArgs('id');
        if(empty($uid) || !spClass('taskModel')->allow($tid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(empty($tid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        spClass('taskModel')->delete(array('id' => $tid));
        spClass('commentModel')->delete(array('rid' => $tid, 'owner' => 'task'));
        $this->jumpTaskPage();
    }

    function cmt() {
        $uid = $this->tUser['id'];
        $tid = $this->spArgs('id');
        if(!spClass('taskModel')->allow($tid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $comment = $this->spArgs('reply');
        if(empty($tid) || empty($comment)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        $sess = spClass('spSession');
        $data = array(
            'uid' => $uid,
            'prj' => $pid,
            'owner' => 'task',
            'rid' => $tid,
            'content' => $comment
        );
        spClass('commentModel')->create($data);
        $this->navi("/task.php?c=main&a=view&id=$tid");
    }
}
