@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-8xl mx-auto">
    <div class="rounded-lg bg-white/40 p-3 shadow-md">
        <div class="flex justify-center">
            <h1 class="text-lg font-semibold text-gray-900">ระบบเบิกอุปกรณ์/ของใช้ภายใน</h1>
            <!-- <input id="searchInput" type="text" placeholder="ค้นหาอุปกรณ์/ของใช้..." class="rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" /> -->
        </div>
            <div class="flex flex-wrap items-center justify-between gap-4 py-2">
                <!-- Category Dropdown -->
                <div class="dropdown dropdown-hover">
                    <label tabindex="0" class="btn btn-ghost flex items-center gap-2 px-3 py-2 rounded-lg text-base font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <span>หมวดหมู่สินค้า</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[100] menu p-2 shadow bg-base-100 rounded-box w-64 mt-2">
                        @php
                        $categories = \App\Models\serviceshams\Items_type::where('status', '1')->get();
                        @endphp
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('items.itemsalllist').'?category='.$category->item_type_id }}" class="flex justify-between items-center py-2 px-2 hover:bg-base-200 rounded text-base">
                                <span>{{ $category->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Search Box -->
                <div class="flex-1 flex justify-end">
                    <div class="relative w-full max-w-md">
                        <input
                            type="text"
                            id="searchInput"
                            name="q"
                            autocomplete="off"
                            value="{{ request('q') }}"
                            placeholder="ค้นหาสินค้า รหัส หรือชื่อสินค้า"
                            class="input input-bordered w-full pl-10 pr-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4-4m0 0A7 7 0 104 4a7 7 0 0013 13z" /></svg>
                        </span>
                    </div>
                </div>
            </div>
        <div class="py-3">
                <div id="itemsGrid" class="grid grid-cols-6">
                @foreach($items as $item)

                <div class="w-60 bg-white rounded-lg border border-gray-200 shadow-md p-3 flex flex-col items-center hover:shadow-lg transition-shadow duration-200 m-2">
                <div class="w-32 h-32 flex items-center justify-center">
                    @if ($item->item_pic)
                    <img src="{{ asset('images/items/' . $item->item_pic) }}" alt="{{ $item->name }}" class="max-h-38 object-contain">
                    @else
                    <div class="w-32 h-32 bg-gray-100 flex items-center justify-center text-xs text-gray-500 rounded">No Image</div>
                    @endif
                </div>
                <h2 class="mt-2 text-[13px] font-medium text-gray-800 text-center line-clamp-2 leading-tight">{{ $item->name }}</h2>
                <p class="mt-1 text-[11px] text-gray-500">รหัส: {{ $item->item_code }}</p>
                <!-- <p class="mt-0.5 text-[11px] text-gray-500">
                    @if($item->quantity > 0)
                    <span class="text-green-600 font-medium">พร้อมใช้งาน</span>
                    @else
                    <span class="text-red-600 font-medium">หมด</span>
                    @endif
                </p> -->
                <div class="flex gap-3">
                <p class="mt-0.5 text-[12px] font-semibold text-green-700">
                    คงเหลือ: {{ $item->quantity }}
                </p>/
                <p class="mt-0.5 text-[12px] font-semibold text-red-400">
                    ฿ {{ number_format($item->per_unit ?? 0,2) }}
                </p></div>
                <form method="POST" action="{{ url('/cartitem/add') }}" class="w-full mt-2">
                    @csrf
                    <!-- Hidden item_id required by controller validation -->
                    <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                    <div class="flex items-center gap-1">
                    <input name="quantity" type="number" min="1" max="{{ $item->quantity }}" value="1"
                           class="w-12 h-6 text-center text-[12px] border border-gray-300 rounded bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
                           @if($item->quantity==0) disabled @endif>
                    <button type="submit"
                        class="flex-1 h-6 text-[11px] font-medium text-white rounded bg-gradient-to-r from-red-600 to-red-700 hover:opacity-90 disabled:from-gray-400 disabled:to-gray-400"
                        @if($item->quantity==0) disabled @endif>
                        เพิ่มในตะกร้า
                    </button>
                    </div>
                </form>
                </div>
                                
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const input = document.getElementById('searchInput');
        const grid = document.getElementById('itemsGrid');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const priceFmt = new Intl.NumberFormat('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        let t = null;

        function buildItemCard(item) {
            const disabled = Number(item.quantity) === 0;
            const img = item.item_pic
                ? `<img src="${window.location.origin}/images/items/${item.item_pic}" alt="${item.name}" class="max-h-38 object-contain">`
                : `<div class="w-32 h-32 bg-gray-100 flex items-center justify-center text-xs text-gray-500 rounded">No Image</div>`;

            return `
            <div class="w-60 bg-white rounded-lg border border-gray-200 shadow-md p-3 flex flex-col items-center hover:shadow-lg transition-shadow duration-200 m-2">
                <div class="w-32 h-32 flex items-center justify-center">${img}</div>
                <h2 class="mt-2 text-[13px] font-medium text-gray-800 text-center line-clamp-2 leading-tight">${item.name}</h2>
                <p class="mt-1 text-[11px] text-gray-500">รหัส: ${item.item_code ?? ''}</p>
                <div class="flex gap-3">
                    <p class="mt-0.5 text-[12px] font-semibold text-green-700">คงเหลือ: ${item.quantity ?? 0}</p>/
                    <p class="mt-0.5 text-[12px] font-semibold text-red-400">฿ ${priceFmt.format(Number(item.per_unit ?? 0))}</p>
                </div>
                <form method="POST" action="${window.location.origin}/cartitem/add" class="w-full mt-2">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="item_id" value="${item.item_id}">
                    <div class="flex items-center gap-1">
                        <input name="quantity" type="number" min="1" max="${item.quantity ?? 0}" value="1" class="w-12 h-6 text-center text-[12px] border border-gray-300 rounded bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500" ${disabled ? 'disabled' : ''}>
                        <button type="submit" class="flex-1 h-6 text-[11px] font-medium text-white rounded bg-gradient-to-r from-red-600 to-red-700 hover:opacity-90 disabled:from-gray-400 disabled:to-gray-400" ${disabled ? 'disabled' : ''}>
                            เพิ่มในตะกร้า
                        </button>
                    </div>
                </form>
            </div>`;
        }

        function render(items) {
            if (!Array.isArray(items) || items.length === 0) {
                grid.innerHTML = '<div class="col-span-6 text-center text-gray-500 py-6">ไม่พบรายการ</div>';
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
@endsection