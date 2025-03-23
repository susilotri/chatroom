<template>
    <div class="p-6">        
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-white shadow rounded">
                <h2 class="text-lg font-semibold">Total Users</h2>
                <p class="text-3xl font-bold">{{ stats.total_users }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded">
                <h2 class="text-lg font-semibold">Active Users</h2>
                <p class="text-3xl font-bold">{{ stats.active_users }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded">
                <h2 class="text-lg font-semibold">Active Chat Rooms</h2>
                <p class="text-3xl font-bold">{{ stats.active_chat_rooms }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const stats = ref({
    total_users: 0,
    active_users: 0,
    active_chat_rooms: 0
});

const fetchStats = async () => {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/dashboard", {
            headers: { Authorization: `Bearer ${token}` }
        });
        stats.value = response.data;
    } catch (error) {
        console.error("Failed to fetch stats:", error);
    }
};

onMounted(fetchStats);
</script>
