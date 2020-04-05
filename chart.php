
    <script type="text/javascript">
        // configure for module loader


        var humidity_soil_table = []; 
        var time_table=[]; 

        function draw(data, num, humidity_soil_table, time_table) {


        require.config({
            paths: {
                echarts: 'http://echarts.baidu.com/build/dist'
            }
        });
        
        // use
        require(
            [
                'echarts',
                'echarts/chart/line' // require the specific chart type
            ],
            function (ec) {
                // Initialize after dom ready
                var myChart = ec.init(document.getElementById('main'+num)); 
                option = {
    title : {
        text: 'Wilgotność gleby',
        subtext: 'Wykres prezentuje ostatnie 24h.'
    },
    tooltip : {
        trigger: 'axis'
    },

    toolbox: {
        show : false,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            boundaryGap : false,
            data : time_table
        }
    ],
    yAxis : [
        {
            type : 'value',
            axisLabel : {
                formatter: '{value} %'
            }
        }
    ],
    series : [
        
        {
            name:'Wilgotność gleby '+ num ,
            type:'line',
            data:humidity_soil_table,
            
        }
    ]
};
                 //console.log (data);   
        
                // Load data into the ECharts instance 
                myChart.setOption(option); 
            }
        );
    }
    </script>
<script>
        $(document).ready(function(){
            var num = "<?php echo $number_humidity_plant ?>";
            //console.log(num);
            $.ajax({
                url: 'fetch.php?ID_plant=<?php echo $number_humidity_plant?>',
                type: 'get',
                dataType: 'JSON',
                success: function(response){

                    var len = response.length;

                    humidity_soil_table = [];
                    time_table = [];

                    for(var i=0; i<len; i++){
                        humidity_soil_table[i] = response[i].humidity_soil;
                        time_table[i] = response[i].time;
                    }
                    draw(response, num, humidity_soil_table, time_table);
                   //console.log(humidity_soil_table);
                }
            });
        });
    </script>
    
