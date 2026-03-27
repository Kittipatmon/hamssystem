@extends('layouts.sidebar')

@section('title', $type === 'policy' ? 'จัดการนโยบาย' : 'จัดการขั้นตอนการดำเนินงาน')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 p-6 rounded-2xl shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid {{ $type === 'policy' ? 'fa-scroll' : 'fa-list-check' }} text-kumwell-red"></i>
                    {{ $type === 'policy' ? 'จัดการนโยบาย' : 'จัดการขั้นตอนการดำเนินงาน' }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">จัดการข้อมูล{{ $type === 'policy' ? 'นโยบาย' : 'ขั้นตอนการดำเนินงาน' }}ที่แสดงในหน้าหลัก</p>
            </div>
            <a href="{{ route('backend.policy.create', ['type' => $type]) }}"
                class="bg-kumwell-red text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-red-500/30 transition-all duration-300 flex items-center gap-2">
                <i class="fa-solid fa-plus uppercase"></i>
                <span>เพิ่มข้อมูลใหม่</span>
            </a>
        </div>


        <!-- Table Card -->
        <div class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20 text-center">ลำดับ</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">หัวข้อ</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">ประเภท</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($policies as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors">
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-300">{{ $item->order }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800 dark:text-white">{{ $item->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ strip_tags($item->content) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->type === 'policy')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            นโยบาย
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            การดำเนินงาน
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('backend.policy.edit', $item) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 hover:bg-orange-500 hover:text-white transition-all">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('backend.policy.destroy', $item) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข้อมูลนี้?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
