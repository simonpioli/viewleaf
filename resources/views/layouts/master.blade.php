<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laravel Dashboard v1.6.0</title>
    <link href="https://fonts.googleapis.com/css?family=Oxygen:300,400,700" rel="stylesheet">
    <link href="{{ elixir("css/app.css") }}" rel="stylesheet"/>
    <link href="{{ elixir("css/vendor.css") }}" rel="stylesheet"/>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    @yield('content')

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src="{{ elixir("js/app.js") }}"></script>

</body>
</html>
