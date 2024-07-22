<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import ChatLayout from "../Layouts/ChatLayout.vue";
import Sidebar from "../components/Sidebar/Sidebar.vue";
import Header from "../components/Header/Header.vue";
import ChatComponent from "../components/Chat/ChatComponent.vue";

const chats = ref([]);

// Verileri API'den çekme işlemi
onMounted(async () => {
    try {
        const response = await axios.get('/api/chat/show');
        chats.value = response.data.chats;
    } catch (error) {
        console.error("Veriler alınırken hata oluştu:", error);
    }
});

// Method tanımlaması
const calculateLength = (data) => {
    if (Array.isArray(data)) {
        return data.length; // Dizinin uzunluğunu döndürür
    } else if (typeof data === 'string') {
        return data.length; // Stringin uzunluğunu döndürür
    } else {
        return 0; // Diğer durumlar için 0 döndürebiliriz veya bir hata işareti verebiliriz
    }
};
</script>



<template>
    <div class="flex flex-col h-screen ">
        <!-- Header'ı ekle -->
        <Header />

        <!-- İçerik kısmı -->
        <div class="flex flex-grow mt-16 ">
            <ChatLayout>
                <template v-slot:sidebar>
                    <Sidebar :chats="chats"/>
                </template>
                <ChatComponent/>
            </ChatLayout>
        </div>
    </div>
</template>



