module.exports = {
  apps: [
    {
      // นี่สำหรับ Laravel
      name: "laravel",
      script: "artisan",
      interpreter: "php",
      args: "serve --host=172.17.23.222"
    },
    {
      // นี่สำหรับ Vite
      name: "vite",
      script: "npm",
      args: "run dev -- --host"
    }
  ]
};

// **หมายเหตุ:** ถ้าโปรเจกต์คุณเป็น Laravel 11
// คุณไม่จำเป็นต้องมี "vite" ครับ ลบส่วนนั้นทิ้งได้เลย
// เพราะ "laravel" (artisan serve) จะรัน vite ให้เองครับ