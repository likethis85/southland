<?php
if (!defined('SOUTHLAND')) { exit(1);}
class messageModel extends spModel
{
    var $pk = "id";		 // 按id排序
    var $table = "message"; // 数据表的名称
    
    public function send_message($sender, $receiver, $msg){
        if(empty($receiver) || empty($msg))
            return false;
        $data = array(
            'sender' => $sender,
            'receiver' => $receiver,
            'subject' => $msg['subject'],
            'msgbody' => $msg['body']
        );    
        
        return $this->create($data);
    }
    
    public function receive_message($uid){
        if(empty($uid))
            return false;
            
        return $this->findAll(array('receiver' => $uid));
    }
}