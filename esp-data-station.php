<?php
include_once 'esp-database.php';
// if ($_GET['readingsCount']) {
//     $data = $_GET['readingsCount'];
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     $readings_count = $_GET['readingsCount'];
// }
// default readings count set to 20
// else {
//     $readings_count = 20;
// }

// $last_reading = getLastReadings();
// $last_reading_noise = $last_reading['value'];
// $last_reading_time = $last_reading['reading_time'];
// Uncomment to set timezone to - 1 hour (you can change 1 to any number)
//$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
// Uncomment to set timezone to + 7 hours (you can change 7 to any number)
//$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));
// $min_noise = minReading($readings_count, 'value');
// $max_noise = maxReading($readings_count, 'value');
// $avg_noise = avgReading($readings_count, 'value');
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">ESP32 Noise Monitoring</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Data</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">User</a>
            </li>
        </ul>

    </div>
</nav>

<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.getJSON(
            'http://localhost/esp32/api.php',

            function(data) {
                console.log(data)
                const date = data.map(d => d.ts.$date.$numberLong);
                console.log(date);
                const value = data.map(d => d.value);
                console.log(value);
                var result = [];
                for (var i = 0; i < date.length; i++) {
                    result.push([date[i], value[i]]);
                }
                console.log(result);

                Highcharts.chart('container', {
                    chart: {
                        zoomType: 'x'
                    },
                    title: {
                        text: 'Grafik Kebisingan'
                    },
                    subtitle: {
                        text: document.ontouchstart === undefined ?
                            'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                    },
                    xAxis: {
                        type: 'datetime'
                    },
                    yAxis: {
                        title: {
                            text: ' Tingakat Kebisingan (dB)'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        area: {
                            fillColor: {
                                linearGradient: {
                                    x1: 0,
                                    y1: 0,
                                    x2: 0,
                                    y2: 1
                                },
                                stops: [
                                    [0, Highcharts.getOptions().colors[0]],
                                    [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                                ]
                            },
                            marker: {
                                radius: 2
                            },
                            lineWidth: 1,
                            states: {
                                hover: {
                                    lineWidth: 1
                                }
                            },
                            threshold: null
                        }
                    },

                    series: [{
                        type: 'area',
                        name: 'Kebisingan',
                        data: result
                    }]
                });
            }
        );
    </script>
    <figure class="highcharts-figure">
        <div id="container"></div>
        <p class="highcharts-description">
            Highcharts has extensive support for time series, and will adapt
            intelligently to the input data. Click and drag in the chart to zoom in
            and inspect the data.
        </p>
    </figure>

    <div class="mx-auto w-75">
        <?php
        echo '<h2> Sensor Readings </h2>
            <table class="table" id="tableReadings">

            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Sensor</th>
                    <th scope="col">Value</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Classification</th>
                </tr>
            </thead>';
        require 'config.php';
        $result = $collection->find([]);

        foreach ($result as $r) {
            $mongo_date = $r->ts;
            $datetime = $mongo_date->toDateTime();
            $time = $datetime->format(DATE_RSS);
            $date = new DateTime($time);
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            echo '<tbody>';
            echo '<tr>';
            echo '<td>' . $r->_id . '</td>';
            echo '<td>' . $r->sensorId . '</td>';
            echo '<td>' . $r->value . '</td>';
            echo '<td>' . $date->format('Y-m-d H:i:s') . '</td>';
            echo '<td>' . $r->class . '</td>';
            echo '</tr>';
            echo '</tbody>';
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>