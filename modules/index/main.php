<?php
if (!defined('SOUTHLAND')) { exit(1);}
class main extends general
{
	public $logNid = 0;
	public $logModule = 0;
	public $logModuleAction = '';
	public $logModuleXid = 0;
	function __construct(){ // 公用
		parent::__construct(); // 这是必须的
	}
	
	function index(){ // 这里是首页
	    $uid = $this->tUser['id'];
	    $this->tTitle = T('FirstPage');
	    $this->tNid = 0;
        spClass('spSession')->getUser()->setCurrentNid($this->tNid);
        $this->tProjects = spClass('projectModel')->getUserProjects($uid);
		$this->display("index.html");
	}
	
	function page(){ // 其他内容
	    $nid = $this->spArgs('nid');
	    $module = $this->tNavigation[$nid-1];
	    if(empty($module)) {
	        $this->jumpFirstPage();
	    } else {
    		$this->tNid = $this->spArgs("nid");
            spClass('spSession')->getUser()->setCurrentNid($this->tNid);
    		$this->tModule = $this->tNavigation[$this->tNid-1]['module'];
    		$this->_project();
    		eval('$this->_'.$this->tModule.'();');
    		$this->display("page.html");
    	}
	}
	function project() {// 选择项目
	    $this->_project();
        $this->jumpTaskPage();
	}
	function _project() {
		$pid = $this->spArgs('pid');
        if(empty($pid))
            $pid = $this->tCurrProj;
        $uid = $this->tUser['id'];
        if(!spClass('projectModel')->allow($pid,$uid)) {
            spClass('keeper')->speak(T('Error Operation not permit'), '/index.php');
            exit;
        }

		if(!empty($pid) && $pid!=$this->tCurrProj) {
		    spClass('spSession')->getUser()->setCurrentProject($pid);
		    $this->tCurrProj = spClass('spSession')->getUser()->getCurrentProject();
		    $this->tProject  = spClass('projectModel')->getCurrentInfo();
		}
		
		if($this->tNid==1) {
		    $this->tView = array(
		        'require' => array(
		            'chronoline' => true,
		            'editor' => true
		        )
		    );
		    $this->tTitle = $this->tProject['title'].'-'.T('ProjectDesc');
            $this->tMembers = spClass('projectModel')->getProjectMembers($this->tCurrProj);
		    $tTimeline = array();
            $timelines = spClass('timelineModel')->getProject($this->tCurrProj);
            foreach($timelines as $timeline){
                array_push($tTimeline, array('brief' => $timeline['brief'], 
                                             'content' => $timeline['content'],
                                             'start' => $timeline['stime'], 
                                             'end' => $timeline['etime'], 
                                             'id' => $timeline['id'], 
                                             'source' => spClass('timelineModel')->scope2string($timeline['scope']))
                );
            }
            $this->tTimeline = $tTimeline;
        }
	}

    /** @brief 用户smarty的函数，显示讨论组话题的可操作项
     *
     */
    function __template_TopicOperation($param){
        $uid = $this->tUser['id'];
        $topic = $param['topic'];
        $uo = spClass('spSession')->getUser()->getRole();
        $op_edit = array(
            'icon' => '/'.$this->skinpath.'/img/edit.png',
            'caption' => T('EditTopic'),
            'callback'=> 'location.href=\"/forum.php?a=edit&id=\"+elem.id.replace(\"f\",\"\")'
        );
        $op_del = array(
            'icon' => '/'.$this->skinpath.'/img/delete.png',
            'caption' => T('DelTopic'),
            'callback'=> 'if(confirm(\"'.T('Confirm?').T('DelTopic').'\"))location.href=\"/forum.php?a=del&id=\"+elem.id.replace(\"f\",\"\")'
        );
        if($uid == $topic['author']){
            echo spClass('Services_JSON')->encode(array(
                            $this->array2class($op_edit),
                            $this->array2class($op_del)));                 
        } else {
            echo spClass('Services_JSON')->encode(array());
        }
    }
	function _forum(){
	    $this->tTitle = $this->tProject['title'].'-'.T('Topic');
    	$objForum = spClass("forumModel");
        $this->tSubjects = $objForum->getTopics();
        spAddViewFunction('spTopicOperation', array(&$this, '__template_TopicOperation'));
	}

