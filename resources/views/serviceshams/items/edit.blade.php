@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-5xl mx-auto py-6  bg-white p-6 rounded shadow">
	<h1 class="text-xl font-semibold mb-6">แก้ไขข้อมูลอุปกรณ์</h1>

	@if ($errors->any())
		<div class="mb-4 p-4 rounded bg-red-50 border border-red-200 text-red-700">
			<strong>เกิดข้อผิดพลาด:</strong>
			<ul class="list-disc pl-5 mt-2 text-sm">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form action="{{ route('items.update', $item->item_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
		@csrf
		@method('PUT')
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<div>
				<label class="block text-sm font-medium mb-1" for="item_code">รหัสอุปกรณ์ (Item Code)</label>
				<input type="text" id="item_code" name="item_code" value="{{ old('item_code', $item->item_code) }}" class="input input-bordered w-full" required>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1" for="name">ชื่ออุปกรณ์ (Name)</label>
				<input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" class="input input-bordered w-full" required>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1" for="type_id">ประเภท (Type)</label>
				<select id="type_id" name="type_id" class="input input-bordered w-full" required>
					<option value="">-- เลือกประเภท --</option>
					@foreach($items_types as $type)
						<option value="{{ $type->item_type_id }}" @selected(old('type_id', $item->type_id) == $type->item_type_id)>{{ $type->name }}</option>
					@endforeach
				</select>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1" for="quantity">จำนวน (Quantity)</label>
				<input type="number" id="quantity" name="quantity" min="0" value="{{ old('quantity', $item->quantity) }}" class="input input-bordered w-full" required>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1" for="per_unit">ราคา/หน่วย (Price per Unit)</label>
				<input type="number" step="0.01" id="per_unit" name="per_unit" value="{{ old('per_unit', $item->per_unit) }}" class="input input-bordered w-full" required>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1" for="item_pic">รูปภาพ (Image)</label>
				<input type="file" id="item_pic" name="item_pic" accept="image/*" class="file-input file-input-warning w-full">
				@if($item->item_pic)
					<div class="mt-2">
						<img src="{{ asset('images/items/'.$item->item_pic) }}" alt="Current Image" class="h-28 rounded border border-gray-300/60">
					</div>
				@endif
			</div>
		</div>
		<div>
			<label class="block text-sm font-medium mb-1" for="description">รายละเอียด (Description)</label>
			<textarea id="description" name="description" rows="4" class="textarea textarea-bordered w-full" placeholder="รายละเอียดเพิ่มเติม">{{ old('description', $item->description) }}</textarea>
		</div>
		<div class="flex items-center space-x-4">
			<button type="submit" class="btn btn-success text-white">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                 บันทึกข้อมูล
            </button>
			<a href="{{ route('items.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-cancel mr-1"></i>
                ยกเลิก
            </a>
		</div>
	</form>
</div>
@endsection