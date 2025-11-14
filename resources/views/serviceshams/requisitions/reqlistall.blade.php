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
			<table class="table table-sm">
				<thead>
					<tr>
						<th class="w-14">#</th>
						<th>เลขที่คำขอ</th>
						<th>ผู้ขอ</th>
						<th>วันที่ขอ</th>
						<th>จำนวนรายการ</th>
						<th>ราคารวม(บาท)</th>
						<th>สถานะจัดส่ง</th>
						<th>สถานะคำขอ</th>
						<th class="w-40">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($requisitions as $req)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>
								<div class="text-sm">{{ $req->requisitions_code ?? '-' }}</div>
							</td>
							<td>
								<div class="text-sm">{{ $req->user->fullname }}</div>
								<!-- <div class="text-xs text-base-content/60">ID: {{ $req->requester_id }}</div> -->
							</td>
							<td class="text-sm">{{ optional($req->request_date)->format('d/m/Y') ?? '-' }}</td>
							<td class="text-sm">
								{{ $req->requisition_items->count() ?? 0 }} รายการ
							</td>
							<td class="text-sm">{{ number_format((float) ($req->total_price ?? 0), 2) }}</td>
							<td>
								<span class="{{ $req->packing_status_class }} badge-sm px-2 py-3">
									<i class="{{ $req->packing_status_icon }} mr-1"></i>
									{{ $req->packing_status_label ?? '—' }}
								</span>
							</td>
							<td>
								<span class="{{ $req->status_class }} badge-sm px-2 py-3">
									<i class="{{ $req->status_icon }} mr-1"></i>
									{{ $req->status_label ?? '—' }}
								</span>
							</td>
							<td class="align-top">
								<a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" class="btn btn-sm btn-info text-white">
									ดูรายละเอียด
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="9" class="text-center">
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