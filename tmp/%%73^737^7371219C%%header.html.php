<?php /* Smarty version 2.6.26, created on 2013-04-02 13:15:51
         compiled from skin/think/header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'T', 'skin/think/header.html', 45, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible"content="IE=edge"/>
<title>Title Here</title>
<meta name="keywords" content="keyword"/>
<meta name="description" content="description"/>
<script src="/media/js/jquery.js" type="text/javascript"></script>
<script src="/media/js/jquery.metadata.js" type="text/javascript"></script>
<script src="/media/js/jquery.validate.js" type="text/javascript"></script>
<script src="/media/js/jquery.form.js" type="text/javascript"></script>
<script src="/media/js/jquery.md5.js" type="text/javascript"></script>
<link rel='index' title='SpeedCMS' href='http://speedcms.yancreate.com' />
<meta name="generator" content="YanCreate 1.0" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="rating" content="General" />
<meta name="revisit-after" content="2 weeks" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
/css/base.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
/css/menu.css" type="text/css" />
<script src="/vendors/ckeditor/ckeditor.js" type="text/javascript"></script>

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" type="text/css"/>

<link rel="stylesheet" href="/vendors/DataTables/media/css/demo_page.css" type="text/css" />
<link rel="stylesheet" href="/vendors/DataTables/media/css/demo_table_jui.css" type="text/css" />
<link rel="stylesheet" href="/vendors/DataTables/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css" type="text/css" />
<script src="/vendors/DataTables/media/js/jquery.dataTables.js" type="text/javascript"></script>
</head>

<body>
    <script language="javascript" type="text/javascript">
    $().ready(function(){
        $("#project").change(function(){
            location.href = $("#project").val();
        });   
    });
    </script>
    <div class="box">
        <div style="position:relative;top:0px;width:100%;height:164px;background:url(<?php echo $this->_tpl_vars['skinpath']; ?>
/img/bg.jpg) repeat-x;">
            <div style="position:absolute;width:100%;top:0px;height:16px;">
                <div class="login_bar">
                    <?php if ($this->_tpl_vars['tUser']['acl'] == 'ANONYMOUS'): ?>
                        <a href="/index.php?c=user&a=login">[<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Login'), $this);?>
]</a>&nbsp;&nbsp;
                        <a href="/index.php?c=user&a=signon">[<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Signon'), $this);?>
]</a>
                    <?php else: ?>
                        <?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Welcome'), $this);?>
&nbsp;&nbsp;<?php echo $this->_tpl_vars['tUser']['nick']; ?>
&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="/index.php?c=user&a=logout">[<?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'Logout'), $this);?>
]</a>
                    <?php endif; ?>
                </div>
                
                <div style="clear:both;color:white;margin-left:60px;padding-top:22px;">
                    <h1><?php echo $this->_tpl_vars['tCaption']; ?>
</h1>
                </div>
            </div>
            <div style="position:absolute;top:132px;width:100%;height:32px;overflow:hidden;">
                <ul class="navigator">
                    <a href="/"><li><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'FirstPage'), $this);?>
</li></a>
                    <?php if ($this->_tpl_vars['tUser']['acl'] != 'ANONYMOUS'): ?>
                        <?php if (! empty ( $this->_tpl_vars['tCurrProj'] )): ?>
                            <?php $_from = $this->_tpl_vars['tNavigation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['nav']['iteration']++;
?>
                                <?php if ($this->_tpl_vars['item']['enabled']): ?>
                                    <li <?php if ($this->_tpl_vars['tNid'] == $this->_tpl_vars['item']['nid']): ?>class="active_nav"<?php endif; ?>><a href="index.php?c=main&a=page&nid=<?php echo $this->_tpl_vars['item']['nid']; ?>
" class="parent"><span><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => $this->_tpl_vars['item']['name']), $this);?>
</span></a></li>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>

                        <?php if (count ( $this->_tpl_vars['tProjects'] ) == 0): ?>
                            <li><a href="/project.php?c=main&a=create"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'CreateNewProject'), $this);?>
</a></li>
                        <?php else: ?>
                            <li style="margin-right:0px;padding-right:2px;"><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'MyProject'), $this);?>
:</li>
                            <li>
                                <select id="project">
                                    <?php $_from = $this->_tpl_vars['tProjects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                                    <?php if ($this->_tpl_vars['tCurrProj'] == $this->_tpl_vars['item']['id']): ?>
                                    <option value="/index.php?c=main&a=project&id=<?php echo $this->_tpl_vars['item']['id']; ?>
" selected><?php echo $this->_tpl_vars['item']['title']; ?>
</option>											
                                    <?php else: ?>
                                    <option value="/index.php?c=main&a=project&id=<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['title']; ?>
</option>											
                                    <?php endif; ?>
                                    <?php endforeach; endif; unset($_from); ?>
                                    <option value="/project.php?c=main&a=create" <?php if ($this->_tpl_vars['tCurrProj'] == 0): ?>selected<?php endif; ?> ><?php echo $this->_plugins['function']['T'][0][0]->__template_T(array('w' => 'CreateNewProject'), $this);?>
...</option>
                                </select>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
				</ul>
            </div>
        </div>