<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ __("ui.title") . " " . __("about.p1") }}">
    <meta name="theme-color" content="#222222">
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    <link rel="icon" href="/images/library.png">
    @include("baidupush")
    @include("track")
    <title>{{ __("ui.title") }}</title>
</head>
<body>
@include("header")
<div class="container mb-3">
    <p class="text-muted text-center text-sm-right">Datasource: <a href="https://yayponies.no/" target="_blank">Yayponies</a></p>
    @include("nav")
    <div class="mt-3">
        @yield("content")
    </div>
</div>
<script src="{{ mix("js/app.js") }}"></script>
</body>
</html>
