<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function publish() {
		$submit = $this->spArgs("submit");
		if( $submit == 1 ){
            $keywords = split(',',$this->spArgs('kwd'));
            spClass('wikiModel')->CreateWiki($this->spArgs('subject'), $this->spArgs('WikiContent'), $keywords);
            $this->jumpWikiPage();
        } else {
            $this->display('wiki/add.html');
        }
	}
    function update() {
		$submit = $this->spArgs("submit");
		if($submit == 1)
		{
            $condition = array(
                'id' => $this->spArgs('id')
            );
			$data = array(
				'subject'=>$this->spArgs('subject'),
				'content'=>$this->spArgs('Artical')
			);
			spClass('forumModel')->update($condition, $data);
			$this->jumpWikiPage();
		}
		else
		{
            $condition = array(
                'id' => $this->spArgs('id')
            );
            $this->tWiki = spClass('wikiModel')->find($condition);
			$this->display("wiki/update.html");
		}
	}
    function del() {
    }
	function view() {
	    $this->tWiki = spClass('wikiModel')->getWikiDetail($this->spArgs('id'));
		$this->display("wiki/view.html");
	}
    function search(){
        
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}
}
