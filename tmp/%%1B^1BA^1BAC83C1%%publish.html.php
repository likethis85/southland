<?php /* Smarty version 2.6.26, created on 2013-04-02 15:04:05
         compiled from skin/think/forum/publish.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/forum/publish.html', 19, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div style="clear:both;margin-left:32px;padding-top:22px;width:600px;">
    <script type="text/javascript">
    $(document).ready(function(){
        CKEDITOR.replace("Artical");
    	$("#form").validate({
    		rules:{
    			subject: {required:true}
    		}
    	});
    	$("#project").change(function(){
    		location.href = "/index.php?c=main&a=project&id="+$("#project").val();
    	});
    });
    </script>
    <div style="margin-left:12px;">
        <form id="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>
">
            <div style="margin-bottom:8px">
                <label for="subject" style="margin-right:3px;"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Title'), $this);?>
</label>
                <input name="subject" id="subject" type="text" value="" style="width:490px"/>
            </div>
            <div>
                
                <textarea name="Artical" id="Artical"></textarea>
                <input type="submit" value="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Publish'), $this);?>
"/>
                <input name="submittopic" type="hidden" id="submittopic" value="1" />
            </div>
        </form>
    </div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>