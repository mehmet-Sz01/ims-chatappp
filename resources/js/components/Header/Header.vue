<template>
    <header class="bg-white shadow-md py-4 px-6 flex items-center justify-between fixed top-0 inset-x-0 z-50">
        <!-- Logo -->
        <div class="flex items-center text-xl font-bold text-gray-700">
            <img
                src="{{ user.profile_picture }}"
                alt="Profile Picture"
                class="w-8 h-8 rounded-full mr-2"
            />
           {{user.name}} {{user.surname}}
        </div>

        <!-- Sağ taraftaki butonlar -->
        <div class="flex items-center space-x-4">
            <icon-button :icon="['fas', 'comment']" @click="goToChat" ></icon-button>
            <icon-button :icon="['fas', 'user']"  @click="goToProfile"></icon-button>
            <icon-button :icon="['fas', 'arrow-right-from-bracket']" @click="logout" ></icon-button>
        </div>
    </header>
</template>

<script setup>
import {computed, ref} from 'vue';
import router from "../../router/router.js";
import IconButton from "../UI/IconButton.vue"
import {useStore} from "vuex";

const user = computed(() => store.getters.user);
const store = useStore();

const goToProfile = () => {
    window.location.href = '/profile';
};

    const logout = () => {
        store.dispatch('logout');
    };

const goToChat = () => {
    window.location.href = '/chat';
};
</script>
