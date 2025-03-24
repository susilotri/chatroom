import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './views/Dashboard.vue';
// import ChatRoom from './components/ChatRoom.vue';
import Login from './views/Login.vue';
import ChatDashbord from './views/chat/ChatDashbord.vue';
import JoinChatRoom from './views/chat/JoinChatRoom.vue';
import CreateChatRoom from './views/chat/CreateChatRoom.vue';
import ActiveChatroom from './views/chat/ActiveChatroom.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/chatroom', component: ChatDashbord, meta: { requiresAuth: true } },
    { path: '/chat/join', component: JoinChatRoom, meta: { requiresAuth: true } },
    { path: '/chat/create', component: CreateChatRoom, meta: { requiresAuth: true } },
    { path: '/chat/active', component: ActiveChatroom, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login'); 
    } else {
        next(); 
    }
});

export default router;
