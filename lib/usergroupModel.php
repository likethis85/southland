<?php
if (!defined('SOUTHLAND')) { exit(1);}
class usergroupModel extends spModel
{
	var $pk = "gid"; // 每个留言唯一的标志，可以称为主键
	var $table = "usergroup"; // 数据表的名称
	
	public function glist($lang='en'){
		$arr = $this->findAll();
		foreach($arr as $k=>$v){
			$arr[$k]['gname']=$v['gname_'.$lang];
			$arr[$k]['gdescription']=$v['gdescription_'.$lang];
		}
		return $arr;
	}
}
