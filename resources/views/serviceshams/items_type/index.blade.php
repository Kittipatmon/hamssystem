@extends('layouts.serviceitem.appservice')
@section('content')
<div class="mx-auto max-w-8xl py-3 rounded-lg bg-white/40 p-3 shadow-md">
	<div class="flex items-center justify-between mb-6">
		<h1 class="text-lg font-semibold text-gray-800">ประเภทอุปกรณ์ (Items Types)</h1>
		<button id="btnOpenCreate" class="btn btn-success btn-sm text-white rounded shadow"><i class="fa-solid fa-plus"></i> เพิ่มประเภท</button>
	</div>

	{{-- Flash Messages --}}
	@if(session('success'))
		<div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('success') }}</div>
	@endif
	@if(session('error'))
		<div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">{{ session('error') }}</div>
	@endif

	<div class="overflow-x-auto">
		<table class="min-w-full divide-y divide-gray-200">
			<thead class="bg-gray-50">
				<tr>
					<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
					<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
					<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รายละเอียด</th>
					<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
					<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@forelse($items_types as $type)
					<tr class="hover:bg-gray-50">
						<td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
						<td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $type->name }}</td>
						<td class="px-4 py-2 text-sm text-gray-700">{{ $type->description ?? '-' }}</td>
						<td class="px-4 py-2 text-sm">
							<form action="{{ route('items_type.toggleStatus', $type->item_type_id) }}" method="POST" class="inline">
								@csrf
								<button type="submit" class="px-2 py-1 text-xs rounded {{ $type->status ? 'bg-green-100 text-green-700' : 'bg-red-500 text-white' }}">
									{{ $type->status ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
								</button>
							</form>
						</td>
						<td class="px-4 py-2 text-sm">
							<button 
								class="btn btn-warning btn-sm"
								data-edit
								data-id="{{ $type->item_type_id }}"
								data-name="{{ $type->name }}"
								data-description="{{ $type->description }}"
							>
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
							<button 
								class="btn btn-error btn-sm text-white"
								data-delete
								data-id="{{ $type->item_type_id }}"
								data-name="{{ $type->name }}"
							><i class="fa-solid fa-trash"></i></button>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="5" class="px-4 py-6 text-center text-gray-500">ไม่มีข้อมูล</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>

{{-- Create Modal --}}
<div id="modalCreate" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40 p-4">
	<div class="bg-white w-full max-w-md rounded shadow-lg">
		<div class="border-b px-6 py-4 flex justify-between items-center">
			<h2 class="text-lg font-semibold">เพิ่มประเภทอุปกรณ์</h2>
			<button class="text-gray-500 hover:text-gray-700" data-close>Create</button>
		</div>
		<form action="{{ route('items_type.store') }}" method="POST" class="px-6 py-4 space-y-4">
			@csrf
			<div>
				<label class="block text-sm font-medium text-gray-700">ชื่อ *</label>
				<input type="text" name="name" required class="mt-1 w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500"/>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">รายละเอียด</label>
				<textarea name="description" rows="3" class="mt-1 w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
			</div>
			<div class="flex justify-end space-x-2 pt-2">
				<button type="button" data-close class="px-4 py-2 rounded bg-gray-200 text-gray-700">ยกเลิก</button>
				<button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">บันทึก</button>
			</div>
		</form>
	</div>
	<span class="sr-only">Create Modal</span>
</div>

{{-- Edit Modal --}}
<div id="modalEdit" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40 p-4">
	<div class="bg-white w-full max-w-md rounded shadow-lg">
		<div class="border-b px-6 py-4 flex justify-between items-center">
			<h2 class="text-lg font-semibold">แก้ไขประเภทอุปกรณ์</h2>
			<button class="text-gray-500 hover:text-gray-700" data-close>Edit</button>
		</div>
		<form id="formEdit" method="POST" class="px-6 py-4 space-y-4">
			@csrf
			@method('PUT')
			<div>
				<label class="block text-sm font-medium text-gray-700">ชื่อ *</label>
				<input id="editName" type="text" name="name" required class="mt-1 w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500"/>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700">รายละเอียด</label>
				<textarea id="editDescription" name="description" rows="3" class="mt-1 w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
			</div>
			<div class="flex justify-end space-x-2 pt-2">
				<button type="button" data-close class="px-4 py-2 rounded bg-gray-200 text-gray-700">ยกเลิก</button>
				<button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">บันทึกการแก้ไข</button>
			</div>
		</form>
	</div>
	<span class="sr-only">Edit Modal</span>
</div>

{{-- Delete Modal --}}
<div id="modalDelete" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40 p-4">
	<div class="bg-white w-full max-w-md rounded shadow-lg">
		<div class="border-b px-6 py-4 flex justify-between items-center">
			<h2 class="text-lg font-semibold">ลบประเภทอุปกรณ์</h2>
			<button class="text-gray-500 hover:text-gray-700" data-close>Delete</button>
		</div>
		<form id="formDelete" method="POST" class="px-6 py-4 space-y-4">
			@csrf
			@method('DELETE')
			<p class="text-sm text-gray-700">คุณต้องการลบ <span id="deleteName" class="font-semibold text-red-600"></span> ใช่หรือไม่?</p>
			<div class="flex justify-end space-x-2 pt-2">
				<button type="button" data-close class="px-4 py-2 rounded bg-gray-200 text-gray-700">ยกเลิก</button>
				<button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">ลบ</button>
			</div>
		</form>
	</div>
	<span class="sr-only">Delete Modal</span>
</div>
@endsection

@push('scripts')
<script>
	(function(){
		const openCreateBtn = document.getElementById('btnOpenCreate');
		const modalCreate = document.getElementById('modalCreate');
		const modalEdit = document.getElementById('modalEdit');
		const modalDelete = document.getElementById('modalDelete');
		const formEdit = document.getElementById('formEdit');
		const formDelete = document.getElementById('formDelete');
		const editName = document.getElementById('editName');
		const editDescription = document.getElementById('editDescription');
		const deleteName = document.getElementById('deleteName');

		function show(modal){ modal.classList.remove('hidden'); modal.classList.add('flex'); }
		function hide(modal){ modal.classList.add('hidden'); modal.classList.remove('flex'); }
		function closeAll(){ [modalCreate, modalEdit, modalDelete].forEach(m => hide(m)); }

		openCreateBtn && openCreateBtn.addEventListener('click', () => show(modalCreate));

		document.querySelectorAll('[data-close]').forEach(btn => {
			btn.addEventListener('click', closeAll);
		});

		// Edit buttons
		document.querySelectorAll('[data-edit]').forEach(btn => {
			btn.addEventListener('click', () => {
				const id = btn.getAttribute('data-id');
				const name = btn.getAttribute('data-name');
				const desc = btn.getAttribute('data-description');
				editName.value = name || '';
				editDescription.value = desc || '';
				formEdit.action = `{{ url('items_type') }}/${id}`; // matches PUT route
				show(modalEdit);
			});
		});

		// Delete buttons
		document.querySelectorAll('[data-delete]').forEach(btn => {
			btn.addEventListener('click', () => {
				const id = btn.getAttribute('data-id');
				const name = btn.getAttribute('data-name');
				deleteName.textContent = name || '';
				formDelete.action = `{{ url('items_type') }}/${id}`; // matches DELETE route
				show(modalDelete);
			});
		});

		// Close on backdrop click
		[modalCreate, modalEdit, modalDelete].forEach(modal => {
			modal.addEventListener('click', e => { if(e.target === modal) hide(modal); });
		});
	})();
</script>
@endpush