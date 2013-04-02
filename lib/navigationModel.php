<?php
if (!defined('SOUTHLAND')) { exit(1);}
class navigationModel extends spModel
{
	public $pk = "nid"; // 每个留言唯一的标志，可以称为主键
	public $table = 'navigation'; // 数据表的名称
	
	public function navigationTree($position=0){
		$data = $this->findAll(array('position'=>$position),' weight asc');
		$tree = $this->_navigationTree($data,0);
		return $tree;
	}
	
	/**
	 * 读取菜单
	 * @param array $data
	 * @param int $pId
	 */
	public function _navigationTree($data, $pId=0){
		$tree = array();
		foreach($data as $k => $v){
			if($v['parentid'] == $pId) //父ID找到子id
			{
				$arrSubMenu = $this->_navigationTree($data, $v['nid']);   //递归
				$v['submenu'] = $arrSubMenu;
				$v['hassub'] = (count($arrSubMenu)==0)?0:1;
				$tree[] = $v;
			}
		}
		return $tree;
	}
	
	public function navigationDetail($nid){
		$condition = array('nid'=>$nid);
		$arr = $this->find($condition);
		return $arr;
	}
	
	public function navigationPosition() {
		return array(
			0=>array('name'=>T('None'),'value'=>'0'),
			1=>array('name'=>T('Top'),'value'=>'1'),
			2=>array('name'=>T('Bottom'),'value'=>'2'),
		);
	}
	
	public function navigationEnabled() {
		return array(
			0=>array('name'=>T('On'),'value'=>'1'),
			1=>array('name'=>T('Off'),'value'=>'0'),
		);
	}
}
