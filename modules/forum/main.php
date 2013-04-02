<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function publish()
	{
		$submittopic = $this->spArgs("submittopic");
		if($submittopic == 1)
		{
			$data = array(
			    'prj' => spClass('spSession')->getUser()->getCurrentProject(),
			    'author' => spClass('spSession')->getUser()->getUserId(),
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
        $id = $this->spArgs('id');
        if(empty($id))
            return;

        $condition = array(
            'id' => $id
        );
        spClass('forumModel')->delete($condition);

        $condition = array(
            'owner' => 'forum',
            'rid' => $id
        );
        spClass('commentModel')->delete($condition);
        $this->jumpTopicPage();
    }

	function view()
	{
		$nid = $this->spArgs("nid");
		$condition = array(
			'id' => $nid
		);
		
		$objModel = spClass('forumModel');
		$this->tRow = $objModel->find($condition);
        if($this->tRow['commentable'])
            $this->tComments = spClass('commentModel')->getForumComments($nid);
        else
            $this->tComments = array();

		$this->display("forum/view.html");
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
                'owner' => 'forum',
                'rid' => $id,
                'content' => $comment
                );
        spClass('commentModel')->create($data);

        $nid = 0;
        foreach($this->tNavigation as $nav) {
            if($nav['name'] == 'Topic'){
                $nid = $nav['nid'];
                break;
            }
        }
        if(empty($nid)) {
            $this->jumpTopicPage();
        } else {
            $this->navi("/forum.php?a=view&nid=$id");
        }
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
