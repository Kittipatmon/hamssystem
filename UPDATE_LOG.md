# UPDATE LOG - HAMS System Redesigns

This log details the changes made to the Housing and Vehicle Booking backend systems to provide a premium clinical theme, seamless AJAX reloads, and smooth transitions.

---

## 17 June 2026

### 13. Department Backend Redesign
* **File modified**: `resources/views/backend/department/index.blade.php`
* **Changes**:
  * Redesigned department listing page into the premium **Clinical Hospital Ledger Table** layout with zebra stripes, vertical borders, and solid dark table headers (`bg-slate-800`).
  * Removed all department creation, editing, and deletion capabilities (including buttons, form modals, and related JS scripts) to make it a clean, high-performance read-only database list with live search functionality.

### 12. Employee Registry (Users) Backend Redesign
* **File modified**: `resources/views/backend/users/index.blade.php`
* **Changes**:
  * Redesigned user directory into a clinical hospital ledger table format with alternating row colors, vertical separators, and solid dark table headers (`bg-slate-800`).
  * Removed all UI components, actions, and JS dialog confirmations for Adding, Editing, and Deleting employees, keeping it as a high-performance read-only database list with a details toggle.
  * Preserved live HAMS editor toggle capabilities and real-time search / filter functionalities.

### 11. Policy & Announcement Backend Redesign
* **Files modified**:
  * `resources/views/backend/policy/index.blade.php`
  * `resources/views/backend/policy/create.blade.php`
  * `resources/views/backend/policy/edit.blade.php`
  * `resources/views/backend/announcement/index.blade.php`
  * `resources/views/backend/announcement/create.blade.php`
  * `resources/views/backend/announcement/edit.blade.php`
* **Changes**:
  * Complete UI overhaul from card-based layouts to premium **Clinical Hospital Ledger Tables**.
  * Structured control center headers showing total record statistics.
  * Bordered tables with clean spacing, sharp icons, clear type colors, and distinct bordered priority / category labels.
  * Grid-based, clean forms for creating and editing documents with standard form field layouts.

### 0. Welcome Page — Luxury Corporate Redesign
* **File modified**: `resources/views/welcome.blade.php`
* **Changes**:
  * Complete visual redesign with "Committed" Kumwell Red color strategy on pure white canvas.
  * **Hero Banner**: Cinematic full-viewport slider with soft vignette (replaced heavy dark red overlay), cleaner typography hierarchy, horizontal bar-style slider dots, removed floating preview box.
  * **Announcement Ticker**: Redesigned as a clean red bar below the hero (replaced floating glass-effect bar).
  * **Services Grid**: Premium cards with `border-radius: 12px` (replaced over-rounded 2.5rem), proper shadow-only hover effects, icon pills, and status badges.
  * **Policy & Operations**: Clean two-column layout with divider-separated items (replaced card-in-card pattern with side-stripe borders).
  * **News Section**: Editorial layout with featured article + sidebar, filled red CTA button, refined typography.
  * **Announcement Carousel**: Cards with 12px radius, center-zoom effect, refined navigation controls.
  * **Announcement Modal**: Cleaner 12px radius, improved spacing, refined close button.
  * **CSS Tokens**: Added OKLCH color custom properties (`--kml-primary`, `--kml-bg`, `--kml-surface`, etc.).
  * **Motion**: Added `@media (prefers-reduced-motion: reduce)` support for all animations.
  * **Design Principles**: No dark/black themes, no minimalism, no excessive glassmorphism, no over-rounded corners.

---

### 1. Housing Management Dashboard AJAX Integration
* **File modified**: `resources/views/backend/housing/management.blade.php`
* **Changes**:
  * Added `id="management-card"` wrapper to the main control island.
  * Implemented client-side AJAX fetch logic (`loadTableData`) to handle tab switches, searches, filters, and pagination without full-page reloads.
  * Added dynamic loading overlays ("กำลังประมวลผล...") and click-lock actions when reloading or deleting data.
  * Integrated HTML5 history state (`pushState`) to support browser back/forward buttons seamlessly.
  * **Smooth Transition**: Applied container min-height locking and `0.15s` opacity fade transition to prevent screen jumping/layout shifts when updating lists.

---

### 2. Vehicle Booking Dashboard AJAX Integration
* **File modified**: `resources/views/bookingcar/dashboard.blade.php`
* **Changes**:
  * Added `id="booking-filter-form"` to the filter form and `id="booking-table-card"` to the main table card.
  * Intercepted filter submissions, pagination clicks, and reset commands to reload the table container via AJAX.
  * Linked background fetch actions for "Approve" and "Reject" submissions to update the listing dynamically.
  * Appended the localized "กำลังประมวลผล..." overlay to block overlapping user interactions.
  * **Smooth Transition**: Integrated container height-locking and fade transitions to eliminate layout shifts on table refresh.

---

### 3. Report Pages Processing Blockers
* **Files modified**:
  * `resources/views/backend/housing/report/index.blade.php`
  * `resources/views/bookingcar/report.blade.php`
* **Changes**:
  * Intercepted year/filter form submissions on report pages to display a global SweetAlert modal showing **"กำลังประมวลผล... กรุณารอซักครู่"** (Processing... Please wait).
  * This blocks the interface and prevents multiple query submissions while charts and statistics are calculating.

## [2026-06-17] Redesign System Alert Dashboard
- Rewrote welcomedatamanage.blade.php to use a clinical/hospital-style table layout.
- Removed reliance on welcomedatamanage_item_card.blade.php for this view.
- Replaced grid of cards with highly structured, data-dense HTML tables.


