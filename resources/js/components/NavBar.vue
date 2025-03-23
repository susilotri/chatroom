<template>
    <header class="bg-white shadow-md p-4 flex justify-between">
        <h1 class="text-lg font-semibold">Dashboard</h1>
        <div class="relative">
            <button @click="toggleDropdown" class="flex items-center space-x-2 focus:outline-none">
                <img :src="userAvatar" class="w-10 h-10 rounded-full border" alt="user avatar" />
            </button>

            <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg p-2">
                <p class="text-sm font-semibold text-gray-700 px-4 py-2">{{ user.name }}</p>
                <hr />
                <button @click="logout" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>
<script setup>
    import { ref } from "vue";
    import { computed, defineProps } from "vue";

    const isDropdownOpen = ref(false);

    const toggleDropdown = () => {
        isDropdownOpen.value = !isDropdownOpen.value;
    };

    const props = defineProps(["user"]);

    const userAvatar = computed(() =>
        props.user.google_id
            ? `https://lh3.googleusercontent.com/a-/AOh14Gj${props.user.google_id}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.user.name || "Guest")}`
    );
</script>