@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">หัวข้อ</label>
        <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" class="border rounded-xl p-2 form-input w-full" required>
        @error('title')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">วันที่เผยแพร่</label>
        <input type="date" name="published_date" value="{{ old('published_date', optional($news->published_date)->format('Y-m-d')) }}" class="border rounded-xl p-2 form-input w-full" required>
        @error('published_date')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">กลุ่มผู้รับ (newto)</label>
        <input type="text" name="newto" value="{{ old('newto', $news->newto ?? '') }}" class="border rounded-xl p-2 form-input w-full" placeholder="เช่น บุคลากรทั้งหมด / นักศึกษา ฯลฯ">
        @error('newto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="flex items-center mt-6">
        <input type="checkbox" id="is_active" name="is_active" value="1" class="mr-2" {{ old('is_active', $news->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active">เผยแพร่</label>
        @error('is_active')<div class="text-red-600 text-sm ml-2">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm font-medium mb-1">รายละเอียด</label>
    <textarea name="content" rows="6" class="border rounded-xl p-4 form-textarea w-full" required>{{ old('content', $news->content ?? '') }}</textarea>
    @error('content')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-medium mb-1">รูปภาพประกอบ (ไม่บังคับ) - เลือกได้หลายรูป</label>
    <input type="file" name="images[]" accept="image/*" multiple class="file-input file-input-warning form-input w-full">
    @error('images')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    @error('images.*')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror

    @if(!empty($news->image_path))
        @php
            // รองรับทั้งกรณีที่เก็บเป็น JSON array, comma-separated string หรือ path เดียว
            $decoded = json_decode($news->image_path, true);
            if (is_array($decoded)) {
                $images = $decoded;
            } elseif (is_string($news->image_path) && strpos($news->image_path, ',') !== false) {
                $images = explode(',', $news->image_path);
            } else {
                $images = [$news->image_path];
            }
        @endphp

        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($images as $img)
                @if(!empty($img))
                    <div class="border rounded overflow-hidden">
                        <img src="{{ trim($img) }}" alt="preview" class="h-24 w-24 object-cover"/>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<div class="mt-6 flex justify-between space-x-2">
    <button type="submit" class="btn btn-success text-white">บันทึกข้อมูล</button>
    <a href="{{ route('datamanage.news.index') }}" class="btn btn-secondary">ยกเลิก</a>
</div>
