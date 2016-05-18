
/*Wrapper for noConflict()*/
jQuery(document).ready(function($) {

    /**
     * Donut chart
     */
    $(function(){
        var $ppc = $('.progress-pie-chart'),
          percent = parseInt($ppc.data('percent')),
          deg = 360*percent/100;
        if (percent > 50) {
          $ppc.addClass('gt-50');
        }
        $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
        $('.ppc-percents span').html(percent+'%');
    });

    /**
     * Pie chart with tooltip
     */
    
    $(function(){
    $("#pieChart").drawPieChart([
      { title: "Cash",              value : 40,   color: "#7ED6CB" },
      { title: "Fixed Interest",    value:  8,    color: "#7ECB1D" },
      { title: "Property",          value:  24,   color: "#ED7D58" },
      { title: "Australian shares", value : 16,   color: "#FEB228" },
      { title: "Intl. shares",      value : 12,   color: "#BFB8AE" }
    ]);
    });

    /*!
     * jquery.drawPieChart.js
     * Version: 0.3(Beta)
     * Inspired by Chart.js(http://www.chartjs.org/)
     *
     * Copyright 2013 hiro
     * https://github.com/githiro/drawPieChart
     * Released under the MIT license.
     */
    ;(function($, undefined) {
      $.fn.drawPieChart = function(data, options) {
        var $this = this,
          W = $this.width(),
          H = $this.height(),
          centerX = W/2,
          centerY = H/2,
          cos = Math.cos,
          sin = Math.sin,
          PI = Math.PI,
          settings = $.extend({
            segmentShowStroke : true,
            segmentStrokeColor : "#fff",
            segmentStrokeWidth : 1,
            baseColor: "#fff",
            baseOffset: 15,
            edgeOffset: 25,//offset from edge of $this
            pieSegmentGroupClass: "pieSegmentGroup",
            pieSegmentClass: "pieSegment",
            lightPiesOffset: 12,//lighten pie's width
            lightPiesOpacity: 1,//lighten pie's default opacity
            lightPieClass: "lightPie",
            animation : true,
            animationSteps : 90,
            animationEasing : "easeInOutExpo",
            tipOffsetX: -15,
            tipOffsetY: -45,
            tipClass: "pieTip",
            beforeDraw: function(){  },
            afterDrawed : function(){  },
            onPieMouseenter : function(e,data){  },
            onPieMouseleave : function(e,data){  },
            onPieClick : function(e,data){  }
          }, options),
          animationOptions = {
            linear : function (t){
              return t;
            },
            easeInOutExpo: function (t) {
              var v = t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t;
              return (v>1) ? 1 : v;
            }
          },
          requestAnimFrame = function(){
            return window.requestAnimationFrame ||
              window.webkitRequestAnimationFrame ||
              window.mozRequestAnimationFrame ||
              window.oRequestAnimationFrame ||
              window.msRequestAnimationFrame ||
              function(callback) {
                window.setTimeout(callback, 1000 / 60);
              };
          }();

        var $wrapper = $('<svg width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>').appendTo($this);
        var $groups = [],
            $pies = [],
            $lightPies = [],
            easingFunction = animationOptions[settings.animationEasing],
            pieRadius = Min([H/2,W/2]) - settings.edgeOffset,
            segmentTotal = 0;

        //Draw base circle
        var drawBasePie = function(){
          var base = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
          var $base = $(base).appendTo($wrapper);
          base.setAttribute("cx", centerX);
          base.setAttribute("cy", centerY);
          base.setAttribute("r", pieRadius+settings.baseOffset);
          base.setAttribute("fill", settings.baseColor);
        }();

        //Set up pie segments wrapper
        var pathGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        var $pathGroup = $(pathGroup).appendTo($wrapper);
        $pathGroup[0].setAttribute("opacity",0);

        //Set up tooltip
        var $tip = $('<div class="' + settings.tipClass + '" />').appendTo('body').hide(),
          tipW = $tip.width(),
          tipH = $tip.height();

        for (var i = 0, len = data.length; i < len; i++){
          segmentTotal += data[i].value;
          var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
          g.setAttribute("data-order", i);
          g.setAttribute("class", settings.pieSegmentGroupClass);
          $groups[i] = $(g).appendTo($pathGroup);
          $groups[i]
            .on("mouseenter", pathMouseEnter)
            .on("mouseleave", pathMouseLeave)
            .on("mousemove", pathMouseMove)
            .on("click", pathClick);

          var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
          p.setAttribute("stroke-width", settings.segmentStrokeWidth);
          p.setAttribute("stroke", settings.segmentStrokeColor);
          p.setAttribute("stroke-miterlimit", 2);
          p.setAttribute("fill", data[i].color);
          p.setAttribute("class", settings.pieSegmentClass);
          $pies[i] = $(p).appendTo($groups[i]);

          var lp = document.createElementNS('http://www.w3.org/2000/svg', 'path');
          lp.setAttribute("stroke-width", settings.segmentStrokeWidth);
          lp.setAttribute("stroke", settings.segmentStrokeColor);
          lp.setAttribute("stroke-miterlimit", 2);
          lp.setAttribute("fill", data[i].color);
          lp.setAttribute("opacity", settings.lightPiesOpacity);
          lp.setAttribute("class", settings.lightPieClass);
          $lightPies[i] = $(lp).appendTo($groups[i]);
        }

        settings.beforeDraw.call($this);
        //Animation start
        triggerAnimation();

        function pathMouseEnter(e){
          var index = $(this).data().order;
          $tip.text(data[index].title + ": " + data[index].value).fadeIn(200);
          if ($groups[index][0].getAttribute("data-active") !== "active"){
            $lightPies[index].animate({opacity: .8}, 180);
          }
          settings.onPieMouseenter.apply($(this),[e,data]);
        }
        function pathMouseLeave(e){
          var index = $(this).data().order;
          $tip.hide();
          if ($groups[index][0].getAttribute("data-active") !== "active"){
            $lightPies[index].animate({opacity: settings.lightPiesOpacity}, 100);
          }
          settings.onPieMouseleave.apply($(this),[e,data]);
        }
        function pathMouseMove(e){
          $tip.css({
            top: e.pageY + settings.tipOffsetY,
            left: e.pageX - $tip.width() / 2 + settings.tipOffsetX
          });
        }
        function pathClick(e){
          var index = $(this).data().order;
          var targetGroup = $groups[index][0];
          for (var i = 0, len = data.length; i < len; i++){
            if (i === index) continue;
            $groups[i][0].setAttribute("data-active","");
            $lightPies[i].css({opacity: settings.lightPiesOpacity});
          }
          if (targetGroup.getAttribute("data-active") === "active"){
            targetGroup.setAttribute("data-active","");
            $lightPies[index].css({opacity: .8});
          } else {
            targetGroup.setAttribute("data-active","active");
            $lightPies[index].css({opacity: 1});
          }
          settings.onPieClick.apply($(this),[e,data]);
        }
        function drawPieSegments (animationDecimal){
          var startRadius = -PI/2,//-90 degree
              rotateAnimation = 1;
          if (settings.animation) {
            rotateAnimation = animationDecimal;//count up between0~1
          }

          $pathGroup[0].setAttribute("opacity",animationDecimal);

          //draw each path
          for (var i = 0, len = data.length; i < len; i++){
            var segmentAngle = rotateAnimation * ((data[i].value/segmentTotal) * (PI*2)),//start radian
                endRadius = startRadius + segmentAngle,
                largeArc = ((endRadius - startRadius) % (PI * 2)) > PI ? 1 : 0,
                startX = centerX + cos(startRadius) * pieRadius,
                startY = centerY + sin(startRadius) * pieRadius,
                endX = centerX + cos(endRadius) * pieRadius,
                endY = centerY + sin(endRadius) * pieRadius,
                startX2 = centerX + cos(startRadius) * (pieRadius + settings.lightPiesOffset),
                startY2 = centerY + sin(startRadius) * (pieRadius + settings.lightPiesOffset),
                endX2 = centerX + cos(endRadius) * (pieRadius + settings.lightPiesOffset),
                endY2 = centerY + sin(endRadius) * (pieRadius + settings.lightPiesOffset);
            var cmd = [
              'M', startX, startY,//Move pointer
              'A', pieRadius, pieRadius, 0, largeArc, 1, endX, endY,//Draw outer arc path
              'L', centerX, centerY,//Draw line to the center.
              'Z'//Cloth path
            ];
            var cmd2 = [
              'M', startX2, startY2,
              'A', pieRadius + settings.lightPiesOffset, pieRadius + settings.lightPiesOffset, 0, largeArc, 1, endX2, endY2,//Draw outer arc path
              'L', centerX, centerY,
              'Z'
            ];
            $pies[i][0].setAttribute("d",cmd.join(' '));
            $lightPies[i][0].setAttribute("d", cmd2.join(' '));
            startRadius += segmentAngle;
          }
        }

        var animFrameAmount = (settings.animation)? 1/settings.animationSteps : 1,//if settings.animationSteps is 10, animFrameAmount is 0.1
            animCount =(settings.animation)? 0 : 1;
        function triggerAnimation(){
          if (settings.animation) {
            requestAnimFrame(animationLoop);
          } else {
            drawPieSegments(1);
          }
        }
        function animationLoop(){
          animCount += animFrameAmount;//animCount start from 0, after "settings.animationSteps"-times executed, animCount reaches 1.
          drawPieSegments(easingFunction(animCount));
          if (animCount < 1){
            requestAnimFrame(arguments.callee);
          } else {
            settings.afterDrawed.call($this);
          }
        }
        function Max(arr){
          return Math.max.apply(null, arr);
        }
        function Min(arr){
          return Math.min.apply(null, arr);
        }
        return $this;
      };
    })(jQuery);

    








    /**
     * Polar chart
     * <canvas id='polarChart'
     */
    
    var usr_color = 160; //Change value to change color scheme


    var canvas = document.getElementById('polarChart');
    var ctx = canvas.getContext("2d");

    var w = 220, h = 220;
    canvas.width = w;
    canvas.height = h;

    var arcs = [];

    function init(){
      console.log("init called");
      reset();
      arcs = [];
      var m = new arc();    //innersta
      m.class = "month";
      arcs.push(m);
      
      var d = new arc();
      d.class = "date";
      d.r = 46.7;        //135
      arcs.push(d);
      
      var d = new arc();
      d.class = "day";
      d.r = 61.4;        //170
      arcs.push(d);
      
      var h = new arc();
      h.class = "hours";
      h.r = 76.1;        //205
      arcs.push(h);
      
      var m = new arc();
      m.class = "mins";
      m.r = 90.8;        //240
      arcs.push(m);
      
    }

    function arc(){
      this.class = "month";
      this.r = 32;         //100
      this.rot = 1;
      
      this.draw = function(){
        ctx.beginPath();
        ctx.arc(116,116,this.r,(Math.PI/(2/3)),this.rot,false);       //300, 300
        ctx.lineWidth = 12;
        ctx.strokeStyle = "#7ECB1D";            //ctx.strokeStyle = "hsla("+(this.rot*(180/Math.PI)+usr_color)+",60%,50%,1)";
        ctx.stroke();
        
        ctx.save();
        ctx.fillStyle = "#000";
        ctx.translate(116, 116);
        ctx.rotate(this.rot - 45);
        ctx.font="8px Arial";
        /*if(this.class == "secs"){
          var d = new Date();
          ctx.fillText(d.getSeconds(), 134, -5);      //267
        }*/
        if(this.class == "mins"){          //skriver ut text för procentsats
          //var d = new Date();
          ctx.fillText('95%', 32.69, 40);      //233
        //ctx.stroke();
        }
        else if(this.class == "hours"){
          //var d = new Date();
          ctx.fillText('75%', 2.74, -5);        //197
        }
        else if(this.class == "day"){
          ctx.fillText('50%', 6.36, -5);       //158
        }
        else if(this.class == "date"){
          //var d = new Date();
          ctx.fillText('25%', 6.88, -5);  //ctx.fillText(d.getDate(), 64, -5);       //127
        }
        else if(this.class == "month"){
          //var d = new Date();
          ctx.fillText('9%', 98.74, -5);     //97    innerst
        }
        ctx.restore();
      }
    }

    function reset(){
      //ctx.fillStyle = "#fff";
      //ctx.fillRect(0,0,w,h);
    }

    function draw(){
      reset();
      ctx.fillStyle = "#7ECB1D";           //färg på beskrivande text
      ctx.font = "9px Arial"
      ctx.fillText("Mycket Bra", 67, 29);  //1a talet fr vä, 2a talet uppifrån           252, 63
      ctx.fillText("Bra", 98, 43);   //264, 98
      ctx.fillText("Ok", 101, 57);     //274, 134
      ctx.fillText("Dåligt", 89, 72);     //270, 168
      ctx.fillText("Mycket Dåligt", 57, 88);      //260, 201    innerst
      
      for(var i=0;i<arcs.length;i++){               //ritar ut själva bågens längd (% av cirkeln)
        var a = arcs[i];
        //var d = new Date();
        if(a.class == "month"){   //innerst?
          var n = 9;        //hämta från db                                              //var n = d.getMonth()+1;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);           //a.rot = (n/12)*(Math.PI*2) - (Math.PI/2);
        }
        else if(a.class == "date"){
          var n = 25;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);
        }
        else if(a.class == "day"){
          var n = 50;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);
        }
        else if(a.class == "hours"){
          var n = 75;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);
        }
        else if(a.class == "mins"){                     //yttersta jag anv
          var n = 95;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);
        }
        /*else if(a.class == "secs"){
          var n = 25;
          a.rot = (n/100)*(Math.PI*2) - (Math.PI/2);
        }*/
        a.draw();
      }
    }

    init();
    draw();




    /**
     * Bar vertical
     * class verticalChart
     */
    $(function(){
        var labels = ['mycket dåligt', 'dåligt', 'ok', 'bra', 'mycket bra'];
        var i = 0;        

            $('.verticalBar').each(function() {

                var height = parseInt($(this).data('height'));     //set height of each bar
                $(this).css("width", height);

                   
                if (((i==0 || i==4) && height < 81) || height < 70) {               //place label after or on top of bar
                    $(this).after('<div class="text-left">'+labels[i]+'</div>');
                }

                else {
                    $(this).prepend('<span class="bartext">'+labels[i]+'</span>');
                }
                i++;
            //}
            });
    });





    /**
     * Bar horizontal
     * class horizontalChart
     */
    
    $(function(){
        var labels = ['mycket dåligt', 'dåligt', 'ok', 'bra', 'mycket bra'];
        var i = 0;        

            $('.horizontalBar').each(function() {

                var width = parseInt($(this).data('width'));     //set width of each bar
                $(this).css("width", width);

                   
                if (((i==0 || i==4) && width < 81) || width < 70) {               //place label after or on top of bar
                    $(this).after('<div class="text-left">'+labels[i]+'</div>');
                }

                else {
                    $(this).prepend('<span class="bartext">'+labels[i]+'</span>');
                }
                i++;
            //}
            });
    });
    
    






});