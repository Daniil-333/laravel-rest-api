<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Vue REST-API Application</title>

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/storage/public/favicon-32x32.png') }}">

        @vite('resources/js/app.js')
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
