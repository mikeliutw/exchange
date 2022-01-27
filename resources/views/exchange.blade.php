<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        max-width: 800px;
        margin: 1em auto;
    }


    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
    </style>

</head>

<body class="container">
    <div class="mt-3 justify-center">

        <div class="row mb-3">
            <h2> Conversion API</h2>
            <span>Because free plan can't use Conversion API to call fixed content using api</span>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eur" class="form-label">EUR</label>
                    <input type="text" value="100" class="form-control" id="eur" placeholder="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">HKD</label>
                    <input type="text" value="" class="form-control" id="hkd" placeholder="HKD">
                </div>

                <button type="button" id="exchangeBtn" class="btn btn-light btn-outline-primary">Exchange</button>

            </div>

        </div>

        <div class="row ">
            <h2>Exchange Rate</h2>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="base" class="form-label">From</label>
                    <select name="base" class="form-control" id="base">
                        <option value="eur">EUR</option>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="symbols" class="form-label">To</label>
                    <select name="symbols" class="form-control" id="symbols">
                        <option value="hkd">HKD</option>
                    </select>
                </div>


                <input type="text" name="daterange" class="form-control pull-right">

                <button type="button" id="historyBtn" class="btn btn-light btn-outline-primary">Show</button>
            </div>

            <div class="col-md-12">

                <figure class="highcharts-figure">
                    <div id="container1"></div>
                    <p class="highcharts-description">

                    </p>
                </figure>

            </div>

        </div>
</body>

<script>
$(document).ready(function() {

    var today = new Date();
    console.log(today.toLocaleDateString("en-US")); // 9/17/2016
    var fdate = today.toLocaleDateString("en-US");
    var tdate = today.toLocaleDateString("en-US");
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        startDate: today.toLocaleDateString("en-US")
    }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
            .format('YYYY-MM-DD'));

        fdate = start.format('YYYY-MM-DD');
        tdate = end.format('YYYY-MM-DD')
    });

    $('#exchangeBtn').on('click', function() {

        var eur = $("#eur").val();
        var endpoint = "/api/exchange?eur=" + eur;

        $.ajax({
            type: "GET",
            url: endpoint,
            dataType: "json",

            success: function(response) {
                $('#hkd').val(response.data);
            },
            error: function(thrownError) {
                console.log(thrownError);
            }
        });


    });


    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        console.log(picker.startDate.format('YYYY-MM-DD'));
        console.log(picker.endDate.format('YYYY-MM-DD'));

        fdate = start.format('YYYY-MM-DD');
        tdate = end.format('YYYY-MM-DD')
    });

    $('#historyBtn').on('click', function() {
        var base = $("#base :selected").val(); // The value of the selected option
        var symbols = $("#symbols :selected").val(); // The value of the selected otion

        var endpoint = "/api/history?base=" + base + "&symbols=" + symbols + "&fdate=" + fdate +
            "&tdate=" + tdate;


        $.ajax({
            type: "GET",
            url: endpoint,
            dataType: "json",

            success: function(response) {

                Highcharts.chart('container1', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Exchange Rate'
                    },
                    subtitle: {
                        text: 'Source: exchangeratesapi.com'
                    },
                    xAxis: {
                        categories: response.categories
                    },
                    yAxis: {
                        title: {
                            text: 'Value'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: false
                        }
                    },
                    series: [{
                        name: 'EUR->HKD',
                        data: response.data
                    }]
                });

            },
            error: function(thrownError) {
                console.log(thrownError);
            }
        });


    });


});
</script>


</html>