<?php /* Smarty version 2.6.26, created on 2013-04-02 13:16:03
         compiled from skin/think/page/issue.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/page/issue.html', 15, false),)), $this); ?>
<div style="clear:both;margin-left:36px;padding-top:22px;width:800px;">
    <script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $('#issues').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
    });
    </script>
    <div style="margin-top:16px;height:16px;">
        <table align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="btn-capsule" style="margin-bottom:12px;">
                        <a href="issue.php?a=add"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'NewIssues'), $this);?>
</a>
                    </div>
                </td>
            </tr>
        </table>
        <div style="clear:both;"></div>
    </div>
    <table id="issues" name="issues" class="display" width="100%">
        <thead>
            <tr>
                <th><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueTitle'), $this);?>
</th>
                <th><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueReporter'), $this);?>
</th>
                <th><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueAssigner'), $this);?>
</th>
                <th><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueOwner'), $this);?>
</th>
                <th><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'IssueDate'), $this);?>
</th>
            </tr>
        </thead>
        <tbody>
            <?php $_from = $this->_tpl_vars['tIssues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
            <tr>
                <td><?php echo $this->_tpl_vars['item']['brief']; ?>
</td>
                <td><?php echo $this->_tpl_vars['item']['owner']['nick']; ?>
</td>
                <td><?php echo $this->_tpl_vars['item']['owner']['nick']; ?>
</td>
                <td><?php echo $this->_tpl_vars['item']['owner']['nick']; ?>
</td>
                <td><?php echo $this->_tpl_vars['item']['addtime']; ?>
</td>
            </tr>
            <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>