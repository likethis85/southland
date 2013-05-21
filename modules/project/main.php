<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public function create() {
        if(empty($this->tUser['id'])){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		if($this->spArgs('submitcreate')!=1){
            $this->display(WORKSPACE.'/create.html');
            exit;
        }

        $data = array(
            'uid' => $this->tUser['id'],
            'title' => $this->spArgs('title'),
            'description'=> $this->spArgs('projDesc')
        );
        $pid = spClass('projectModel')->create($data);
        if($pid === false) {
            spClass('keeper')->speak(T('Error DB operation failed'), '/index.php');
            exit;
        }
        spClass('spSession')->getUser()->setCurrentProject($pid);
        spClass('userorgModel')->addProjectCreator($pid,spClass('spSession')->getUser()->getUserId());
        $this->jumpProjectPage();
	}

    public function update() {
        $uid = $this->tUser['id'];
        $pid = $this->spArgs('id');
        if(empty($pid))
            $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		if(1 != $this->spArgs('submit')) {
			$this->display(WORKSPACE.'/update.html');
		} else {
			$objModel = spClass('projectModel');
            $condition = array(
				'id' => $pid
            );
			$data = array(
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc')
			);
			$pid = $objModel->update($condition, $data);
            $this->jumpProjectPage();
		}
    }

    /** @brief add event to timeline 
     *
     */
    public function addEvent() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $title = $this->spArgs('title');
        $date = $this->spArgs('date');
        $isdate = strtotime($date);
        if(empty($title) || -1==$isdate || false==$isdate) {
            $this->jumpProjectPage();
            return;
        }

        $pid = $this->tCurrProj;
        $uid = spClass('spSession')->getUser()->getUserId();
        if(false == spClass('timelineModel')->createForProject($pid, $uid, $date, $title)) {
            spClass('keeper')->speak(T('Error DB operation failed'));
        } else {
            $this->jumpProjectPage();
        }
    }
    public function del() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        spClass('projectModel')->deleteProject($this->spArgs('id'));
        spClass('spSession')->getUser()->setCurrentProject(0);
		$this->tCurrProj = 0;
        $this->jumpFirstPage();
    }
}
