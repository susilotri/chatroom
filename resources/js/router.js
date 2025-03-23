import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './views/Dashboard.vue';
// import ChatRoom from './components/ChatRoom.vue';
import Login from './views/Login.vue';

const routes = [
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    // { path: '/chatroom', component: ChatRoom, meta: { requiresAuth: true } },
    { path: '/login', component: Login }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Middleware untuk mengecek autentikasi sebelum masuk ke halaman tertentu
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login'); // Redirect ke login jika belum login
    } else {
        next(); // Lanjutkan jika sudah login atau halaman tidak butuh autentikasi
    }
});

export default router;
