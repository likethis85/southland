<?php
if (!defined('SOUTHLAND')) { exit(1);}
class orderformModel extends spModel
{
	var $pk = "id"; // 每个留言唯一的标志，可以称为主键
	var $table = "orderform"; // 数据表的名称
	
	public function getList(){
		$arr = $this->findAll(array('isdelete'=>0));
		return $arr;
	}
	
	public function getDetail($id){
		$condition = array('id'=>$id);
		$arr = $this->find($condition);
		return $arr;
	}
}
