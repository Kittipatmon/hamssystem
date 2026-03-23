<div class="flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg ring-1 ring-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800" id="downItemsStockTypeModalLabel">ลด stock อุปกรณ์</h2>
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
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            onclick="this.closest('dialog').close();"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>