<?php
if (!defined('SOUTHLAND')) { exit(1);}

/**
 * 用户管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class keeper extends general {
    
    public function __construct(){ // 公用
        parent::__construct(); // 这是必须的
    }
    
     public function speak($msg, $forward=null) {
        if(empty($forward))
            $forward = $_SERVER['HTTP_REFERER'];
        if(empty($forward))
            $forward = '/index.php';

        $this->tRefUrl = $forward;
        $this->tMsg = $msg;
        $this->Display('keeper.html');
        exit;
    }
}
