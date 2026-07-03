@extends('layouts.serviceitem.appservice')

@section('content')
    <div class="max-w-7xl mx-auto px-3 sm:px-8 lg:px-16 py-4 md:py-6 space-y-4 md:space-y-6 text-xs font-semibold">

        <!-- Header & Search Box -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#c31919] rounded flex items-center justify-center text-white shadow">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-black text-slate-800 uppercase tracking-wide">ระบบเบิกพัสดุพนักงาน (HAMS Catalog)</h1>
                        <p class="text-[10px] text-slate-400 font-bold mt-0.5">ค้นหาพัสดุอุปกรณ์ที่ต้องการ กำหนดจำนวน และกดเพิ่มลงตะกร้าเพื่อส่งคำขอเบิกพัสดุ</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 max-w-3xl text-xs">
                    <!-- Search Box -->
                    <div class="relative flex-1">
                        <input type="text" id="searchInput" autocomplete="off" value="{{ request('q') }}"
                            placeholder="ระบุรหัสพัสดุ หรือชื่อที่ต้องการค้นหา..."
                            class="w-full h-9 pl-9 pr-3 bg-slate-50 border border-slate-200 rounded font-bold focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="dropdown dropdown-end sm:w-60 relative">
                        @php
                            $selectedCategory = \App\Models\serviceshams\Items_type::find(request('category'));
                            $categories = \App\Models\serviceshams\Items_type::where('status', '1')->get();
                        @endphp
                        <label tabindex="0"
                            class="w-full h-9 bg-red-600 text-white rounded flex items-center justify-between px-4 cursor-pointer hover:bg-red-700 transition-all shadow-sm active:scale-95 font-bold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                                <span class="truncate max-w-[120px]">{{ $selectedCategory->name ?? 'ทุกหมวดหมู่พัสดุ' }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content z-[300] menu p-3 shadow-xl bg-white rounded border border-slate-200 w-full sm:w-60 mt-1 space-y-1 text-slate-700 font-semibold">
                            <li>
                                <a href="{{ route('items.itemsalllist') }}"
                                    class="flex items-center gap-2 py-2 px-3 {{ !request('category') ? 'bg-red-50 text-[#c31919] font-bold' : 'hover:bg-slate-50' }} rounded transition-all">
                                    <i class="fa-solid fa-border-all text-xs"></i>
                                    <span>พัสดุทั้งหมด</span>
                                </a>
                            </li>
                            <div class="max-h-60 overflow-y-auto divide-y divide-slate-100">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('items.itemsalllist') . '?category=' . $category->item_type_id }}"
                                            class="flex items-center gap-2 py-2 px-3 {{ request('category') == $category->item_type_id ? 'bg-red-50 text-[#c31919] font-bold' : 'hover:bg-slate-50' }} rounded transition-all">
                                            <span class="text-[11px]">{{ $category->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div id="itemsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-3 sm:gap-4">
            @forelse($items as $item)
                <div class="bg-white rounded border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group overflow-hidden">
                    <!-- Image Section -->
                    <div class="aspect-square bg-slate-50/50 flex items-center justify-center p-3 relative overflow-hidden border-b border-slate-200">
                        @if ($item->item_pic)
                            <img src="{{ asset('images/items/' . $item->item_pic) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex flex-col items-center gap-1.5 text-slate-300">
                                <i class="fa-solid fa-image text-3xl opacity-20"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">ไม่มีภาพพัสดุ</span>
                            </div>
                        @endif

                        <!-- Stock Badge Overlay -->
                        @if($item->quantity <= 5 && $item->quantity > 0)
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[9px] font-bold border border-amber-200 shadow-sm">
                                    สต็อกต่ำ
                                </span>
                            </div>
                        @elseif($item->quantity == 0)
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded text-[9px] font-bold border border-red-200 shadow-sm">
                                    พัสดุหมด
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content Section -->
                    <div class="p-3 flex flex-col flex-1 justify-between space-y-3">
                        <div class="space-y-1.5">
                            <span class="text-[9px] font-bold text-slate-400 font-mono tracking-wider">{{ $item->item_code ?? 'CODE-NULL' }}</span>
                            <h2 class="text-[12px] font-bold text-slate-800 line-clamp-2 leading-tight uppercase min-h-[32px]">
                                {{ $item->name }}
                            </h2>
                        </div>

                        <!-- Specs clinical-sheet style -->
                        <div class="border border-slate-200 rounded divide-y divide-slate-100 text-[10px]">
                            <div class="p-1.5 flex justify-between items-center bg-slate-50/50">
                                <span class="text-slate-400">สต็อกคงเหลือ</span>
                                <span class="font-bold {{ $item->quantity <= 5 ? 'text-red-600' : 'text-emerald-700' }}">{{ $item->quantity }} ชิ้น</span>
                            </div>
                            <div class="p-1.5 flex justify-between items-center">
                                <span class="text-slate-400">ราคาต่อหน่วย</span>
                                <span class="font-bold text-slate-700">฿{{ number_format($item->per_unit ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        <form method="POST" action="{{ url('/cartitem/add') }}" class="w-full m-0 p-0">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                            <div class="flex items-stretch gap-1">
                                <input name="quantity" type="number" min="1" max="{{ $item->quantity }}" value="1"
                                    class="w-12 h-8 text-center bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-500 focus:outline-none outline-none transition-all"
                                    @if($item->quantity == 0) disabled @endif>
                                <button type="submit"
                                    class="flex-1 h-8 bg-red-600 hover:bg-red-700 text-white rounded font-bold text-[10px] uppercase transition-all disabled:bg-slate-150 disabled:text-slate-400 disabled:shadow-none active:scale-95 flex items-center justify-center gap-1 shadow-sm"
                                    @if($item->quantity == 0) disabled @endif>
                                    <i class="fa-solid fa-plus text-[8px]"></i>
                                    <span class="hidden sm:inline">เพิ่มลงตะกร้า</span>
                                    <span class="sm:hidden">ใส่ตะกร้า</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-150">
                        <i class="fa-solid fa-magnifying-glass text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">ไม่พบอุปกรณ์พัสดุที่ต้องการ</h3>
                    <p class="text-slate-400 text-[10px] mt-1">ทดลองค้นหาด้วยรหัสหรือชื่อพัสดุชนิดอื่น</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-8 flex justify-center no-print">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('searchInput');
            const grid = document.getElementById('itemsGrid');
            const originalGridHtml = grid.innerHTML; // Store original paginated HTML
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const priceFmt = new Intl.NumberFormat('th-TH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            let t = null;

            function buildItemCard(item) {
                const disabled = Number(item.quantity) === 0;
                const isLowStock = Number(item.quantity) <= 5 && Number(item.quantity) > 0;
                const isOutOfStock = Number(item.quantity) === 0;
                const img = item.item_pic
                    ? `${window.location.origin}/images/items/${item.item_pic}`
                    : '';
                const imgTag = img
                    ? `<img src="${img}" alt="${item.name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-350">`
                    : `<div class="flex flex-col items-center gap-1.5 text-slate-300">
                           <i class="fa-solid fa-image text-3xl opacity-20"></i>
                           <span class="text-[9px] font-bold">ไม่มีภาพพัสดุ</span>
                       </div>`;

                let overlayBadge = '';
                if (isLowStock) {
                    overlayBadge = '<div class="absolute top-2.5 left-2.5"><span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[9px] font-bold border border-amber-200 shadow-sm">สต็อกต่ำ</span></div>';
                } else if (isOutOfStock) {
                    overlayBadge = '<div class="absolute top-2.5 left-2.5"><span class="px-2 py-0.5 bg-red-50 text-red-700 rounded text-[9px] font-bold border border-red-200 shadow-sm">พัสดุหมด</span></div>';
                }

                return `
                    <div class="bg-white rounded border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group overflow-hidden">
                        <div class="aspect-square bg-slate-50/50 flex items-center justify-center p-3 relative overflow-hidden border-b border-slate-200">
                            ${imgTag}
                            ${overlayBadge}
                        </div>
                        <div class="p-3 flex flex-col flex-1 justify-between space-y-3">
                            <div class="space-y-1.5">
                                <span class="text-[9px] font-bold text-slate-400 font-mono tracking-wider">${item.item_code ?? 'CODE-NULL'}</span>
                                <h2 class="text-[12px] font-bold text-slate-800 line-clamp-2 leading-tight uppercase min-h-[32px]">${item.name}</h2>
                            </div>
                            <div class="border border-slate-200 rounded divide-y divide-slate-100 text-[10px]">
                                <div class="p-1.5 flex justify-between items-center bg-slate-50/50">
                                    <span class="text-slate-400">สต็อกคงเหลือ</span>
                                    <span class="font-bold ${Number(item.quantity) <= 5 ? 'text-red-600' : 'text-emerald-700'}">${item.quantity} ชิ้น</span>
                                </div>
                                <div class="p-1.5 flex justify-between items-center">
                                    <span class="text-slate-400">ราคาต่อหน่วย</span>
                                    <span class="font-bold text-slate-700">฿${priceFmt.format(Number(item.per_unit ?? 0))}</span>
                                </div>
                            </div>
                            <form method="POST" action="${window.location.origin}/cartitem/add" class="w-full m-0 p-0">
                                <input type="hidden" name="_token" value="${csrf}">
                                <input type="hidden" name="item_id" value="${item.item_id}">
                                <div class="flex items-stretch gap-1">
                                    <input name="quantity" type="number" min="1" max="${item.quantity ?? 0}" value="1" class="w-12 h-8 text-center bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-500 focus:outline-none outline-none transition-all" ${disabled ? 'disabled' : ''}>
                                    <button type="submit" class="flex-1 h-8 bg-red-600 hover:bg-red-700 text-white rounded font-bold text-[10px] uppercase transition-all disabled:bg-slate-150 disabled:text-slate-400 disabled:shadow-none active:scale-95 flex items-center justify-center gap-1 shadow-sm" ${disabled ? 'disabled' : ''}>
                                        <i class="fa-solid fa-plus text-[8px]"></i> <span class="hidden sm:inline">เพิ่มลงตะกร้า</span><span class="sm:hidden">ใส่ตะกร้า</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>`;
            }

            function render(items) {
                if (!Array.isArray(items) || items.length === 0) {
                    grid.innerHTML = `
                        <div class="col-span-full py-16 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-150">
                                <i class="fa-solid fa-magnifying-glass text-slate-300"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">ไม่พบอุปกรณ์พัสดุที่ต้องการ</h3>
                            <p class="text-slate-400 text-[10px] mt-1">ทดลองค้นหาด้วยรหัสหรือชื่อพัสดุชนิดอื่น</p>
                        </div>`;
                    return;
                }
                grid.innerHTML = items.map(buildItemCard).join('');
            }

            async function search(q) {
                const paginationEl = document.getElementById('paginationContainer');
                if (q && q.trim() !== '') {
                    if (paginationEl) paginationEl.classList.add('hidden');
                } else {
                    if (paginationEl) paginationEl.classList.remove('hidden');
                    grid.innerHTML = originalGridHtml; // Restore original paginated view
                    return;
                }
                const url = new URL(`${window.location.origin}/items/search`);
                if (q && q.trim() !== '') url.searchParams.set('query', q.trim());
                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                render(data.data ?? []);
            }

            input?.addEventListener('input', (e) => {
                const q = e.target.value;
                clearTimeout(t);
                t = setTimeout(() => search(q), 300);
            });
        })();
    </script>
@endsection