    /** @brief 用于Smarty的函数，用于显示可用的Task操作项
     *
     */
    function __template_TaskOperation($param){
        $uid = $this->tUser['id'];
        $task = $param['task'];
        $uo = spClass('spSession')->getUser()->getRole();
        $op_work = array(
                'icon' => '/'.$this->skinpath.'/img/working.png',
                'caption' => T('TaskWorking'),
                'callback'=> 'location.href=\"/task.php?a=working&id=\"+elem.id.replace(\"t\",\"\")'
        );
        $op_cc = array(
            'icon' => '/'.$this->skinpath.'/img/codecompleted.png',
            'caption' => T('TaskCC'), 
            'callback'=> 'location.href=\"/task.php?a=cc&id=\"+elem.id.replace(\"t\",\"\")'
        );
        $op_veri = array(
            'icon' => '/'.$this->skinpath.'/img/verified.png', 
            'caption' => T('TaskVeri'),
            'callback'=> 'location.href=\"/task.php?a=veri&id=\"+elem.id.replace(\"t\",\"\")'
        );
        $op_edit = array(
            'icon' => '/'.$this->skinpath.'/img/edit.png' , 
            'caption'=>T('EditTask'), 
            'callback' => 'location.href=\"/task.php?a=update&id=\"+elem.id.replace(\"t\",\"\")'
        );
        $op_del = array(
            'icon' => '/'.$this->skinpath.'/img/delete.png' ,
            'caption'=> T('DelTask'),                       
            'callback'=> 'if(confirm(\"'.T('Confirm?').T('DelTask').'\"))location.href=\"/task.php?a=del&id=\"+elem.id.replace(\"t\",\"\")'
        );
        $op_transfer = array(
            'icon' => '/'.$this->skinpath.'/img/transfer.png' ,
            'caption'=> T('PostTask')
        );
        $op_bug = array(
             'icon' => '/'.$this->skinpath.'/img/bug.png',
             'caption'=> T('IssueReport'), 
             'callback'=> 'location.href=\"/issue.php?a=add&tid=\"+elem.id.replace(\"t\",\"\")'
        ); 
           
        if($uo['Manager'])
            echo spClass('Services_JSON')->encode(array(
                    $this->array2class($op_work),
                    $this->array2class($op_cc),
                    $this->array2class($op_veri),
                    $this->array2class($op_edit),
                    $this->array2class($op_del),
                    $this->array2class($op_transfer),
                    $this->array2class($op_bug)));                 
        else if($uo['DevMgr'] || ($uo['Dev']&&$uid==$task['owner']))
            echo spClass('Services_JSON')->encode(array(
                    $this->array2class($op_work),
                    $this->array2class($op_cc),
                    $this->array2class($op_edit),
                    $this->array2class($op_del),
                    $this->array2class($op_transfer),
                    $this->array2class($op_bug)));
        else if($uo['QAMgr'] || ($uo['QA']&&$uid==$task['owner']))
            echo spClass('Services_JSON')->encode(array(
                    $this->array2class($op_work),
                    $this->array2class($op_veri),
                    $this->array2class($op_edit),
                    $this->array2class($op_del),
                    $this->array2class($op_bug)));
        else
            echo spClass('Services_JSON')->encode(array());
    }
    /** @brief 任务页
     *
     */
    function _task() {
        $this->tTitle = $this->tProject['title'].'-'.T('Task');
        $this->tTasks = spClass('taskModel')->getTasks($this->tCurrProj);
        spAddViewFunction('spTaskOperation', array(&$this, '__template_TaskOperation'));
    }

