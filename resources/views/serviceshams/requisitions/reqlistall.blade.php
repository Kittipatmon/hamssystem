@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-full mx-auto p-2 md:p-4">
	<div class="card bg-base-100 shadow">
		<div class="card-body">
			<div class="flex items-center justify-between mb-3">
				<h1 class="text-xl font-semibold">รายการคำขอทั้งหมด</h1>
			</div>

			<div class="overflow-x-auto">
				<table class="table table-sm">
					<thead>
						<tr>
							<th class="w-14">#</th>
							<th>เลขที่คำขอ</th>
							<th>ผู้ขอ</th>
							<th>วันที่ขอ</th>
							<th>สถานะจัดส่ง</th>
							<th>สถานะคำขอ</th>
							<th class="text-right">ราคารวม</th>
							<th class="w-40">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($requisitions as $req)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									<div class="text-xs text-base-content/60">{{ $req->requisitions_code ?? '-' }}</div>
								</td>
								<td>
									<div class="text-sm">{{ $req->user->fullname }}</div>
									<!-- <div class="text-xs text-base-content/60">ID: {{ $req->requester_id }}</div> -->
								</td>
								<td>{{ optional($req->request_date)->format('d/m/Y') ?? '-' }}</td>
								<td>
									@php
										$packBadge = match((int)($req->packing_staff_status ?? 0)) {
											\App\Models\serviceshams\Requisitions::PACKING_STATUS_APPROVED => 'badge-success',
											\App\Models\serviceshams\Requisitions::PACKING_STATUS_CANCELLED => 'badge-error',
											default => 'badge-warning',
										};
									@endphp
									<span class="badge {{ $packBadge }} badge-sm">{{ $req->packing_status_label ?? '—' }}</span>
								</td>
								<td>
									@php
										$statusBadge = match((string)($req->status ?? '')) {
											\App\Models\serviceshams\Requisitions::STATUS_APPROVED => 'badge-success',
											\App\Models\serviceshams\Requisitions::STATUS_REJECTED => 'badge-error',
											\App\Models\serviceshams\Requisitions::STATUS_RETURNED => 'badge-secondary',
											\App\Models\serviceshams\Requisitions::STATUS_CANCELLED => 'badge-neutral',
											\App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS => 'badge-info',
											default => 'badge-warning',
										};
									@endphp
									<span class="badge {{ $statusBadge }} badge-sm">{{ $req->status_label ?? '—' }}</span>
								</td>
								<td class="text-right">{{ number_format((float) ($req->total_price ?? 0), 2) }}</td>
								<td class="align-top">
									<a href="#" class="btn btn-sm btn-info text-white">
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
</div>
@endsection