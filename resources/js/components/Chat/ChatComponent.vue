<template>
    <div class="flex flex-col h-screen">
        <!-- Mesaj Listeleme Bölümü -->
        <div class="flex-grow overflow-auto p-4 bg-white">
            <div v-if="messages.length > 0">
                <ul>
                    <li v-for="message in messages" :key="message.id" :class="{'text-right': !message.sender}">
                        <div :class="{'flex-row-reverse': !message.sender, 'flex-row': message.sender}" class="flex items-start mb-4">
                            <img :src="message.user.avatar" class="w-8 h-8 rounded-full mr-2" :alt="message.user.name" />
                            <div :class="{'bg-blue-600 text-white': !message.sender, 'bg-gray-100': message.sender}" class="p-4 rounded-xl max-w-lg">
                                <p>{{ message.content }}</p>
                                <UserTime :message="message" />
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else class="text-center text-gray-500">
                Bu oldukça boş görünüyor...
            </div>
        </div>

        <!-- Mesaj Gönderim Bölümü -->
        <div class="bg-white p-4 border-t border-gray-300">
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
                <button @click="sendMessage" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white ml-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-6.504-6.504a1 1 0 0 1 1.414-1.414l6.504 6.504a1 1 0 0 1 0 1.414l-6.504 6.504a1 1 0 1 1-1.414-1.414l6.504-6.504z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import UserTime from "@/components/Chat/Partials/UserTime.vue";
import EmojiPicker from 'emoji-picker';
import axios from 'axios';

const form = ref({
    content: ''
});
const showEmojis = ref(false);
const messages = ref([]);

const toggleEmojis = () => {
    showEmojis.value = !showEmojis.value;
};

const onSelectEmoji = (emoji) => {
    form.value.content += emoji.native;
};

const sendMessage = async () => {
    if (form.value.content.trim()) {
        try {
            // Mesaj verilerini oluştur
            const response = await axios.post('/api/messages', {
                sender_id: 1, // Burada uygun bir kullanıcı ID'si ile değiştirin
                receiver_id: 2, // Burada uygun bir alıcı ID'si ile değiştirin
                message: form.value.content,
            });

            if (response.data.success) {
                // Başarılı yanıt alındığında mesajı ekle
                messages.value.push({
                    id: response.data.data.id,
                    content: form.value.content,
                    user: { avatar: '/path/to/avatar.png', name: 'Kullanıcı' },
                    sender: true
                });
                form.value.content = ''; // Mesajı gönderdikten sonra alanı temizle
            } else {
                console.error("Mesaj gönderilirken hata oluştu:", response.data.errors);
            }
        } catch (error) {
            console.error("Mesaj gönderilirken hata oluştu:", error);
        }
    }
};

const handleFileUpload = (event) => {
    const files = event.target.files;
    console.log("Yüklenen Dosyalar:", files);
};
</script>

