<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{ @need('title', 'Hello this is title instead') }}</title>
  {{ @need('meta') }}
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Bootstrap core CSS -->
  <link href="{{ assets('assets/template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="{{ assets('assets/template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
    type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet'
    type='text/css'>
  <link
    href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'
    rel='stylesheet' type='text/css'>

  <!-- Custom styles for this template -->
  <link href="{{ assets('assets/template/css/clean-blog.min.css') }}" rel="stylesheet">

</head>

<body>
  {{ @included('layouts.header') }}
  {{ @need('content') }}
  <hr>
  {{ @included('layouts.footer'); }}

  <!-- Bootstrap core JavaScript -->
  {{ @included('layouts.script'); }}

</body>

</html>