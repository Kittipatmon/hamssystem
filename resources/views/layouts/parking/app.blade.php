<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php \Carbon\Carbon::setLocale('th'); @endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - ระบบลานจอดรถ</title>
    @if(request()->secure())
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
</head>

<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen pt-16">
        @include('layouts.parking.navparking')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="min-h-[70vh]">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    @stack('scripts')
    
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
            document.addEventListener('DOMContentLoaded', function () {
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
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
