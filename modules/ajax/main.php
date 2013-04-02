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

	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
