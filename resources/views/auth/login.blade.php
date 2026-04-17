<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HAMS</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', 'Kanit', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* subtle blur shadow similar to screenshot */
        .soft-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-rounded {
            border-radius: 1rem;
        }

        .glass {
            backdrop-filter: blur(2px);
        }

        .gradient-btn {
            background: linear-gradient(90deg, #ff3b3bff 0%, #ff3d3dff 100%);
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b0b0b0;
        }

        .input-field {
            padding-left: 2.25rem;
        }

    </style>
</head>

<body class="min-h-screen bg-slate-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-5xl card-rounded overflow-hidden soft-shadow bg-white">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Left: image + title overlay -->
                <div class="relative h-64 md:h-150 ">
                    <img src="{{ asset('images/welcome/kmlhq.jpg') }}" alt="HAMS Building"
                        class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center px-6">
                        <h1 class="text-2xl md:text-2xl font-bold drop-shadow-md leading-tight">
                            Human Asset Management<br class="hidden md:block">&amp; Service Building
                        </h1>
                    </div>
                </div>

                <!-- Right: form panel -->
                <div class="p-8 md:p-10 text-gray-800 flex flex-col justify-center md:mt-25">
                    <div class="max-w-sm mx-auto border border-gray-300/60 rounded-xl shadow-xl p-6">
                        <div class="border-l-4 border-red-700 pl-4 mb-6">
                            <h2 class="text-lg font-semibold tracking-wide">Human Asset Management<br>&amp; Service
                                Building</h2>
                        </div>
                        <p class="text-sm/6 mb-4 opacity-90">กรุณาเข้าสู่ระบบเพื่อใช้งานระบบ</p>

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- emp_code -->
                            <div>
                                <label for="emp_code" class="sr-only">Employee Code</label>
                                <div class="relative">
                                    <svg class="input-icon size-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.761-3.582-5-8-5Z" />
                                    </svg>
                                    <input id="emp_code" name="emp_code" type="text"
                                        value="{{ old('emp_code') }}" required autofocus autocomplete="username"
                                        placeholder="รหัสพนักงาน"
                                        class="input-field w-full rounded-lg border-0 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-600 py-2.5" />
                                </div>
                                @error('emp_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- password -->
                            <div>
                                <label for="password" class="sr-only">Password</label>
                                <div class="relative">
                                    <svg class="input-icon size-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17 8V7a5 5 0 0 0-10 0v1H5a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1h-2Zm-8 0V7a3 3 0 0 1 6 0v1H9Zm3 5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                    </svg>
                                    <input id="password" name="password" type="password" required
                                        autocomplete="current-password" placeholder="Password"
                                        class="input-field w-full rounded-lg border-0 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-600 py-2.5 pr-10" />
                                    <button type="button" id="togglePassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-white/90 hover:text-white"
                                        aria-label="Show password">
                                        <svg id="eyeIcon" class="size-5" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-3a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full gradient-btn text-white font-semibold py-2.5 rounded-full shadow-md hover:opacity-95 transition">
                                เข้าสู่ระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // toggle password visibility
        (function () {
            const btn = document.getElementById('togglePassword');
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (btn && input) {
                btn.addEventListener('click', () => {
                    const isPwd = input.type === 'password';
                    input.type = isPwd ? 'text' : 'password';
                    icon.innerHTML = isPwd
                        ? '<path d="M3.707 2.293 2.293 3.707 6.092 7.506C4.114 8.77 2.742 10.46 2 12c0 0 4 7 10 7 2.098 0 3.93-.664 5.45-1.61l2.843 2.843 1.414-1.414L3.707 2.293zM12 17c-2.761 0-5-2.239-5-5 0-.69.141-1.347.396-1.944l1.55 1.55A3 3 0 0 0 12 15a2.98 2.98 0 0 0 1.394-.35l1.638 1.638A4.97 4.97 0 0 1 12 17zm0-12c7 0 11 7 11 7a16.62 16.62 0 0 1-4.213 4.779l-2.115-2.115A6.98 6.98 0 0 0 19 12c0 0-4-7-7-7-.985 0-1.916.156-2.79.421L7.88 3.092A8.985 8.985 0 0 1 12 5z"/>'
                        : '<path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-3a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>';
                });
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('logout-success'))
                Swal.fire({
                    icon: 'success',
                    title: 'ออกจากระบบ!',
                    text: "{{ session('logout-success') }}",
                    timer: 2500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            @endif
        });
    </script>
</body>

</html>