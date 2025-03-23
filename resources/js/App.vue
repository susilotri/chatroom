<template>
    <div>
        <Layout v-if="isAuthenticated" />
        <router-view v-else />
    </div>
</template>

<script setup>
import { computed } from "vue";
import Layout from "./views/Layout.vue";

const isAuthenticated = computed(() => !!localStorage.getItem("token"));
</script>
