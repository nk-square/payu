<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payu Local</title>
	<style type="text/css">
		body{
			font-family: "Arial", serif;
		}
		.center {
		  	text-align: center;
		  	margin: auto;
  			width: 50%;
		}
		.button {
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 10px 10px;
			text-align: center;
			display: inline-block;
			font-size: 16px;
			border-radius: 5px;
			margin: 5px;
		}
		.button:hover {
			background-color: #5fd864;
		}
	</style>
</head>
<body>
	<div class="center">
		<h3>Payu Local Payment Page</h3>
		<form action="{{$surl}}" method="post">
			@csrf
			@foreach($paymentPayu as $key => $value)
			<input type="hidden" name="{{$key}}" value="{{$value}}">
			@endforeach
			<input type="hidden" name="status" value="success">
			<button type="submit"  class="button">Success Page</button>
		</form>
		<form action="{{$furl}}" method="post">
			@csrf
			@foreach($paymentPayu as $key => $value)
			<input type="hidden" name="{{$key}}" value="{{$value}}">
			@endforeach
			<input type="hidden" name="status" value="failure">
			<button class="button">Failure Page</button>
		</form>
	</div>
</body>
</html>