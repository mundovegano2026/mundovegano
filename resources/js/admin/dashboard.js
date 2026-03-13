$('document').ready(function() {
    
    // Visitors chart
    var $chrt_border_color = "#efefef";
    var $chrt_second = "#6595b4";
    var $chrt_fthird = "#7e9d3a";
    
    if ($("#visitors-chart").length) {
        var visitData = [];
        var max = 0;
        
        for (var key in window.visitList) {
            if(max < window.visitList[key]) max = window.visitList[key].length;
            var dateParts = key.split("/");
            visitData.push([new Date(dateParts[2], dateParts[1]-1, dateParts[0]).getTime(), window.visitList[key].length]);
        }
        
        visitData = visitData.sort((a, b) => (a[0] > b[0] ? 1 : -1));
   
        var options = {
            xaxis : {
                mode : "time",
                tickLength : 5,
                tickSize: [1, "day"]
            },
            series : {
                lines : {
                    show : true,
                    lineWidth : 1,
                    fill : true,
                    fillColor : {
                        colors : [{
                            opacity : 0.1
                        }, {
                            opacity : 0.15
                        }]
                    }
                },
                points: { show: true },
                shadowSize : 0
            },
            selection : {
                mode : "x"
            },
            grid : {
                hoverable : true,
                clickable : true,
                tickColor : $chrt_border_color,
                borderWidth : 0,
                borderColor : $chrt_border_color,
            },
            tooltip : true,
            tooltipOpts : {
                content : "Visitantes: <span>%y</span>",
                //dateFormat : "%y-%0m-%0d",
                defaultTheme : false
            },
            colors : [$chrt_second],

        };

        var plot = $.plot($("#visitors-chart"), [visitData], options);
    };
});