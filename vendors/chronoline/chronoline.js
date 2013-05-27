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

        eventAttrs: { width: 5, 
                      height:5,
                      'fill': '#8CEA00','stroke': '#8CEA00','stroke-width': 2
                    },
                
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
    t.events.sort(t.sortEvents);
    
    t.event_rows = [];
    for(i=0;i<t.events.length;i++){
        var found = false;
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
    
    // 设置视口的时间区域
    t.today = new Date();
    t.today = new Date(t.today.getFullYear(), t.today.getMonth(),t.today.getDate());
    if(t.config.startDate == null){
        if(t.events.length)
            t.config.startDate = t.events[0].dates[0];
        else if(t.config.sections.length) 
            t.config.startDate = t.config.sections[0].dates[0];
        else
            t.config.startDate = t.today;

        t.config.startDate = new Date(t.config.startDate.getTime()-t.config.timelinePadding);
        t.config.startDate.setDate(1);
    }
    t.startTime = t.config.startDate.getTime();

    if(t.config.endDate == null) {
        if(t.events.length)
            t.config.endDate = t.events[t.events.length-1].dates[t.events[t.events.length-1].dates.length-1];
        else if(t.config.sections.length)
            t.config.endDate = t.sections[t.config.sections.length-1].dates[t.config.sections[t.config.sections.length-1].dates.length-1];
        else
            t.config.endDate = t.today;
          
        t.config.endDate = new Date(t.config.endDate.getTime()+t.config.timelinePadding);  
    }
    t.endTime = t.config.endDate.getTime();
    
    //创建显示部件
    t.myCanvas = document.createElement('div');
    t.myCanvas.className = 'chronoline-canvas';
    t.wrapper.appendChild(t.myCanvas);
    t.paper = Raphael(t.myCanvas, t.wrapper.clientWidth, t.wrapper.clientHeight);
    t.paperType = t.paper.raphael.type;
    t.paperElem = t.myCanvas.childNodes[0];
    t.visibleWidth = t.domElement.clientWidth;
    t.visibleHeight = t.domElement.clientHeight;
   
    var DrawEvents = function(startTime, endTime){
        var delta = t.config.eventAttrs.width/2;
        var margin = 3;
        for(var row = 0; row < t.event_rows.length; row++){
            var upperY = t.visibleHeight-t.config.dateLabelHeight-(row+1)*(t.config.eventAttrs.height/2+8);
            for(var col = 0; col < t.event_rows[row].length; col++){
                var event = t.event_rows[row][col];
                var elem = null;
                if(event.dates.length == 1 || ((event.dates[1].getTime()-event.dates[0].getTime())<t.resolution*DAY)){
                    var startX = msToPx(event.dates[0].getTime());
                    elem = t.paper.circle(startX-delta, upperY + delta, delta).attr(t.config.eventAttrs);
                    
                } else {
                    var startX = (event.dates[0].getTime() - t.startTime) * t.pxRatio;
                    var leftMark = t.paper.circle(startX-delta, upperY + delta, delta).attr(t.config.eventAttrs);
                    var endX = (event.dates[1].getTime() - t.startTime) * t.pxRatio;
                    var rightMark = t.paper.circle(endX-delta, upperY + delta, delta).attr(t.config.eventAttrs);    
                    addElemClass(t.paperType, leftMark.node, 'chronoline-event');
                    addElemClass(t.paperType, rightMark.node, 'chronoline-event');
                    elem = t.paper.rect(startX-delta, upperY, endX-startX, t.config.eventAttrs.height)
                            .attr({ 'fill': t.config.eventAttrs.fill,
                                    'stroke': t.config.eventAttrs.stroke,
                                    'stroke-width': t.config.eventAttrs['stroke-width']});
                }
                addElemClass(t.paperType, elem.node, 'chronoline-event');
                elem.attr('title', event.title);
                if(t.tooltips && !jQuery.browser.msie){
                    var description = event.description;
                    var title = event.title;
                    if(typeof description == "undefined" || description == ''){
                        description = title;
                        title = '';
                    }
                    jQuery(elem.node).parent().qtip({
                        content: {
                            title: title,
                            text: description
                        },
                        position: {
                            my: 'top left',
                            target: 'mouse',
                            viewport: jQuery(window), // Keep it on-screen at all times if possible
                            adjust: {
                                x: 10,  y: 10
                            }
                        },
                        hide: {
                            fixed: true // Helps to prevent the tooltip from hiding ocassionally when tracking!
                        },
                        style: {
                            classes: 'ui-tooltip-shadow ui-tooltip-dark ui-tooltip-rounded'
                        }
                    });
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
                var cd = new Date(year, month, 1);
                var x = msToPx(cd.getTime());
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
            if(day!=1 && day!=5 && day!=10 && day!=15 && day!=20 && day!=25)
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

/*
    t.drawnStartMs = null;
    t.drawnEndMs = null;
    // this actually draws labels. It calculates the set of labels to draw in-between
    // what it currently has and needs to add
    t.drawLabels = function(leftPxPos){
        var newStartPx = Math.max(0, leftPxPos - t.visibleWidth);
        var newEndPx = Math.min(t.totalWidth, leftPxPos + 2 * t.visibleWidth);

        var newStartDate = new Date(t.pxToMs(newStartPx));
        newStartDate = new Date(Date.UTC(newStartDate.getUTCFullYear(), newStartDate.getUTCMonth(), 1));
        var newStartMs = newStartDate.getTime();
        var newEndDate = stripTime(new Date(t.pxToMs(Math.min(t.totalWidth, leftPxPos + 2 * t.visibleWidth))))
        var newEndMs = newEndDate.getTime();

        if(t.drawnStartMs == null){  // first time
            t.drawnStartMs = newStartMs;
            t.drawnEndMs = newEndMs;
            t.drawLabelsHelper(newStartMs, newEndMs);
        }else if(newStartMs > t.drawnEndMs){  // new labels are to the right
            t.drawLabelsHelper(t.drawnEndMs, newEndMs);
            t.drawnEndMs = newEndMs;
        }else if(newEndMs < t.drawnStartMs){  // to the left
            t.drawLabelsHelper(newStartMs, t.drawnStartMs);
            t.drawnStartMs = newStartMs;
        }else {  // overlap
            if(newStartMs < t.drawnStartMs){
                t.drawLabelsHelper(newStartMs, t.drawnStartMs);
                t.drawnStartMs = newStartMs;
            }
            if(newEndMs > t.drawnEndMs){
                t.drawLabelsHelper(t.drawnEndMs, newEndMs);
                t.drawnEndMs = newEndMs;
            }
        }
    }

    t.isMoving = false;
    t.goToPx = function(finalLeft, isAnimated, isLabelsDrawn) {

        if(t.isMoving) return false;

        isAnimated = typeof isAnimated !== 'undefined' ? isAnimated : t.animated;
        isLabelsDrawn = typeof isLabelsDrawn !== 'undefined' ? isLabelsDrawn : true;

        finalLeft = Math.min(finalLeft, 0);
        finalLeft = Math.max(finalLeft, -t.maxLeftPx);

        if(isLabelsDrawn)
            t.drawLabels(-finalLeft);

        var left = getLeft(t.paperElem);

        // hide scroll buttons if you're at the end
        if(t.scrollable){
            if(finalLeft == 0){
                t.leftControl.style.display = 'none';
                t.isScrolling = false;
            } else {
                t.leftControl.style.display = '';
            }
            if(finalLeft == t.visibleWidth - t.totalWidth){
                t.rightControl.style.display = 'none';
                t.isScrolling = false;
            } else {
                t.rightControl.style.display = '';
            }
        }

        var movingLabels = [];
        var floatedLeft = -finalLeft + 5;
        t.floatingSet.forEach(function(label){
            // pin the to the left side
            if(label.data('left-bound') < floatedLeft && label.data('right-bound') > floatedLeft) {
                movingLabels.push([label, label.attr('x'),
                                   floatedLeft - label.attr('x') + 10]);
            } else if(label.attr('x') != label.data('left-bound')) { // push it to where it should be
                movingLabels.push([label, label.attr('x'),
                                   label.data('left-bound') - label.attr('x')]);
            }
        });

        if(isAnimated){
            t.isMoving = true;

            var start = null;
            var elem = t.paperElem;
            function step(timestamp) {
                if(start == null)
                    start = timestamp;
                var progress = (timestamp - start) / 200;
                var pos = (finalLeft - left) * progress + left;
                elem.style.left = pos + "px";

                // move the labels
                for(var i = 0; i < movingLabels.length; i++){
                    movingLabels[i][0].attr('x', movingLabels[i][2] * progress + movingLabels[i][1]);
                }

                if (progress < 1) {  // keep going
                    requestAnimationFrame(step);
                }else{  // put it in its final position
                    t.paperElem.style.left = finalLeft + "px";
                    for(var i = 0; i < movingLabels.length; i++){
                        movingLabels[i][0].attr('x', movingLabels[i][2] + movingLabels[i][1]);
                    }
                    t.isMoving = false;
                }
            }
            requestAnimationFrame(step);

        }else{  // no animation is just a shift
            t.paperElem.style.left = finalLeft + 'px';
            for(var i = 0; i < movingLabels.length; i++){
                movingLabels[i][0].attr('x', movingLabels[i][2] + movingLabels[i][1]);
            }
        }

        return finalLeft != 0 && finalLeft != -t.maxLeftPx;
    }

    t.goToDate = function(date, position){
        // position is negative for left, 0 for middle, 1 for right
        date = stripTime(date);
        if(position < 0){
            t.goToPx(-t.msToPx(date.getTime()));
        } else if(position > 0){
            t.goToPx(-t.msToPx(date.getTime()) + t.visibleWidth);
        } else {
            t.goToPx(-t.msToPx(date.getTime()) + t.visibleWidth / 2);
        }
    }

    // CREATING THE NAVIGATION
    // this is boring
    if(t.scrollable){
        t.leftControl = document.createElement('div');
        t.leftControl.className = 'chronoline-left';
        t.leftControl.style.marginTop = t.topMargin + 'px';

        var leftIcon = document.createElement('div');
        leftIcon.className = 'chronoline-left-icon';
        t.leftControl.appendChild(leftIcon);
        t.wrapper.appendChild(t.leftControl);
        var controlHeight = Math.max(t.eventsHeight,
                                     t.leftControl.clientHeight);
        t.leftControl.style.height =  controlHeight + 'px';
        leftIcon.style.marginTop = (controlHeight - 15) / 2 + 'px';

        t.rightControl = document.createElement('div');
        t.rightControl.className = 'chronoline-right';
        t.rightControl.style.marginTop = t.topMargin + 'px';

        var rightIcon = document.createElement('div');
        rightIcon.className = 'chronoline-right-icon';
        t.rightControl.appendChild(rightIcon);
        t.wrapper.appendChild(t.rightControl);
        t.rightControl.style.height = t.leftControl.style.height;
        rightIcon.style.marginTop = leftIcon.style.marginTop;

        t.scrollLeftDiscrete = function(e){
            t.goToDate(t.scrollLeft(new Date(t.pxToMs(-getLeft(t.paperElem)))), -1);
            return false;
        };

        t.scrollRightDiscrete = function(e){
            t.goToDate(t.scrollRight(new Date(t.pxToMs(-getLeft(t.paperElem)))), -1);
            return false;
        };

        // continuous scroll
        // left and right are pretty much the same but need to be flipped
        if(t.continuousScroll){
            t.isScrolling = false;
            t.timeoutId = -1;

            t.scrollLeftContinuous = function(timestamp){
                if(t.scrollStart == null)
                    t.scrollStart = timestamp;
                if(t.isScrolling){
                    requestAnimationFrame(t.scrollLeftContinuous);
                }
                var finalLeft = t.continuousScrollSpeed * (timestamp - t.scrollStart) + t.scrollPaperStart;
                t.goToPx(finalLeft, false, finalLeft > - t.msToPx(t.drawnStartMs));
            };

            t.endScrollLeft = function(e){
                clearTimeout(t.scrollTimeoutId);
                if(t.toScrollDiscrete){
                    t.toScrollDiscrete = false;
                    t.scrollLeftDiscrete();
                }
                t.isScrolling = false;
            };

            t.leftControl.onmousedown = function(e){
                t.toScrollDiscrete = true;
                t.scrollPaperStart = getLeft(t.paperElem);
                t.scrollTimeoutId = setTimeout(function(){
                    t.toScrollDiscrete = false;  // switched is flipped
                    t.scrollStart = null;
                    t.isScrolling = true;  // whether it's currently moving
                    requestAnimationFrame(t.scrollLeftContinuous);
                }, 200);
            };
            t.leftControl.onmouseup = t.endScrollLeft;
            t.leftControl.onmouseleave = t.endScrollLeft;


            t.scrollRightContinuous = function(timestamp){
                if(t.scrollStart == null)
                    t.scrollStart = timestamp;
                if(t.isScrolling){
                    requestAnimationFrame(t.scrollRightContinuous);
                }
                var finalLeft = t.continuousScrollSpeed * (t.scrollStart - timestamp) + t.scrollPaperStart;
                t.goToPx(finalLeft, false, finalLeft - t.visibleWidth < - t.msToPx(t.drawnEndMs));
            };

            t.endScrollRight = function(e){
                clearTimeout(t.scrollTimeoutId);
                if(t.toScrollDiscrete){
                    t.toScrollDiscrete = false;
                    t.scrollRightDiscrete();
                }
                t.isScrolling = false;
            };

            t.rightControl.onmousedown = function(e){
                t.toScrollDiscrete = true;
                t.scrollPaperStart = getLeft(t.paperElem);
                t.scrollTimeoutId = setTimeout(function(){
                    t.toScrollDiscrete = false;  // switched is flipped
                    t.scrollStart = null;
                    t.isScrolling = true;  // whether it's currently moving
                    requestAnimationFrame(t.scrollRightContinuous);
                }, 500);
            };
            t.rightControl.onmouseup = t.endScrollRight;
            t.rightControl.onmouseleave = t.endScrollRight;

        } else {  // just hook up discrete scrolling
            t.leftControl.onclick = t.scrollLeftDiscrete;
            t.rightControl.onclick = t.scrollLeftDiscrete;
        }

    }

    // ENABLING DRAGGING
    // i'm not using raphael.js built-in dragging since this is for the entire canvas
    // also, i didn't see that function before I wrote this
    // using jQuery to get mouseleave to work cross-browser
    if(t.draggable){
        t.stopDragging = function(e){
            jQuery(t.wrapper).removeClass('dragging')
                .unbind('mousemove', t.mouseMoved)
                .unbind('mouseleave', t.stopDragging)
                .unbind('mouseup', t.stopDragging);
            t.drawLabels(-getLeft(t.paperElem));
        }

        t.mouseMoved = function(e){
            t.goToPx(t.dragPaperStart - (t.dragMouseStart - e.pageX), false, false);
        }

        t.wrapper.className += ' chronoline-draggable';
        jQuery(t.paperElem).mousedown(function(e){
            e.preventDefault();
            t.dragMouseStart = e.pageX;
            t.dragPaperStart = getLeft(t.paperElem);
            jQuery(t.wrapper).addClass('dragging')
                .bind('mousemove', t.mouseMoved)
                .bind('mouseleave', t.stopDragging)
                .bind('mouseup', t.stopDragging);
        });
    }

    t.goToToday = function(){
        t.goToDate(t.today, 0);
    };

    t.getLeftTime = function(){
        return Math.floor(t.startTime - getLeft(t.paperElem) / t.pxRatio);
    };

    t.getRightTime = function(){
        return Math.floor(t.startTime - (getLeft(t.paperElem) - t.visibleWidth) / t.pxRatio);
    };

    // set the default position
    t.paperElem.style.left = - (t.defaultStartDate - t.startDate) * t.pxRatio + 20 + 'px';
    t.goToPx(getLeft(t.paperElem));
    t.myCanvas.style.height = t.totalHeight + 'px';
    */
}
