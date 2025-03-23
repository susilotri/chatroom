<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <SideBar />

        <!-- Main Content -->
        <div class="flex flex-col flex-grow">
            <Navbar :user="user" @logout="logout" />

            <main class="p-6">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import Navbar from "../components/NavBar.vue"; 
import SideBar from "../components/SideBar.vue";

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem("user")) || { name: "", google_id: "" });

const logout = async () => {
    try {
        await axios.post("/api/logout", {}, {
            headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
        });
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        router.push("/login");
    } catch (error) {
        console.error("Logout gagal:", error);
    }
};
</script>
