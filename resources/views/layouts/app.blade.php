<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @if(request()->secure())
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap"
        rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="font-sans antialiased bg-[#FAF9F6]">
    <div class="min-h-screen pt-16">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('login-success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: "{{ session('login-success') }}",
                    timer: 2500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            @endif

            // Notification Polling Script
            let lastCheckedTime = localStorage.getItem('lastNotificationCheckTime') || '';

            function checkNewRequests() {
                fetch(`/parking/api/notifications/check-new?last_checked=${encodeURIComponent(lastCheckedTime)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.has_new && lastCheckedTime !== '') {
                                data.notifications.forEach(notif => {
                                    Swal.fire({
                                        icon: 'info',
                                        title: notif.title,
                                        text: notif.message,
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 7000,
                                        timerProgressBar: true,
                                        customClass: {
                                            popup: 'rounded-xl shadow-lg border border-slate-100',
                                            title: 'font-bold text-sm',
                                            htmlContainer: 'text-xs text-slate-600'
                                        },
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer);
                                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                                            toast.style.cursor = 'pointer';
                                            toast.addEventListener('click', () => {
                                                window.location.href = notif.url;
                                            });
                                        }
                                    });
                                });
                            }
                            lastCheckedTime = data.timestamp;
                            localStorage.setItem('lastNotificationCheckTime', lastCheckedTime);
                        }
                    })
                    .catch(err => console.error('Notification check error:', err));
            }

            // Check immediately on load
            checkNewRequests();
            
            // Polling every 15 seconds
            setInterval(checkNewRequests, 15000);
        });
    </script>
</body>

</html>