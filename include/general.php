<?php
if (!defined('SOUTHLAND')) { exit(1);}
/**
 * 全部控制器页面的父类
 * 
 * 实现一些全局的页面显示
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class general extends spController
{
	/**
	 * 覆盖控制器构造函数，进行相关的赋值操作
	 */
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的

		$this->setLang('cn');
		$this->skin = __SKIN_NAME;
		$this->skinpath = 'template/skin/'.$this->skin;

		//设置应用变量
		GLOBAL $__action;
		$spSess = spClass('spSession');
		$this->tHost = $_SERVER["HTTP_HOST"];
		$this->tWorkspace = WORKSPACE;
		$this->tAction = $__action;
		$this->tUser = $spSess->getUser()->getUserInfo();
        $this->tCurrProj = $spSess->getUser()->getCurrentProject();
        $this->tProject = spClass('projectModel')->getCurrentInfo();
        $this->tNavigation = array(
            array('nid' => 1, 'enabled' => true, 'name' =>'ProjectDesc', 'module' => 'project'),
            array('nid' => 2, 'enabled' => true, 'name' =>'Task', 'module' => 'task'),
            array('nid' => 3, 'enabled' => true, 'name' =>'Topic', 'module' => 'forum'),
            array('nid' => 4, 'enabled' => true, 'name' =>'BugTracker', 'module' => 'issue'),
            array('nid' => 5, 'enabled' => true, 'name' =>'Wiki', 'module' => 'wiki'),
            //array('nid' => 6, 'enabled' => true, 'name' =>'Source', 'module' => 'source'),
        );
        $this->tNid = $spSess->getUser()->getCurrentNid();
	}
	/** @brief 保存上传文件到个人目录
	 *
	 */
	public function saveFile($uid, $files, $msg){
	    if(empty($uid))
	        return false;
	        
	    if(empty($files))
	        return false;
	        
	    $dir = APP_PATH."/mass/$uid";
        __mkdirs($dir, 0777);
        __mkdirs("$dir/origin", 0777);
        __mkdirs("$dir/thumbnail", 0777);
        __mkdirs("$dir/avatar", 0777);
        
        $stores = array();
        $uf = spClass('spUpload', array('save_path' => "$dir/origin"));
        foreach($files as $attachments){
            for($i=0; $i<count($attachments['name']);$i++){
                if(empty($attachments['name'][$i]))
                    continue;
                $file = array(  'name' => $attachments['name'][$i],
                                'type' => $attachments['type'][$i],
                                'tmp_name' => $attachments['tmp_name'][$i],
                                'error' => $attachments['error'][$i],
                                'size' => $attachments['size'][$i]);
                if(TRUE === $uf->upload_file($file)) {
                    $total_size += $file['size'];
                    array_push($stores, array(  'origin' => $file['name'],
                                                'save' => "/mass/$uid/origin/".$uf->file_name));
                } else {
                    print_r($uf->errmsg);
                }
                
            }
        }

        if($total_size){
            $recorder = array(
                'uid' => $uid,
                'bytes' => $total_size,
                'reason' => $msg,
            );
            spClass('occupyModel')->create($recorder);
        }
        
        return $stores;
	}
	/** @brief 把数组转化为一个类，方便json输出
	 *
	 */
	public function array2class($arr){
	    if(is_array($arr)){
            $obj = new stdClass();
            foreach($arr as $k => $v){
                $obj->$k = $v;
            }
            return $obj;
        }
        
        return $arr;
	}
	/**
	 * 错误提示程序  应用程序的控制器类可以覆盖该函数以使用自定义的错误提示
	 * @param $msg   错误提示需要的相关信息
	 * @param $url   跳转地址
	 * 
	 * @param msg
	 * @param url
	 */
	public function jsonerror($msg, $url='')
	{
		exit("{'status': 0, 'data': {".$msg."},'url': '".$url."'}");
	}

	/**
	 * 成功提示程序  应用程序的控制器类可以覆盖该函数以使用自定义的成功提示
	 * @param $msg   成功提示需要的相关信息
	 * @param $url   跳转地址
	 * 
	 * @param msg
	 * @param url
	 */
	public function jsonsuccess($msg, $url)
	{
		exit("{'status': 1, 'msg': '".$msg."', 'url':'".$url."'}");
	}

    /**
     *  重载显示方法，以实现换肤功能
     */
    public function display($tplname, $output = TRUE)
    {
        parent::display('skin/'.$this->skin."/$tplname", $output);
    }
   
    protected function navi($url) {
        header('location:'.'http://'.$_SERVER["HTTP_HOST"].$url);
        exit;
    }
    protected function jumpFirstPage() {
        $this->navi('/index.php');
    }
    /** @brief 跳到项目介绍页
     *
     */
    protected function jumpProjectPage() {
        $this->navi('/index.php?c=main&a=page&nid='.$this->tNavigation[0]['nid']);
    }
    /** @brief 跳到项目任务页
     *
     */
    protected function jumpTaskPage() {
        $this->navi('/index.php?a=page&nid='.$this->tNavigation[1]['nid']);
    }
    /** @brief 跳到项目话题页
     *
     */
    protected function jumpTopicPage() {
        $this->navi('/index.php?c=main&a=page&nid='.$this->tNavigation[2]['nid']);
    }
    /** @brief 跳到项目问题页
     *
     */
    protected function jumpIssuePage() {
        $this->navi('/index.php?c=main&a=page&nid='.$this->tNavigation[3]['nid']);
        exit(1);
    }
    /** @brief 跳到项目术语页
     *
     */
    protected function jumpWikiPage() {
        $this->navi('/index.php?c=main&a=page&nid='.$this->tNavigation[4]['nid']);
    }
    /** @brief 跳到项目源码页
     *
     */
    protected function jumpSourcePage() {
        $this->navi('/index.php?c=main&a=page&nid='.$this->tNavigation[5]['nid']);
    }
    
	public function __destruct(){
		parent::__destruct(); // 这是必须的
	}

}
?>
