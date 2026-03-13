<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <title>Mundo Vegano</title>
        <meta charset="utf-8" />
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1" /> --}}
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/storage/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/storage/img/favicon/favicon.ico" type="image/x-icon">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="/css/main.css" />
        <link rel="stylesheet" href="/css/main_custom.css" />
        <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/fm.tagator.jquery.min.css') }}">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>
    <body>
 
        {{-- Vue Front End App Element --}}
        <div id="app"></div>
        {{-- <div id="loader"></div> --}}

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> --}}
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="{{asset('js/app.js')}}"></script>
		<!-- Tagator -->
		<script src="/js/plugin/jquery-tagator/fm.tagator.jquery.js"></script>
        <script src="/js/mask.js"></script>
    </body>
</html>
