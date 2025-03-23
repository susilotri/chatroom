<template>
    <header class="bg-white shadow-md p-4 flex justify-between">
        <h1 class="text-lg font-semibold">Dashboard</h1>
        <div class="relative flex items-center space-x-3">
            <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full cursor-pointer" />
            <button @click="logout" class="text-red-500 hover:underline">Logout</button>
        </div>
    </header>
</template>