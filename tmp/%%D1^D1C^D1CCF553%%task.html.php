<?php /* Smarty version 2.6.26, created on 2013-04-07 13:33:14
         compiled from skin/think/page/task.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/page/task.html', 16, false),)), $this); ?>
<div style="clear:both;margin-left:40px;margin-top:32px;">
    <script language="javascript" type="text/javascript">
        $().ready(function(){
            $(".set_task_complete").change(function(){
                location.href = $(this).val();
            });   
            $(".set_task_incomplete").change(function(){
                location.href = $(this).val();
            });
            $(".operator").hide();
        });
    </script>
    <div style="margin-top:16px;height:16px;margin-bottom:16px;">
        <table align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td><a class="btn-capsule" href="/task.php?c=main&a=add&id=0"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'AddTask'), $this);?>
</a></td>
            </tr>
        </table>
        <div style="clear:both;"></div>
    </div>
    <?php $_from = $this->_tpl_vars['tTasks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <div style="padding-top:2px;" id="<?php echo $this->_tpl_vars['item']['id']; ?>
">
         <div class="task-link" style="clear:both;float:left;margin-bottom:8px;">
            <?php if ($this->_tpl_vars['item']['status'] > 127): ?>
            <input class="set_task_incomplete" type="checkbox"  value="/task.php?c=main&a=incomplete&id=<?php echo $this->_tpl_vars['item']['id']; ?>
" checked></input>
            <span style="background:#53FF53;">&nbsp;&nbsp;&nbsp;</span>
            <?php else: ?>
            <input class="set_task_complete" type="checkbox"  value="/task.php?c=main&a=complete&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"></input>
            <?php if ($this->_tpl_vars['item']['priority'] == 1): ?><span style="background:red;">&nbsp;&nbsp;&nbsp;</span><?php endif; ?>
            <?php if ($this->_tpl_vars['item']['priority'] == 2): ?><span style="background:#FC0;">&nbsp;&nbsp;&nbsp;</span><?php endif; ?>
            <?php if ($this->_tpl_vars['item']['priority'] > 2): ?><span style="background:gray;">&nbsp;&nbsp;&nbsp;</span><?php endif; ?>
            <?php endif; ?>
            <a href="/task.php?c=main&a=view&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['subject']; ?>
</a>
            <a class="operator" title="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueReport'), $this);?>
" href="/issue.php?a=add&tid=<?php echo $this->_tpl_vars['item']['id']; ?>
"><img src="/template/skin/<?php echo $this->_tpl_vars['skin']; ?>
/img/bug.png" width=16 height=16></a>
            <a class="operator" title="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'EditTask'), $this);?>
" href="/task.php?a=update&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><img src="/template/skin/<?php echo $this->_tpl_vars['skin']; ?>
/img/edit.png" width=16 height=16></a>
            <a class="operator" title="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'DelTask'), $this);?>
" href="/task.php?a=update&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><img src="/template/skin/<?php echo $this->_tpl_vars['skin']; ?>
/img/delete.png" width=16 height=16></a>
        </div>
    </div>
    <?php endforeach; endif; unset($_from); ?>

    <script type="text/javascript">
        ($("div.task-link")).each(function() {
            $(this).hover(function() {
                $(this).children(".operator").show();
            },function() {
                $(this).children(".operator").hide();
            });
        });
    </script>
</div>