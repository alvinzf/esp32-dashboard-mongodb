<?php
include_once 'esp-database.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="esp-style.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Perpustakaan</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="day.php">Day</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Year</a>
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
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.getJSON(
            'http://localhost/esp32/api.php',

            function(data) {
                // console.log(data)

                const _date = data.map(d => (parseInt(d.ts) * 1000));
                // console.log(_date);
                const value = data.map(d => (parseInt(d.value)));
                // console.log(value);
                var result = [];
                for (var i = 0; i < _date.length; i++) {
                    result.push([_date[i], value[i]]);
                }
                console.log(result);
                const timezone = new Date().getTimezoneOffset()

                Highcharts.setOptions({
                    global: {
                        timezoneOffset: timezone
                    }
                });
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
    <h3 class="cent mt-5">Recent Readings</h3>
    <section class="content">
        <div class="cent">
            <div class=" d-flex flex-row">

                <div class="p-2">
                    <div class="mask">
                        <div class="semi-circle"></div>
                        <div class="semi-circle--mask"></div>
                    </div>
                    <p style="font-size: 30px;" class="cent" id="max-val">Max : 55</p>
                    <table cellspacing="5" cellpadding="5" class="cent">
                        <tr>
                            <th colspan="3">Last 3 Hours</th>
                        </tr>

                    </table>
                </div>
                <div class="p-2">
                    <div class="mask">
                        <div class="semi-circle"></div>
                        <div class="semi-circle--mask"></div>
                    </div>
                    <p style="font-size: 30px;" class="cent" id="temp">Max : 55</p>
                    <table cellspacing="5" cellpadding="5" class="cent">
                        <tr>
                            <th colspan="3">Last 3 Hours</th>
                        </tr>

                    </table>
                </div>



            </div>
        </div>
    </section>

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
        $sql = "SELECT * FROM readings";
        $query = mysqli_query($db, $sql);

        while ($r = mysqli_fetch_array($query)) {
            // $mongo_date = $r->ts;
            // $datetime = $mongo_date->toDateTime();
            // $time = $datetime->format(DATE_RSS);
            // $date = new DateTime($time);
            // $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $classification = $r['classification'];
            if (strlen($classification) > 0) {
                $classification = explode("#", $classification);
                if (count($classification) > 6) {
                    $fallingObj = round(($classification[0] + $classification[5]) / 2  * 100);
                    $horn = round(($classification[1] + $classification[6]) / 2  * 100);
                    $human = round(($classification[2] + $classification[7]) / 2  * 100);
                    $phone = round(($classification[3] + $classification[8]) / 2  * 100);
                    $siren = round(($classification[4] + $classification[9]) / 2  * 100);
                } else if (count($classification) > 11) {

                    $fallingObj = round(($classification[0] + $classification[5]) / 2  * 100);
                    $horn = round(($classification[1] + $classification[6]) / 2  * 100);
                    $human = round(($classification[2] + $classification[7]) / 2  * 100);
                    $phone = round(($classification[3] + $classification[8]) / 2  * 100);
                    $siren = round(($classification[4] + $classification[9]) / 2  * 100);
                } else {
                    $fallingObj = round($classification[0] * 100);
                    $horn = round($classification[1] * 100);
                    $human = round($classification[2] * 100);
                    $phone = round($classification[3] * 100);
                    $siren = round($classification[4] * 100);
                }

                if ($fallingObj > 70) {
                    $result = "Falling Obj " . $fallingObj . "%";
                } else if ($horn > 70) {
                    $result = "Horn " . $horn . "%";
                } else if ($human > 70) {
                    $result = "Human " . $human . "%";
                } else if ($phone > 70) {
                    $result = "Phone " . $phone . "%";
                } else if ($siren > 70) {
                    $result = "Siren " . $siren . "%";
                } else {
                    $result = "Not Sure";
                }
            } else {
                $result = "-";
            }

            echo '<tbody>';
            echo '<tr>';
            echo '<td>' . $r['id'] . '</td>';
            echo '<td>' . $r['sensor'] . '</td>';
            echo '<td>' . $r['value'] . '</td>';
            echo '<td>' . $r['reading_time'] . '</td>';
            echo '<td>' . $result . '</td>';
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