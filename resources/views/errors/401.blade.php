<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Unauthorized - HAMS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=No_Normal_Display:wght@100..900&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Thai', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .accent-gradient { background: linear-gradient(135deg, #DC2626 0%, #B32025 100%); }
    </style>
</head>

<body class="bg-[#FAF7F2] min-h-screen flex items-center justify-center p-6 overflow-hidden relative">
    <!-- Animated background elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-red-100 rounded-full blur-3xl opacity-40 animate-pulse"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-red-100 rounded-full blur-3xl opacity-40 animate-pulse" style="animation-delay: 2s"></div>

    <div class="max-w-lg w-full relative z-10 text-center">
        <!-- Error Content -->
        <div class="glass p-12 rounded-[3rem] shadow-2xl space-y-8 animate-fade-in">
            <div class="space-y-4">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-50 text-red-600 mb-4 shadow-inner">
                    <i class="fa-solid fa-lock text-4xl"></i>
                </div>
                <h1 class="text-7xl font-black text-slate-900 tracking-tighter">401</h1>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">กรุณาเข้าสู่ระบบ</h2>
                <p class="text-slate-500 font-medium leading-relaxed">คุณยังไม่ได้เข้าสู่ระบบหรือช่วงเวลาการใช้งานของคุณหมดอายุ <br class="hidden sm:block">กรุณาลงชื่อเข้าใช้เพื่อเข้าถึงหน้านี้</p>
            </div>

            <div class="flex flex-col gap-4 mt-6">
                <a href="{{ route('login') }}" class="accent-gradient text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-red-900/20 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                    เข้าสู่ระบบด้วย Microsoft
                </a>
                <a href="{{ url('/') }}" class="text-slate-400 hover:text-red-600 font-bold text-sm transition-colors py-2 flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i> กลับหน้าหลัก
                </a>
            </div>
        </div>

        <p class="mt-8 text-slate-400 text-xs font-bold tracking-widest uppercase opacity-50">HAMS System © 2024</p>
    </div>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</body>

</html>
