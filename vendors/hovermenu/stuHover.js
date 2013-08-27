$.fn.HoverMenu = function(options) {
    if(options.item.length==0)
        return;

    var settings = $.extend({'lazy':false}, options);
    var _e = this[0];
    var m = $('<div style="background:transparent;position:absolute;">'+
                '<div style="width:23px;height:16px;background:transparent;"><div id="hm_icon"  style="width:16px;height:16px;float:left;background:transparent;"></div>' +
                '<div id="hm_arrow" style="display:none;background-color:#6E88B7;text-align:center;color:white;line-height:16px;width:7px;height:16px;float:left;">' +
                '<div style="border:3px solid;border-color:white #6E88B7 #6E88B7 #6E88B7;margin-top:6px;"></div></div>' +
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
    
    var createMenu = function(items){
        var createMenuHtml = function(items){
            var menus = '<div id="hm_menu" style="border:1px solid #0080FF;display:none;background:white;position:relative;top:0px;z-index:100;cursor:pointer;"><ul>';
            for(var i=0; i<items.length; i++){
                if(typeof items[i].callback == 'string')
                    items[i].callback = new Function('elem', items[i].callback);
                else
                    items[i].callback = null;
                menus += '<li id="'+i+'"><img style="margin-right:6px;" src="'+items[i].icon+'" width=16 height=16>';
                if(typeof items[i].caption=='string')
                    menus += items[i].caption
                if(typeof items[i].item!='undefined' && items[i].item.length){
                    items[i].hasSub = true;
                    menus += createMenuHtml(items[i].item);
                }
                menus += '</li>';
            }
            menus += '</ul></div>';
            return menus;
        }
        
        menus = $(createMenuHtml(settings.item));
        menus.find('li').css({'padding':'2px','padding-right':'8px'}).hover(
            function() { $(this).css('background','#6E88B7');},
            function() { $(this).css('background','white');}
        ).click(function(){
            if(settings.item[this.id].callback)
                settings.item[this.id].callback(_e);
            else if(settings.onSelect)
                settings.onSelect(_e,settings.item[this.id]);
        });
        
        return menus;
    }
    
    m.append(createMenu());
    this.append(m);
}
