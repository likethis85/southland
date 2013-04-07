<?php /* Smarty version 2.6.26, created on 2013-04-02 13:15:57
         compiled from skin/think/page/project.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/page/project.html', 4, false),)), $this); ?>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#Del").click(function(){
            return confirm('<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => "Confirm?"), $this);?>
'+$(this).html());
        });
    });
</script>
<div style="margin-left:36px;margin-top:16px;border-right:#c3daf9 1px solid;overflow:none;width:20%;float:left;">
	<table>
		<tr><td><h2><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'ProjectAuthor'), $this);?>
</h2></td></tr>
		<tr><td><div title="<?php echo $this->_tpl_vars['tProject']['uid']['email']; ?>
"><?php echo $this->_tpl_vars['tProject']['uid']['nick']; ?>
</td></tr>
		<tr><td><h2><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'ProjectCreateTime'), $this);?>
</h2></td></tr>
		<tr><td><?php echo $this->_tpl_vars['tProject']['addtime']; ?>
</td></tr>
		<tr><td><h2><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'ProjectOperator'), $this);?>
</h2></td></tr>
        <tr><td><div class="btn-capsule"><a id="Del" href="/project.php?c=main&a=del&id=<?php echo $this->_tpl_vars['tCurrProj']; ?>
"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'DeleteProject'), $this);?>
</a></div></td></tr>
        <tr><td><div class="btn-capsule"><a href="/project.php?c=main&a=update&id=<?php echo $this->_tpl_vars['tCurrProj']; ?>
"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'EditProject'), $this);?>
</a></div></td></tr>
	</table>
</div>
<div style="margin-top:16px;float:left;margin-left:12px;width:70%">
	<?php echo $this->_tpl_vars['tProject']['description']; ?>
	
</div>