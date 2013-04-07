<?php /* Smarty version 2.6.26, created on 2013-04-02 13:16:02
         compiled from skin/think/page/forum.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/page/forum.html', 10, false),)), $this); ?>
<div style="clear:both;margin-left:40px;margin-top:32px;">
    <script language="javascript" type="text/javascript">
        $().ready(function(){
            $(".operator").hide();
        });
    </script>
    <div style="margin-top:16px;height:16px;margin-bottom:16px;">
        <table align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td><a class="btn-capsule" href="forum.php?c=main&a=publish"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Publish New Topic'), $this);?>
</a></td>
            </tr>
        </table>
        <div style="clear:both;"></div>
    </div>
    
    <?php $_from = $this->_tpl_vars['tSubjects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <div>
        <div class="topic-link"  style="float:left;margin-bottom:8px;">
            <a href="forum.php?a=view&nid=<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['subject']; ?>
</a>
            <a class="operator" title="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'EditTopic'), $this);?>
" href="/forum.php?a=update&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><img src="/template/skin/<?php echo $this->_tpl_vars['skin']; ?>
/img/edit.png" width=16 height=16></a>
            <a class="operator" title="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'DelTopic'), $this);?>
" href="/forum.php?a=update&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><img src="/template/skin/<?php echo $this->_tpl_vars['skin']; ?>
/img/delete.png" width=16 height=16></a>
        </div>
    </div>
    <div style="clear:both;"></div>
    <?php endforeach; endif; unset($_from); ?>
    
    <script type="text/javascript">
        ($("div.topic-link")).each(function() {
            $(this).hover(function() {
                $(this).children(".operator").show();
            },function() {
                $(this).children(".operator").hide();
            });
        });
    </script>
</div>