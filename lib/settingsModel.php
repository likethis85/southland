<?php
if (!defined('SOUTHLAND')) { exit(1);}
class settingsModel extends spModel
{
	var $pk = "skey"; // 每个留言唯一的标志，可以称为主键
	var $table = "settings"; // 数据表的名称
	
	public function itemlist($lang='en'){
		$arr = $this->findAll();
		
		foreach ($arr as $v){
			$ret[$v['skey']] = $v['svalue'];
		}
		return $ret;
	}
	
	public function getDetail($skey){
		$condition = array('skey'=>$skey);
		$arr = $this->find($condition);
		return $arr;
	}
}
