<?php
if (!defined('SOUTHLAND')) { exit(1);}

/**
 * �û�����������
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class keeper extends general {
    
    public function __construct(){ // ����
        parent::__construct(); // ���Ǳ����
    }
    
    public function speak($msg) {
        $this->tRefUrl = $_SERVER['HTTP_REFERER'];
        $this->tMsg = $msg;
        $this->Display('keeper.html');
        exit;
    }
}