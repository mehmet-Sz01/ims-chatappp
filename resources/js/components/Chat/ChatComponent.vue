<template>
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- Başlık -->
        <div class="bg-white p-3 text-black font-bold flex justify-between items-center border-b border-gray-300">
            <!-- Profil Fotoğrafı -->
            <div class="flex items-center space-x-4">
                <img v-if="selectedFriend?.profile_picture" :src="`/storage/${selectedFriend.profile_picture}`" alt="Profil Fotoğrafı" class="w-12 h-12 rounded-full object-cover">
                <span>{{ selectedFriend?.name }} {{ selectedFriend?.surname }}</span>
            </div>
            <!-- Üç Nokta Menüsü -->
            <div class="relative">
                <icon-button :icon="['fas', 'ellipsis-vertical']" @iconClick="toggleMenu" class="bg-white"></icon-button>
                <div v-if="showMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <button @click="deleteMessages" class="block w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-100">Mesajları Sil</button>
                </div>
            </div>
        </div>


        <div class="sidebar flex flex-grow overflow-auto p-4 bg-white">
            <div class="flex-grow">
                <div v-if="selectedFriend">
                    <ul>
                        <li v-for="message in messages" :key="message.id" class="mb-4">
                            <div :class="{'flex-row-reverse': message.sender_id === user.id, 'flex-row': message.sender_id !== user.id}" class="flex items-start">
                                <div :class="{'bg-blue-600 text-white': message.sender_id === user.id, 'bg-gray-100': message.sender_id !== user.id}" class="p-4 rounded-xl max-w-lg">
                                    <p>{{ message.message }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-else class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                    <icon-button :icon="['fas','comments']" size="xl" style="background: none; box-shadow: none; outline: none;"></icon-button>
                    mesaj seçin veya bir mesajlaşma başlatın
                </div>

            </div>
        </div>

        <!-- Mesaj Gönderim Bölümü -->
        <div v-if="selectedFriend" class="bg-white p-4 border-t border-gray-300">
            <div class="flex items-center">
                <!-- Emoji Seçici -->
                <svg @click="toggleEmojis" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 cursor-pointer mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                </svg>

                <!-- Emoji Seçici Alanı -->
                <div v-if="showEmojis" class="absolute bottom-12 left-0 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <EmojiPicker @select="onSelectEmoji" :native="true"/>
                </div>

                <!-- Dosya Yükleme Butonu -->
                <input type="file" id="attachments" accept="image/*" multiple @change="handleFileUpload" class="hidden">
                <label for="attachments" class="flex items-center cursor-pointer mr-2">
                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                </label>

                <!-- Mesaj Girişi ve Gönderim Butonu -->
                <textarea v-model="form.content" placeholder="Bir mesaj yazın" class="flex-grow bg-white text-gray-700 placeholder-gray-500 border-none rounded-full p-2 focus:ring-0 resize-none" rows="1"></textarea>
                <icon-button :icon="['fas', 'paper-plane']"  @click="sendMessage"></icon-button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, defineProps } from 'vue';
import EmojiPicker from 'emoji-picker';
import axios from 'axios';
import Echo from 'laravel-echo';
import {useStore} from "vuex";

import Pusher from 'pusher-js';
import IconButton from "../UI/IconButton.vue";
window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const props = defineProps({
    selectedFriend: Object
});
const store = useStore();
const user = ref(null);
const userId = ref(null);
const form = ref({ content: '' });
const showEmojis = ref(false);
const messages = ref([]);
const showMenu = ref(false);
const currentUserId = store.getters.user.id;


watch(() => props.selectedFriend, async (newFriend) => {
    if (newFriend) {
        await fetchMessages(newFriend.id);
    }
}, { immediate: true });

const toggleEmojis = () => {
    showEmojis.value = !showEmojis.value;
};

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};

const onSelectEmoji = (emoji) => {
    form.value.content += emoji.native;
};

