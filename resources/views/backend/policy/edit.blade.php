@extends('layouts.sidebar')

@section('title', 'แก้ไขข้อมูล' . ($policy->type === 'operation' ? 'ขั้นตอนการดำเนินงาน' : 'นโยบาย'))

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('backend.policy.index', ['type' => $policy->type]) }}" class="text-sm text-gray-500 hover:text-kumwell-red flex items-center gap-1 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> กลับไปหน้ารายการ
            </a>
        </div>

        <div class="bg-white dark:bg-kumwell-card border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">แก้ไข{{ $policy->type === 'operation' ? 'ขั้นตอนการดำเนินงาน' : 'นโยบาย' }}</h2>


            <form action="{{ route('backend.policy.update', $policy) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">หัวข้อ</label>
                    <input type="text" name="title" value="{{ old('title', $policy->title) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red outline-none transition-all dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">เนื้อหา / รายละเอียด</label>
                    <textarea name="content" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red outline-none transition-all dark:text-white">{{ old('content', $policy->content) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ประเภท</label>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red outline-none transition-all dark:text-white">
                            <option value="policy" {{ $policy->type === 'policy' ? 'selected' : '' }}>นโยบาย (Policy)</option>
                            <option value="operation" {{ $policy->type === 'operation' ? 'selected' : '' }}>การดำเนินงาน (Operation)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ลำดับการแสดงผล</label>
                        <input type="number" name="order" value="{{ old('order', $policy->order) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red outline-none transition-all dark:text-white">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-kumwell-red text-white font-bold py-4 rounded-xl shadow-lg shadow-red-500/20 hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all duration-300">
                        อัปเดตข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
