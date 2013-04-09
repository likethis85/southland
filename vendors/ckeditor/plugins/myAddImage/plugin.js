CKEDITOR.plugins.add( 'myAddImage', {
    init : function( editor ) {   
        var getFuncNum = function(url) {
            var reParam = new RegExp('(?:[\?&]|&amp;)CKEditorFuncNum=([^&]+)', 'i') ;
            var match = url.match(reParam) ;
            return (match && match.length > 1) ? match[1] : '' ;
        }

        var getAjaxResult = function (t){
            var _id = this.getId();
            var _doc = this.getFrameDocument();
            //获取页面返回值
            var data = _doc.getBody().getHtml();
            //firebrowser的处理
            CKEDITOR.tools.callFunction(t.listenerData, data);
            this.removeListener('load', getAjaxResult);
        }

        CKEDITOR.dialog.add( 'myAddImage', function( editor ) {
            return {
                title :     '添加图片',
                minWidth  : 400,
                minHeight : 200,
                contents : 
                [
                    {
                        id :    'addImage',
                        label : '添加图片',
                        title : '添加图片',
                        filebrowser : 'uploadButton',
                        elements :
                        [
                            {    
                                id :    'txtUrl',
                                type :  'text',
                                label : '图片网址',
                                required: true
                            },
                            {
                                id :    'photo',
                                type :  'file',
                                label : '上传图片',
                                style:  'height:40px',
                                size :  38,
                                action: 'ajax.php?a=pushimg'
                            },
                            {
                                type :  'fileButton',
                                id :    'uploadButton',
                                label : '上传',
                                filebrowser : {
                                    action : 'QuickUpload',
                                    target : 'addImage:txtUrl',
                                    onSelect:function(fileUrl, errorMessage){
                                    }
                                },
                                onClick: function(){
                                    var d = this.getDialog();
                                    var _photo =  d.getContentElement('addImage','photo');
                                    _funcNum = getFuncNum(_photo.getAction());
                                    var _iframe =  CKEDITOR.document.getById(_photo._.frameId);
                                    //可以查看ckeditor.event doc 了解此段代码
                                    //http://docs.cksource.com/ckeditor_api/
                                    _iframe.on('load', getAjaxResult, _iframe, _funcNum);
                                },
                                'for' : [ 'addImage', 'photo']
                            }
                        ]
                    }
                ],
                onOk : function(){
                    _src = this.getContentElement('addImage', 'txtUrl').getValue();
                    if (_src.match(/(^\s*(\d+)((px)|\%)?\s*$)|^$/i)) {
                        alert('请输入网址或者上传文件');
                        return false;
                    }
                    this.imageElement = editor.document.createElement( 'img' );
                    this.imageElement.setAttribute( 'alt', '' );
                    this.imageElement.setAttribute( 'src', _src );
                    //图片插入editor编辑器
                    editor.insertElement( this.imageElement );
               }
            };
        });
        editor.addCommand( 'myImageCmd', new CKEDITOR.dialogCommand( 'myAddImage' ) );
        editor.ui.addButton( 'AddImage',
        {
            label : '图片',
            icon:   this.path+'images/image.png', //toolbar上icon的地址,要自己上传图片到images下
            command : 'myImageCmd'
        });
    },
    requires : [ 'dialog' ]
});
