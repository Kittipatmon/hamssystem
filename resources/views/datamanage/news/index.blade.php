@extends('layouts.datamanagement.app')
@section('content')
<div class="max-w-8xl mx-auto border border-gray-300/60 bg-white p-6 rounded-xl shadow-xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">ข้อมูลข่าวสาร</h1>
        <a href="{{ route('datamanage.news.create') }}" class="btn btn-success text-white btn-sm">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มข่าวสารใหม่
        </a>
    </div>

    <!-- @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif -->

    <div class="bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                        รูป
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">วันที่เผยแพร่</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">หัวข้อ</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($news as $item)
                    <tr>
                        <td>
                            @php
                                $paths = [];
                                if (method_exists($item, 'imagePaths')) {
                                    $paths = (array) $item->imagePaths();
                                } else {
                                    $raw = $item->image_path ?? ($item->primaryImagePath() ?? '');
                                    if (is_array($raw)) {
                                        $paths = $raw;
                                    } elseif (is_string($raw) && $raw !== '') {
                                        $decoded = json_decode($raw, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $paths = $decoded;
                                        } else {
                                            $paths = array_filter(array_map('trim', preg_split('/\s*,\s*/', $raw)));
                                        }
                                    }
                                }
                                if (empty($paths)) {
                                    $paths = ['images/welcome/news1.jpg'];
                                }

                                $imageUrls = [];
                                foreach ($paths as $p) {
                                    $p = (string) $p;
                                    if ($p === '') continue;

                                    if (preg_match('/^(https?:)?\/\//i', $p)) {
                                        $imageUrls[] = $p;
                                    } else {
                                        $slashed = str_replace('\\', '/', $p);
                                        $normalized = preg_replace('#^/?(?:public/)?#i', '', $slashed);
                                        $normalized = ltrim($normalized, '/');
                                        $imageUrls[] = asset($normalized);
                                    }
                                }
                                if (empty($imageUrls)) {
                                    $imageUrls[] = asset('images/welcome/news1.jpg');
                                }
                            @endphp

                            <div class="flex items-center space-x-2">
                                @foreach(array_slice($imageUrls, 0, 3) as $url)
                                    <img src="{{ $url }}" alt="รูปข่าว" class="w-16 h-16 object-cover rounded">
                                @endforeach
                                @if(count($imageUrls) > 3)
                                    <span class="w-16 h-16 flex items-center justify-center text-xs bg-gray-100 rounded">
                                        +{{ count($imageUrls) - 3 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ optional($item->published_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-sm font-semibold text-gray-800">{{ $item->title }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($item->is_active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">เผยแพร่</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">ปิด</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <div class="flex space-x-2">
                                <form action="{{ route('datamanage.news.notifyOutlook', $item) }}" method="POST" class="space-y-1 relative" onsubmit="return confirm('ส่งอีเมลแจ้งเตือน Outlook สำหรับข่าวนี้?');">
                                    @csrf
                                    @php
                                        $notifyEmailOptions = [
                                            'Kittiphan.Bu@kumwell.com',
                                            'hr@kumwell.com',
                                            'sale@kumwell.com',
                                        ];
                                    @endphp
                                    <button type="button" class="btn btn-sm btn-outline border-gray-300 text-gray-700 w-full" onclick="toggleEmailPanel(this)">
                                        เลือกอีเมลเพิ่มเติม
                                    </button>
                                    <div class="hidden absolute z-20 top-full left-0 mt-1 w-64 bg-white border border-gray-300 rounded shadow p-3 space-y-2 email-panel">
                                        <div>
                                            <label class="text-[10px] font-medium text-gray-600 mb-1 block">เลือกจากรายการ</label>
                                            <select name="extra_emails[]" multiple class="select2 w-full" data-placeholder="เลือกอีเมล">
                                                @foreach($notifyEmailOptions as $em)
                                                    <option value="{{ $em }}">{{ $em }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-medium text-gray-600 mb-1 block">เพิ่มอีเมลอื่น (คั่น ,)</label>
                                            <input type="text" name="extra_emails_text" placeholder="other1@domain.com, other2@domain.com" class="border rounded px-2 py-1 text-xs w-full focus:outline-none focus:ring" />
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" class="btn btn-xs" onclick="closeEmailPanel(this)">ปิด</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-info text-white w-full">
                                        ส่งไปแจ้งเตือน Outlook
                                    </button>
                                </form>
                                <a href="{{ route('datamanage.news.edit', $item) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('datamanage.news.destroy', $item) }}" onsubmit="return confirm('ยืนยันการลบข่าวนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-error">
                                        <i class="fa-solid fa-trash text-white"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">ไม่มีข้อมูลข่าวสาร</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--multiple {
                min-height: 32px;
                border-color: #d1d5db;
                border-radius: 0.375rem;
                font-size: 0.70rem;
            }
            .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
                font-size: 0.65rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            window.toggleEmailPanel = function(btn){
                const panel = btn.parentElement.querySelector('.email-panel');
                if(panel){ panel.classList.toggle('hidden'); }
            };
            window.closeEmailPanel = function(btn){
                const panel = btn.closest('.email-panel');
                if(panel){ panel.classList.add('hidden'); }
            };
            document.addEventListener('DOMContentLoaded', function(){
                $('.select2').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: function(){
                        return $(this).data('placeholder') || 'เลือก';
                    }
                });
            });
        </script>
    @endpush