## [2026-08-21] Implement System & Activity Logs Archiving
- สร้างหน้า UI `System Logs` เพื่อแสดงประวัติการทำงาน (CRUD, Login/Logout, Error, Failed Login Risk)
- สร้างคำสั่ง `LogsArchiveCommand` รันอัตโนมัติทุกคืนเพื่อตรวจสอบว่าสิ้นปีหรือยัง
- หากสิ้นปี ระบบจะนำ Log ของปีก่อนหน้ามาแยกเป็นไฟล์ CSV ย่อย 12 เดือน และบีบอัดเป็น Zip 1 ไฟล์
- ระบบจะลบข้อมูลที่ถูก Archive ออกจากฐานข้อมูลเพื่อลดภาระเครื่อง
- ตรวจสอบไฟล์ Archive หากมีอายุเกิน 3 ปีจะทำการลบทิ้งอัตโนมัติ

## [2026-08-24] Security Alerts, IP Blacklist & Log Enhancements
- **ระบบ IP Blacklist (แบน IP):**
  - สร้างตาราง `ip_blacklists` เพื่อจัดเก็บข้อมูล IP ที่บล็อกและบันทึกผู้ดำเนินการแบน
  - พัฒนา `CheckIpBlacklist` Middleware เพื่อตรวจสอบและตัดสิทธิ์การเข้าถึงระบบโดยส่งกลับ 403 Forbidden ทันทีหาก IP ติดแบล็คลิสต์
  - ปรับปรุงหน้าจอ Security Alerts: เพิ่มคอลัมน์การจัดการ, ปุ่มดึงข้อมูลพิกัด (Geolocation), และสวิตช์เปิด-ปิด (Toggle) บล็อก IP สีน้ำเงินสไตล์ Bootstrap
  - เพิ่มระบบป้องกันการกดแบน IP ปัจจุบันของตนเองเพื่อความปลอดภัย
  - ปรับแต่งหน้าจอรายละเอียดบันทึกให้แปลง User Agent ที่ซับซ้อนให้เป็นชื่อระบบปฏิบัติการและเบราว์เซอร์จริงที่อ่านง่าย (เช่น Windows 11 / Chrome 151)
- **ระบบความปลอดภัยและการจัดการสิทธิ์ (Role Access Control):**
  - กำหนดให้เฉพาะผู้ใช้งานที่เป็น Admin หลักของฐานข้อมูลกลาง (`appkum_user` ตาราง `employees`) เท่านั้นที่จะมีสิทธิ์เพิ่มหรือปรับลด (Demote) สิทธิ์ Admin ในระบบ HAMS ได้
  - หากไม่ใช่แอดมินกลางและพยายามมอบสิทธิ์/ลดสิทธิ์ แอดมินทั่วไปจะโดนปฏิเสธพร้อมข้อความเตือนให้ติดต่อฝ่าย IT
  - ปรับระดับการเข้าถึง: ให้ Admin หลักของฐานข้อมูลกลาง สามารถผ่านด่านความปลอดภัยและเข้าถึงระบบย่อยทั้งหมดของ HAMS ได้โดยอัตโนมัติ
- **ระบบบันทึกประวัติการทำงาน (System Logs):**
  - ปรับปรุง `LogUserActivity` ให้บันทึกประวัติการทำธุรกรรมสร้าง/แก้ไข/ลบ (POST, PUT, PATCH, DELETE) ของทุกผู้ใช้งานลงบันทึกระบบโดยอัตโนมัติ
  - เพิ่มฟังก์ชันแปลบันทึกรายละเอียดเป็นประโยคภาษาไทยให้อ่านและเข้าใจได้ง่าย เช่น *"กิตติพัฒน์ มานุช ได้เปลี่ยนสิทธิ์ของพนักงาน จาก Viewer เป็น Editor (เหตุผล: เทส)"*
  - เพิ่มการบันทึก IP Address, Browser User Agent, และค่าพารามิเตอร์ข้อมูลดิบทุกการแก้ไขลงในประวัติเพื่อการตรวจสอบย้อนหลังแบบ 100%
  - ปรับปรุงไอคอนปุ่มแบน IP ให้เปลี่ยนสไลด์ในขอบอย่างถูกต้อง ไม่ลอยทะลุออกไปนอกกรอบ
  - ปรับสีไอคอนโล่เตือนภัยในแถบข้าง (Sidebar) ให้เปลี่ยนเป็นสี Slate เทากลืนเป็นเนื้อเดียวกับทุกเมนูตามธีมของหน้าจอหลัก
  - ปรับปรุงตารางหลักเป็น **jQuery DataTables** ทั้งหมด:
    - **จัดการผู้ใช้งาน (User & Role Management):** จัดการแบบ Client-side ผูกกล่องค้นหา, การเลือกแผนก/สิทธิ์/สถานะแบบทันที
    - **รายชื่อแผนกองค์กร (Department Registry):** เปิดใช้ DataTables พร้อมผูกกล่องค้นหา Custom
    - **รายชื่อหัวหน้าแผนก (Department Managers):** ปรับใช้ DataTables ค้นหาและจัดเรียงข้อมูลหัวหน้าแผนกได้แบบ Realtime
    - **จัดการนโยบายระบบ (Policies):** เปลี่ยนตารางแสดงผลเป็นระบบ DataTables พร้อมสลับการกรองและค้นหาข้อมูลอย่างลื่นไหล
    - **จัดการขั้นตอนการดำเนินงาน (Operations):** เปลี่ยนเป็น DataTables สไตล์พรีเมียมควบคุมผ่าน jQuery API
    - **จัดการประกาศข่าวสาร & แจ้งให้ทราบ (Announcements):** พัฒนาตารางประชาสัมพันธ์เป็น DataTables เรียงลำดับวันที่ประกาศจากล่าสุด (desc) เป็นหลักอัตโนมัติ