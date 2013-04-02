<?php
if (!defined('SOUTHLAND')) { exit(1);}
class newsModel extends spModel
{
	var $pk = "newsid"; // 每个留言唯一的标志，可以称为主键
	var $table = "news"; // 数据表的名称
	
	public function getList($limit){
		$arr = $this->findAll(array('enabled'=>1),'newsid desc',null,$limit);
		return $arr;
	}
	
	public function newsEnabled() {
		return array(
			0=>array('name'=>T('On'),'value'=>'1'),
			1=>array('name'=>T('Off'),'value'=>'0'),
		);
	}
	
	public function getDetail($nid){
		$condition = array('newsid'=>$nid,'enabled'=>1);
		$arr = $this->find($condition);
		return $arr;
	}
}
