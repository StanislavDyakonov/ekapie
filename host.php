<!DOCTYPE html>
<html lang = "ru">
    <head>
        <title>Торты на заказ от Екатерины Калашниковой</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "/css/bootstrap.css">
        <link href = "https://fonts.googleapis.com/css?family=Open+Sans" rel = "stylesheet" type = "text/css">
        <link href = "https://fonts.googleapis.com/css?family=Cormorant+Garamond" rel = "stylesheet" type = "text/css">
        <script src = "/js/jquery.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#calls").load("/handler.php?list=calls&start=0"),
                $("#forms").load("/handler.php?list=forms&start=0")
            });
            function calls(start) {
                $.ajax({
                    type: 'GET',
                    url: 'handler.php',
                    data: 'list=calls&start=' + start,
                    success: function (data) {
                        $('#calls').html(data);
                    }
                });
            }

            function form(start) {
                $.ajax({
                    type: 'GET',
                    url: 'handler.php',
                    data: 'list=forms&start=' + start,
                    success: function (data) {
                        $('#forms').html(data);
                    }
                });
            }
        </script>
    </head>
    <body data-offset="60">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" style="border-right: 1px solid #993360;">
                    <div id="calls">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div id="forms">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>