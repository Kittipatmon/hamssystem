@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-full">
	<div class="card bg-base-100 shadow">
		<div class="px-4 text-center rounded-t-2xl bg-gradient-to-r from-orange-500 to-orange-600">
			<nav aria-label="breadcrumb">
				<div class="text-sm breadcrumbs text-white justify-center">
					<ul class="flex items-center justify-center gap-2">
						<li>
							<a href="{{ route('items.itemsalllist') }}" class="text-white/90 hover:text-white font-medium">
								รายการอุปกรณ์
							</a>
						</li>
						<li>
							<span class="font-medium text-white/80">
								<i class="fa-solid fa-list ml-2"></i>
								รายการเบิกของทั้งหมด 
							</span>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		<div class="card-body overflow-x-auto">
            <div class="flex justify-end mb-4 gap-3">
				<button class="btn btn-primary btn-sm text-white" id="filter-button">
                    <i class="fa-solid fa-filter"></i>
                    filter
                </button>
				<a class="btn btn-error btn-sm text-white" href="{{ route('requisitions.reportslistall.export.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
					<i class="fa-solid fa-file-pdf"></i>
					 Export PDF
				</a>
				<a class="btn btn-success btn-sm text-white" href="{{ route('requisitions.reportslistall.export.csv', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
					<i class="fa-solid fa-file-excel"></i>
					 Export Excel
				</a>
            </div>
            <div id="filter-section" class="mb-4" style="display: none;">
                <form method="GET" action="{{ route('requisitions.reportslistall') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block mb-1 font-medium">วันที่เริ่มต้น</label>
                            <input type="date" name="start_date" class="input input-bordered w-full" value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">วันที่สิ้นสุด</label>
                            <input type="date" name="end_date" class="input input-bordered w-full" value="{{ request('end_date') }}">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary btn-sm text-white">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                ค้นหา
                            </button>
                        </div>
                    </div>
                </form>
            </div>
			<table id="reqlist-table" class="table table-sm w-full">
				<thead>
					<tr>
						<!-- <th class="w-14">#</th> -->
						<th>เลขที่คำขอ</th>
						<th>ผู้ขอ</th>
                        <th>สายงาน</th>
                        <th>ฝ่าย</th>
                        <th>แผนก</th>
						<th>วันที่ขอ</th>
						<th>จำนวนรายการ</th>
						<th>ราคารวม(บาท)</th>
						<th>สถานะจัดส่ง</th>
						<th>สถานะคำขอ</th>
						<th class="w-20">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($requisitions as $req)
						<tr>
							<!-- <td>{{ $loop->iteration }}</td> -->
							<td>
								<div class="text-sm">{{ $req->requisitions_code ?? '-' }}</div>
							</td>
							<td>
								<div class="text-sm">คุณ{{ $req->user->fullname }}</div>
								<!-- <div class="text-xs text-base-content/60">ID: {{ $req->requester_id }}</div> -->
							</td>
                            <td class="text-sm">{{ $req->user->section->section_code ?? '-' }}</td>
                            <td class="text-sm">{{ $req->user->division->division_name ?? '-' }}</td>
                            <td class="text-sm">{{ $req->user->department->department_name ?? '-' }}</td>
							<td class="text-sm">{{ optional($req->request_date)->format('d/m/Y') ?? '-' }}</td>
							<td class="text-sm">
								{{ $req->requisition_items->count() ?? 0 }} รายการ
							</td>
							<td class="text-sm">{{ number_format((float) ($req->total_price ?? 0), 2) }}</td>
							<td>
								<span class="{{ $req->packing_status_class }} badge-sm px-2 py-3">
									<i class="{{ $req->packing_status_icon }}"></i>
									{{ $req->packing_status_label ?? '—' }}
								</span>
							</td>
							<td>
								<span class="{{ $req->status_class }} badge-sm px-2 py-3">
									<i class="{{ $req->status_icon }}"></i>
									{{ $req->status_label ?? '—' }}
								</span>
							</td>
							<td class="align-top">
								<a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" class="btn btn-xs btn-info text-white">
									<i class="fa-regular fa-eye"></i>
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="11" class="text-center">
								<div class="py-10 text-base-content/60">ไม่พบข้อมูลคำขอ</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

@push('styles')
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const table = $('#reqlist-table').DataTable({
				language: {
					url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json'
				},
				pageLength: 25,
				lengthMenu: [10, 25, 50, 100],
				order: [],
				columnDefs: [
					{ orderable: false, targets: [10] } // ปิดการ sort คอลัมน์ Actions
				]
			});

			// Toggle filter section
			const filterBtn = document.getElementById('filter-button');
			const filterSection = document.getElementById('filter-section');
			if (filterBtn && filterSection) {
				filterBtn.addEventListener('click', function () {
					const isHidden = filterSection.style.display === 'none' || filterSection.style.display === '';
					filterSection.style.display = isHidden ? 'block' : 'none';
				});
			}
		});
	</script>
@endpush