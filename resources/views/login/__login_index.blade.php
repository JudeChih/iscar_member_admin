<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' | ' : '' }}isCar_Member後台</title>
        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/css/sass/stylesheets/style.css">

        <!-- Scripts -->
        <script type="text/javascript" src="/js/plugin/jquery-3.1.1.min.js"></script>
        <script>
            window.Laravel = <?php
                echo json_encode(['csrfToken' => csrf_token()]);
            ?>
        </script>
    </head>
    <body class="login_body">
        @yield('content_tip')
        @yield('content')
        <script src="/js/app.js"></script>
    </body>
</html>
