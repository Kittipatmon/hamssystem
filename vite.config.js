import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const rawBase = env.VITE_BASE || env.ASSET_URL || '/';
    const base = (rawBase.endsWith('/') ? rawBase : rawBase + '/');

    return {
        base,
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
    // server: {
    //     host: '0.0.0.0', // อนุญาตให้เข้าถึงจากภายนอก
    //     port: 5174,      // ล็อก port ให้เป็น 5174 (หรือ 5173 ถ้าคุณเคลียร์ process เก่าแล้ว)
    //     hmr: {
    //         host: '172.17.23.222' // ใส่ IP เครื่องคุณตามที่เห็นในภาพ
    //     }
    // },
    };
});
