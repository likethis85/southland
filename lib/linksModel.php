<?php
if (!defined('SOUTHLAND')) { exit(1);}
class linksModel extends spModel
{
	var $pk = "id"; // 每个留言唯一的标志，可以称为主键
	var $table = "links"; // 数据表的名称
	
	public function getList(){
		$arr = $this->findAll(array('enabled'=>1),'id desc');
		return $arr;
	}
	
	public function linksEnabled() {
		return array(
			0=>array('name'=>T('On'),'value'=>'1'),
			1=>array('name'=>T('Off'),'value'=>'0'),
		);
	}
	
	public function getDetail($id){
		$condition = array('id'=>$id,'enabled'=>1);
		$arr = $this->find($condition);
		return $arr;
	}
}
