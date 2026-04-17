@csrf
<div class="space-y-10">
    <!-- Basic Information Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="group">
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">หัวข้อข่าวสาร</label>
            <div class="relative">
                <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" 
                    class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/10 focus:border-blue-500 transition-all outline-none placeholder:text-gray-400 shadow-sm"
                    placeholder="ระบุหัวข้อข่าวสารที่นี่..." required>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">
                    <i class="fa-solid fa-heading"></i>
                </div>
            </div>
            @error('title')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="group">
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">วันที่เผยแพร่</label>
            <div class="relative">
                <input type="date" name="published_date" value="{{ old('published_date', optional($news->published_date)->format('Y-m-d')) }}" 
                    class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/10 focus:border-blue-500 transition-all outline-none shadow-sm"
                    required>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            @error('published_date')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="group">
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">กลุ่มเป้าหมาย (News To)</label>
            <div class="relative">
                <input type="text" name="newto" value="{{ old('newto', $news->newto ?? '') }}" 
                    class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/10 focus:border-blue-500 transition-all outline-none placeholder:text-gray-400 shadow-sm"
                    placeholder="เช่น บุคลากรทั้งหมด, นักศึกษา, พนักงานฝ่ายผลิต...">
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            @error('newto')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        </div>

        <div class="flex items-center">
            <label class="relative inline-flex items-center cursor-pointer group mt-6">
                <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $news->is_active ?? true) ? 'checked' : '' }}>
                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-100 dark:peer-focus:ring-green-900/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                <span class="ml-4 text-sm font-black text-slate-500 peer-checked:text-green-600 transition-colors uppercase tracking-widest">สถานะ: เผยแพร่</span>
            </label>
        </div>
    </div>

    <!-- Content Section -->
    <div class="group">
        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">รายละเอียดข่าวสาร</label>
        <textarea name="content" rows="8" 
            class="w-full bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-3xl p-8 text-sm font-medium text-gray-700 dark:text-gray-200 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/10 focus:border-blue-500 transition-all outline-none shadow-sm leading-relaxed" 
            placeholder="เขียนเนื้อหาข่าวสารของคุณที่นี่..." required>{{ old('content', $news->content ?? '') }}</textarea>
        @error('content')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
    </div>

    <!-- Media Section -->
    <div class="bg-slate-50/50 dark:bg-gray-900/30 rounded-[2.5rem] p-8 border border-dashed border-slate-200 dark:border-gray-700 group hover:border-blue-400 transition-all">
        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
            <i class="fa-solid fa-images text-blue-500"></i> รูปภาพประกอบ (ไม่บังคับ)
        </label>
        
        <div class="flex flex-col items-center justify-center py-6">
            <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cloud-arrow-up text-2xl text-blue-500"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 mb-6">คลิกเพื่อเลือกไฟล์ หรือลากรูปภาพมาวางที่นี่</p>
            
            <input type="file" name="images[]" id="news-images" accept="image/*" multiple 
                class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:uppercase file:tracking-widest file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
        </div>

        @error('images')<p class="text-[10px] font-bold text-red-500 mt-3 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror
        @error('images.*')<p class="text-[10px] font-bold text-red-500 mt-3 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>@enderror

        @if(!empty($news->image_path))
            @php
                $decoded = json_decode($news->image_path, true);
                if (is_array($decoded)) {
                    $images = $decoded;
                } elseif (is_string($news->image_path) && strpos($news->image_path, ',') !== false) {
                    $images = explode(',', $news->image_path);
                } else {
                    $images = [$news->image_path];
                }
            @endphp

            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-gray-700">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">รูปภาพปัจจุบันในระบบ</p>
                <div class="flex flex-wrap gap-4" id="current-images-preview">
                    @foreach($images as $img)
                        @if(!empty($img))
                            <div class="relative group/img overflow-hidden rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-all hover:shadow-md">
                                <img src="{{ trim($img) }}" alt="preview" class="h-28 w-28 object-cover transition-transform group-hover/img:scale-110"/>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="fa-solid fa-magnifying-glass text-white"></i>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Client-side Preview Container -->
        <div id="new-images-preview" class="mt-8 flex flex-wrap gap-4 hidden">
            <p class="w-full text-[10px] font-black text-blue-500 uppercase tracking-widest mb-4">รูปภาพที่กำลังจะอัปโหลด</p>
            <!-- Previews will be injected here via JS -->
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="mt-12 pt-10 border-t border-slate-100 dark:border-gray-800 flex flex-col md:flex-row items-center justify-between gap-6">
    <button type="submit" 
        class="w-full md:w-auto px-12 h-16 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-black rounded-2xl shadow-[0_15px_30px_-10px_rgba(34,197,94,0.4)] hover:shadow-[0_20px_40px_-10px_rgba(34,197,94,0.5)] transition-all flex items-center justify-center gap-4 active:scale-95 group">
        <i class="fa-solid fa-save text-sm group-hover:scale-120 transition-transform"></i>
        บันทึกข้อมูลข่าวสาร
    </button>
    <a href="{{ route('datamanage.news.index') }}" 
        class="w-full md:w-auto px-10 h-16 bg-slate-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold rounded-2xl hover:bg-slate-200 dark:hover:bg-gray-700 transition-all flex items-center justify-center gap-3 active:scale-95">
        <i class="fa-solid fa-xmark"></i>
        ยกเลิกและย้อนกลับ
    </a>
</div>

<script>
    // Simple client-side preview logic
    document.getElementById('news-images').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('new-images-preview');
        previewContainer.innerHTML = '<p class="w-full text-[10px] font-black text-blue-500 uppercase tracking-widest mb-4">รูปภาพที่กำลังจะอัปโหลด</p>';
        
        if (this.files && this.files.length > 0) {
            previewContainer.classList.remove('hidden');
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'relative group/img overflow-hidden rounded-2xl border border-blue-100 dark:border-blue-900/30 bg-white dark:bg-gray-800 shadow-sm';
                    div.innerHTML = `<img src="${event.target.result}" class="h-28 w-28 object-cover animate-popIn">`;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
