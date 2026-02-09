<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sadhana Weekly')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        header {
            background: #fff;
            padding: 15px 0;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        header img {
            height: 70px;
        }

        main {
            padding: 20px;
            min-height: calc(100vh - 180px);
        }

        footer {
            background: #bc6409;
            color: #fff;
            text-align: center;
            padding: 15px 10px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: 1px solid #ccc;
        }
    </style>
    
    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="@yield('og:title', 'Sadhana Weekly')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og:image')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    @stack('styles')
</head>
<body>

<header>
    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
</header>

<main>
    @yield('content')
</main>

<footer>
    <a href="https://play.google.com/store/apps/details?id=com.sadhanaweekly.app" target="_blank">
        📱 Download our App on Play Store
    </a>
</footer>

</body>
</html>
