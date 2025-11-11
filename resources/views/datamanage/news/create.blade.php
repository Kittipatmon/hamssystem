@extends('layouts.datamanagement.app')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-3">
        <h1 class="text-xl font-bold">เพิ่มข่าวสารใหม่</h1>
        <a href="{{ route('datamanage.news.index') }}" class="btn btn-secondary btn-sm">ย้อนกลับ</a>
    </div>

    <div class="bg-white p-6 rounded shadow border border-gray-300 rounded-xl">
        <form method="POST" action="{{ route('datamanage.news.store') }}" enctype="multipart/form-data">
            @include('datamanage.news._form')
        </form>
    </div>
</div>
@endsection
