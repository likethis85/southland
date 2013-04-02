<?php
if (!defined('SOUTHLAND')) { exit(1);}
class logModel extends spModel
{
	public $pk = "id"; // 每个留言唯一的标志，可以称为主键
	public $table = "log"; // 数据表的名称
	
	function Add($navigationid=0,$module=0,$module_action='',$module_xid=0){
		$pairs = array(
		'sessionid'		=>session_id(),
		'ip'			=>$_SERVER['REMOTE_ADDR'],
		'uri'			=>$_SERVER['REQUEST_URI'],
		'host'		=>$_SERVER['HTTP_HOST'],
		'domain'  =>$this->get_domain(urldecode($_SERVER['HTTP_HOST'])),
		'referer'		=>isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'',
		'navigationid'	=>$navigationid,
		'module'		=>$module,
		'module_action'	=>$module_action,
		'module_xid'	=>$module_xid,
		);
		
		if($pairs['referer']!=''){
			$urlinfo = parse_url($pairs['referer']);
			$pairs['host'] = ''.$urlinfo['host'];
			$pairs['domain'] = $this->get_domain(urldecode($pairs['referer']));
		}
		
		if (isset($_GET['debug'])) {
			print_pre($pairs);
		}
		$ret = $this->create($pairs);
		return $ret;
	}
	
	
	function get_domain($url){
		$pattern = "/[\w-]+\.(com|net|org|gov|cc|biz|info|cn)(\.(cn|hk))*/";
		preg_match($pattern, $url, $matches);
		
		if(count($matches) > 0) {
			return $matches[0];
		}else{
			$rs = parse_url($url);
			$main_url = $rs["host"];
	
			if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)) {
				return $main_url;
			}else{
				$arr = explode(".",$main_url);
				$count=count($arr);

				$endArr = array("com","net","org","3322");//com.cn  net.cn 等情况
				if (in_array($arr[$count-2],$endArr)){
					$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
				}else{
					$domain =  $arr[$count-2].".".$arr[$count-1];
				}
				return $domain;
			}
		}
	}
}
