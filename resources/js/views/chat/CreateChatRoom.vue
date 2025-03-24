<template>
    <div class="h-screen bg-gray-100">
        <h1 class="text-2xl font-bold">Create Chat Room</h1>
        <div class="bg-white p-6 rounded-lg shadow-md w-[50%]">
            <label class="block text-sm font-medium">Chat Room Name:</label>
            <input v-model="roomName" type="text" class="w-full p-2 border rounded mt-2"
                placeholder="Enter room name" />

            <label class="block text-sm font-medium mt-4">Invite Users:</label>
            <Multiselect
                v-model="selectedUsers"
                :options="users"
                :multiple="true"
                :searchable="true"
                label="name"
                track-by="id"
                placeholder="Select users to invite"
                class="w-full"
            ></Multiselect>

            <button @click="createRoom" class="w-full bg-blue-500 text-white font-semibold p-2 mt-4 rounded">
                Create & Invite
            </button>
        </div>

        <div v-if="showNotification" class="fixed top-10 right-10 bg-white p-4 shadow-md rounded-lg">
            <p>{{ notificationMessage }}</p>
            <div class="flex justify-end mt-2">
                <button class="text-blue-500 font-semibold" @click="showNotification = false">Close</button>
            </div>
        </div>

        <div v-if="invitePopup" class="fixed top-10 left-10 bg-white p-4 shadow-md rounded-lg">
            <p>You have been invited to join <strong>{{ invitedRoom }}</strong></p>
            <div class="flex justify-between mt-2">
                <button class="bg-green-500 text-white p-2 rounded" @click="acceptInvite">Join</button>
                <button class="bg-red-500 text-white p-2 rounded" @click="rejectInvite">Reject</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';


const roomName = ref("");
const selectedUsers = ref([]);
const users = ref([]);
const showNotification = ref(false);
const notificationMessage = ref("");
const invitePopup = ref(false);
const invitedRoom = ref("");
const currentUserId = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get("/api/users", {
            headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
        });
        const currentUser = JSON.parse(localStorage.getItem("user"));
        currentUserId.value = currentUser.google_id;

        console.log("Current User ID:", currentUser, response.data);
        
        users.value = response.data
            .filter(user => user.google_id !== currentUserId.value)
            .map(user => ({
                id: user.id,
                name: user.name
            }));

    } catch (error) {
        console.error("Error fetching users:", error);
    }
});

const createRoom = async () => {
    console.log("Selected Users:", selectedUsers.value, roomName.value);
    if (!roomName.value || !selectedUsers.value) return alert("Room name and user selection required!");

    try {
        const response = await axios.post("/chatroom/create", {
            name: roomName.value,
            invited_user_ids: selectedUsers.value.map(user => user.id)
        }, {
            headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
        });

        notificationMessage.value = `Chat room '${roomName.value}' created! Invitation sent `;
        showNotification.value = true;
    } catch (error) {
        console.error("Error creating chat room:", error);
    }
};

const acceptInvite = () => {
    notificationMessage.value = `You joined chat room '${invitedRoom.value}'`;
    showNotification.value = true;
    invitePopup.value = false;
};

const rejectInvite = () => {
    notificationMessage.value = `User rejected invitation to '${invitedRoom.value}'`;
    showNotification.value = true;
    invitePopup.value = false;
};
</script>
