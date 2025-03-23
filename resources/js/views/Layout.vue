<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <SideBar />

        <!-- Main Content -->
        <div class="flex flex-col flex-grow">
            <Navbar @logout="logout" />

            <main class="p-6">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import axios from "axios";
import Navbar from "../components/NavBar.vue"; // Pastikan path benar
import SideBar from "../components/SideBar.vue"; // Pastikan path benar

const router = useRouter();

const logout = async () => {
    try {
        await axios.post("/api/logout", {}, {
            headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
        });
        localStorage.removeItem("token");
        router.push("/login");
    } catch (error) {
        console.error("Logout gagal:", error);
    }
};
</script>
