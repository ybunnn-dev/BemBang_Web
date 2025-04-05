<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/hotel-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/man-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
    @yield('styles')
</head>
<body>

    <!-- Include Front Desk Top Bar -->
    @include('partials.management-topbar')
    
    <div class="container">
        <div class="sidebar">
            <!-- Include Front Desk Sidebar -->
            @include('partials.management-sidebar')
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('extra-scripts')
</body>
</html>
