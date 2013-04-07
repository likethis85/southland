<?php /* Smarty version 2.6.26, created on 2013-04-02 13:32:42
         compiled from skin/think/user/login.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'spUrl', 'skin/think/user/login.html', 21, false),array('function', 'T', 'skin/think/user/login.html', 24, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<center>
<div style="margin-top:80px;">
<script language="javascript" type="text/javascript">
    jQuery.extend(jQuery.validator.messages, {
        required:"<span style=\"color:red\">*</span>"
    });
    $(document).ready(function(){
        $("#form").validate({
            rules:{
                uname:{required:true},
                pass:{required:true}
            },
            submitHandler:function(form){
                $("#upass").attr("value",$.md5($("#pass").attr("value")));
                form.submit();
            }
        });
    });
</script>
<form id=form name=form action="<?php echo $this->_plugins['function']['spUrl'][0][0]->__template_spUrl(array('c' => 'user','a' => 'login'), $this);?>
" method="POST">
	<table width="400" cellpadding="2" cellspacing="0">
      <tr>
        <td width="120" align="right" valign="top"><label for="uname"><span class="fontwhite"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'User'), $this);?>
</span></label></td>
		<td><input name="uname" id="uname" style="width:80 px" size="40" /></td>

      </tr>
      <tr>
        <td width="120><label for=" align="right" valign="top" class="fontwhite"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Password'), $this);?>
</td>
		<td><input name="pass" type="password" id="pass" style="width:80 px" size="40"/>
		 </td>
      </tr>
      <tr>
        <td></td>

		<td align="center"><br />
		<input name="upass" id="upass" type="hidden" value=""><input class="blackbutton" type="submit" value="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Login'), $this);?>
" />
		  &nbsp;&nbsp;<input class="blackbutton" type="reset" value="<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Reset'), $this);?>
" /></td>
      </tr>
    </table>
</form>
</div>
</center>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "skin/".($this->_tpl_vars['skin'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>