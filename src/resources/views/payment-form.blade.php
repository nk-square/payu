<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to payment gateway</title>
    <style type="text/css">
        body {
            font-family: "Arial", serif;
            background-color: #7bd7bf;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .redirect-text {
            text-align: center;
            vertical-align: middle;
            color: white;
        }
        .container {
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="redirect-text">Redirecting to payment gateway....</h1>
        <form method="post" action="{{$endpoint}}" id="payu-form" style="display: none">
            @csrf
            @foreach($fields as $key => $value)
            <input type="hidden" name="{{$key}}" value="{{$value}}">
            @endforeach
        </form>
    </div>
    <script type="text/javascript">
        window.onload = function(e){
            document.getElementById('payu-form').submit();
        };
    </script>
</body>
</html>