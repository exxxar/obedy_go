<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="yandex-verification" content="dc164e606696b6b2"/>
    <meta name="description"
          content="Доставка самых вкусных обедов из ресторанов для любителей вкусной еды для Донецка и города Макеевки.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=0">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons -->
    <link rel="shortcut icon" href="/images/logo_obed_go.jpg">
    <link rel="apple-touch-icon" href="/images/logo_obed_go.jpg">
    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body>
@inertia
<script>
    window.Laravel = {csrfToken: '{{ csrf_token() }}'}
</script>
</body>
</html>
