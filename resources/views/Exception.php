<!doctype html>
<html lang="en">

<head>
	<title>{{ echo $exception->getMessage() }}</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"
		integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
</head>

<body style="overflow:auto">
	<div style="width:100%; height:100px; background-color:#b0413e">
		<div class="container">
			<div class="col-md-12 text-right">
				<h1 class="text-white">GOT EXCEPTION !!</h1>
			</div>
		</div>
	</div>
	<div class="container" style="overflow:auto">
		<!-- Image and text -->
		<nav class="navbar navbar-light bg-dark">
			<a class="navbar-brand text-white" href="#">
				<img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30"
					class="d-inline-block align-top" alt="">
				Throws `{{ echo get_class($exception)}}`
			</a>
		</nav>

		<h1 style='color:#b0413e;font-weight:bold'>{{ echo $exception->getMessage() }}</h1>
		<h3>From {{ echo str_replace(base_path(), '', $exception->getFile()) }} in line {{ echo $exception->getLine() }}
		</h3>
		@foreach($exception->getTrace() as $trace)
			@php
				$file = isset($trace['file']) ? $trace['file'] : '';
				$line = isset($trace['line']) ? $trace['line'] : '';
				$class = isset($trace['class']) ? $trace['class'] : '';
				$function = isset($trace['function']) ? $trace['function'] : '';
			@endphp

			@if(!empty($file))
				<h5>File <strong>{{ echo $file }}</strong> got error from class {{ echo $class }} in function
					{{ echo $function }}() <strong>line {{ echo $line }}</strong></h5>
				<br>
			@endif
		@endforeach
	</div>
	<div style="width:100%; height:200px; max-height:auto; background-color:#b0413e">
	</div>
</body>

</html>