const sendMessage = async () => {
    if (!props.selectedFriend) {
        console.error("Mesaj gönderilecek arkadaş seçilmemiş.");
        return;
    }

    if (!user.value) {
        console.error("Kullanıcı bilgileri mevcut değil.");
        return;
    }

    if (form.value.content.trim()) {
        try {
            const response = await axios.post('/api/messages', {
                sender_id: user.value.id,
                receiver_id: props.selectedFriend.id,
                message: form.value.content,
            });

            if (response.data.success) {
                messages.value.push({
                    id: response.data.message.id,
                    message: form.value.content,
                    user: {avatar: user.value.avatar, name: user.value.name},
                    sender_id: user.value.id
                });
                form.value.content = '';
            } else {
                console.error("Mesaj gönderilirken hata oluştu:", response.data.errors || response.data);
            }
        } catch (error) {
            console.error("Mesaj gönderilirken hata oluştu:", error);
        }
    }
};

const fetchMessages = async () => {
    if (!props.selectedFriend) {
        console.error("Mesajları getirmek için bir arkadaş seçilmemiş.");
        return;
    }

    try {
        const response = await axios.get(`/api/messages/${props.selectedFriend.id}`);
        if (response.data.success) {
            messages.value = response.data.data.filter(message => {
                if (message.sender_id === userId.value && !message.deleted_by_sender) {
                    return true;
                }
                return message.receiver_id === userId.value && !message.deleted_by_receiver;
            });
        } else {
            console.error('Mesajlar getirilirken hata oluştu:', response.data.errors || response.data);
        }
    } catch (error) {
        console.error('Mesajlar getirilirken hata oluştu:', error);
    }
};

const deleteMessages = async () => {
    if (!props.selectedFriend) {
        console.error("Mesajları silmek için bir arkadaş seçilmemiş.");
        return;
    }

    try {
        const response = await axios.delete(`/api/messages/${props.selectedFriend.id}`);
        if (response.data.success) {
            messages.value = [];
        } else {
            console.error('Mesajlar silinirken hata oluştu:', response.data.errors || response.data);
        }
    } catch (error) {
        console.error('Mesajlar silinirken hata oluştu:', error);
    }
};

const getUserId = async () => {
    try {
        const response = await axios.get('/api/user');
        userId.value = response.data.id;
    } catch (error) {
        console.error('Kullanıcı ID alınırken hata oluştu:', error);
    }
};

const getUser = async () => {
    try {
        const response = await axios.get('/api/user');
        user.value = response.data;
    } catch (error) {
        console.error("Kullanıcı bilgileri alınırken hata oluştu:", error);
    }
}

// İlk yüklemede kullanıcı bilgilerini al
onMounted(async () => {
    getUser();
    getUserId();
    fetchMessages();

    echo.private(`chat.${currentUserId}`)
        .listen('MessageSent', (e) => {
             messages.value.push(e.message);
        });

});

const handleFileUpload = async (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('attachments[]', files[i]);
        }
        try {
            const response = await axios.post('/api/messages/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            if (response.data.success) {
                const attachments = response.data.attachments.map(attachment => ({
                    id: attachment.id,
                    url: attachment.url,
                }));

                const newMessage = {
                    sender_id: user.value.id,
                    receiver_id: props.selectedFriend.id,
                    message: form.value.content,
                    attachments: attachments,
                };

                messages.value.push(newMessage);
                form.value.content = '';
            } else {
                console.error('Dosya yüklenirken hata oluştu:', response.data.errors || response.data);
            }
        } catch (error) {
            console.error('Dosya yüklenirken hata oluştu:', error);
        }
    }
};

</script>

<style>
/* WebKit (Chrome, Safari, Edge) */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent; /* Track'in arka planı şeffaf, WhatsApp'taki gibi */
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: #888; /* Kaydırma çubuğu için gri renk */
    border-radius: 10px; /* Köşeleri yuvarlama */
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #555; /* Hover durumunda koyu gri renk */
}

/* Firefox */
.sidebar {
    scrollbar-width: thin;
    scrollbar-color: #888 transparent; /* Kaydırma çubuğu için gri renk, track için şeffaf */
}


body {
    font-family: 'Nunito', sans-serif;
}

textarea {
    outline: none;
}

textarea:focus {
    outline: none;
}
</style>