    /** @brief 配置用户允许的Issue操作 */
    function __template_IssueOperation($param) {
        $uid = $this->tUser['id'];
        $issue = $param['issue'];
        $op_open = array(
            'icon' => '/'.$this->skinpath.'/img/open.png',
            'caption' => T('IssueOpen'),
            'callback' => 'location.href=\"/issue.php?a=open&iid=\"+elem.id.replace(\"i\",\"\")'
        );

        $op_fixed = array(
            'icon' => '/'.$this->skinpath.'/img/fixed.png',
            'caption' => T('IssueFixed'),
            'callback' => 'location.href=\"/issue.php?a=fixed&iid=\"+elem.id.replace(\"i\",\"\")'
        );

        $op_verified = array(
            'icon' => '/'.$this->skinpath.'/img/verified.png',
            'caption' => T('IssueVerified'),
            'callback' => 'location.href=\"/issue.php?a=verified&iid=\"+elem.id.replace(\"i\",\"\")'
        );

        $op_completed = array(
            'icon' => '/'.$this->skinpath.'/img/completed.png',
            'caption' => T('IssueCompleted'),
            'callback' => 'location.href=\"/issue.php?a=completed&iid=\"+elem.id.replace(\"i\",\"\")'
        );

        $op_post = array(
            'icon' => '/'.$this->skinpath.'/img/transfer.png',
            'caption' => T('IssuePost')
        );

        if($issue['role']==spClass('userroleModel')->role['role_issue_owner'])
            echo spClass('Services_JSON')->encode(array(
                    $this->array2class($op_open),
                    $this->array2class($op_fixed),
                    $this->array2class($op_verified),
                    $this->array2class($op_completed),
                    $this->array2class($op_post)));
        else
            echo spClass('Services_JSON')->encode(array());
    }
    /** @brief 获取Issue的创建人 */
    function __template_GetIssueCreator($param) {
        $user = spClass('userroleModel')->getIssueCreator($param['issue']['id']);
        if(!empty($user)) echo $user['nick'];
    }
    /** @brief 获取Issue的负责人 */
    function __template_GetIssueOwner($param) {
        $users = spClass('userroleModel')->getIssueOwner($param['issue']['id']);
        foreach($users as $user) {
            $nick .=$user['nick'].',';
        }
        echo $nick;
    }
    function _issue() {
        $this->tTitle = $this->tProject['title'].'-'.T('BugTracker');
        $pid = $this->tCurrProj;
        $uid = $this->tUser['id'];
        $this->tIssues = spClass('userroleModel')->getIssuesByUser($pid, $uid);
        spAddViewFunction('spIssueOperation', array(&$this, '__template_IssueOperation'));
        spAddViewFunction('spIssueReporter', array(&$this, '__template_GetIssueCreator'));
        spAddViewFunction('spIssueOwner', array(&$this, '__template_GetIssueOwner'));
        $this->tView = array(
            'require' => array(
                'dataTable' => true
            )
        );
    }
    function _wiki() {
        $this->tTitle = $this->tProject['title'].'-'.T('Wiki');
        $Wikis = spClass('wikiModel')->getWikis();
        foreach($Wikis as &$wiki){
            $str = strip_tags(substr($wiki['content'], 0, 512), '<img>');
            $wiki['content'] = preg_replace('/<\s*img/i', '<img width=128 height=128', $str);
        }
        $this->tWikis = $Wikis;
        $this->tKeywords = spClass('keywordsModel')->findForProject($this->tCurrProj);
        
        $this->tView = array(
            'require' => array(
                'tagball' => true
            )
        );
    }
	public function __destruct(){
		parent::__destruct(); // 这是必须的
		//$objLog = spClass("logModel");
		//$objLog->add($this->logNid,$this->logModule,$this->logModuleAction,$this->logModuleXid);
	}
}	
