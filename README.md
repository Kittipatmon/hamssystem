# HAMS (Human Assets Management & Service Building)

ระบบบริหารจัดการทรัพยากรบุคคลและบริการอาคาร (HAMS) พัฒนาด้วย Laravel Framework สำหรับการจัดการข้อมูลภายในองค์กรอย่างมีประสิทธิภาพ

## 🚀 กระบวนการทำงานของระบบ (Workflow)

### 1. การเข้าสู่ระบบ (Authentication)
- **ระบบล็อกอินปกติ:** ใช้รหัสพนักงานและรหัสผ่านที่จัดเก็บในฐานข้อมูล
- **Microsoft OAuth:** รองรับการล็อกอินผ่านบัญชี Microsoft 365 (Outlook) เพื่อใช้สำหรับการส่งอีเมลแจ้งเตือนและการยืนยันตัวตน

### 2. การจัดการข้อมูล (Data Management)
- **พนักงาน (Employees):** ดูรายละเอียดข้อมูลพนักงาน (จำกัดสิทธิ์การแก้ไข/ลบ เฉพาะระดับ Admin ผ่านระบบหลังบ้าน)
- **แผนก (Departments):** รายการแผนกต่างๆ ภายในองค์กร
- **ข่าวสาร (News):** 
  - การจัดการข่าวสาร: เพิ่ม แก้ไข และลบข่าวสาร (Admin)
  - การแสดงผล: ข่าวสารทั้งหมดสำหรับพนักงาน พร้อมระบบค้นหา

### 3. ระบบแจ้งเตือนผ่าน Outlook (Outlook Notification)
เป็นฟีเจอร์เด่นสำหรับการส่งข่าวสารไปยังอีเมลของพนักงานโดยตรง:
1. ผู้ดูแลระบบเลือกข่าวสารที่ต้องการแจ้งเตือน
2. ระบบตรวจสอบสิทธิ์ Microsoft OAuth ของผู้ใช้
3. หากยังไม่ได้เชื่อมต่อ ระบบจะพาไปยังหน้า Microsoft Sign-in
4. เมื่อล็อกอินสำเร็จ ระบบจะส่งอีเมลโดยใช้ **Microsoft Graph API** (หรือ SMTP สำรอง) ไปยังผู้รับที่เลือก

---

## 🛠 การตั้งค่าระบบ (Configuration)

### 1. ฐานข้อมูล (Database)
ตั้งค่าในไฟล์ `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hamssystem
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Microsoft Azure OAuth
สำหรับการล็อกอินและส่งอีเมล Outlook ต้องตั้งค่า App Registration ใน Azure Portal:
```env
AZURE_CLIENT_ID=your_client_id
AZURE_CLIENT_SECRET=your_client_secret
AZURE_TENANT_ID=your_tenant_id
AZURE_REDIRECT_URI=https://your-domain.com/auth/microsoft/callback
```

### 3. การส่งอีเมล (Mail)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=your_email@kumwell.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

---

## 📦 การนำระบบขึ้นโฮสติ้ง (Deployment)

เพื่อให้ระบบทำงานได้สมบูรณ์ทั้งบน HTTP และ HTTPS รวมถึงหน้าตา CSS ไม่เพี้ยน ให้ทำตามขั้นตอนดังนี้:

1. **Build Assets:** รันคำสั่งในเครื่องตัวเองก่อนอัปโหลด
   ```bash
   npm run build
   ```
2. **ตั้งค่า Protocol Agnostic:** ในไฟล์ `.env` ของโฮสติ้ง ให้เพิ่มบรรทัดนี้เพื่อแก้ปัญหา Mixed Content:
   ```env
   ASSET_URL=/
   ```
3. **อัปโหลดไฟล์:**
   - อัปโหลดโฟลเดอร์ `public/build` ไปทับบนโฮสต์ทุกครั้งที่มีการเปลี่ยนแปลง CSS/JS
   - อัปโหลดไฟล์ `.env` ที่ตั้งค่าโดเมนจริงเรียบร้อยแล้ว
4. **ตั้งค่า Azure Portal:**
   - เพิ่ม Redirect URI ใน Azure เป็น URL จริงของโฮสติ้ง (เช่น `https://hams.appkumwell.com/auth/microsoft/callback`)
5. **Clear Cache:**
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

---

## ⚠️ การแก้ไขปัญหาเบื้องต้น (Troubleshooting)

- **หน้าจอขาว/CSS ไม่โหลด:** ตรวจสอบว่ามีโฟลเดอร์ `public/build` หรือยัง และเช็กค่า `ASSET_URL=/` ใน `.env`
- **Error "No connection could be made":** ตรวจสอบว่าได้ Start MySQL ใน XAMPP หรือฐานข้อมูลบนโฮสต์ทำงานปกติหรือไม่
- **Redirect URI Mismatch:** เช็กว่าลิงก์ใน `.env` และใน Azure Portal ตรงกันทุกตัวอักษรหรือไม่ (รวมถึง http/https)
