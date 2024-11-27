<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME', "Farmer's") }} - {{ $title }}</title>

    <!-- Vendor CSS Files -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background-image: {{ asset('images/web-assets/rows-of-cows-being-milked.jpeg') }}" class="bg-gray-100 dark:bg-gray-900">

    @guest
        @yield('hero')
    @endguest

    <div class="">
        {{ $slot }}
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <div id="preloader"></div>

</body>

</html>
