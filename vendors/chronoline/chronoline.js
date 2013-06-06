// chronoline.js
// by Kevin Leung for Zanbato, https://zanbato.com
// MIT license at https://github.com/StoicLoofah/chronoline.js/blob/master/LICENSE.md

$.fn.Chronoline = function (events, options) {
        
    var addElemClass = function(paperType, node, newClass){
        if(paperType == 'SVG'){
            node.setAttribute('class', newClass);
        } else {
            node.className += ' ' + newClass
        }
    }
    
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
        t.paper.clear();
        DrawTimeAxis(t.startTime,t.endTime);
        DrawEvents(t.startTime, t.endTime);
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
        t.show();
    }
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
            'eventAttr':{ 'fill': '#8CEA00','stroke': '#8CEA00','stroke-width': 5 }, 
            'draw' :function(event, startX, Y, endX){
                        Y += 2.5;
                        elem = t.paper.circle(startX, Y, 2.5).attr(this.eventAttr);
                        addElemClass(t.paperType, elem.node, 'chronoline-event');
                        elem.attr('title', "");
                        $(elem.node.parentNode).qtip({
                            content:
                                {
                                    title: 
                                        {
                                            text:event.dates[0].toDateString()
                                        },
                                    text: event.description
                                },
                            position:
                                {
                                    my: 'top left',
                                    at: 'right top',
                                    adjust: {x:6,y:6}
                                },
                            /*show:
                                {
                                    event: false,
                                    ready: true
                                },
                            hide: false,*/
                            style:
                                {
                                    classes: 'qtip-shadow qtip-dark'
                                }
                        });
                        if(typeof endX!='undefined'){
                            elem = t.paper.circle(endX, Y, 2.5).attr(this.eventAttr);
                            addElemClass(t.paperType, elem.node, 'chronoline-event');
                            elem.attr('title', event.description);
                            t.paper.path('M'+startX+', '+Y+'L'+endX+','+Y).attr({'fill': '#8CEA00','stroke': '#8CEA00','stroke-width': 5});
                        }
                    },
            'events': Array(),
            'visible':true
        },
        {   
            'name':'task', 
            'eventAttr':{ 'fill': '#FF00FF','stroke': '#FF00FF','stroke-width': 5 }, 
            'draw' :function(event, startX, Y, endX){
                        startX -= 2.5;
                        elem = t.paper.rect(startX, Y, 5, 5).attr(this.eventAttr);
                        addElemClass(t.paperType, elem.node, 'chronoline-event');
                        elem.attr('title', "");
                        $(elem.node.parentNode).qtip({
                            content:
                                {
                                    title: 
                                        {
                                            text:event.dates[0].toDateString()
                                        },
                                    text: event.description
                                },
                            position:
                                {
                                    my: 'top left',
                                    adjust: {x:10,y:10},
                                    at: 'right top'
                                },
                            /*show:
                                {
                                    event: false,
                                    ready: true
                                },
                            hide: false,*/
                            style:
                                {
                                    classes: 'qtip-shadow qtip-dark'
                                }
                        });
                        if(typeof endX!='undefined'){
                            endX -= 2.5;
                            elem = t.paper.rect(startX, Y, 5, 5).attr(this.eventAttr);
                            addElemClass(t.paperType, elem.node, 'chronoline-event');
                            elem = t.paper.rect(endX, Y, 5, 5).attr(this.eventAttr);
                            addElemClass(t.paperType, elem.node, 'chronoline-event');
                            elem = t.paper.path('M'+startX+', '+Y+'L'+endX+','+Y).attr(this.eventAttr);
                            addElemClass(t.paperType, elem.node, 'chronoline-event');
                        }
                    },
            'events': Array(),
            'visible':true
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
    t.paperType = t.paper.raphael.type;
    t.paperElem = t.myCanvas.childNodes[0];
    t.visibleWidth = t.domElement.clientWidth;
    t.visibleHeight = t.domElement.clientHeight;
   
    var DrawEvents = function(startTime, endTime){
        for(var row = 0; row < t.event_rows.length; row++){
            var upperY = t.visibleHeight-t.config.dateLabelHeight-(row+1)*16;
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
    }

    var DrawTimeAxis = function(startTime, endTime){
        var dateLineY =t.visibleHeight - t.config.dateLabelHeight;
        var baseline = t.paper.path('M0,' + dateLineY + 'L' + t.visibleWidth + ',' + dateLineY);
        baseline.attr('stroke', '#b8b8b8');
        
        var bottomHashY = dateLineY + t.config.hashLength;
        var labelY = bottomHashY + t.config.fontAttrs['font-size'];
        var subLabelY = bottomHashY + t.config.fontAttrs['font-size'] * 2 + t.config.subLabelMargin;
        var subSubLabelY = subLabelY + t.config.fontAttrs['font-size'] + t.config.subSubLabelMargin;

        if( t.config.markToday ) {
            var x = msToPx(t.today.getTime());
            var line = t.paper.path('M' + x  + ',0L' + x + ',' + dateLineY);
            line.attr(t.config.todayAttrs);
        }

        var endYear = t.config.endDate.getFullYear();
        for(var year = t.config.startDate.getFullYear(); year <= endYear; year++){
            var curDate = new Date(year, 0, 1);
            var x = msToPx(curDate.getTime());
            if(x<0) x=12;
            var subSubLabel = t.paper.text(x, subSubLabelY, formatDate(curDate, '%Y').toUpperCase());
            subSubLabel.attr(t.config.fontAttrs);
            subSubLabel.attr(t.config.subSubLabelAttrs);
            
            subSubLabel.data('left-bound', x);
            var endOfYear = new Date(year, 11, 31);
            subSubLabel.data('right-bound',Math.min((endOfYear.getTime() - startTime) * t.pxRatio - 5,t.visibleWidth));

            for(var month=curDate.getMonth();month<12; month++){
                if(year==t.config.startDate.getFullYear() && month<t.config.startDate.getMonth())
                    continue;
                var cd = new Date(year, month, 1);
                var x = msToPx(cd.getTime());
                if(x<0) x=12;
                var subLabel = t.paper.text(x, subLabelY, formatDate(cd, '%b').toUpperCase());
                subLabel.attr(t.config.fontAttrs);
                subLabel.attr(t.config.subLabelAttrs);
                subLabel.data('left-bound', x);
                var hash = t.paper.path('M' + x + ',' + dateLineY + 'L' + x + ',' + bottomHashY);
                hash.attr('stroke', '#b8b8b8');
            }
        }
       
        for(var curMs = startTime; t.resolution<30 && curMs < endTime; curMs+=DAY){
            var curDate = new Date(curMs);
            var day = curDate.getDate();
            if(t.resolution==5 && day!=1 && day!=5 && day!=10 && day!=15 && day!=20 && day!=25)
                continue;

            curDate = new Date(curDate.getFullYear(), curDate.getMonth(), curDate.getDate());
            var x = msToPx(curDate.getTime());
            var hash = t.paper.path('M' + x + ',' + dateLineY + 'L' + x + ',' + bottomHashY);
            hash.attr('stroke', '#b8b8b8');
            var displayDate = String(day);
            if(displayDate.length == 1) displayDate = '0' + displayDate;
            var label = t.paper.text(x, labelY, displayDate);
            label.attr(t.config.fontAttrs);
        }
    }
  
    t.show();
}
