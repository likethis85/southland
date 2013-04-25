<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public function create() {
		$create = $this->spArgs('submitcreate'); 
		if(empty($create)) {
		    $this->setUsingProject(0);
			$this->display(WORKSPACE.'/create.html');
		} else {
			$objModel = spClass('projectModel');
			$data = array(
				'uid' => spClass('spSession')->getUser()->getUserId(),
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc')
			);
			$nid = $objModel->create($data);
			if($nid !== false) {
				if(is_array($nid))
					$nid = $nid['id'];
				spClass('userorgModel')->addProjectManager($nid, $data['uid']);
				$this->setUsingProject($nid);
				$this->jumpProjectPage();
			}
		}
	}

    public function update() {
		$update = $this->spArgs('submitupdate'); 
		if(empty($update)) {
			$this->display(WORKSPACE.'/update.html');
		} else {
			$objModel = spClass('projectModel');
            $condition = array(
				'id' => $this->spArgs('id'),
            );
			$data = array(
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc')
			);
			$nid = $objModel->update($condition, $data);
			if($nid !== false) {
				if(is_array($nid))
					$nid = $nid['id'];
				$this->SetUsingProject($nid);
				$this->jumpProjectPage();
			}
		}
    }

    public function del() {
        spClass('projectModel')->deleteProject($this->spArgs('id'));
        spClass('spSession')->getUser()->setCurrentProject(0);
		$this->tCurrProj = 0;
        $this->jumpFirstPage();
    }
}
