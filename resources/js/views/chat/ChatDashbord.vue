<template>
    <div class="flex flex-col items-center justify-center bg-gray-100">
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <button @click="createChatRoom" class="chat-button bg-blue-500 hover:bg-blue-600">
                Create Chat Room
            </button>

            <button @click="joinChatRoom" class="chat-button bg-green-500 hover:bg-green-600">
                Join Chat Room
            </button>

            <button @click="viewActiveChatRooms" class="chat-button bg-yellow-500 hover:bg-yellow-600">
                Chat Room Active
            </button>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const createChatRoom = () => {
    router.push("/chat/create");
};

const joinChatRoom = () => {
    router.push("/chat/join");
};

const viewActiveChatRooms = () => {
    router.push("/chat/active");
};
</script>

<style scoped>
.chat-button {
    @apply w-64 h-20 text-white font-semibold rounded-lg shadow-md transition duration-300;
}
</style>
