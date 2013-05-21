<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function publish()
	{
		$submittopic = $this->spArgs("submit");
		if($submittopic == 1)
		{
            $uid = spClass('spSession')->getUser()->getUserId();
            $pid = $this->tCurrProj;
            if(!spClass('projectModel')->allow($pid,$uid)){
                spClass('keeper')->speak(T('Error Operation not permit'));
                return;
            }
			$data = array(
			    'prj' => $pid,
			    'author' => $uid,
				'subject'=>$this->spArgs('subject'),
				'content'=>$this->spArgs('Artical')
			);
			$objMod = spClass('forumModel');
			$objMod->create($data);
			$this->jumpTopicPage();
		}
		else
		{
			$this->display("forum/publish.html");
		}
	}
    function update()
	{
        $uid = spClass('spSession')->getUser()->getUserId();
        $fid = $this->spArgs('id');
        if(!spClass('forumModel')->allow($fid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

		$submittopic = $this->spArgs("submit");
		if($submittopic == 1)
		{
            $condition = array(
                'id' => $this->spArgs('id')
            );
			$data = array(
				'subject'=>$this->spArgs('subject'),
				'content'=>$this->spArgs('Artical')
			);
			$objMod = spClass('forumModel');
			$objMod->update($condition, $data);
			$this->jumpTopicPage();
		}
		else
		{
            $condition = array(
                'id' => $this->spArgs('id')
            );
            $this->tTopic = spClass('forumModel')->find($condition);
			$this->display("forum/update.html");
		}
	}
    function del() {
        $fid = $this->spArgs('id');
        if(empty($fid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }

        $uid = $this->tUser['id'];
        if(!spClass('forumModel')->allow($fid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $condition = array(
            'id' => $fid
        );
        spClass('forumModel')->delete($condition);

        $condition = array(
            'owner' => 'forum',
            'rid' => $fid
        );
        spClass('commentModel')->delete($condition);
        $this->jumpTopicPage();
    }

	function view()
	{
		$fid = $this->spArgs("id");
        if(empty($fid)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        $uid = $this->tUser['id'];
        if(!spClass('forumModel')->allow($fid,$uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

		$this->tRow = spClass('forumModel')->find(array('id'=>$fid));
        if($this->tRow['commentable'])
            $this->tComments = spClass('commentModel')->getForumComments($fid);
        else
            $this->tComments = array();

		$this->display("forum/view.html");
	}

    function cmt() {
        $fid = $this->spArgs('id');
        $comment = $this->spArgs('reply');
        if(empty($fid) || empty($comment)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        $uid = $this->tUser['id'];
        if(!spClass('forumModel')->allow($fid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        $data = array(
                'uid' => $uid,
                'prj' => $this->tCurrProj,
                'owner' => 'forum',
                'rid' => $fid,
                'content' => $comment
                );
        spClass('commentModel')->create($data);

        if(empty($nid)) {
            $this->jumpTopicPage();
        } else {
            $this->navi("/forum.php?a=view&id=$fid");
        }
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
