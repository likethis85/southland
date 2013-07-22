<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public function create() {
        $uid = $this->tUser['id'];
        if(empty($uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		if($this->spArgs('submit')!=1){
            $this->display(WORKSPACE.'/create.html');
            exit;
        }

        $data = array(
            'uid' => $uid,
            'title' => $this->spArgs('title'),
            'description'=> $this->spArgs('projDesc'),
            'acl'   => $this->spArgs('acl')
        );
        $pid = spClass('projectModel')->create($data);
        if($pid === false) {
            spClass('keeper')->speak(T('Error DB operation failed'), '/index.php');
            exit;
        }
        spClass('spSession')->getUser()->setCurrentProject($pid);
        spClass('userorgModel')->addProjectCreator($pid,spClass('spSession')->getUser()->getUserId());
        spClass('timelineModel')->createForProject($pid, $uid, date('y-m-d'), null, T('Create'));
        $this->jumpProjectPage();
	}

    /** @brief 更新项目基本信息
     *
     */
    public function update() {
        $uid = $this->tUser['id'];
        $pid = $this->spArgs('id');
        if(empty($pid))
            $pid = $this->tCurrProj;
        if(empty($uid) || !spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		if(1 != $this->spArgs('submit')) {
		    $this->tTitle = $this->tProject['title'].'-'.T('EditProject');
			$this->display('project/update.html');
		} else {
			$objModel = spClass('projectModel');
            $condition = array(
				'id' => $pid
            );
			$data = array(
				'title' => $this->spArgs('title'),
				'description'=> $this->spArgs('projDesc'),
                'acl'   => $this->spArgs('acl')
			);
			$pid = $objModel->update($condition, $data);
            $this->jumpProjectPage();
		}
    }

    /** @brief 关闭项目
     *
     */
    public function close(){
        $uid = $this->tUser['id'];
        $pid = $this->spArgs('id');
        if(empty($uid) || empty($pid)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }

        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        if(false == spClass('projectModel')->closeProject($pid)){
            spClass('keeper')->speak(T('Error DB operation failed'));
            exit;
        }

        $reason = $this->spArgs('reason');
        if(!empty($reason)) {
            $date = date('Y-m-d H:i:s');
            spClass('timelineModel')->createForProject($pid, $uid, $date, null, $reason);
        }
        $this->jumpProjectPage();
    }

    /** @brief add event to timeline 
     *
     */
    public function addEvent() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(empty($uid) || !spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $title = $this->spArgs('EventSummary');
        $est = $this->spArgs('EventStartTime');
        $eet = $this->spArgs('EventEndTime');
        $isdate = strtotime($est);
        $isdate = $isdate!=-1 && $isdate!=false;
        if($isdate && !empty($eet)) {
            $isdate = strtotime($eet);
            $isdate = $isdate!=-1 && $isdate!=false;
        }
        if(!$isdate || empty($title)) {
            $this->jumpProjectPage();
            return;
        }

        $newline = array("\n", "\r\n", "\r");
        $title = str_replace($newline, '<br/>', $title);
        if(false == spClass('timelineModel')->createForProject($pid, $uid, $est, $eet, $title, $this->spArgs('content'))) {
            spClass('keeper')->speak(T('Error DB operation failed'));
        } else {
            $this->jumpProjectPage();
        }
    }
    /** @brief update event to timeline 
     *
     */
    public function updateEvent(){
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        $eid = $this->spArgs('id');
        if(empty($eid)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }
        if(empty($uid) || !spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        $title = $this->spArgs('EventSummary');
        $est = $this->spArgs('EventStartTime');
        $eet = $this->spArgs('EventEndTime');
        $isdate = strtotime($est);
        $isdate = $isdate!=-1 && $isdate!=false;
        if($isdate && !empty($eet)) {
            $isdate = strtotime($eet);
            $isdate = $isdate!=-1 && $isdate!=false;
        }
        if(!$isdate || empty($title)) {
            $this->jumpProjectPage();
            return;
        }

        $newline = array("\n", "\r\n", "\r");
        $title = str_replace($newline, '<br/>', $title);
        $title = preg_replace('/(<\s*br\s*\/\s*>)+/i','<br/>', $title);
        if(false == spClass('timelineModel')->updateTimeline($eid, $est, $eet, $title, $this->spArgs('content'))) {
            spClass('keeper')->speak(T('Error DB operation failed'));
        } else {
            $this->jumpProjectPage();
        }  
    }
    public function del() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(empty($uid) || !spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

        spClass('projectModel')->deleteProject($this->spArgs('id'));
        spClass('spSession')->getUser()->setCurrentProject(0);
		$this->tCurrProj = 0;
        $this->jumpFirstPage();
    }
}
