<template>
    <!-- Sidebar içeriği -->
    <div class="flex flex-grow w-64 xl:w-80 max-w-xs xl:shrink-0 xl:border-b-0 xl:border-r flex-col border-b border-gray-200">
        <div class="flex p-4 justify-between">
            <h1 class="text-lg font-bold">Sohbetler</h1>
            <button @click="toggleModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Arkadaş Ekle
            </button>
        </div>
        <input type="search"
               v-model="searchQuery"
               placeholder="Yeni sohbet.."
               class="border-gray-300 w-full p-1 dark:border-gray-700 dark:text-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">

        <div v-if="filteredFriends.length">
            <ul>
                <li v-for="friend in filteredFriends" :key="friend.id" class="relative p-3 border-b border-gray-200 font-bold cursor-pointer flex items-center justify-between">
                    <!-- Profil Fotoğrafı -->
                    <img v-if="friend.profile_picture" :src="`/storage/profile_pictures/${friend.profile_picture}`" alt="Profil Fotoğrafı" class="w-12 h-12 rounded-full object-cover mr-3">
                    <div @click="startChat(friend)">
                        {{ friend.name }} {{ friend.surname }}
                    </div>
                    <!-- Silme işlemi için üç nokta menüsü -->
                    <div class="relative">
                        <button @click="toggleMenu(friend)" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12m-6 6h6M6 6h12"></path>
                            </svg>
                        </button>
                        <!-- Silme Menüsü -->
                        <div v-if="activeMenu === friend.id" class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                            <button @click="removeFriend(friend)" class="block px-5 py-2 text-red-600 hover:bg-gray-100 w-full text-left">
                                Arkadaşı Çıkar
                            </button>
                            <button @click="showFriendDetails(friend)" class="block px-5 py-2 text-gray-800 hover:bg-gray-100 w-full text-left">
                                Bilgileri Görüntüle
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else>
            <p>Arkadaşın yok, arkadaş ekle</p>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg">
                <h2 class="text-lg font-bold mb-4">Arkadaş Ekle</h2>
                <div v-if="filteredUsers.length">
                    <ul>
                        <li v-for="user in filteredUsers" :key="user.id" class="flex justify-between items-center p-2 border-b border-gray-200 rounded-lg mb-2">
                            <div class="flex items-center">
                                <!-- Profil Fotoğrafı -->
                                <img v-if="user.profile_picture" :src="`/storage/${user.profile_picture}`" alt="Profil Fotoğrafı" class="w-8 h-8 rounded-full object-cover mr-3">
                                <div class="text-gray-800 font-bold">{{ user.name }} {{ user.surname }}</div>
                            </div>
                            <button v-if="!user.add" @click="addFriend(user)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded focus:outline-none focus:shadow-outline">
                                +
                            </button>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <p>Kullanıcı Bulunamadı</p>
                </div>
                <button @click="toggleModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                    Kapat
                </button>
            </div>
        </div>

        <!-- Friend Details Modal -->
        <FriendDetailsModal :friend="selectedFriend" :show="showFriendDetailsModal" @close="toggleFriendDetailsModal" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import FriendDetailsModal from '../Sidebar/FriendDetailsModal.vue';

const friends = ref([]);
const users = ref([]);
const showModal = ref(false);
const showFriendDetailsModal = ref(false);
const selectedFriend = ref(null);
const searchQuery = ref('');
const activeMenu = ref(null);
const emit = defineEmits(['start-chat']);

function toggleModal() {
    showModal.value = !showModal.value;
    if (showModal.value) {
        fetchUsers();
    }
}

function toggleFriendDetailsModal() {
    showFriendDetailsModal.value = !showFriendDetailsModal.value;
}

async function fetchUsers() {
    try {
        const response = await axios.get('/api/users');
        console.log('API Response:', response);

        users.value = response.data;
        console.log('Users fetched:', users);

    } catch (error) {
        console.error('Error fetching users (Catch):', error.response?.data || error.message || 'Unknown error');
    }
}

async function addFriend(user) {
    try {
        const response = await axios.post('/api/add-friend', {
            user_id: user.id
        });

        if (response.data.success) {
            friends.value.push(user);
            users.value = users.value.filter(u => u.id !== user.id);
        } else {
            console.error('Error adding friend:', response.data.message);
        }
    } catch (error) {
        console.error('Error adding friend:', error);
    }
}

async function removeFriend(friend) {
    try {
        const response = await axios.post('/api/remove-friend', {
            user_id: friend.id
        });

        if (response.data.success) {
            friends.value = friends.value.filter(f => f.id !== friend.id);
        } else {
            console.error('Error removing friend:', response.data.message);
        }
    } catch (error) {
        console.error('Error removing friend:', error);
    }
}

function startChat(friend) {
    emit('start-chat', friend);
}

function showFriendDetails(friend) {
    selectedFriend.value = friend;
    toggleFriendDetailsModal();
}

// Arama fonksiyonu
const filteredFriends = computed(() => {
    return friends.value.filter(friend => {
        const fullName = `${friend.name} ${friend.surname}`.toLowerCase();
        return fullName.includes(searchQuery.value.toLowerCase());
    });
});

// users arama fonksiyonu
const filteredUsers = computed(() => {
    return users.value.filter(user => {
        const fullName = `${user.name} ${user.surname}`.toLowerCase();
        return fullName.includes(searchQuery.value.toLowerCase());
    });
});

function toggleMenu(friend) {
    activeMenu.value = activeMenu.value === friend.id ? null : friend.id;
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/friends');
        if (response.data.success) {
            friends.value = response.data.data;
        } else {
            console.error('Error fetching friends:', response.data.errors || 'Unknown error');
        }
    } catch (error) {
        console.error('Error fetching friends:', error.response?.data || error.message || 'Unknown error');
    }
    fetchUsers();
});
</script>
