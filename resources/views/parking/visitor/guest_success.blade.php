<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลงทะเบียนจองสำเร็จ (Parking Ticket)</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'Kanit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center min-h-screen py-12 px-4">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden relative">
        <div class="h-3 w-full bg-emerald-500"></div>

        <!-- Success Header -->
        <div class="p-8 text-center bg-slate-50/50 border-b border-dashed border-slate-200 relative">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl mb-4 shadow-sm">
                <i class="fa-solid fa-circle-check animate-bounce"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">ลงทะเบียนจองที่จอดรถสำเร็จ!</h1>
            <p class="text-xs text-slate-500 font-semibold mt-1">Ticket ID: #PK-VIS-{{ $reservation->id }}</p>
            
            <!-- Ticket notch cuts -->
            <div class="absolute -bottom-3 -left-3 w-6 h-6 rounded-full bg-slate-50 border border-slate-100 shadow-inner"></div>
            <div class="absolute -bottom-3 -right-3 w-6 h-6 rounded-full bg-slate-50 border border-slate-100 shadow-inner"></div>
        </div>

        <!-- Details -->
        <div class="p-8 space-y-6">
            
            <!-- Spot Pill -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-emerald-700 font-bold uppercase tracking-wider">ช่องจอดของคุณ</p>
                <p class="text-4xl font-black text-emerald-800 mt-1">
                    ช่อง {{ $reservation->slot ? $reservation->slot->slot_number : 'ระบบจัดสรร' }}
                </p>
                <p class="text-xs text-emerald-600 font-medium mt-1">
                    {{ $reservation->slot && $reservation->slot->zone ? ($reservation->slot->zone->zone . ' ' . $reservation->slot->zone->building) : 'กรุณาติดต่อ รปภ. เมื่อมาถึงเพื่อนำทาง' }}
                </p>
            </div>

            <!-- Profile and Car -->
            <div class="space-y-3.5">
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-500 font-semibold text-sm">ชื่อผู้ติดต่อ (Visitor)</span>
                    <span class="text-slate-800 font-bold text-sm">{{ $reservation->guest_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-500 font-semibold text-sm">ทะเบียนรถ</span>
                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 rounded font-bold border border-slate-200 text-xs">{{ $reservation->car_registration }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-500 font-semibold text-sm">ผู้ติดต่อภายใน</span>
                    <span class="text-slate-800 font-bold text-sm">{{ $reservation->contactUser ? $reservation->contactUser->fullname : '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-slate-500 font-semibold text-sm">วัน-เวลาที่จะเข้า</span>
                    <span class="text-slate-800 font-bold text-sm">{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-500 font-semibold text-sm">ระยะเวลาที่จอด</span>
                    <span class="text-slate-800 font-bold text-sm">{{ $reservation->duration_hours ?? '-' }} ชั่วโมง</span>
                </div>
            </div>

            <!-- Footer Alert -->
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-xs text-amber-800 leading-relaxed flex gap-2">
                <i class="fa-solid fa-triangle-exclamation text-base mt-0.5 shrink-0"></i>
                <div>
                    <p class="font-bold">ข้อแนะนำสำหรับผู้เข้าติดต่อ</p>
                    <p class="mt-0.5 text-amber-700">กรุณาแสดงหน้าจอหน้านี้ให้กับเจ้าหน้าที่รักษาความปลอดภัย (รปภ.) หรือเจ้าหน้าที่ประชาสัมพันธ์เมื่อเดินทางมาถึงสำนักงานเพื่ออำนวยความสะดวกในการเข้าจอด</p>
                </div>
            </div>

            <div class="pt-4 text-center">
                <p class="text-xs text-slate-400 font-semibold">ขอขอบคุณที่ลงทะเบียนล่วงหน้าเพื่อความสะดวกของคุณ</p>
            </div>
        </div>
    </div>

</body>
</html>
