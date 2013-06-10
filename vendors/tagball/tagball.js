$.fn.TagBall = function(options) {
    var active = false;
    var howElliptical=1;
    
    this.cfg = $.extend({radius:80,speed:10,distance:500}, options);
    this.css({'width':'230px','height':'230px'});
	this.hover(
	    function() { active = true;},
	    function() { active = false;}
	);
	
	this.aA=this.find('a');
	this.mcList = new Array();
	for(i=0;i<this.aA.length;i++)
	{
		var oTag={};
		
		oTag.offsetWidth=this.aA[i].offsetWidth;
		oTag.offsetHeight=this.aA[i].offsetHeight;
		
		this.mcList.push(oTag);
	}
	
	var updateBall = function (){
    	var a;
    	var b;
    	
    	if(active)
    	{
    		return;
    	}
    	else
    	{
    		a = this.cfg.speed*0.18;
    		b = this.cfg.speed*0.18;
    	}
    	
    	
    	var c=0;
    	TB_sineCosine.call(this,a,b,c);
    	for(var j=0;j<this.mcList.length;j++)
    	{
    		var rx1=this.mcList[j].cx;
    		var ry1=this.mcList[j].cy*ca+this.mcList[j].cz*(-sa);
    		var rz1=this.mcList[j].cy*sa+this.mcList[j].cz*ca;
    		
    		var rx2=rx1*cb+rz1*sb;
    		var ry2=ry1;
    		var rz2=rx1*(-sb)+rz1*cb;
    		
    		var rx3=rx2*cc+ry2*(-sc);
    		var ry3=rx2*sc+ry2*cc;
    		var rz3=rz2;
    		
    		this.mcList[j].cx=rx3;
    		this.mcList[j].cy=ry3;
    		this.mcList[j].cz=rz3;
    		
    		per=this.cfg.distance/(this.cfg.distance+rz3);
    		
    		this.mcList[j].x=(howElliptical*rx3*per)-(howElliptical*2);
    		this.mcList[j].y=ry3*per;
    		this.mcList[j].scale=per;
    		this.mcList[j].alpha=per;
    		
    		this.mcList[j].alpha=(this.mcList[j].alpha-0.6)*(10/6);
    	}
    	
    	TB_doPosition.call(this);
    	TB_depthSort.call(this);
    }

    var TB_depthSort = function(){
    	var i=0;
    	var aTmp=[];
    	
    	for(i=0;i<this.aA.length;i++)
    	{
    		aTmp.push(this.aA[i]);
    	}
    	
    	aTmp.sort
    	(
    		function (vItem1, vItem2)
    		{
    			if(vItem1.cz>vItem2.cz)
    			{
    				return -1;
    			}
    			else if(vItem1.cz<vItem2.cz)
    			{
    				return 1;
    			}
    			else
    			{
    				return 0;
    			}
    		}
    	);
    	
    	for(i=0;i<aTmp.length;i++)
    	{
    		aTmp[i].style.zIndex=i;
    	}
    }

    var TB_positionAll = function() {
    	var phi=0;
    	var theta=0;
    	var max=this.mcList.length;
    	var i=0;
    	
    	var aTmp=[];
    	var oFragment=document.createDocumentFragment();
    	
    	for(i=0;i<this.aA.length;i++)
    	{
    		aTmp.push(this.aA[i]);
    	}
    	
    	aTmp.sort
    	(
    		function ()
    		{
    			return Math.random()<0.5?1:-1;
    		}
    	);
    	
    	for(i=0;i<aTmp.length;i++)
    	{
    		oFragment.appendChild(aTmp[i]);
    	}
    	
    	this.append(oFragment);
    	
    	for( var i=1; i<max+1; i++){
			phi = Math.acos(-1+(2*i-1)/max);
			theta = Math.sqrt(max*Math.PI)*phi;
    		this.mcList[i-1].cx = this.cfg.radius * Math.cos(theta)*Math.sin(phi);
    		this.mcList[i-1].cy = this.cfg.radius * Math.sin(theta)*Math.sin(phi);
    		this.mcList[i-1].cz = this.cfg.radius * Math.cos(phi);
    		
    		this.aA[i-1].style.left=this.mcList[i-1].cx+this[0].offsetWidth/2-this.mcList[i-1].offsetWidth/2+'px';
    		this.aA[i-1].style.top=this.mcList[i-1].cy+this[0].offsetHeight/2-this.mcList[i-1].offsetHeight/2+'px';
    	}
    }

    var TB_doPosition = function() {
    	var l=this[0].offsetWidth/2;
    	var t=this[0].offsetHeight/2;
    	for(var i=0;i<this.mcList.length;i++)
    	{
    		this.aA[i].style.left=this.mcList[i].cx+l-this.mcList[i].offsetWidth/2+'px';
    		this.aA[i].style.top=this.mcList[i].cy+t-this.mcList[i].offsetHeight/2+'px';
    		
    		this.aA[i].style.fontSize=Math.ceil(12*this.mcList[i].scale/2)+8+'px';
    		
    		this.aA[i].style.filter="alpha(opacity="+100*this.mcList[i].alpha+")";
    		this.aA[i].style.opacity=this.mcList[i].alpha;
    	}
    }

    var TB_sineCosine = function( a, b, c){
        var dtr = 0.01734218;
    	sa = Math.sin(a * dtr);
    	ca = Math.cos(a * dtr);
    	sb = Math.sin(b * dtr);
    	cb = Math.cos(b * dtr);
    	sc = Math.sin(c * dtr);
    	cc = Math.cos(c * dtr);
    }
    
	TB_sineCosine.call(this, 0,0,0 );
	
	TB_positionAll.call(this);
	
	var _this = this;
	var callback = function() { updateBall.call(_this); }
	setInterval(callback, 100);
}
