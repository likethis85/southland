<?php
if (!defined('SOUTHLAND')) { exit(1);}
class moduleModel extends spModel
{
	var $pk = "mid"; // 每个留言唯一的标志，可以称为主键
	var $table = "module"; // 数据表的名称
	
	public function itemlist($lang='en'){
		$arr = $this->findAll();
		foreach($arr as $k=>$v){
			$arr[$k]['mname']=$v['mname_'.$lang];
			$arr[$k]['gdescription']=$v['gdescription_'.$lang];
		}
		return $arr;
	}
}
