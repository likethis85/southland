$.fn.HoverMenu = function(options) {
    if(options.item.length==0)
        return;

    var settings = $.extend({'lazy':false}, options);
    var _e = this[0];
    var m = $('<div style="background:transparent;position:absolute;">'+
                '<div style="width:23px;height:16px;background:transparent;"><div id="hm_icon"  style="width:16px;height:16px;float:left;background:transparent;"></div>' +
                '<div id="hm_arrow" style="display:none;background-color:#6E88B7;text-align:center;color:white;line-height:16px;width:7px;height:16px;float:left;">v</div></div>' +
              '</div>');
    m.hover(
        function(){
            $(this).find('div:first').css({'border':'1px solid #6E88B7'});
            $(this).find('#hm_arrow').show();
            $(this).click(function(){
                $(this).find('#hm_menu').slideDown('fast');
            });
        },
        function(){
            $(this).find('div:first').css({'border':'none'});
            $(this).find('#hm_arrow').hide();
            $(this).find('#hm_menu').slideUp('fast');
        }
    );
    
    var menus = '<div id="hm_menu" style="border:1px solid #0080FF;display:none;background:white;position:relative;top:0px;z-index:100;"><ul>';
    for(var i=0; i<settings.item.length; i++){
        settings.item[i].callback = new Function('elem', settings.item[i].callback);
        menus += '<li id="'+i+'"><img style="margin-right:6px;" src="'+settings.item[i].icon+'" width=16 height=16>';
        if(typeof settings.item[i].caption=='string')
            menus += settings.item[i].caption
        menus += '</li>';
    }
    menus += '</ul></div>';
    menus = $(menus);
    menus.find('li').css({'padding':'2px','padding-right':'8px'}).hover(
        function() { $(this).css('background','#6E88B7');},
        function() { $(this).css('background','white');}
    ).click(function(){
        settings.item[this.id].callback(_e);
    });
    
    m.append(menus);
    this.append(m);
}
