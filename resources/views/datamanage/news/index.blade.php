@extends('layouts.datamanagement.app')
@section('title', 'ข้อมูลข่าวสาร')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 animate-fadeIn">
        <!-- Header Card -->
        <!-- Header Card -->
        <div
            class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 p-6 py-10 rounded-2xl shadow-xl shadow-gray-200/50 flex justify-between items-center transition-all hover:shadow-2xl overflow-visible relative z-10">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-newspaper text-kumwell-red"></i>
                    รายการข่าวสารล่าสุด
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">จัดการและเผยแพร่ข้อมูลข่าวสารภายในองค์กร</p>
            </div>
            <a href="{{ route('datamanage.news.create') }}"
                class="group flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-7 py-3.5 rounded-2xl shadow-[0_15px_40px_-10px_rgba(220,38,38,0.5)] hover:shadow-[0_20px_50px_-5px_rgba(220,38,38,0.6)] hover:-translate-y-1.5 active:scale-95 transition-all duration-300 relative z-20">
                <i class="fa-solid fa-plus text-base group-hover:rotate-90 transition-transform duration-300"></i>
                <span class="font-black tracking-wider text-base">เพิ่มข่าวสารใหม่</span>
            </a>
        </div>

        <!-- Table Content -->
        <div
            class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 transition-all hover:shadow-2xl relative z-30 overflow-visible min-h-[450px] mb-20">
            <div class="relative overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-800 uppercase tracking-[0.15em] text-[10px] font-black">
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400 text-center w-32">รูปภาพ</th>
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400 whitespace-nowrap">วันที่เผยแพร่</th>
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400">หัวข้อข่าว</th>
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400 text-center">ยอดเข้าดู</th>
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400 text-center">สถานะ</th>
                            <th class="px-6 py-5 text-gray-500 dark:text-gray-400 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($news as $item)
                            <tr class="group flex flex-col md:table-row hover:bg-gray-50/80 dark:hover:bg-gray-900/30 transition-colors border-b md:border-none last:border-0 p-4 md:p-0">
                                <td class="flex justify-between items-center md:table-cell px-6 py-6 font-medium">
                                    <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest">รูปภาพ</span>
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
                                            if ($p === '')
                                                continue;
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

                                    <div
                                        class="flex items-center -space-x-4 hover:space-x-1 transition-all duration-300 justify-center">
                                        @foreach(array_slice($imageUrls, 0, 3) as $url)
                                            <div class="relative group/img">
                                                <img src="{{ $url }}"
                                                    class="w-12 h-12 object-cover rounded-xl border-2 border-white dark:border-gray-800 shadow-sm transition-transform hover:scale-110 hover:z-10 cursor-pointer"
                                                    alt="News Thumbnail">
                                            </div>
                                        @endforeach
                                        @if(count($imageUrls) > 3)
                                            <div
                                                class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-white dark:border-gray-900 flex items-center justify-center text-[10px] font-bold text-gray-500 z-0">
                                                +{{ count($imageUrls) - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                <td class="hidden md:table-cell px-6 py-6 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ optional($item->published_date)->format('d M Y') }}
                                        </span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[10px] text-slate-400 font-bold tracking-tighter">
                                                {{ optional($item->published_date)->format('H:i') }}
                                            </span>
                                            <span class="text-[9px] text-slate-300 font-medium uppercase">
                                                · {{ optional($item->published_date)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="flex flex-row md:table-cell px-6 py-6 font-medium border-none items-start gap-4">
                                    <span class="md:hidden text-[10px] font-black text-slate-400 uppercase tracking-widest sr-only">ข้อมูลข่าว</span>
                                    
                                    {{-- Image Group on Mobile --}}
                                    <div class="md:hidden">
                                        {{-- We will move the image generation logic up if needed, but for now we just reference the existing block --}}
                                    </div>
                                    
                                    {{-- Vertical Data Block for Mobile --}}
                                    <div class="flex flex-col gap-3 w-full">
                                        <div class="flex justify-between items-start md:hidden">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">วันที่เผยแพร่</span>
                                            <div class="flex flex-col text-right">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                                    {{ optional($item->published_date)->format('d M Y') }}
                                                </span>
                                                <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                    <span class="text-[11px] text-slate-400 font-black">
                                                        {{ optional($item->published_date)->format('H:i') }}
                                                    </span>
                                                    <span class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter">
                                                        ({{ optional($item->published_date)->diffForHumans() }})
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-start md:hidden border-t border-gray-50 dark:border-gray-800/50 pt-3">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">หัวข้อ</span>
                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 text-right">
                                                {{ $item->title }}
                                            </p>
                                        </div>

                                        <div class="flex justify-between items-center md:hidden border-t border-gray-50 dark:border-gray-800/50 pt-3">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ยอดเข้าดู</span>
                                            <div class="flex flex-row items-center gap-1.5">
                                                <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                                                    {{ number_format($item->views_count ?? 0) }}
                                                </span>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-tighter">ครั้ง</span>
                                            </div>
                                        </div>

                                        {{-- Desktop Title Content --}}
                                        <div class="hidden md:block">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 group-hover:text-kumwell-red transition-colors">
                                                {{ $item->title }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                {{-- Views column (Desktop Only) --}}
                                <td class="hidden md:table-cell px-6 py-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                                            {{ number_format($item->views_count ?? 0) }}
                                        </span>
                                        <span class="text-[9px] text-gray-400 uppercase tracking-tighter">ครั้ง</span>
                                    </div>
                                </td>
                                </td>
                                <td class="flex flex-row justify-center items-center gap-4 md:table-cell px-6 py-4 text-center border-t md:border-none border-gray-50 dark:border-gray-800/50 pt-6">
                                    {{-- Group Status and Actions in a specific row for mobile --}}
                                    <div class="md:hidden contents">
                                        {{-- This will be handled by the next TD for a unified centered row on mobile --}}
                                    </div>

                                    <div class="hidden md:block">
                                        @if($item->is_active)
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50 shadow-sm">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                                เผยแพร่
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest bg-slate-50 text-slate-400 border border-slate-100 dark:bg-gray-800/50 dark:text-gray-500 dark:border-gray-700/50">
                                                <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-gray-600 mr-2.5"></span>
                                                ปิดใช้งาน
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="flex flex-row justify-center items-center gap-4 md:table-cell px-6 py-6 font-medium whitespace-nowrap text-center md:border-none pt-2 md:pt-6">
                                    <div class="flex flex-row items-center justify-center gap-3">
                                        {{-- Show status badge here on mobile as part of the action row --}}
                                        <div class="md:hidden">
                                            @if($item->is_active)
                                                <span class="inline-flex items-center px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 shadow-sm leading-tight text-center">
                                                    เผยแพร่
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-400 border border-slate-100 dark:bg-gray-800/50 dark:text-gray-500 shadow-sm leading-tight text-center">
                                                    ปิดใช้งาน
                                                </span>
                                            @endif
                                        </div>

                                        <div class="relative">
                                            <button type="button"
                                                onclick="openNotifyModal('{{ $item->news_id }}', '{{ addslashes($item->title) }}', '{{ route('datamanage.news.notifyOutlook', $item) }}')"
                                                title="แจ้งเตือนผ่าน Outlook"
                                                class="w-11 h-11 flex items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm active:scale-90 border border-blue-100 dark:border-blue-900/50">
                                                <i class="fa-solid fa-envelope text-lg"></i>
                                            </button>
                                        </div>

                                        <a href="{{ route('datamanage.news.edit', $item) }}" title="แก้ไขข่าวสาร"
                                            class="w-11 h-11 flex items-center justify-center rounded-2xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 hover:bg-orange-500 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm active:scale-90 border border-orange-100 dark:border-orange-900/50">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>

                                        <form method="POST" action="{{ route('datamanage.news.destroy', $item) }}"
                                            onsubmit="return confirm('ยืนยันการลบข่าวนี้?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="ลบข่าวสาร"
                                                class="w-11 h-11 flex items-center justify-center rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm active:scale-90 border border-red-100 dark:border-red-900/50">
                                                <i class="fa-solid fa-trash-can text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-folder-open text-gray-300 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                            ยังไม่มีข้อมูลข่าวสารในขณะนี้</p>
                                        <a href="{{ route('datamanage.news.create') }}"
                                            class="text-kumwell-red text-xs mt-2 hover:underline font-bold">เริ่มสร้างข่าวสารแรกที่นี่</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notification Modal (Single implementation outside the transform context) -->
    <div id="email-notify-modal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-[4px] z-[9999] flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-gray-800 w-full max-w-4xl h-[680px] rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden animate-popIn flex flex-col md:flex-row border border-white/20">

            <!-- Left Side: Target Selection (Step 1) -->
            <div
                class="w-full md:w-[42%] bg-slate-50/50 dark:bg-gray-900/40 p-10 flex flex-col border-r border-slate-100 dark:border-gray-700/50">
                <h5 class="text-[14px] font-black text-blue-600 uppercase tracking-[0.15em] mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    STEP 1: เลือกกลุ่มเป้าหมาย
                </h5>

                <div class="space-y-8 flex-1 flex flex-col min-h-0">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">เลือกแผนก /
                            ฝ่าย</label>
                        <select id="modal-dept-select"
                            class="w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl px-5 py-4 text-xs font-bold text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 transition-all outline-none"
                            onchange="filterEmployeesByDeptModal(this.value)">
                            <option value="all">ทั้งหมด (ทุกแผนก)</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <div
                            class="flex justify-between items-center mb-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <label>รายชื่อพนักงาน</label>
                            <div class="flex gap-3">
                                <button type="button" onclick="toggleSelectAllModal(true)"
                                    class="text-blue-500 hover:text-blue-700 transition-colors uppercase">เลือกทั้งหมด</button>
                                <span class="text-gray-200">|</span>
                                <button type="button" onclick="toggleSelectAllModal(false)"
                                    class="text-gray-400 hover:text-gray-600 transition-colors uppercase">ล้าง</button>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto pr-3 custom-scrollbar space-y-2" id="modal-employee-list">
                            @foreach($employees as $emp)
                                <label
                                    class="employee-item group flex items-start gap-3 p-4 rounded-2xl border border-transparent hover:border-blue-100 hover:bg-white dark:hover:bg-gray-800 transition-all cursor-pointer"
                                    data-dept="{{ $emp->dept_id }}">
                                    <div class="relative flex items-center mt-1">
                                        <input type="checkbox" value="{{ strtolower($emp->email) }}"
                                            class="employee-checkbox w-5 h-5 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500/20 transition-all"
                                            onchange="handleCheckboxChangeModal(this)">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-[13px] font-bold text-gray-700 dark:text-gray-200 truncate group-hover:text-blue-600 transition-colors">
                                            {{ $emp->fullname }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ strtolower($emp->email) }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Notification Summary -->
            <div class="flex-1 p-12 flex flex-col bg-white dark:bg-gray-800 relative overflow-y-auto custom-scrollbar">
                <button type="button" onclick="closeNotifyModal()"
                    class="absolute top-8 right-8 w-10 h-10 flex items-center justify-center rounded-full text-gray-300 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>

                <div class="mb-12">
                    <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-1">แจ้งเตือน OUTLOOK</h4>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] opacity-70">NOTIFICATION
                        SUMMARY</p>
                    <p id="modal-news-title" class="text-[12px] font-medium text-blue-600 mt-2 line-clamp-1 italic"></p>
                </div>

                <form id="modal-notify-form" action="" method="POST" class="flex-1 flex flex-col space-y-10">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    รายชื่อผู้รับแจ้งเตือน <span id="recipient-count" class="ml-1 text-blue-600">(0)</span>
                                </label>
                                <button type="button" onclick="handleClearAllEmailsModal()"
                                    class="text-[10px] font-bold text-red-500 hover:text-red-700 transition-colors uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i> ล้างข้อมูล
                                </button>
                            </div>

                            <select id="modal-select-emails" name="extra_emails[]" multiple
                                class="select2-email w-full"></select>
                            <p class="text-[11px] text-blue-500 font-medium mt-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-[10px]"></i>
                                ระบบจะรวมอีเมลจาก Checklist ด้านซ้ายและที่ระบุเอง
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 mb-3 block uppercase tracking-widest">ระบุอีเมลอื่นๆ
                                เพิ่มเติม</label>
                            <div class="relative">
                                <input type="email" id="modal-custom-email-input"
                                    class="w-full px-6 py-5 text-sm bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 focus:border-blue-500 transition-all outline-none placeholder:text-gray-400"
                                    placeholder="example@kumwell.com"
                                    onkeydown="if(event.key === 'Enter'){ event.preventDefault(); handleAddCustomEmailModal(); }">
                                <button type="button" onclick="handleAddCustomEmailModal()"
                                    class="absolute right-2.5 top-2.5 w-12 h-12 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all shadow-[0_8px_15px_-4px_rgba(37,99,235,0.4)] active:scale-90">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </button>
                                <p class="hidden text-[10px] text-red-500 mt-3 items-center gap-1.5" id="modal-email-error">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    รูปแบบอีเมลไม่ถูกต้อง
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-10 flex gap-4">
                        <button type="submit"
                            class="flex-1 h-16 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(37,99,235,0.4)] hover:shadow-[0_20px_40px_-10px_rgba(37,99,235,0.5)] transition-all flex items-center justify-center gap-4 active:scale-95 group">
                            <i
                                class="fa-solid fa-paper-plane text-sm group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                            ส่งแจ้งเตือน
                        </button>
                        <button type="button" onclick="closeNotifyModal()"
                            class="px-10 h-16 bg-slate-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-2xl hover:bg-slate-200 dark:hover:bg-gray-600 transition-all active:scale-95">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .animate-popIn {
            animation: popIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            min-height: 46px !important;
            max-height: 180px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 6px !important;
        }

        .select2-selection--multiple::-webkit-scrollbar {
            width: 4px;
        }

        .select2-selection--multiple::-webkit-scrollbar-track {
            background: transparent;
        }

        .select2-selection--multiple::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .select2-selection--multiple::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .dark .select2-selection--multiple::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .select2-container--default .select2-selection--multiple {
            background-color: #111827 !important;
            border-color: #374151 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #2563eb !important;
            color: #fff !important;
            border: 0 !important;
            border-radius: 999px !important;
            margin: 4px !important;
            padding: 6px 12px 6px 10px !important;
            font-size: 12px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            float: none !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            position: static !important;
            order: 2 !important;
            margin-left: 4px !important;
            color: #fff !important;
            background: transparent !important;
            border: 0 !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            font-size: 14px !important;
            cursor: pointer !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fecaca !important;
            background: transparent !important;
        }

        .select2-search--inline .select2-search__field {
            margin-top: 6px !important;
            font-size: 13px !important;
        }

        .select2-dropdown {
            border-radius: 20px !important;
            border: 1px solid #e2e8f0 !important;
            overflow: hidden !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
        }

        .employee-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }

        /* Ensure SweetAlert2 is on top of modals with z-index 9999 */
        .swal2-container {
            z-index: 11000 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.isValidEmail = function (email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // --- Single Modal Logic ---
        const modal = document.getElementById('email-notify-modal');
        const $modalSelect = $('#modal-select-emails');
        const modalForm = document.getElementById('modal-notify-form');
        const modalTitle = document.getElementById('modal-news-title');
        const modalCustomInput = document.getElementById('modal-custom-email-input');
        const modalError = document.getElementById('modal-email-error');

        window.openNotifyModal = function (newsId, newsTitle, actionUrl) {
            modalForm.action = actionUrl;
            modalTitle.textContent = `เรื่อง: ${newsTitle}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset modal state
            handleClearAllEmailsModal();
        };

        window.closeNotifyModal = function () {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        window.handleClearAllEmailsModal = function () {
            $('#modal-select-emails').val(null).trigger('change');
            document.querySelectorAll('#modal-employee-list .employee-checkbox').forEach(cb => {
                cb.checked = false;
            });
            modalCustomInput.value = '';
            modalError.classList.add('hidden');
            modalError.classList.remove('flex');
            document.getElementById('modal-dept-select').value = 'all';
            filterEmployeesByDeptModal('all');
        };

        window.handleAddCustomEmailModal = function () {
            const email = (modalCustomInput.value || '').trim().toLowerCase();

            if (!email) return;

            if (!isValidEmail(email)) {
                modalError.classList.remove('hidden');
                modalError.classList.add('flex');
                modalCustomInput.focus();
                return;
            }

            modalError.classList.add('hidden');
            modalError.classList.remove('flex');

            const $modalSelect = $('#modal-select-emails');
            if ($modalSelect.find(`option[value="${email}"]`).length === 0) {
                $modalSelect.append(new Option(email, email, true, true));
            }

            let values = $modalSelect.val() || [];
            if (!values.includes(email)) values.push(email);
            $modalSelect.val(values).trigger('change');

            modalCustomInput.value = '';
            modalCustomInput.focus();
        };

        window.filterEmployeesByDeptModal = function (deptId) {
            const items = document.querySelectorAll('#modal-employee-list .employee-item');
            items.forEach(item => {
                if (deptId === 'all' || item.dataset.dept === deptId) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        };

        window.toggleSelectAllModal = function (state) {
            const visibleCheckboxes = document.querySelectorAll('#modal-employee-list .employee-item:not(.hidden) .employee-checkbox');
            const $modalSelect = $('#modal-select-emails');
            let values = $modalSelect.val() || [];

            visibleCheckboxes.forEach(cb => {
                cb.checked = state;
                const email = cb.value;
                if (state) {
                    if (!values.includes(email)) values.push(email);
                    if ($modalSelect.find(`option[value="${email}"]`).length === 0) {
                        $modalSelect.append(new Option(email, email, true, true));
                    }
                } else {
                    values = values.filter(v => v !== email);
                }
            });

            $modalSelect.val(values).trigger('change');
        };

        window.handleCheckboxChangeModal = function (cb) {
            const email = cb.value;
            const $modalSelect = $('#modal-select-emails');
            let values = $modalSelect.val() || [];

            if (cb.checked) {
                if (!values.includes(email)) values.push(email);
                if ($modalSelect.find(`option[value="${email}"]`).length === 0) {
                    $modalSelect.append(new Option(email, email, true, true));
                }
            } else {
                values = values.filter(v => v !== email);
            }

            $modalSelect.val(values).trigger('change');
        };

        // Modal Backdrop Click
        modal.addEventListener('mousedown', function (e) {
            if (e.target === modal) closeNotifyModal();
        });

        document.addEventListener('DOMContentLoaded', function () {
            $('#modal-select-emails').select2({
                width: '100%',
                placeholder: 'คัดเลือกผู้รับแจ้งเตือน...',
                tags: true,
                tokenSeparators: [',', ' '],
                createTag: function (params) {
                    const term = $.trim(params.term).toLowerCase();
                    if (term === '' || !isValidEmail(term)) return null;
                    return { id: term, text: term, newTag: true };
                }
            }).on('change', function () {
                const currentValues = $(this).val() || [];
                document.querySelectorAll('#modal-employee-list .employee-checkbox').forEach(cb => {
                    cb.checked = currentValues.includes(cb.value);
                });

                // Update recipient count
                const count = currentValues.length;
                document.getElementById('recipient-count').textContent = `(${count})`;
            });

            // Handle Notification Form Submission with SweetAlert
            modalForm.addEventListener('submit', function (e) {
                e.preventDefault();
                
                const recipientCount = ($('#modal-select-emails').val() || []).length;
                if (recipientCount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกผู้รับ',
                        text: 'กรุณาเลือกพนักงานหรือระบุอีเมลผู้รับอย่างน้อย 1 รายการ',
                        confirmButtonColor: '#2563eb',
                    });
                    return;
                }

                Swal.fire({
                    title: 'ยืนยันการส่งแจ้งเตือน?',
                    text: `ระบบจะส่งอีเมลแจ้งเตือน Outlook ไปยังรายชื่อที่เลือกทั้งหมด ${recipientCount} รายการ`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa-solid fa-paper-plane mr-2"></i> ยืนยันส่งอีเมล',
                    cancelButtonText: 'ยกเลิก',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush