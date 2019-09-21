<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Oneall</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
</head>
<body>
<div id="app">
    <main class="py-4">
        @yield('content')
    </main>
</div>
<link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
