<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GameVerse REGISTRO</title>

        <!-- Fonts -->

        <!-- Styles -->

    </head>

    <body>
        <div id="app" data-csrf-token="{{ csrf_token() }}" data-errors="{{$errors}}"></div>
    </body>
</html>

