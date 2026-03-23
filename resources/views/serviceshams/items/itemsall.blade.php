@extends('layouts.serviceitem.appservice')

@section('content')
    <div class="max-w-[1600px] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

        <!-- Header & Search Box -->
        <div class="relative z-[50] bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                        <i class="fa-solid fa-cart-shopping text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tighter leading-none">ระบบเบิกพัสดุ / ของใช้</h1>
                        <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-mono">HAMS CATALOG</span>
                            <span>•</span>
                            <span class="uppercase">เลือกรายการที่ต้องการและเพิ่มลงในตะกร้า</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 flex-1 max-w-3xl">
                    <!-- Search Box -->
                    <div class="relative flex-1">
                        <input type="text" id="searchInput" name="q" autocomplete="off" value="{{ request('q') }}"
                            placeholder="ค้นหาพัสดุ รหัส หรือชื่อ..."
                            class="w-full h-14 pl-14 pr-6 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none placeholder:text-slate-300" />
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300">
                            <i class="fa-solid fa-magnifying-glass text-xl"></i>
                        </div>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="dropdown dropdown-end sm:w-80">
                        @php
                            $selectedCategory = \App\Models\serviceshams\Items_type::find(request('category'));
                            $categories = \App\Models\serviceshams\Items_type::where('status', '1')->get();
                        @endphp
                        <label tabindex="0" class="w-full h-14 bg-red-600 text-white rounded-2xl flex items-center justify-between px-6 cursor-pointer hover:bg-red-700 transition-all shadow-xl shadow-red-100 active:scale-95 border-b-4 border-red-800">
                            <div class="flex items-center gap-3 font-black text-sm uppercase">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-layer-group text-white"></i>
                                </div>
                                <div class="flex flex-col items-start leading-none text-left">
                                    <span class="text-[9px] opacity-70 mb-0.5">FILTER CATEGORY</span>
                                    <span class="truncate max-w-[120px] font-black">{{ $selectedCategory->name ?? 'ทุกหมวดหมู่' }}</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs opacity-70"></i>
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[300] menu p-4 shadow-2xl bg-white rounded-[2.5rem] w-full sm:w-[22rem] mt-4 border border-slate-100 space-y-1">
                            <div class="px-5 py-2 mb-2">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Select a Category</p>
                            </div>
                            <li>
                                <a href="{{ route('items.itemsalllist') }}" class="flex items-center gap-4 py-4 px-6 {{ !request('category') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-2xl font-black transition-all mb-1 border-b border-slate-50">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ !request('category') ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                        <i class="fa-solid fa-border-all text-sm"></i>
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <span class="text-sm">ทั้งหมด</span>
                                        <span class="text-[9px] opacity-50 font-bold uppercase">All Items Catalog</span>
                                    </div>
                                </a>
                            </li>
                            <div class="grid grid-cols-1 gap-1 max-h-[400px] overflow-y-auto pr-1">
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('items.itemsalllist').'?category='.$category->item_type_id }}" 
                                       class="flex items-center gap-4 py-3.5 px-6 {{ request('category') == $category->item_type_id ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-2xl font-black transition-all">
                                        <div class="w-2 h-2 rounded-full {{ request('category') == $category->item_type_id ? 'bg-red-600' : 'bg-slate-200 opacity-50' }}"></div>
                                        <span class="text-[13px]">{{ $category->name }}</span>
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
        <div id="itemsGrid"
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 sm:gap-6">
            @forelse($items as $item)
                <div
                    class="bg-white rounded-2xl sm:rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-100 sm:hover:-translate-y-2 transition-all duration-300 flex flex-col group overflow-hidden">
                    <!-- Image Section -->
                    <div class="aspect-square bg-slate-50/50 flex items-center justify-center p-4 sm:p-8 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-white/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        @if ($item->item_pic)
                            <img src="{{ asset('images/items/' . $item->item_pic) }}" alt="{{ $item->name }}"
                                class="w-full h-full object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="flex flex-col items-center gap-3 text-slate-300">
                                <i class="fa-solid fa-image text-4xl opacity-20"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">No Preview</span>
                            </div>
                        @endif

                        <!-- Stock Badge Overlay -->
                        @if($item->quantity <= 5)
                            <div class="absolute top-4 left-4">
                                <span
                                    class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[9px] font-black uppercase shadow-sm border border-red-200">
                                    Low Stock
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content Section -->
                    <div class="p-3 sm:p-6 flex flex-col items-center text-center flex-1">
                        <span
                            class="text-[8px] sm:text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1 leading-none">{{ $item->item_code ?? 'CODE-NULL' }}</span>
                        <h2
                            class="text-[12px] sm:text-[14px] font-black text-slate-800 line-clamp-2 leading-tight h-8 sm:h-10 group-hover:text-red-600 transition-colors uppercase">
                            {{ $item->name }}</h2>

                        <div class="w-full h-px bg-slate-50 my-3 sm:my-4"></div>

                        <div class="flex items-center justify-center gap-3 sm:gap-6 mb-4 sm:mb-6">
                            <div class="flex flex-col">
                                <span class="text-[7.5px] sm:text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">In Stock</span>
                                <span class="text-[14px] sm:text-[16px] font-black text-emerald-600 font-mono leading-none">{{ $item->quantity }}</span>
                            </div>
                            <div class="w-px h-6 bg-slate-100"></div>
                            <div class="flex flex-col">
                                <span class="text-[7.5px] sm:text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Price/Unit</span>
                                <span
                                    class="text-[14px] sm:text-[16px] font-black text-red-600 font-mono leading-none">฿{{ number_format($item->per_unit ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        <form method="POST" action="{{ url('/cartitem/add') }}" class="w-full mt-auto">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                            <div class="flex items-stretch gap-2">
                                <input name="quantity" type="number" min="1" max="{{ $item->quantity }}" value="1"
                                    class="w-10 sm:w-16 h-10 sm:h-12 text-center text-[12px] sm:text-[14px] font-black bg-slate-50 border-2 border-slate-50 rounded-xl sm:rounded-2xl focus:bg-white focus:border-red-200 focus:outline-none transition-all"
                                    @if($item->quantity == 0) disabled @endif>
                                <button type="submit"
                                    class="flex-1 h-10 sm:h-12 bg-red-600 hover:bg-red-700 text-white rounded-xl sm:rounded-2xl font-black text-[10px] sm:text-[12px] uppercase transition-all shadow-lg shadow-red-100 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none active:scale-95 flex items-center justify-center gap-1.5"
                                    @if($item->quantity == 0) disabled @endif>
                                    <i class="fa-solid fa-plus text-[9px] sm:text-[10px]"></i>
                                    ADD CART
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-magnifying-glass text-slate-200 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 uppercase">ไม่พบพัสดุที่ต้องการ</h3>
                    <p class="text-slate-400 font-bold mt-2 uppercase text-xs">No items found matching your criteria</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('searchInput');
            const grid = document.getElementById('itemsGrid');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const priceFmt = new Intl.NumberFormat('th-TH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            let t = null;

            function buildItemCard(item) {
                const disabled = Number(item.quantity) === 0;
                const isLowStock = Number(item.quantity) <= 5;
                const img = item.item_pic
                    ? `<img src="${window.location.origin}/images/items/${item.item_pic}" alt="${item.name}" class="w-full h-full object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-500">`
                    : `<div class="flex flex-col items-center gap-3 text-slate-300">
                        <i class="fa-solid fa-image text-3xl sm:text-4xl opacity-20"></i>
                        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest">No Preview</span>
                      </div>`;

                return `
                <div class="bg-white rounded-2xl sm:rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-100 sm:hover:-translate-y-2 transition-all duration-300 flex flex-col group overflow-hidden animate-zoom-in">
                    <div class="aspect-square bg-slate-50/50 flex items-center justify-center p-4 sm:p-8 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-white/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        ${img}
                        ${isLowStock ? '<div class="absolute top-3 sm:top-4 left-3 sm:left-4"><span class="px-2 sm:px-3 py-0.5 sm:py-1 bg-red-100 text-red-600 rounded-full text-[8px] sm:text-[9px] font-black uppercase shadow-sm border border-red-200">Low Stock</span></div>' : ''}
                    </div>
                    <div class="p-3 sm:p-6 flex flex-col items-center text-center flex-1">
                        <span class="text-[8px] sm:text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1 leading-none">${item.item_code ?? 'CODE-NULL'}</span>
                        <h2 class="text-[12px] sm:text-[14px] font-black text-slate-800 line-clamp-2 leading-tight h-8 sm:h-10 group-hover:text-red-600 transition-colors uppercase">${item.name}</h2>
                        <div class="w-full h-px bg-slate-50 my-3 sm:my-4"></div>
                        <div class="flex items-center justify-center gap-3 sm:gap-6 mb-4 sm:mb-6">
                            <div class="flex flex-col">
                                <span class="text-[7.5px] sm:text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">In Stock</span>
                                <span class="text-[14px] sm:text-[16px] font-black text-emerald-600 font-mono leading-none">${item.quantity ?? 0}</span>
                            </div>
                            <div class="w-px h-6 bg-slate-100"></div>
                            <div class="flex flex-col">
                                <span class="text-[7.5px] sm:text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Price/Unit</span>
                                <span class="text-[14px] sm:text-[16px] font-black text-red-600 font-mono leading-none">฿${priceFmt.format(Number(item.per_unit ?? 0))}</span>
                            </div>
                        </div>
                        <form method="POST" action="${window.location.origin}/cartitem/add" class="w-full mt-auto">
                            <input type="hidden" name="_token" value="${csrf}">
                            <input type="hidden" name="item_id" value="${item.item_id}">
                            <div class="flex items-stretch gap-2">
                                <input name="quantity" type="number" min="1" max="${item.quantity ?? 0}" value="1" class="w-10 sm:w-16 h-10 sm:h-12 text-center text-[12px] sm:text-[14px] font-black bg-slate-50 border-2 border-slate-50 rounded-xl sm:rounded-2xl focus:bg-white focus:border-red-200 focus:outline-none transition-all" ${disabled ? 'disabled' : ''}>
                                <button type="submit" class="flex-1 h-10 sm:h-12 bg-red-600 hover:bg-red-700 text-white rounded-xl sm:rounded-2xl font-black text-[10px] sm:text-[12px] uppercase transition-all shadow-lg shadow-red-100 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none active:scale-95 flex items-center justify-center gap-1.5" ${disabled ? 'disabled' : ''}>
                                    <i class="fa-solid fa-plus text-[9px] sm:text-[10px]"></i> ADD CART
                                </button>
                            </div>
                        </form>
                    </div>
                </div>`;
            }

            function render(items) {
                if (!Array.isArray(items) || items.length === 0) {
                    grid.innerHTML = `
                        <div class="col-span-full py-20 text-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa-solid fa-magnifying-glass text-slate-200 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 uppercase">ไม่พบพัสดุที่ต้องการ</h3>
                            <p class="text-slate-400 font-bold mt-2 uppercase text-xs">Try searching for something else</p>
                        </div>`;
                    return;
                }
                grid.innerHTML = items.map(buildItemCard).join('');
            }

            async function search(q) {
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

    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.4s ease-out forwards;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection