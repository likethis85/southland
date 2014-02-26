<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tView = array(
		    'require' => array(
		        'editor' => true
		    )
		);
	}
	
	function add()
	{
	    $pid = $this->tCurrProj;
	    $uid = $this->tUser['id'];
	    if(!spClass('projectModel')->allow($pid,$uid,'AddTopic')) {
	        spClass('keeper')->speak(T('Error Operation not permit'));
            return;
	    }
	    
		$submit = $this->spArgs("submit");
		if($submit == 1)
		{
			$data = array(
			    'prj' => $pid,
			    'author' => $uid,
				'subject'=> $this->spArgs('subject'),
				'content'=> $this->spArgs('Artical'),
                'acl'    => $this->spArgs('acl')
			);
			$tid = spClass('forumModel')->addTopic($pid,
			                                $uid,
			                                $this->spArgs('subject'),
			                                $this->spArgs('Artical'),
			                                $this->spArgs('acl'));
            if($tid !== false) {
                $members = $this->spArgs('members');
                foreach($members as $key => $member)
                    spClass('userroleModel')->addTopicMember($pid,$tid,$key);
            }
			$this->jumpTopicPage();
		}
		else
		{
		    $this->tTitle = $this->tProject['title'].'-'.T('Publish New Topic');
		    $this->tMembers = spClass('userroleModel')->getUsersByProject($pid);
			$this->display("forum/add.html");
		}
	}
    function update()
	{
	    $pid = $this->tCurrProj;
        $uid = $this->tUser['id'];
        $fid = $this->spArgs('id');
        if(!spClass('forumModel')->allow($fid, $uid, 'Update')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

		$submit = $this->spArgs("submit");
		if($submit == 1)
		{
            $condition = array(
                'id' => $this->spArgs('id')
            );
			$data = array(
				'subject'=> $this->spArgs('subject'),
				'content'=> $this->spArgs('Artical'),
                'acl'    => $this->spArgs('acl')
			);
			spClass('forumModel')->update($condition, $data);
            $members = $this->spArgs('members');
            foreach($members as $key => $member)
                spClass('userroleModel')->addTopicMember($pid,$fid,$key);
			$this->jumpTopicPage();
		}
		else
		{
            $this->tTopic = spClass('forumModel')->find(array('id' => $this->spArgs('id')));
            $members = spClass('userroleModel')->getUsersByProject($this->tCurrProj);
            $this->tTopicMembers = spClass('userroleModel')->getUsersByTopic($fid);
            foreach($this->tTopicMembers as $key => $val){
                unset($members[$key]);
            }
            $this->tMembers = $members;
            $this->tTitle = $this->tProject['title'].'-'.T('EditTopic').'-'.$this->tTopic['subject'];
			$this->display("forum/update.html");
		}
	}
    function del() {
        $fid = $this->spArgs('id');
        $uid = $this->tUser['id'];
        if(empty($fid)) {
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            return;
        }
        
        if(!spClass('forumModel')->allow($fid, $uid, 'Delete')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

        spClass('forumModel')->drop($fid);
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
        if(!spClass('forumModel')->allow($fid,$uid, 'View')){
            spClass('keeper')->speak(T('Error Operation not permit'));
            return;
        }

		$this->tRow = spClass('forumModel')->find(array('id'=>$fid));
		$this->tTitle = $this->tProject['title'].'-'.$this->tRow['subject'];
        $this->tMembers = spClass('userroleModel')->getUsersByTopic($fid);
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

        spClass('commentModel')->createForForum($uid,$this->tCurrProj,$fid, $comment);

        $this->navi("/forum.php?a=view&id=$fid");
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
