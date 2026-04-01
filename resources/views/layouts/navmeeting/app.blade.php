<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        @include('layouts.navmeeting.navmeeting')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="p-6 pt-[88px] min-h-[70vh]">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    @stack('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '<span class="text-emerald-600 font-black">สำเร็จ!</span>',
                    html: '<p class="text-slate-600 font-medium">{{ session('success') }}</p>',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#10b981',
                    padding: '2rem',
                    borderRadius: '2rem',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-3xl border-0 shadow-2xl',
                        title: 'font-prompt'
                    }
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: '<span class="text-rose-600 font-black">เกิดข้อผิดพลาด</span>',
                    html: '<p class="text-slate-600 font-medium">{{ session('error') }}</p>',
                    confirmButtonText: 'ลองอีกครั้ง',
                    confirmButtonColor: '#f43f5e',
                    padding: '2rem',
                    borderRadius: '2rem',
                    customClass: {
                        popup: 'rounded-3xl border-0 shadow-2xl',
                        title: 'font-prompt'
                    }
                });
            });
        </script>
    @endif
</body>

</html>