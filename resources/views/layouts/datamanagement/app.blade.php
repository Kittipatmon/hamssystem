<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}@yield('title' ? ' - ' . $__env->yieldContent('title') : '')</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
</head>

<body class="font-sans antialiased bg-slate-50/50 text-slate-900">
    <div class="min-h-screen flex flex-col">
        @include('layouts.datamanagement.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow-sm border-b border-slate-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#D71920',
                });
            });
        </script>
    @endif
</body>

</html>
