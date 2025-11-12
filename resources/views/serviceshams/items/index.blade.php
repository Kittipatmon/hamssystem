@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-8xl mx-auto">
    <div class="rounded-lg bg-white/40 p-3 shadow-md">
        <div class="flex justify-between">
             <h1 class="text-xl font-semibold text-gray-900">รายการอุปกรณ์</h1>
            <a href="{{ route('items.create') }}"
               class="btn btn-success btn-sm text-white">
               <i class="fa-solid fa-plus mr-1"></i>
                เพิ่มอุปกรณ์ใหม่
            </a>
        </div>
        <div class="overflow-x-auto py-3 rounded-xl">
            <table id="itemsTable" class="table table-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2">รูป</th>
                        <th class="px-4 py-2">รหัสอุปกรณ์</th>
                        <th class="px-4 py-2">ชื่ออุปกรณ์</th>
                        <th class="px-4 py-2">ประเภทอุปกรณ์</th>
                        <th class="px-4 py-2">จำนวนคงเหลือ</th>
                        <th class="px-4 py-2">ราคา/ชิ้น</th>
                        <th class="px-4 py-2">คำอธิบาย</th>
                        <th class="px-4 py-2">สถานะ</th>
                        <th class="px-4 py-2" style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-2">
                                @if ($item->item_pic)
                                    <button type="button" onclick="document.getElementById('itemPicModal-{{ $item->item_id }}').showModal()">
                                        <img src="{{ asset('images/items/'.$item->item_pic) }}" alt="Item Image" class="h-20 w-20 object-cover rounded hover:opacity-80 transition">
                                    </button>
                                    <dialog id="itemPicModal-{{ $item->item_id }}" class="modal">
                                        <div class="modal-box max-w-2xl">
                                            <h3 class="font-bold mb-3">ภาพ: {{ $item->name }}</h3>
                                            <img src="{{ asset('images/items/'.$item->item_pic) }}" alt="Item Full Image" class="w-full h-120 rounded shadow">
                                            <div class="modal-action">
                                                <form method="dialog">
                                                    <button class="btn">ปิด</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $item->item_code }}</td>
                            <td class="px-4 py-2">{{ $item->name }}</td>
                            <td class="px-4 py-2">
                                {{ $item->items_type ? $item->items_type->name : 'N/A' }}
                            </td>
                            <td class="px-4 py-2">
                                @if($item->quantity <= 5)
                                    <span style="color: red;">{{ $item->quantity }}</span>
                                @else
                                    <span style="color: green;">{{ $item->quantity }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $item->per_unit }}</td>
                            <td class="px-4 py-2">
                                {{ Str::limit($item->description, 40) }}
                                @if(Str::length($item->description) > 40)
                                    <button type="button" class="btn btn-info btn-xs ml-1"
                                        onclick="document.getElementById('descModal-{{ $item->item_id }}').showModal()">
                                        ดูเพิ่มเติม
                                    </button>
                                    <dialog id="descModal-{{ $item->item_id }}" class="modal">
                                        <div class="modal-box">
                                            <h3 class="font-bold text-lg">คำอธิบาย: {{ $item->name }}</h3>
                                            <p class="py-4">{{ $item->description }}</p>
                                            <div class="modal-action">
                                                <form method="dialog">
                                                    <button class="btn">ปิด</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($item->quantity > 0)
                                <span class="badge badge-success badge-sm">มีอุปกรณ์</span>
                                @else
                                <span class="badge badge-error badge-sm">ไม่มีอุปกรณ์</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <!-- addITems -->
                                <button type="button" class="btn btn-success btn-xs text-white"
                                    onclick="document.getElementById('addItemsModal-{{ $item->item_id }}').showModal()" title="เพิ่ม stock"> 
                                    <i class="fa-solid fa-up-long"></i>
                                </button>
                                <dialog id="addItemsModal-{{ $item->item_id }}" class="modal">
                                    <div class="modal-box">
                                        @include('serviceshams.items.addstock', ['item' => $item])
                                        <div class="modal-action">
                                            <form method="dialog">
                                                <button class="btn">ปิด</button>
                                            </form>
                                        </div>
                                    </div>
                                </dialog>

                                <!-- downITems -->
                                <button type="button" class="btn btn-primary btn-xs text-white"
                                    onclick="document.getElementById('downItemsModal-{{ $item->item_id }}').showModal()" title="ลด stock"> 
                                    <i class="fa-solid fa-down-long"></i>
                                </button>
                                <dialog id="downItemsModal-{{ $item->item_id }}" class="modal">
                                    <div class="modal-box">
                                        @include('serviceshams.items.downstock', ['item' => $item])
                                        <div class="modal-action">
                                            <form method="dialog">
                                                <button class="btn">ปิด</button>
                                            </form>
                                        </div>
                                    </div>
                                </dialog>

                                <a href="{{ route('items.edit', $item->item_id) }}" class="btn btn-warning btn-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-xs text-white"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ',
        text: @json(session('success')),
        timer: 2500,
        showConfirmButton: false
    });
@endif
</script>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#itemsTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']], // sort by item code
                columnDefs: [
                    { orderable: false, targets: [0, 6, 7, 8] }, // image, description, status, actions
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
                }
            });
        });
    </script>
@endpush