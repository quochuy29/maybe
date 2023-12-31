<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
        <!-- <script async src="https://cse.google.com/cse.js?cx=e206e7879ec5a423b"></script> -->
        @vite('resources/less/app.css')
    </head>
    <body class="antialiased bg-gray-100">

        <!-- <div class="gcse-search"></div> -->
        <div id="app" class="g-sidenav-show"></div>
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        @vite('resources/js/app.js')
    </body>
</html>