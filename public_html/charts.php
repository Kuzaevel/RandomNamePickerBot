<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NOVOMET - SELPRO CHART</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

</head>

<body>

<?php
$settings = include 'settings.php';
$settings = $settings['db'];
$dbConection = new PDO($settings['driver'].":host=" . $settings['host'] . ";dbname=" . $settings['dbname'],$settings['user'], $settings['pass']);
$dbConection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT id,name FROM stages_ppd ORDER BY id";
$stmt = $dbConection->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array());
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" style="margin-left: 50px;">Построение графиков</h1>
            <div style="margin-left: 50px;margin-bottom: 50px;">
                <label for="select_stage">Выберите ступень:</label>
                <select class="select_stage">
                    <?php
                    foreach ($arr as $key=>$val) { ?>
                        <option value="<?=$val['id']?>"><?=$val['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="container" style="width: 720px; height:720px;"></div>
            <div id="container3D" style="width:720px; height:600px;"></div>
        </div>
    </div>
</div>

<script src="jquery/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<script src="Highcharts/code/highcharts.js"></script>
<script src="Highcharts/code/modules/data.js"></script>
<script src="Highcharts/code/modules/series-label.js"></script>
<!--<script src="Highcharts/code/modules/exporting.js"></script>-->
<!--<script src="Highcharts/code/modules/export-data.js"></script>-->

<script src="Highcharts/code/highcharts-3d.js"></script>

<script type="application/javascript">

    function init (id) {
        var hpoly;
        var npoly;
        var epoly;
        $.post('data.php',{'id':id}, function (data) {

            var data = $.parseJSON(data);
            hpoly = data['hpoly'];
            npoly = data['npoly'];
            epoly = data['epoly'];
//            console.log(hpoly);

            /* if(data['success']) {
                 location.reload();
             } else {
                 alert(data['data']);
             }*/


            // var myChart = Highcharts.chart('container', {
            $('#container').highcharts({
                chart: {
                    type: 'line',
                    //type: 'area'
                },
                title: {
                    text: 'График зависимостей'
                },
                xAxis: {
                    lineWidth: 2,
                    lineColor: 'black',
                    labels:{
                        rotation: -45,
                        style: {
                            color: "black"
                        }
                    },
                    title: {
                        text: "Подача, м3/сут",
                        align: "low",
                        color: "red",
                        style: {
                            color: "black",
                            fontWeight: 'bold'
                        }
                    },
                    plotBands: [{
                        color: '#FCFFC5',
                        from: 1400,
                        to: 2600,
                        label: {
                            text: 'Needed band',
                            align: 'center',
                            x: 0,
                            y: -10,
                            style: {
                                fontWeight: 'bold'
                            }
                        }
                    }
                    ],
                    plotLines: [{
                        color: 'black',
                        width: 3,
                        value: 1400,
                        dashStyle: 'dash',
                        label: {
                            text: 'QMinimum',
                            align: 'left',
                            x: -15,
                            y: -10
                        }
                    },
                        {
                            color: 'black',
                            width: 3,
                            value: 2600,
                            dashStyle: 'dash',
                            label: {
                                text: 'QMaximum',
                                align: 'left',
                                x: 5,
                                y: -10
                            }
                        }
                    ],
                    /// выделение по x
                    crosshair: {
                        snap: true
                    }
                },
                yAxis:  [{
                    lineWidth: 2,
                    lineColor: 'red',
                    title: {
                        text: 'Номинал TDH (метр)',
                        //text: null
                        style: {
                            color: "red"
                        },
                        offset: 20
                    },
                    crosshair: {
                        snap: false
                    },
                    labels: {
                        style: {
                            color: 'red'
                        },
                        rotation: -90,
                        x: -5
                    }
                },
                    {
                        lineWidth: 2,
                        lineColor: 'blue',
                        offset: 10,
                        title: {
                            text: 'Мощность (кВт)',
                            offset: -10,
                            rotation: -90,
                            //text: null
                            style: {
                                color: "blue"
                            }
                        },
                        max: 5,
                        min: 0,
                        opposite: true,
                        labels: {
                            style: {
                                color: "blue"
                            },
                            rotation: -90
                        }
                    },
                    {
                        lineWidth: 2,
                        lineColor: 'green',
                        offset: 50,
                        max: 100,
                        min: 0,
                        title: {
                            text: 'КПД, %',
                            offset: -10,
                            style: {
                                color: 'green'
                            },
                            rotation: -90
                        },
                        labels: {
                            style: {
                                color: "green"
                            },
                            rotation: -90
                        },
                        //gridLineWidth: 2,
                        opposite: true
                    }],
                // series: [
                //     {data: [129.9, 71.5, 106.4, 19.2, 14.0, 76.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]},
                //     {data: [100, 110, 120, 10, 14.0, 100, 80, 120, 200, 200, 90, 50]},
                //     {data: [129.9, 271.5, 306.4, 129.2, 444.0, 376.0, 435.6, 348.5, 216.4, 294.1, 35.6, 354.4], yAxis: 1}]
                series: [{
                    name: 'Head',
                    data: hpoly,
                    /*pointStart: 1600,
                    pointInterval: 10,*/
                    //dashStyle: 'dash',
                    color: 'red' },
                    {
                        name: 'Power',
                        data: npoly,
                        color: 'blue',
                        yAxis: 1
                    },
                    {
                        name: 'Efficiency',
                        data: epoly,
                        color: 'green',
                        yAxis: 2
                    }]
            });

        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        init(1);


        $('.select_stage').change(function () {
            console.log($(this).val());
            init($(this).val());
        });


        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container3D',
                margin: 100,
                //type: 'line',
                type: 'scatter3d',
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 30,
                    depth: 350,
                    viewDistance: 5,

                    frame: {
                        bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                        back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                        side: { size: 1, color: 'rgba(0,0,0,0.06)' }
                    }
                }
            },
            title: {
                text: 'Draggable box'
            },
            subtitle: {
                text: 'Click and drag the plot area to rotate in space'
            },
            /* plotOptions: {
                 scatter: {
                     width: 10,
                     height: 10,
                     depth: 10
                 }
             },*/
            yAxis: {
                min: 0,
                max: 10,
                title: null
            },
            xAxis: {
                min: 0,
                max: 10,
                gridLineWidth: 1
            },
            zAxis: {
                min: 0,
                max: 10
            },
            legend: {
                enabled: false
            },
            series: [{
                lineWidth: 2,
                name: 'Reading',
                data: [
                    [0,0,0],
                    [1,1,1],
                    [1,1,3],
                    [2,2,4],
                    [3,2,4],
                    [4,2,4],
                    [5,2,4],
                    [5,3,4],
                    [6,4,5],
                    [7,5,5],
                    [8,6,6],
                    [8,7,7],
                    [8,8,8],
                    [8,9,8]
                ]
            }]
        });

        $(chart.container).bind('mousedown.hc touchstart.hc', function (e) {
            e = chart.pointer.normalize(e);

            var posX = e.pageX,
                posY = e.pageY,
                alpha = chart.options.chart.options3d.alpha,
                beta = chart.options.chart.options3d.beta,
                newAlpha,
                newBeta,
                sensitivity = 3; // lower is more sensitive

            $(document).bind({
                'mousemove.hc touchdrag.hc': function (e) {
                    // Run beta
                    newBeta = beta + (posX - e.pageX) / sensitivity;
                    newBeta = Math.min(100, Math.max(-100, newBeta));
                    chart.options.chart.options3d.beta = newBeta;

                    // Run alpha
                    newAlpha = alpha + (e.pageY - posY) / sensitivity;
                    newAlpha = Math.min(100, Math.max(-100, newAlpha));
                    chart.options.chart.options3d.alpha = newAlpha;

                    chart.redraw(false);
                },
                'mouseup touchend': function () {
                    $(document).unbind('.hc');
                }
            });
        });

    });

</script>

<!--http://www.java2s.com/Tutorials/highcharts/index.htm-->
</body>

</html>

