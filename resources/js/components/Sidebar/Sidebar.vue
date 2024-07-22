<template>
    <div class="flex flex-grow w-64 xl:w-80 max-w-xs xl:shrink-0 xl:border-b-0 xl:border-r flex-col border-b border-gray-200">
        <div class="flex p-4 justify-between">
            <h1 class="text-lg font-bold">Chats</h1>
            <Button @click="toggleModal">
                Add Friend
            </Button>
        </div>
        <input type="search"
               placeholder="Search chats.."
               class="border-gray-300 w-full p-1 dark:border-gray-700 dark:text-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">

        <div v-if="friends.length">
            <ul>
                <li v-for="friend in friends" :key="friend.id">
                    {{ friend.name }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>No friends found.</p>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg">
                <h2 class="text-lg font-bold mb-4">Add Friends</h2>
                <div v-if="users.length">
                    <ul>
                        <li v-for="user in users" :key="user.id" class="flex justify-between items-center">
                            <span>{{ user.name }}</span>
                            <Button @click="addFriend(user)">
                                +
                            </Button>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <p>No users found.</p>
                </div>
                <Button @click="toggleModal" class="mt-4">
                    Close
                </Button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Button from 'primevue/button';

const friends = ref([]);
const users = ref([]);
const showModal = ref(false);

function toggleModal() {
    showModal.value = !showModal.value;

    if (showModal.value) {
        fetchUsers();
    }
}

async function fetchUsers() {
    try {
        const response = await axios.get('/api/users');
        if (response.data.success) {
            users.value = response.data.data;
        } else {
            console.error('Error fetching users:', response.data.errors);
        }
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

function addFriend(user) {
    friends.value.push(user);
    users.value = users.value.filter(u => u.id !== user.id);
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/friends');
        if (response.data.success) {
            friends.value = response.data.data;
        } else {
            console.error('Error fetching friends:', response.data.errors);
        }
    } catch (error) {
        console.error('Error fetching friends:', error);
    }
});
</script>

<style scoped>
/* Stil Ayarları */
.bg-gray-50 {
    background-color: #f9f9f9;
}

.bg-blue-600 {
    background-color: #1d4ed8;
}

.border-gray-300 {
    border-color: #d1d5db;
}

.text-gray-500 {
    color: #6b7280;
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>


// veri tabanından istek atılacak arkadaşlar çekilecek ve listelenecek
