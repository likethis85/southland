<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function publish() {
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(empty($uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		$submit = $this->spArgs("submit");
		if( $submit == 1 ){
            $keywords = $this->spArgs('kwd');
            $data = array(
                    'uid' => $this->tUser['id'],
                    'prj' => $this->tCurrProj,
                    'subject' => $this->spArgs('subject'),
                    'content' => $this->spArgs('WikiContent'),
                    'acl'     => $this->spArgs('acl')
            );
            spClass('wikiModel')->CreateWiki($data, $keywords);
            $this->jumpWikiPage();
        } else {
            $this->display('wiki/add.html');
        }
	}
    function update() {
        $uid = $this->tUser['id'];
        $wid = $this->spArgs('id');
        if(empty($uid) || !spClass('wikiModel')->allow($wid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }

		$submit = $this->spArgs("submit");
		if($submit == 1)
		{
            $condition = array(
                'id' => $wid
            );
			$data = array(
				'subject'=>$this->spArgs('subject'),
				'content'=>$this->spArgs('Artical'),
                'acl'    =>$this->spArgs('acl')
			);
			spClass('forumModel')->update($condition, $data);
			$this->jumpWikiPage();
		}
		else
		{
            $condition = array(
                'id' => $wid
            );
            $this->tWiki = spClass('wikiModel')->find($condition);
			$this->display("wiki/update.html");
		}
	}
    function del() {
    }
	function view() {
        $uid = $this->tUser['id'];
        $wid = $this->spArgs('id');
        if(empty($wid)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }
        if(!spClass('wikiModel')->allow($wid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
	    $this->tWiki = spClass('wikiModel')->getWikiDetail($wid);
		$this->display("wiki/view.html");
	}
    function search(){
        $keyword = $this->spArgs('t');
        if(empty($keyword)){
            spClass('keeper')->speak(T('Error Invalid Parameters'));
            exit;
        }
        
        $uid = $this->tUser['id'];
        $pid = $this->tCurrProj;
        if(!spClass('projectModel')->allow($pid, $uid)){
            spClass('keeper')->speak(T('Error Operation not permit'));
            exit;
        }
        
        $this->tModule = 'wiki';
        $this->tWikis = spClass('keywordsModel')->findWikis($keyword);
        $this->display("page.html");
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
