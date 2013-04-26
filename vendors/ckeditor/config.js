/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    CKEDITOR.on('dialogDefinition', function (ev) {
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;

        //消除标签，标签的ID从plugins目录下对应的js文件中查找
        if (dialogName == 'image') {
            //dialogDefinition.removeContents('info');
            //dialogDefinition.removeContents('Link');
            //dialogDefinition.removeContents('Upload');
            dialogDefinition.removeContents('advanced');

            var infoTab = dialogDefinition.getContents( 'info' );
            infoTab.add({
                type : 'button',
                id : 'upload_image',
                align : 'center',
                label : 'upload',
                onClick : function( evt ){
                    var thisDialog = this.getDialog();
                    var txtUrlObj = thisDialog.getContentElement('info', 'txtUrl');
                    var txtUrlId = txtUrlObj.getInputElement().$.id;
                    addUploadImage(txtUrlId);
                }
            }, 'browse'); //place front of the browser button
        }
    });

    function addUploadImage(theURLElementId){
        var uploadUrl = "..."; //这是我自己的处理文件/图片上传的页面URL
        var imgUrl = window.showModalDialog(uploadUrl); 
        imgUrl = imgUrl.trim();      
        if(imgUrl.length!=0){
            reg=/^http:////[A-Za-z0-9]+/.[A-Za-z0-9]+[//=/?%/-&_~`@[/]/':+!]*([^<>/"/"])*$/
            if(!reg.test(imgUrl))
                return;
        }
        //在upload结束后通过js代码window.returnValue=...可以将图片url返回给imgUrl变量。
        //更多window.showModalDialog的使用方法参考
        //http://blog.csdn.net/jrq/archive/2010/01/27/5259946.aspx 
        var urlObj = document.getElementById(theURLElementId);
        urlObj.value = imgUrl;
        urlObj.fireEvent("onchange"); //触发url文本框的onchange事件，以便预览图片
    }

    config.extraPlugins = 'myAddImage,myCode';

    //config.skin='kama';
    config.resize_enabled=false;
    config.removePlugins='elementspath';
    config.toolbar= 'Basic';
    config.toolbar_Full = 
        [
            ['Source','-','Save','NewPage','Preview','-','Templates'],
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
            ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
            ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
            '/',
            ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['Link','Unlink','Anchor'],
            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
            '/',
            ['Styles','Format','Font','FontSize'],
            ['TextColor','BGColor']
        ];
    config.toolbar_Basic = 
        [
            ['Source'],
            ['Cut','Copy','Paste'],
            ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['AddImage','AddCode', 'Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
            ['Font','FontSize','TextColor','BGColor']
        ];
};
