<?php /* Smarty version 2.6.26, created on 2013-04-02 15:04:29
         compiled from skin/think/task/add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/task/add.html', 11, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div style="clear:both;margin-left:32px;margin-top:32px;">
        <script type="text/javascript">
        $(document).ready(function(){
            CKEDITOR.replace("TaskDesc");
            $("#form").validate({
                rules:{
                    subject:{required:true}
                },
                messages:{
                    subject:"<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'cannot be empty'), $this);?>
"
                }
            });
            $("#project").change(function(){
                location.href = "/index.php?c=main&a=project&id="+$("#project").val();
            });
        });
        </script>
        <div style="margin-left:12px;width:600px;">
            <form id="form" method="post">
                <table>
                    <tr>
                        <td><label style="margin-right:8px" for="subject"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskTitle'), $this);?>
</label></td>
                        <td><input name="subject" id="subject" type="text" value="" style="width:490px"/></td>
                    </tr>                
                    <tr>
                        <td style="width:88px;"><label style="margin-right:8px" for="TaskPri"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskPriority'), $this);?>
</label></td>
                        <td>
                            <span style="background:red;"><input type="radio" name="TaskPri" value="1"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'High'), $this);?>
</span>
                            <span style="background:#FC0;"><input type="radio" name="TaskPri" value="2" checked><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Medium'), $this);?>
</span>
                            <span style="background:gray;"><input type="radio" name="TaskPri" value="3"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Low'), $this);?>
</span>
                        </td>
                    </tr>
                    <tr>
                        <td><label style="margin-right:8px" for="TaskCat"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskCategory'), $this);?>
</label></td>
                        <td>
                            <span style="background:red;"><input type="radio" name="TaskCat" value="1"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskTodo'), $this);?>
</span>
                            <span style="background:#FC0;"><input type="radio" name="TaskCat" value="2" checked><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskFeature'), $this);?>
</span>
                        </td>
                    </tr>
                    <tr><td colspan="2"><label style="margin-right:8px" for="TaskDesc"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'TaskDesc'), $this);?>
</label></td></tr>
                    <tr>
                        <td colspan="2">
                            <textarea id="TaskDesc" name="TaskDesc"></textarea>
                            <input class="btn-rounded" type="submit" value=<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'CreateNewTask'), $this);?>
 />
                            <input name="submit" type="hidden" id="submit" value="1" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>