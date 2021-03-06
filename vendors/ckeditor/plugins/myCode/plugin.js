CKEDITOR.plugins.add( 'myCode', {
    init : function( editor ) {   
        CKEDITOR.dialog.add( 'myCode', function( editor ) {
            return {
                title :     '添加代码块',
                minWidth  : 600,
                minHeight : 360,
                contents:
                [
                    {
                        id :    'addCode',
                        label : '添加代码',
                        title : '添加代码',
                        elements :
                        [
                            {
                                id :    'codetype',
                                type :  'select',
                                items:  [ ['CPP'],['Python'],['Java'],['Javascript']],
                                'default': 'CPP',
                                label : '代码类型',
                                required: true
                            },
                            {
                                id :    'codeblock',
                                type :  'textarea',
                                label : '代码',
                                rows:    28,
                                required: true
                            }
                        ]
                    }
                ],
                onOk : function(){
                    cb = this.getContentElement('addCode', 'codeblock').getValue();
                    ct = this.getContentElement('addCode', 'codetype').getValue();
                    ELM_div = '<div style="clear:both;border:1px solid green;background:AliceBlue;"><pre class="'+
                                ct+
                               '" name="code">'+
                                cb+
                               '</pre></div>';
                    editor.insertHtml(ELM_div, 'unfiltered_html');
                }
            };
        });
        editor.addCommand( 'myCodeCmd', new CKEDITOR.dialogCommand( 'myCode' ) );
        editor.ui.addButton( 'AddCode',
        {
            label : '代码块',
            icon:   this.path+'images/image.png', //toolbar上icon的地址,要自己上传图片到images下
            command : 'myCodeCmd'
        });
    },
    requires : [ 'dialog' ]
});
