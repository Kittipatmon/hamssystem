<div class="flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg ring-1 ring-gray-200">
        <div class="flex items-start justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800" id="downItemsStockTypeModalLabel">ลด stock อุปกรณ์</h2>
            <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-modal-close>
                <span class="sr-only">Close</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-5">
            <form action="{{ route('items.downstock', $item->item_id) }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="items_type_id" class="block text-sm font-medium text-gray-700 mb-1">ชื่ออุปกรณ์</label>
                    <input type="text" name="items_type_id" value="{{ $item->name }}" readonly
                           class="w-full rounded-md bg-gray-50 border border-gray-300 px-3 py-2 text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">จำนวน (ชิ้น)</label>
                    <input type="text" name="quantity"
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="กรอกจำนวน" />
                </div>
                <!-- <div>
                    <label for="items_per_pack" class="block text-sm font-medium text-gray-700 mb-1">จำนวน (แพ็ค)</label>
                    <input type="text" name="items_per_pack"
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div> -->
                <div class="pt-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>