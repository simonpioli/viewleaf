<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laravel Dashboard</title>
    <<link href="https://fonts.googleapis.com/css?family=Oxygen:300,400,700" rel="stylesheet">
    <link href="{{ elixir("css/app.css") }}" rel="stylesheet"/>
    <meta name="google" value="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    @yield('content')

    <script src="{{ elixir("js/app.js") }}"></script>

</body>
</html>
