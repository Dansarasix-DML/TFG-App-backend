<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$blog->title}}</title>

        <!-- Fonts -->

        <!-- Styles -->

    </head>

    <body>
        <input type="hidden" name="blogSlug" value="{{$blog->slug}}">
        <div id="blogIndex">
        </div>
    </body>
</html>