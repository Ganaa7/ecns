<?php $this->load->view('header_main.php');?>
    <link rel="stylesheet" src="<?=base_url();?>assets/chart/style.css" type="text/css">
        <script src="<?=base_url();?>assets/chart/amcharts.js" type="text/javascript"></script>
        <script src="<?=base_url();?>assets/chart/serial.js" type="text/javascript"></script>
        <script src="<?=base_url();?>assets/jquery/jquery-2.2.4.js"></script>
        <script src="<?=base_url();?>assets/chart/dataloader.min.js"></script>
        <style type="text/css">
            .amcharts-graph-g1 .amcharts-graph-stroke {
                stroke-dasharray: 3px 3px;
                stroke-linejoin: round;
                stroke-linecap: round;
                -webkit-animation: am-moving-dashes 1s linear infinite;
                animation: am-moving-dashes 1s linear infinite;
            }
            .amcharts-graph-g4 .amcharts-graph-stroke {
                stroke-dasharray: 3px 3px;
                stroke-linejoin: round;
                stroke-linecap: round;
                -webkit-animation: am-moving-dashes 1s linear infinite;
                animation: am-moving-dashes 1s linear infinite;
            }

            @-webkit-keyframes am-moving-dashes {
                100% {
                    stroke-dashoffset: -30px;
                }
            }
            @keyframes am-moving-dashes {
                100% {
                    stroke-dashoffset: -30px;
                }
            }


            .lastBullet {
                -webkit-animation: am-pulsating 1s ease-out infinite;
                animation: am-pulsating 1s ease-out infinite;
            }
            @-webkit-keyframes am-pulsating {
                0% {
                    stroke-opacity: 1;
                    stroke-width: 0px;
                }
                100% {
                    stroke-opacity: 0;
                    stroke-width: 50px;
                }
            }
            @keyframes am-pulsating {
                0% {
                    stroke-opacity: 1;
                    stroke-width: 0px;
                }
                100% {
                    stroke-opacity: 0;
                    stroke-width: 50px;
                }
            }

            .amcharts-graph-column-front {
                -webkit-transition: all .3s .3s ease-out;
                transition: all .3s .3s ease-out;
            }
            .amcharts-graph-column-front:hover {
                fill: #496375;
                stroke: #496375;
                -webkit-transition: all .3s ease-out;
                transition: all .3s ease-out;
            }


            .amcharts-graph-g2 {
              stroke-linejoin: round;
              stroke-linecap: round;
              stroke-dasharray: 500%;
              stroke-dasharray: 0 \0/;    /* fixes IE prob */
              stroke-dashoffset: 0 \0/;   /* fixes IE prob */
              -webkit-animation: am-draw 40s;
              animation: am-draw 40s;
            }
            @-webkit-keyframes am-draw {
                0% {
                    stroke-dashoffset: 500%;
                }
                100% {
                    stroke-dashoffset: 0px;
                }
            }
            @keyframes am-draw {
                0% {
                    stroke-dashoffset: 500%;
                }
                100% {
                    stroke-dashoffset: 0px;
                }
            }




        </style>

        <script>
            // note, we have townName field with a name specified for each datapoint and townName2 with only some of the names specified.
            // we use townName2 to display town names next to the bullet. And as these names would overlap if displayed next to each bullet,
            // we created this townName2 field and set only some of the names for this purpse.
            var chartData = [];   
            var base_url = window.location.origin;
            // Shorthand for $( document ).ready()
            $(function() {
                console.log( "ready!" );
               $.ajax({
                  method: "POST",
                  url: base_url+"/index.php/flog/index/dashboard/1", 
                  dataType: 'json', 
                  async: false
                })
                 .done(function( msg ) {
                    chartData=msg;
                    // chartData.push(msg['nav']);
                    console.log(chartData);
                    
                 });
            });
     
            var chart;

            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.addClassNames = true;
                chart.dataProvider = chartData;
                chart.categoryField = "date";
                chart.dataDateFormat = "YYYY-MM";
                chart.startDuration = 1;
                chart.color = "#000";
                chart.marginLeft = 0;
                chart.hideCredits=true;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;                
                categoryAxis.parseDates = false; // as our data is date-based, we set parseDates to true
                categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
                categoryAxis.autoGridCount = false;
                categoryAxis.gridCount = 50;
                categoryAxis.gridAlpha = 0.1;
                categoryAxis.gridColor = "#000";
                categoryAxis.axisColor = "#555555";
                // we want custom date formatting, so we change it in next line
                categoryAxis.dateFormats = [{
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'MMM DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }];

                // as we have data of different units, we create three different value axes
                // Distance value axis
                var distanceAxis = new AmCharts.ValueAxis();
                distanceAxis.title = "Гэмтлийн тоо";
                distanceAxis.gridAlpha = 0;
                distanceAxis.axisAlpha = 0;
                chart.addValueAxis(distanceAxis);

                // duration value axis
                var durationAxis = new AmCharts.ValueAxis();
                // the following line makes this value axis to convert values to duration
                // it tells the axis what duration unit it should use. mm - minute, hh - hour...
                durationAxis.duration = "mm";
                durationAxis.durationUnits = {
                    DD: "өд. ",
                    hh: "ц ",
                    mm: "м ",
                    ss: ""
                };
                durationAxis.gridAlpha = 0;
                durationAxis.axisAlpha = 0;
                durationAxis.inside = false;
                durationAxis.position = "right";
                durationAxis.title = "Үргэлжилсэн хугацаа";
                chart.addValueAxis(durationAxis);

                // GRAPHS
                // ХОЛБОО
                var distanceGraph = new AmCharts.AmGraph();                
                distanceGraph.valueField = "c_value";
                distanceGraph.title = "Холбоо";
                distanceGraph.type = "column";
                distanceGraph.fillAlphas = 0.9;
                distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
                distanceGraph.balloonText = "[[value]] гэмтэл";
                distanceGraph.legendValueText = "[[value]] гэмтэл";
                distanceGraph.legendPeriodValueText = "нийт: [[value.sum]] гэмтэл";
                distanceGraph.lineColor = "#3F77DE";
                distanceGraph.alphaField = "alpha";
                


                chart.addGraph(distanceGraph);

                 // duration graph
                var durationGraph = new AmCharts.AmGraph();
                durationGraph.id = "g0";
                durationGraph.title = "Холбоо үрг.хуг";
                durationGraph.valueField = "c_duration";
                durationGraph.type = "line";
                durationGraph.valueAxis = durationAxis; // indicate which axis should be used
                durationGraph.lineColor = "#1968F6";
                durationGraph.balloonText = "[[value]]";
                durationGraph.lineThickness = 1;
                durationGraph.legendValueText = "[[value]]";
                durationGraph.bullet = "round";
                durationGraph.bulletBorderColor = "#fff";
                durationGraph.bulletBorderThickness = 1;
                durationGraph.bulletBorderAlpha = 1;
                durationGraph.dashLengthField = "dashLength";
                durationGraph.animationPlayed = true;
                chart.addGraph(durationGraph);

                       
                //Навигаци
                var distanceGraph = new AmCharts.AmGraph();
                distanceGraph.valueField = "n_value";
                distanceGraph.title = "Навигаци ";
                distanceGraph.type = "column";
                distanceGraph.fillAlphas = 0.9;
                distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
                distanceGraph.balloonText = "[[value]] гэмтэл";
                distanceGraph.legendValueText = "[[value]] г";
                distanceGraph.legendPeriodValueText = "нийт: [[value.sum]] гэмтэл";
                distanceGraph.lineColor = "#263138";
                distanceGraph.alphaField = "alpha";
                chart.addGraph(distanceGraph);

                      // duration graph
                var durationGraph = new AmCharts.AmGraph();
                durationGraph.id = "g1";
                durationGraph.title = "Навигацийн үрг.хуг";
                durationGraph.valueField = "n_duration";
                durationGraph.type = "line";
                durationGraph.valueAxis = durationAxis; // indicate which axis should be used
                durationGraph.lineColor = "#263138";
                durationGraph.balloonText = "[[value]]";
                durationGraph.lineThickness = 1;
                durationGraph.legendValueText = "[[value]]";
                durationGraph.bullet = "circle";
                durationGraph.bulletBorderColor = "#fff";
                durationGraph.bulletBorderThickness = 1;
                durationGraph.bulletBorderAlpha = 1;
                durationGraph.dashLengthField = "dashLength";
                durationGraph.animationPlayed = true;
                chart.addGraph(durationGraph);


                //Ажиглалт
                // distance graph
                var distanceGraph = new AmCharts.AmGraph();
                distanceGraph.valueField = "s_value";
                distanceGraph.title = "Ажиглалт";
                distanceGraph.type = "column";
                distanceGraph.fillAlphas = 0.9;
                distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
                distanceGraph.balloonText = "[[value]] гэмтэл";
                distanceGraph.legendValueText = "[[value]] г";
                distanceGraph.legendPeriodValueText = "нийт: [[value.sum]] гэмтэл";
                distanceGraph.lineColor = "#5DB323";
                distanceGraph.alphaField = "alpha";
                chart.addGraph(distanceGraph);

                // duration graph
                var durationGraph = new AmCharts.AmGraph();
                durationGraph.id = "g3";
                durationGraph.title = "Ажиглалт үрг.хуг";
                durationGraph.valueField = "s_duration";
                durationGraph.type = "line";
                durationGraph.valueAxis = durationAxis; // indicate which axis should be used
                durationGraph.lineColor = "#5DB323";
                durationGraph.balloonText = "[[value]]";
                durationGraph.lineThickness = 1;
                durationGraph.legendValueText = "[[value]]";
                durationGraph.bullet = "square";
                durationGraph.bulletBorderColor = "#000";
                durationGraph.bulletBorderThickness = 1;
                durationGraph.bulletBorderAlpha = 1;
                durationGraph.dashLengthField = "dashLength";
                durationGraph.animationPlayed = true;
                chart.addGraph(durationGraph);

                // Гэрэл суулт
                // distance graph
                var distanceGraph = new AmCharts.AmGraph();
                distanceGraph.valueField = "e_value";
                distanceGraph.title = "ГСЦ";
                distanceGraph.type = "column";
                distanceGraph.fillAlphas = 0.9;
                distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
                distanceGraph.balloonText = "[[value]] гэмтэл";
                distanceGraph.legendValueText = "[[value]] гэмтэл";
                distanceGraph.legendPeriodValueText = "нийт: [[value.sum]] гэмтэл";
                distanceGraph.lineColor = "#E80C0C";
                distanceGraph.alphaField = "alpha";
                chart.addGraph(distanceGraph);

                // duration graph
                var durationGraph = new AmCharts.AmGraph();
                durationGraph.id = "g4";
                durationGraph.title = "ГСЦ үрг.хуг";
                durationGraph.valueField = "e_duration";
                durationGraph.type = "line";
                durationGraph.valueAxis = durationAxis; // indicate which axis should be used
                durationGraph.lineColor = "#E80C0C";
                durationGraph.balloonText = "[[value]]";
                durationGraph.lineThickness = 1;
                durationGraph.legendValueText = "[[value]]";
                durationGraph.bullet = "square";
                durationGraph.bulletBorderColor = "#fff";
                durationGraph.bulletBorderThickness = 1;
                durationGraph.bulletBorderAlpha = 1;
                durationGraph.dashLengthField = "dashLength";
                durationGraph.animationPlayed = true;
                chart.addGraph(durationGraph);



                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonDateFormat = undefined;
                chartCursor.cursorAlpha = 0;
                chartCursor.valueBalloonsEnabled = false;
                chartCursor.valueLineBalloonEnabled = true;
                chartCursor.valueLineEnabled = true;
                chartCursor.valueLineAlpha = 0.5;
                chart.addChartCursor(chartCursor);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.bulletType = "round";
                legend.equalWidths = false;
                legend.valueWidth = 120;
                legend.useGraphSettings = true;
                legend.color = "#000";
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");
            });
            
            
            var chart2 = AmCharts.makeChart( "chartdiv2", {
              "type": "serial",
             "theme": "light",
             "hideCredits":true,

              "dataDateFormat": "YYYY-MM",
              "dataLoader": {                
                "url": base_url+"/index.php/flog/index/dashboard/2",
                "format": "json",
                "showErrors": true,
                "noStyles": true,
                "async": true
             },
             
              //"dataProvider": chartData,
              "addClassNames": true,
              "startDuration": 1,
              //"color": "#FFFFFF",
              "marginLeft": 0,

              "categoryField": "date",
              "categoryAxis": {
                "parseDates": false,
                "minPeriod": "DD",
                "autoGridCount": false,
                "gridCount": 50,
                "gridAlpha": 0.1,
                "gridColor": "#000",
                "axisColor": "#555555",
                "dateFormats": [ {
                  "period": 'DD',
                  "format": 'DD'
                }, {
                  "period": 'WW',
                  "format": 'MMM DD'
                }, {
                  "period": 'MM',
                  "format": 'MMM'
                }, {
                  "period": 'YYYY',
                  "format": 'YYYY'
                } ]
              },

              "valueAxes": [ {
                "id": "a1",
                "title": "Дутагдлын тоо",
                "gridAlpha": 0,
                "axisAlpha": 0
              }, {
                "id": "a2",
                "position": "right",
                "gridAlpha": 0,
                "axisAlpha": 0,
                "labelsEnabled": true
              }, {
                "id": "a3",
                "title": "Үргэлжилсэн /t",
                "position": "right",
                "gridAlpha": 0,
                "axisAlpha": 0,
                "inside": true,
                "duration": "mm",                
                "durationUnits": {
                  "DD": "d. ",
                  "hh": "h ",
                  "mm": "min",
                  "ss": ""
                }
              } ],
              "graphs": [ {
                "id": "g1",
                "valueField": "c_value",
                "title": "Холбоо",
                "type": "column",
                "fillAlphas": 0.9,
                "valueAxis": "a1",
                "balloonText": "[[value]] дутагдал",
                "legendValueText": "[[value]] дутагдал",
                "legendPeriodValueText": "Нийт: [[value.sum]] дутагдал",
                "lineColor": "#3F77DE",
                "alphaField": "alpha"
              },
               {
                "id": "g2",
                "title": "Холбоо үр.хуг",
                "valueField": "c_duration",
                "type": "line",
                "valueAxis": "a3",
                "lineColor": "#1968F6",                
                "balloonText": "[[value]]",
                "lineThickness": 1,
                "legendValueText": "[[value]]",
                "bullet": "square",
                "bulletBorderColor": "#1968F6",
                "bulletBorderThickness": 1,
                "bulletBorderAlpha": 1,
                "dashLengthField": "dashLength",
                "showBalloon": true,
                "animationPlayed": true
              },
              {
                "id": "g4",
                "valueField": "n_value",
                "title": "Навигаци",
                "type": "column",
                "fillAlphas": 0.9,
                "valueAxis": "a1",
                "balloonText": "[[value]] дутагдал",
                "legendValueText": "[[value]] дутагдал",
                "legendPeriodValueText": "Нийт: [[value.sum]] дутагдал",
                "lineColor": "#263138",
                "alphaField": "alpha"
              },
                {
                "id": "g1",
                "title": "Навигаци үр.хуг",
                "valueField": "n_duration",
                "type": "line",
                "valueAxis": "a3",
                "lineColor": "#263138",                
                "balloonText": "[[value]]",
                "lineThickness": 1,
                "legendValueText": "[[value]]",
                "bullet": "square",
                "bulletBorderColor": "#263138",
                "bulletBorderThickness": 1,
                "bulletBorderAlpha": 1,
                "dashLengthField": "dashLength",
                "showBalloon": true,
                "animationPlayed": true
              },
               { // Ажиглалт
                "id": "g5",
                "valueField": "s_value",
                "title": "Ажиглалт",
                "type": "column",
                "fillAlphas": 0.9,
                "valueAxis": "a1",
                "balloonText": "[[value]] дутагдал",
                "legendValueText": "[[value]] дутагдал",
                "legendPeriodValueText": "Нийт: [[value.sum]] дутагдал",
                "lineColor": "#5DB323",
                "alphaField": "alpha"
              },
                {
                "id": "g3",
                "title": "Ажиглалт үр.хуг",
                "valueField": "s_duration",
                "type": "line",
                "valueAxis": "a3",
                "lineColor": "#5DB323",                
                "balloonText": "[[value]]",
                "lineThickness": 1,
                "legendValueText": "[[value]]",
                "bullet": "square",
                "bulletBorderColor": "#000",
                "bulletBorderThickness": 1,
                "bulletBorderAlpha": 1,
                "dashLengthField": "dashLength",
                "showBalloon": true,
                "animationPlayed": true
              },
                { // ГСЦ
                "id": "g6",
                "valueField": "e_value",
                "title": "ГСЦ",
                "type": "column",
                "fillAlphas": 0.9,
                "valueAxis": "a1",
                "balloonText": "[[value]] дутагдал",
                "bulletSizeField": "value",
                "legendValueText": "[[value]] дутагдал",
                "legendPeriodValueText": "Нийт: [[value.sum]] дутагдал",
                "lineColor": "#E80C0C",
                "alphaField": "alpha"
              },
                  {
                "id": "g4",
                "title": "ГСЦ үр.хуг",
                "valueField": "s_duration",
                "type": "line",
                "valueAxis": "a3",
                "lineColor": "#E80C0C",                
                "balloonText": "[[value]]",
                "lineThickness": 1,
                "legendValueText": "[[value]]",
                "bullet": "square",
                "bulletBorderColor": "#fff",
                "bulletBorderThickness": 1,
                "bulletBorderAlpha": 1,
                "dashLengthField": "dashLength",
                "showBalloon": true,
                "animationPlayed": true
              }
             ],
            

              "chartCursor": {
                "zoomable": true,
                "categoryBalloonDateFormat": "DD",
                "cursorAlpha": 0,                
                "valueBalloonsEnabled": true,

                "valueLineBalloonEnabled": true,
                "valueLineEnabled":true,
                "valueLineAlpha": 0.5
              },
              "legend": {
                "bulletType": "round",
                "equalWidths": false,
                "valueWidth": 120,
                "useGraphSettings": true,
                //"color": "#FFFFFF"
              }
            } );

        </script>
   
   
        <div style="text-align:center">
            <h3 style="margin-top:20px; margin-left: 10px;">Гэмтлийн график</h3>
        </div>
        <div id="chartdiv" style="width:100%; height:400px;"></div>
        <br/>
        <br/>
        <div style="text-align:center">
        <h3 style="margin-top:20px; margin-left: 10px;">Дутагдлын график</h3>
        </div>
        <div id="chartdiv2" style="width:100%; height:400px;"></div>
    </div>
    </body>
    </html>

    