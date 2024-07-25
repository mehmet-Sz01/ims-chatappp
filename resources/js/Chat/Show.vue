<script setup>
import { ref } from 'vue';
import ChatLayout from "../Layouts/ChatLayout.vue";
import Sidebar from "../components/Sidebar/Sidebar.vue";
import Header from "../components/Header/Header.vue";
import ChatComponent from "../components/Chat/ChatComponent.vue";
import Messages from "../components/Chat/Messages.vue";

const chats = ref([]);
const selectedFriend = ref(null);

const handleStartChat = (friend) => {
    selectedFriend.value = friend;
};
</script>

<template>
    <div class="flex flex-col h-screen">
        <!-- Header'ı ekle -->
        <Header />

        <!-- İçerik kısmı -->
        <div class="flex flex-grow mt-16">
            <ChatLayout>
                <template v-slot:sidebar>
                    <Sidebar :chats="chats" @start-chat="handleStartChat"/>
                </template>
                <ChatComponent :selectedFriend="selectedFriend" />
                <div class="flex flex-grow">
                    <Messages/>
                </div>
            </ChatLayout>
        </div>
    </div>
</template>
