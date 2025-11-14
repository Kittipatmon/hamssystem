<footer class="bg-white border-t-4 border-red-600/90">
	<div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
		<div class="grid grid-cols-1 lg:grid-cols-4 gap-10 py-8">
			<!-- Brand / About -->
			<div class="col-span-1 flex flex-col">
				<div class="flex items-center gap-3">
					<div class="h-10 w-10 rounded-2xl bg-[#E21F2D] text-white flex items-center justify-center shadow-[0_4px_12px_rgba(226,31,45,0.3)]">
						<span class="font-semibold text-lg">K</span>
					</div>
					<div class="leading-tight">
						<div class="flex items-baseline gap-2">
							<span class="font-semibold text-xl text-gray-900">Kumwell</span>
							<span class="text-xs align-super text-gray-400 tracking-wide">HAMS</span>
						</div>
						<div class="text-[11px] uppercase tracking-wide text-[#E21F2D] font-semibold">
							Human Asset Management & Service Building
						</div>
					</div>
				</div>

				<p class="mt-4 text-sm text-gray-700">แผนกจัดการและบำรุงรักษาอาคาร</p>

				<!-- Socials -->
				<div class="mt-4 flex items-center gap-3">
					<!-- Facebook -->
					<a href="#" class="h-9 w-9 rounded-full bg-white ring-1 ring-red-100 shadow-sm flex items-center justify-center text-blue-600 hover:bg-red-50 transition" aria-label="Facebook">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
							<path d="M22 12.07C22 6.48 17.52 2 11.93 2 6.35 2 1.86 6.48 1.86 12.07c0 5.03 3.69 9.2 8.5 9.93v-7.02H7.9v-2.9h2.46V9.41c0-2.43 1.45-3.77 3.67-3.77 1.06 0 2.16.19 2.16.19v2.37h-1.22c-1.2 0-1.58.75-1.58 1.51v1.81h2.69l-.43 2.9h-2.26v7.02c4.81-.73 8.5-4.9 8.5-9.93Z"/>
						</svg>
					</a>
					<!-- Instagram -->
					<a href="#" class="h-9 w-9 rounded-full bg-white ring-1 ring-red-100 shadow-sm flex items-center justify-center text-[#E21F2D] hover:bg-red-50 transition" aria-label="Instagram">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
							<path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm5 3.75A5.25 5.25 0 1 1 6.75 13 5.25 5.25 0 0 1 12 7.75Zm0 2A3.25 3.25 0 1 0 15.25 13 3.25 3.25 0 0 0 12 9.75Zm5.1-3.15a.9.9 0 1 1-.9.9.9.9 0 0 1 .9-.9Z"/>
						</svg>
					</a>
					<!-- Website -->
					<a href="#" class="h-9 w-9 rounded-full bg-white ring-1 ring-red-100 shadow-sm flex items-center justify-center text-blue-400 hover:bg-red-50 transition" aria-label="Website">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
							<path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm6.93 6h-3.02a15.2 15.2 0 0 0-1.44-3.7A8.03 8.03 0 0 1 18.93 8ZM12 4c.77 0 2.2 1.86 3.02 4H8.98C9.8 5.86 11.23 4 12 4ZM7.53 4.3A15.2 15.2 0 0 0 6.09 8H3.07A8.03 8.03 0 0 1 7.53 4.3ZM4 12c0-.69.05-1.37.16-2h3.37a20 20 0 0 0 0 4H4.16A16.7 16.7 0 0 1 4 12Zm.07 4h3.02c.35 1.31.84 2.56 1.44 3.7A8.03 8.03 0 0 1 4.07 16ZM12 20c-.77 0-2.2-1.86-3.02-4h6.04C14.2 18.14 12.77 20 12 20Zm4.47-.3c.6-1.14 1.09-2.39 1.44-3.7h3.02a8.03 8.03 0 0 1-4.46 3.7ZM19.84 14h-3.37a20 20 0 0 0 0-4h3.37c.07.66.11 1.34.11 2s-.04 1.34-.11 2Z"/>
						</svg>
					</a>
				</div>
			</div>

			<!-- Quick Links -->
			<div>
				<h3 class="text-sm font-semibold tracking-wider text-gray-800">QUICK LINKS</h3>
				<ul class="mt-4 space-y-3 text-sm">
					<li><a href="{{ url('/') }}" class="text-[#E21F2D] hover:underline">หน้าแรก</a></li>
					<li><a href="{{ route('datamanage.news.newsalllist') }}" class="text-[#E21F2D] hover:underline">ข่าวสาร/ประชาสัมพันธ์</a></li>
					<li><a href="#" class="text-[#E21F2D] hover:underline">งานสนับสนุน</a></li>
					<li><a href="#" class="text-[#E21F2D] hover:underline">แสดงความคิดเห็น</a></li>
				</ul>
			</div>

			<!-- Support -->
			<div>
				<h3 class="text-sm font-semibold tracking-wider text-gray-800">SUPPORT</h3>
				<ul class="mt-4 space-y-3 text-sm">
					<li><a href="#" class="text-[#E21F2D] hover:underline">ติดต่อเรา</a></li>
					<li><a href="#" class="text-[#E21F2D] hover:underline">ข้อเสนอแนะ</a></li>
					<li><a href="#" class="text-[#E21F2D] hover:underline">นโยบาย</a></li>
				</ul>
			</div>

			<!-- About -->
			<div>
				<h3 class="text-sm font-semibold tracking-wider text-gray-800">เกี่ยวกับ</h3>
				<ul class="mt-4 space-y-3 text-sm">
					<li><a href="#" class="text-[#E21F2D] hover:underline">เว็บไซต์บริษัท</a></li>
					<li><a href="#" class="text-[#E21F2D] hover:underline">HAMS Portal</a></li>
				</ul>
			</div>
		</div>

		<div class="border-t border-red-100"></div>

		<!-- Spacer for visual similarity with the mockup -->
		<div class="h-10"></div>
	</div>
</footer>

