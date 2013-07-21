// chronoline.js
// by Kevin Leung for Zanbato, https://zanbato.com
// MIT license at https://github.com/StoicLoofah/chronoline.js/blob/master/LICENSE.md

$.fn.Chronoline = function (events, options) {
           
    var formatDate = function(date, formatString){
        var ret = formatString;
        var monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
        if(formatString.indexOf('%d') != -1){
            var dateNum = date.getDate().toString();
            if(dateNum.length < 2)
                dateNum = '0' + dateNum;
            ret = ret.replace('%d', dateNum);
        }
        if(formatString.indexOf('%b') != -1){
            var month = monthNames[date.getMonth()].substring(0, 3);
            ret = ret.replace('%b', month);
        }
        if(formatString.indexOf('%Y') != -1){
            ret = ret.replace('%Y', date.getFullYear());
        }
    
        return ret;
    }

    var getEndDate = function(dateArray){
        return dateArray[dateArray.length - 1];
    }
    
    var isFifthDay = function(date){
        var day = date.getDate();
        return (day == 1 || day % 5 == 0) && day != 30;
    }
    
    var isHalfMonth = function(date){
        var day = date.getUTCDate();
        return day == 1 || day == 15;
    }
    
    var sortEvents = function(a, b){
        a = a.dates;
        b = b.dates;

        var aStart = a[0];
        var bStart = b[0];
        if(aStart.getTime()!=bStart.getTime())
            return aStart-bStart;
            
        var aEnd = a[a.length - 1].getTime();
        var bEnd = b[b.length - 1].getTime();
        if(aEnd != bEnd)
            return aEnd - bEnd;
        
        return 0;
    }
    var pxToMs = function(px){ 
        return t.startTime + px / t.pxRatio;
    }
    var msToPx = function(ms){ 
        return (ms - t.startTime) * t.pxRatio;
    }
    
    var defaults = {
        startDate: null,  // start of the timeline. Defaults to first event date
        endDate: null,    // end of the timeline. Defauls to the last event date
        timelinePadding: 86400000*3, // 3 day in ms. Adds this much time to the front and back to get some space

        dateLabelHeight: 50, // how tall the bottom margin for the dates is
        hashLength: 4,  // length of the hash marks for the days
                
        // predefined fns include: null (for daily), isFifthDay, isHalfMonth
        hashInterval: isFifthDay,  // fn: date -> boolean, if a hash should appear
        labelInterval: isFifthDay,  // fn: date -> boolean, if a hash should appear

        subLabel: 'month',  // TODO generalize this code
        subLabelMargin: 2,
        subLabelAttrs: {'font-weight': 'bold'},
        floatingSubLabels: true,  // whether sublabels should float into view

        subSubLabel: 'year',  // TODO generalize this code
        subSubLabelMargin: 2,
        subSubLabelAttrs: {'font-weight': 'bold'},
        floatingSubSubLabels: true,  // whether subSublabels should float into view

        fontAttrs: {'font-size': 10,fill: '#000000'},
        
        // predefined fns include: prevMonth, nextMonth, prevQuarter, nextQuarter, backWeek, forwardWeek
        scrollable: true,
        animated: false,  // whether scrolling is animated or just jumps, requires jQuery

        tooltips: false,  // activates qtip tooltips. Otherwise, you just get title tooltips
        markToday: 'line',  // 'line', 'labelBox', false
        todayAttrs: {'stroke': '#484848'},

        sections: [],
        floatingSectionLabels: true,
        sectionLabelAttrs: {},
        sectionLabelsOnHover: true,

        draggable: false, // requires jQuery, allows mouse dragging

        continuousScroll: true,  // requires that scrollable be true, click-and-hold arrows
        continuousScrollSpeed: 1,  // I believe this is px/s of scroll. There is no easing in it

        toolbar: []
    }
    var DAY = 86400000;
    var t = this;
    t.config = $.extend(defaults,options);
    t.show = function(){
        var span = (t.endTime-t.startTime)/DAY;
        if(span > 800)
            t.resolution = 30;
        else if(span > 100)
            t.resolution = 5;
        else
            t.resolution = 1;
     
        t.cols = (t.endTime-t.startTime)/(t.resolution*DAY);
        t.colspan = t.visibleWidth/t.cols;
        t.pxRatio = t.colspan/(t.resolution*DAY);
        //t.paper.clear();
        DrawTimeAxis(t.startTime,t.endTime);
        DrawEvents(t.startTime, t.endTime);
        if(typeof t.icons == 'undefined') {
            t.icons = {
                'view': "M16,8.286C8.454,8.286,2.5,16,2.5,16s5.954,7.715,13.5,7.715c5.771,0,13.5-7.715,13.5-7.715S21.771,8.286,16,8.286zM16,20.807c-2.649,0-4.807-2.157-4.807-4.807s2.158-4.807,4.807-4.807s4.807,2.158,4.807,4.807S18.649,20.807,16,20.807zM16,13.194c-1.549,0-2.806,1.256-2.806,2.806c0,1.55,1.256,2.806,2.806,2.806c1.55,0,2.806-1.256,2.806-2.806C18.806,14.451,17.55,13.194,16,13.194z",
                'tag': "M14.263,2.826H7.904L2.702,8.028v6.359L18.405,30.09l11.561-11.562L14.263,2.826zM6.495,8.859c-0.619-0.619-0.619-1.622,0-2.24C7.114,6,8.117,6,8.736,6.619c0.62,0.62,0.619,1.621,0,2.241C8.117,9.479,7.114,9.479,6.495,8.859z",
            };
            ray1 = t.paper.path(t.icons.tag)
                .attr({stroke: "#fff", "stroke-width": 3, "stroke-linejoin": "round", opacity: 0})
                .transform('T'+(t.visibleWidth-32)+',0S0.5,0.5');
            t.paper.path(t.icons.tag)
                .attr({fill: "#8CEA00", stroke: "none",'title':'项目'})
                .transform('T'+(t.visibleWidth-32)+',0S0.5,0.5')
                .hover(function(){ray1.stop().animate({opacity:1},200)},function(){ray1.stop().animate({opacity:0},200)})
                .click(function(){
                    for(i=0;i<t.sections.length;i++){
                        if(t.sections[i].name !='project')
                            continue;
                        if(t.sections[i].visible){
                            t.sections[i].elements.attr({'opacity':0});
                            t.sections[i].visible = false;
                            this.attr({fill:'#EEE'});
                        }else{
                            t.sections[i].elements.attr({'opacity':1});
                            t.sections[i].visible = true;
                            this.attr({fill:'#8CEA00'});
                        }
                    }
                });
                
            ray2 = t.paper.path(t.icons.tag)
                .attr({stroke: "#fff", "stroke-width": 3, "stroke-linejoin": "round", opacity: 0})
                .transform('T'+(t.visibleWidth-56)+',0S0.5,0.5');
            t.paper.path(t.icons.tag)
                .attr({fill: "#FF00FF", stroke: "none",'title':'任务'})
                .transform('T'+(t.visibleWidth-56)+',0S0.5,0.5')
                .hover(function(){ray2.stop().animate({opacity:1},200)},function(){ray2.stop().animate({opacity:0},200)})
                .click(function(){
                    for(i=0;i<t.sections.length;i++){
                        if(t.sections[i].name !='task')
                            continue;
                        if(t.sections[i].visible){
                            t.sections[i].elements.attr({'opacity':0});
                            t.sections[i].visible = false;
                            this.attr({fill:'#EEE'});
                        }else{
                            t.sections[i].elements.attr({'opacity':1});
                            t.sections[i].visible = true;
                            this.attr({fill:'#FF00FF'});
                        }
                    }
                });
            
            ray3 = t.paper.path(t.icons.view)
                .attr({stroke: "#fff", "stroke-width": 3, "stroke-linejoin": "round", opacity: 0})
                .transform('T'+(t.visibleWidth-80)+',0S0.6,0.6')  
            t.paper.path(t.icons.view)
                .attr({fill: "#888", stroke: "none", 'title':'全视图'})
                .transform('T'+(t.visibleWidth-80)+',0S0.6,0.6')
                .hover(function(){ray3.stop().animate({opacity:1},200)},function(){ray3.stop().animate({opacity:0},200)})
                .click(t.fullView);
        }
    }
    t.backTimeline = function(){
        var span = t.endTime-t.startTime;
        span /= 3;
        t.startTime -= span;
        t.endTime  -= span;
        t.config.startDate.setTime(t.startTime);
        t.config.endDate.setTime(t.endTime);
        t.show();
    }
    t.zoomIn = function(){
        var DAY_IN_MILLISECONDS = 86400000;
        var span = t.endTime-t.startTime;
        if(span < 30*DAY_IN_MILLISECONDS)
            return;
        span /= 3;
        t.startTime += span;
        t.endTime  -= span;
        t.config.startDate.setTime(t.startTime);
        t.config.endDate.setTime(t.endTime);
        t.show();
    }
    t.fullView = function() {
        t.config.startDate = null;
        t.config.endDate = null;
        CalcViewport();
        t.show();
    }
    t.zoomOut = function(){
        var DAY_IN_MILLISECONDS = 86400000;
        var span = t.endTime-t.startTime;
        if(span/DAY_IN_MILLISECONDS > 2000)
            return;
        span /= 3;
        t.startTime -= span;
        t.endTime  += span;
        t.config.startDate.setTime(t.startTime);
        t.config.endDate.setTime(t.endTime);
        t.show(); }
    t.forwardTimeline = function(){
        var span = t.endTime-t.startTime;
        span /= 3;
        t.startTime += span;
        t.endTime  += span;
        t.config.startDate.setTime(t.startTime);
        t.config.endDate.setTime(t.endTime);
        t.show();
    }
    
    // HTML elements to put everything in
    t.domElement = this[0];
    t.wrapper = document.createElement('div');
    t.wrapper.className = 'chronoline-wrapper';
    t.domElement.appendChild(t.wrapper);
    t.drag = {'sX':0,'cX':0,'sT':0,'cT':0,'os':0,'oe':0};
    $(t.wrapper).mousedown(function(e){
        if(e.srcElement.tagName != 'svg')
            return;
        e.preventDefault();
        t.drag.os = t.startTime;
        t.drag.oe = t.endTime;
        t.drag.sX = e.offsetX;
        t.drag.cT = Date();
        $(t.wrapper).css({'cursor':'move'}).bind('mousemove',function(e){
            e.preventDefault();
            t.drag.cX = e.offsetX;
            span = (t.drag.cX-t.drag.sX)/t.pxRatio;
            t.startTime = t.drag.os-span;
            t.endTime  = t.drag.oe-span;
            t.config.startDate.setTime(t.startTime);
            t.config.endDate.setTime(t.endTime);
            t.show();
        }).bind('mouseup',function(e){
            e.preventDefault();
            t.drag.cT = Date();
            $(t.wrapper).unbind('mousemove').unbind('mouseup').css({'cursor':'default'});
        });
    });
    
    t.config.toolbar = [];
    if(t.config.toolbar.length){
        this.on('toolbarItemClick', function(e,p){
            t.config.toolbar[p.id].callback(t);
        });
        var tbLayer = '<div class="chronoline-toolbar-options">';
        $.each(t.config.toolbar,function(index, item){
            tbLayer += '<a id="'+index+'" href="#" title="'+item.title+'"><i class="'+item.view+'"></i></a>';
        });
        tbLayer += '</div>';
        this.toolbar({'content':tbLayer,'position':'docktop'});
    }

    // 按升序排列事件对象
    // event row描述一种表现形式，保证在同一个层面上不出现事件的重叠
    t.events = events;
    t.events.sort(sortEvents);
   
    var sections = [
        {   
            'name':'project', 
            'ready':false,
            'eventAttr':{ 'fill': '#8CEA00','stroke': '#8CEA00','stroke-width':5,'opacity':'1'}, 
            'draw' :function(event, startX, Y, endX){
                        if(event.element){
                            txt = event.element.pop();
                            rect = event.element.pop();
                            txt.transform('T'+(startX+4)+','+(Y-16));
                            bbox = txt.getBBox();
                            rect.transform('T'+(bbox.x-4)+','+(bbox.y-2));
                            event.element.push(rect,txt);
                            return;
                        }
                        if(this.elements==null)
                            this.elements = t.paper.set();
                        txt = t.paper.text(0, 0, event.title)
                            .attr({'text-anchor':'start','opacity':this.eventAttr.opacity})
                            .transform('T'+(startX+4)+','+(Y-16));
                        bbox = txt.getBBox();
                        rect = t.paper.rect(0,0, bbox.width+8,bbox.height+4)
                            .attr({'fill':'#8CEA00', 'stroke':'white','opacity':this.eventAttr.opacity})
                            .transform('T'+(bbox.x-4)+','+(bbox.y-2));
                        rect.insertBefore(txt);
                        txt.click(function(){
                            alert(event.description);
                        });
                        rect.click(function(){
                            alert(event.description);
                        });
                        elem = t.paper.set().push(rect,txt);
                        this.elements.push(elem);
                        event.element = elem;
                    },
            'events': Array(),
            'visible':true,
            'elements':null
        },
        {   
            'name':'task', 
            'ready':false,
            'eventAttr':{ 'fill': '#FF00FF','stroke': '#FF00FF','stroke-width':5,'opacity':'1' }, 
            'draw' :function(event, startX, Y, endX){
                        if(event.element){
                            txt = event.element.pop();
                            rect = event.element.pop();
                            txt.transform('T'+(startX+4)+','+(Y-16));
                            bbox = txt.getBBox();
                            rect.transform('T'+(bbox.x-4)+','+(bbox.y-2));
                            event.element.push(rect,txt);
                            return;
                        }
                        if(this.elements==null)
                            this.elements = t.paper.set();
                        txt = t.paper.text(0, 0, event.title)
                            .attr({'text-anchor':'start', 'opacity':this.eventAttr.opacity})
                            .transform('T'+(startX+4)+','+(Y-16));
                        bbox = txt.getBBox();
                        rect = t.paper.rect(0,0,bbox.width+8,bbox.height+4)
                            .attr({'fill':'#FF00FF', 'stroke':'white','opacity':this.eventAttr.opacity})
                            .transform('T'+(bbox.x-4)+','+(bbox.y-2));
                        rect.insertBefore(txt);
                        txt.click(function(){
                            alert(event.description);
                        });
                        rect.click(function(){
                            alert(event.description);
                        });
                        elem = t.paper.set().push(rect,txt);
                        this.elements.push(elem);
                        event.element = elem;
                    },
            'events': Array(),
            'visible':true,
            'elements': null
        }
    ];
    t.event_rows = [];
    for(i=0;i<t.events.length;i++){
        var found = false;
        if(t.events[i].section=='task')
            t.events[i].section = sections[1];
        else
            t.events[i].section = sections[0];
        t.events[i].section.events.push(t.events[i]);
        t.events[i].element = null;
        for(j=0;j<t.event_rows.length;j++){
            if(t.event_rows[j][t.event_rows[j].length-1].dates[t.event_rows[j][t.event_rows[j].length-1].dates.length-1].getTime()<t.events[i].dates[0].getTime()){
                found = true;
                t.event_rows[j].push(t.events[i]);
                break;
            }   
        }
        
        if(!found){
            var new_row = new Array();
            new_row.push(t.events[i]);
            t.event_rows.push(new_row);
        }
    }
    
    t.sections = Array();
    for(i=0; i<sections.length;i++){
        if(sections[i].events.length)
            t.sections.push(sections[i]);
    }
   
    // 设置视口的时间区域
    var CalcViewport = function() {
        t.today = new Date();
        t.today = new Date(t.today.getFullYear(), t.today.getMonth(),t.today.getDate());
        if(t.config.startDate == null){
            if(t.events.length)
                t.config.startDate = t.events[0].dates[0];
            else
                t.config.startDate = t.today;

            t.config.startDate = new Date(t.config.startDate.getTime()-t.config.timelinePadding);
        }
        t.startTime = t.config.startDate.getTime();

        if(t.config.endDate == null) {
            if(t.events.length) {
                for(var i=0; i<t.events.length; i++)
                    t.config.endDate = (t.config.endDate==null || (t.config.endDate < t.events[i].dates[t.events[i].dates.length-1])) ? 
                                            t.events[i].dates[t.events[i].dates.length-1] : t.config.endDate;
            }
            else
                t.config.endDate = t.today;
            t.config.endDate = new Date(t.config.endDate.getTime()+t.config.timelinePadding);  
        }
        t.endTime = t.config.endDate.getTime();
    }
    CalcViewport();

    //创建显示部件
    t.myCanvas = document.createElement('div');
    t.myCanvas.className = 'chronoline-canvas';
    t.wrapper.appendChild(t.myCanvas);
    t.paper = Raphael(t.myCanvas, t.domElement.clientWidth, t.domElement.clientHeight);
    t.visibleWidth = t.domElement.clientWidth;
    t.visibleHeight = t.domElement.clientHeight;
    t.axis = t.paper.set();
    
    var DrawEvents = function(startTime, endTime){
        for(var row = 0; row < t.event_rows.length; row++){
            var upperY = t.visibleHeight-t.config.dateLabelHeight-(row)*25;
            for(var col = 0; col < t.event_rows[row].length; col++){
                var event = t.event_rows[row][col];
                if(!event.section.visible)
                    continue;
                if(event.dates.length == 1 || ((event.dates[1].getTime()-event.dates[0].getTime())<t.resolution*DAY)){
                    var startX = msToPx(event.dates[0].getTime());
                    event.section.draw(event,startX,upperY);
                } else {
                    var startX = (event.dates[0].getTime() - t.startTime) * t.pxRatio;
                    var endX = (event.dates[1].getTime() - t.startTime) * t.pxRatio;
                    event.section.draw(event,startX,upperY,endX);
                }
            }
        }
        
        for(i=0;i<t.sections.length;i++){
            t.sections[i].ready = true;
        }
    }

    var DrawTimeAxis = function(startTime, endTime){
        t.axis.remove();
        var dateLineY =t.visibleHeight - t.config.dateLabelHeight;
        t.axis.push(t.paper.path('M0,' + dateLineY + 'L' + t.visibleWidth + ',' + dateLineY).attr('stroke', '#b8b8b8'));
        
        var bottomHashY = dateLineY + t.config.hashLength;
        var labelY = bottomHashY + t.config.fontAttrs['font-size'];
        var subLabelY = bottomHashY + t.config.fontAttrs['font-size'] * 2 + t.config.subLabelMargin;
        var subSubLabelY = subLabelY + t.config.fontAttrs['font-size'] + t.config.subSubLabelMargin;

        if( t.config.markToday ) {
            var x = msToPx(t.today.getTime());
            t.axis.push(t.paper.path('M' + x  + ',0L' + x + ',' + dateLineY).attr(t.config.todayAttrs));
        }

        var endYear = t.config.endDate.getFullYear();
        for(var year = t.config.startDate.getFullYear(); year <= endYear; year++){
            var curDate = new Date(year, 0, 1);
            var x = msToPx(curDate.getTime());
            if(x<0) x=12;
            var subSubLabel = t.paper.text(x, subSubLabelY, formatDate(curDate, '%Y').toUpperCase())
                .attr(t.config.fontAttrs)
                .attr(t.config.subSubLabelAttrs);
            t.axis.push(subSubLabel);
            subSubLabel.data('left-bound', x);
            var endOfYear = new Date(year, 11, 31);
            subSubLabel.data('right-bound',Math.min((endOfYear.getTime() - startTime) * t.pxRatio - 5,t.visibleWidth));

            for(var month=curDate.getMonth();month<12; month++){
                if(year==t.config.startDate.getFullYear() && month<t.config.startDate.getMonth())
                    continue;
                var cd = new Date(year, month, 1);
                var x = msToPx(cd.getTime());
                if(x<0) x=12;
                var subLabel = t.paper.text(x, subLabelY, formatDate(cd, '%b').toUpperCase())
                    .attr(t.config.fontAttrs)
                    .attr(t.config.subLabelAttrs);
                t.axis.push(subLabel);
                subLabel.data('left-bound', x);
                t.axis.push(t.paper.path('M' + x + ',' + dateLineY + 'L' + x + ',' + bottomHashY).attr('stroke', '#b8b8b8'));
            }
        }
       
        for(var curMs = startTime; t.resolution<30 && curMs < endTime; curMs+=DAY){
            var curDate = new Date(curMs);
            var day = curDate.getDate();
            if(t.resolution==5 && day!=1 && day!=5 && day!=10 && day!=15 && day!=20 && day!=25)
                continue;

            curDate = new Date(curDate.getFullYear(), curDate.getMonth(), curDate.getDate());
            var x = msToPx(curDate.getTime());
            t.axis.push(t.paper.path('M' + x + ',' + dateLineY + 'L' + x + ',' + bottomHashY).attr('stroke', '#b8b8b8'));
            var displayDate = String(day);
            if(displayDate.length == 1) displayDate = '0' + displayDate;
            t.axis.push(t.paper.text(x, labelY, displayDate).attr(t.config.fontAttrs));
        }
    }
  
    t.show();
}
