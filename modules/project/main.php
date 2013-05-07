<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public function create() {
		$create = $this->spArgs('submitcreate'); 
		if(empty($create)) {
			$this->display(WORKSPACE.'/create.html');
		} else {
			$data = array(
				'uid' => spClass('spSession')->getUser()->getUserId(),
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc')
			);
            $nid = spClass('projectModel')->create($data);
            if($nid === false)
                spClass('keeper')->speak(T('Error DB operation failed'), '/index.php');
            else {
                spClass('spSession')->getUser()->setCurrentProject($nid);
                spClass('userorgModel')->addProjectCreator($nid,spClass('spSession')->getUser()->getUserId());
               $this->jumpProjectPage();
            }
		}
	}

    public function update() {
		$update = $this->spArgs('submit'); 
		if(empty($update)) {
			$this->display(WORKSPACE.'/update.html');
		} else {
			$objModel = spClass('projectModel');
            $condition = array(
				'id' => $this->tCurrProj
            );
			$data = array(
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc')
			);
			$nid = $objModel->update($condition, $data);
            $this->jumpProjectPage();
		}
    }

    public function del() {
        spClass('projectModel')->deleteProject($this->spArgs('id'));
        spClass('spSession')->getUser()->setCurrentProject(0);
		$this->tCurrProj = 0;
        $this->jumpFirstPage();
    }
}
