@extends('layouts.sidebar')
@section('title', 'ข้อมูลข่าวสาร')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
    <!-- Header Card -->
    <div class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 p-6 rounded-2xl shadow-sm flex justify-between items-center transition-all hover:shadow-md">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-newspaper text-kumwell-red"></i>
                รายการข่าวสารล่าสุด
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">จัดการและเผยแพร่ข้อมูลข่าวสารภายในองค์กร</p>
        </div>
        <a href="{{ route('datamanage.news.create') }}" 
           class="group flex items-center gap-2 bg-gradient-to-r from-kumwell-red to-red-600 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-red-900/20 hover:shadow-red-900/40 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
            <i class="fa-solid fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="font-bold">เพิ่มข่าวสารใหม่</span>
        </a>
    </div>

    <!-- Table Content -->
    <div class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center w-32">รูปภาพ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest whitespace-nowrap">วันที่เผยแพร่</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">หัวข้อข่าว</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center">สถานะ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($news as $item)
                        <tr class="group hover:bg-gray-50/80 dark:hover:bg-gray-900/30 transition-colors">
                            <td class="px-6 py-4">
                                @php
                                    $paths = [];
                                    if (method_exists($item, 'imagePaths')) {
                                        $paths = (array) $item->imagePaths();
                                    } else {
                                        $raw = $item->image_path ?? ($item->primaryImagePath() ?? '');
                                        if (is_array($raw)) {
                                            $paths = $raw;
                                        } elseif (is_string($raw) && $raw !== '') {
                                            $decoded = json_decode($raw, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $paths = $decoded;
                                            } else {
                                                $paths = array_filter(array_map('trim', preg_split('/\s*,\s*/', $raw)));
                                            }
                                        }
                                    }
                                    if (empty($paths)) {
                                        $paths = ['images/welcome/news1.jpg'];
                                    }

                                    $imageUrls = [];
                                    foreach ($paths as $p) {
                                        $p = (string) $p;
                                        if ($p === '') continue;
                                        if (preg_match('/^(https?:)?\/\//i', $p)) {
                                            $imageUrls[] = $p;
                                        } else {
                                            $slashed = str_replace('\\', '/', $p);
                                            $normalized = preg_replace('#^/?(?:public/)?#i', '', $slashed);
                                            $normalized = ltrim($normalized, '/');
                                            $imageUrls[] = asset($normalized);
                                        }
                                    }
                                    if (empty($imageUrls)) {
                                        $imageUrls[] = asset('images/welcome/news1.jpg');
                                    }
                                @endphp

                                <div class="flex items-center -space-x-4 hover:space-x-1 transition-all duration-300 justify-center">
                                    @foreach(array_slice($imageUrls, 0, 3) as $url)
                                        <div class="relative group/img">
                                            <img src="{{ $url }}" 
                                                 class="w-12 h-12 object-cover rounded-xl border-2 border-white dark:border-gray-800 shadow-sm transition-transform hover:scale-110 hover:z-10 cursor-pointer"
                                                 alt="News Thumbnail">
                                        </div>
                                    @endforeach
                                    @if(count($imageUrls) > 3)
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-white dark:border-gray-900 flex items-center justify-center text-[10px] font-bold text-gray-500 z-0">
                                            +{{ count($imageUrls) - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ optional($item->published_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                        {{ optional($item->published_date)->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-kumwell-red transition-colors">
                                    {{ $item->title }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                        เผยแพร่
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800/50 dark:text-gray-400">
                                        <span class="w-1 h-1 rounded-full bg-gray-400 mr-2"></span>
                                        ปิดใช้งาน
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Outlook Notification Popover Logic -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button onclick="toggleEmailPanel(this)" 
                                                title="แจ้งเตือนผ่าน Outlook"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-0.5 shadow-sm">
                                            <i class="fa-solid fa-envelope"></i>
                                        </button>
                                        
                                        <!-- Email Panel (Abstracted Styling) -->
                                        <div class="email-panel hidden absolute right-0 bottom-full mb-3 w-72 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl p-4 z-50 animate-popIn">
                                            <form action="{{ route('datamanage.news.notifyOutlook', $item) }}" method="POST" onsubmit="return confirm('ส่งอีเมลแจ้งเตือน Outlook สำหรับข่าวนี้?');">
                                                @csrf
                                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">แจ้งเตือน Outlook</h4>
                                                
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="text-[10px] font-semibold text-gray-500 mb-1 block">เลือกผู้รับเพิ่มเติม</label>
                                                        <select name="extra_emails[]" multiple class="select2 w-full">
                                                            @foreach(['Kittiphan.Bu@kumwell.com', 'hr@kumwell.com', 'sale@kumwell.com'] as $em)
                                                                <option value="{{ $em }}">{{ $em }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="text-[10px] font-semibold text-gray-500 mb-1 block">พิมพ์อีเมลอื่น</label>
                                                        <input type="text" name="extra_emails_text" 
                                                               placeholder="example@domain.com"
                                                               class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-gray-900 border-0 rounded-lg focus:ring-1 focus:ring-kumwell-red transition-all">
                                                    </div>
                                                </div>

                                                <div class="mt-4 flex gap-2">
                                                    <button type="submit" class="flex-1 bg-blue-600 text-white text-[11px] font-bold py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                        ส่งแจ้งเตือน
                                                    </button>
                                                    <button type="button" onclick="closeEmailPanel(this)" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[11px] rounded-lg hover:bg-gray-200 transition-colors">
                                                        ยกเลิก
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <a href="{{ route('datamanage.news.edit', $item) }}" 
                                       title="แก้ไขข่าวสาร"
                                       class="w-9 h-9 flex items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 hover:bg-orange-500 hover:text-white transition-all transform hover:-translate-y-0.5 shadow-sm">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form method="POST" action="{{ route('datamanage.news.destroy', $item) }}" onsubmit="return confirm('ยืนยันการลบข่าวนี้?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="ลบข่าวสาร"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-0.5 shadow-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-folder-open text-gray-300 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">ยังไม่มีข้อมูลข่าวสารในขณะนี้</p>
                                    <a href="{{ route('datamanage.news.create') }}" class="text-kumwell-red text-xs mt-2 hover:underline font-bold">เริ่มสร้างข่าวสารแรกที่นี่</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
        .animate-popIn { animation: popIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

        .select2-container--default .select2-selection--multiple {
            background-color: transparent !important;
            border-color: #e5e7eb !important;
            border-radius: 0.5rem !important;
            min-height: 38px !important;
        }
        .dark .select2-container--default .select2-selection--multiple {
            border-color: #374151 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #D71920 !important;
            border: none !important;
            color: white !important;
            border-radius: 0.25rem !important;
            font-size: 10px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        window.toggleEmailPanel = function(btn) {
            // Close all other panels first
            document.querySelectorAll('.email-panel').forEach(p => {
                if(p !== btn.nextElementSibling) p.classList.add('hidden');
            });
            const panel = btn.nextElementSibling;
            if (panel) { panel.classList.toggle('hidden'); }
        };
        window.closeEmailPanel = function(btn) {
            const panel = btn.closest('.email-panel');
            if (panel) { panel.classList.add('hidden'); }
        };
        
        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.relative') && !e.target.closest('.select2-container')) {
                document.querySelectorAll('.email-panel').forEach(p => p.classList.add('hidden'));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                width: '100%',
                containerCssClass: 'modern-select2',
                placeholder: 'เลือกผู้รับ...'
            });
        });
    </script>
@endpush
