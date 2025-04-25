<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'User Management System') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>
<body>
    <div id="app">
        @include('includes.header')

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('includes.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('d-none');
        });
        </script>

        <!-- Add this somewhere in your layout -->
        <div id="loading" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-white opacity-75 d-flex justify-content-center align-items-center" style="z-index: 9999;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
</body>
</html>
