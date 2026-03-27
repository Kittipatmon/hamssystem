@extends('layouts.datamanagement.app')
@section('title', 'แก้ไขข่าวสาร')

@section('content')
<div class="max-w-4xl mx-auto animate-fadeIn">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-medium text-slate-500">
                    <li class="inline-flex items-center">
                        <a href="{{ route('backend.welcomedatamanage') }}" class="hover:text-red-600 transition-colors">แผงควบคุม</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right mx-2 text-[10px] opacity-50"></i>
                            <a href="{{ route('datamanage.news.index') }}" class="hover:text-red-600 transition-colors">จัดการข่าวสาร</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right mx-2 text-[10px] opacity-50"></i>
                            <span class="text-slate-400">แก้ไขข่าวสาร</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                </div>
                แก้ไขข่าวสาร
            </h1>
        </div>
        <a href="{{ route('datamanage.news.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-red-600 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            ย้อนกลับ
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-kumwell-card p-8 md:p-12 rounded-[2rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.1)] border border-white dark:border-gray-800 transition-all hover:shadow-[0_30px_80px_-10px_rgba(0,0,0,0.15)] relative overflow-hidden group">
        <!-- Decorative blob -->
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-orange-50/50 rounded-full blur-3xl group-hover:bg-orange-100/40 transition-colors -z-0"></div>
        
        <form method="POST" action="{{ route('datamanage.news.update', $news) }}" enctype="multipart/form-data" class="relative z-10 space-y-8">
            @csrf
            @method('PUT')
            @include('datamanage.news._form')
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush
