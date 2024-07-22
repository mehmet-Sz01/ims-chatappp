<template>
    <header class="bg-white shadow-md py-4 px-6 flex items-center justify-between fixed top-0 inset-x-0 z-50">
        <!-- Logo -->
        <div class="flex items-center">
            <img src="/image/IMS-Logo-Picton-Blue.png" alt="Logo" class="h-8 w-auto" />
        </div>

        <!-- Sağ taraftaki butonlar -->
        <div class="flex items-center space-x-4">
            <!-- Profil Butonu -->
            <button @click="toggleProfileMenu" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 12c2.209 0 4-1.791 4-4s-1.791-4-4-4-4 1.791-4 4 1.791 4 4 4zM12 14c-4.418 0-8 3.582-8 8h16c0-4.418-3.582-8-8-8z"></path>
                </svg>
                <!-- Profil Menü -->
                <div v-if="showProfileMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <ul>
                        <li><a href="#" @click="profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a></li>
                        <li><a href="#" @click="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Çıkış Yap</a></li>
                    </ul>
                </div>
            </button>
        </div>
    </header>
</template>

<script setup>
import { ref } from 'vue';

const showProfileMenu = ref(false);

const toggleProfileMenu = () => {
    showProfileMenu.value = !showProfileMenu.value;
};

const profile = () => {
    window.location.href = '/profile';
};
const logout = () => {
    window.location.href = '/login';
};

</script>
