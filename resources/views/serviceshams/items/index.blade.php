@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-8xl mx-auto">
    <div class="rounded-lg bg-white/40 p-3 shadow-md">
        <div class="flex justify-between">
             <h1 class="text-xl font-semibold text-gray-900">ข้อมูลอุปกรณ์</h1>
            <a href="{{ route('items.create') }}"
               class="btn btn-success btn-sm text-white">
               <i class="fa-solid fa-plus mr-1"></i>
                เพิ่มอุปกรณ์ใหม่
            </a>
        </div>
        <div class="overflow-x-auto py-3 rounded-xl">
            <table class="table table-sm">
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
                                @if ($item->image)
                                    <img src="{{ asset('storage/items/' . $item->image) }}" alt="Item Image" class="h-12 w-12 object-cover rounded">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $item->item_code }}</td>
                            <td class="px-4 py-2">{{ $item->name }}</td>
                            <td class="px-4 py-2">
                                {{ $item->items_type ? $item->items_type->name : 'N/A' }}
                            </td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2">{{ $item->per_unit }}</td>
                            <td class="px-4 py-2">{{ $item->description }}</td>
                            <td class="px-4 py-2">
                                {{ $item->status == 0 ? 'Active' : 'Inactive' }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('items.edit', $item->item_id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm text-white"
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

@endsection