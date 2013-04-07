<?php /* Smarty version 2.6.26, created on 2013-04-02 15:51:29
         compiled from skin/think/task/view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/task/view.html', 26, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
    $(document).ready(function(){
        CKEDITOR.replace("reply", {customConfig:'config-reply.js'});
        $("#form").validate({
            rules:{
                dsrte_text: {required:true}
            }
        });
        $("#project").change(function(){
            location.href = "/index.php?c=main&a=project&id="+$("#project").val();
        });
        $(".btn-rounded").hover(
            function(){ $(this).css('color','red');}, 
            function(){ $(this).css('color','white');}
        );
});
</script>
<div style="margin-left:32px;margin-top:22px;width:600px;">
    <table id="box" cellpadding="5" cellspacing="0">
        <tr>
            <td id="content" valign="top">
                <h1 class="title"><?php echo $this->_tpl_vars['tTask']['subject']; ?>
</h1>
                <div>
                    <?php echo $this->_tpl_vars['tTask']['detail']; ?>

                    <div align=right><a href="/issue.php?a=add&tid=<?php echo $this->_tpl_vars['tTask']['id']; ?>
" class="btn-rounded"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueReport'), $this);?>
</a></div>
                </div>
            </td> 
        </tr>
        <tr>
            <td>
                <div>
                    <?php $_from = $this->_tpl_vars['tComments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                    <div style="border-top:solid grey 1px;">
                        <div><?php echo $this->_tpl_vars['item']['content']; ?>
</div>
                        <div style="text-align:right;"><?php echo $this->_tpl_vars['item']['addtime']; ?>
&nbsp;--&nbsp;<?php echo $this->_tpl_vars['item']['user']['nick']; ?>
</div>
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                </div>
                <div style="width:600px;">
                    <form id="form" method="post" action="/task.php?a=cmt&id=<?php echo $this->_tpl_vars['tTask']['id']; ?>
">
                        <div style="margin-top:32px;">
                            <input type="submit" class="btn-rounded" value=<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Reply'), $this);?>
 />
                            <textarea class="ckeditor" id="reply" name="reply"></textarea>
                        </div>
                        <input name="submitreply" type="hidden" id="submitreply" value="1" />
                    </form>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>