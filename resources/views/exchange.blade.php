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

</head>

<body class="container">
    <div class="mt-3 justify-center">


        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eur" class="form-label">EUR</label>
                    <input type="text" value="100" class="form-control" id="eur" placeholder="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">HKD</label>
                    <input type="text" value="" class="form-control" id="hkd" placeholder="HKD">
                </div>

                <button type="button" id="exchangeBtn" class="btn btn-light">Exchange</button>



            </div>

            <div class="row">
                <h2>Exchange Rate</h2>
                <div class="col-md-6">


                </div>

            </div>
        </div>
</body>

<script>
$(document).ready(function() {
    $('#exchangeBtn').on('click', function() {
        var endpoint = "/api/exchange?eur=" + $('#eur').val();

        $.ajax({
            type: "GET",
            url: endpoint,
            dataType: "json",

            success: function(response) {
                $('#hkd').val(response);
            },
            error: function(thrownError) {
                console.log(thrownError);
            }
        });


    });

});
</script>


</html>