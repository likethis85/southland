$.fn.HoverMenu = function(options) {
    var settings = $.extend({'lazy':false}, options);
    
    var m = $('<div style="background:transparent;position:absolute;"><div style="width:16px;height:16px;float:left;background:transparent;"></div><div style="width:7px;height:16px;float:left;background:transparent;"></div></div>');
    m.hover(
        function(){
            $(this).css({'border':'1px solid #0080FF'});
            //$(this).find('div:last').css('background-color','#0080FF');
            $(this).click(function(){
                $(this).find('div:last').slideDown();
            });
            $(this).find('div:last').css('background-color','#0080FF');
        },
        function(){
            $(this).css({'border':'none'});
            $(this).find('div:last').css('background-color','transparent');
            $(this).find('div:last').slideUp();
        }
    );
    
    var menus = '<div style="display:none;"><ul>';
    for(var i=0; i<settings.item.length; i++){
        menus += '<li><img src="'+settings.item[i].icon+'" width=16 height=16></li>';
    }
    menus += '</ul></div>';
    m.append($(menus));
    this.append(m);
}