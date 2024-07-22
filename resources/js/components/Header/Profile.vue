
<template>
   <div>
       <Header/>
   </div>
    <br><br><br>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Profil Bilgileri</h2>
        <form @submit.prevent="updateProfile">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Ad</label>
                <input v-model="user.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Ad">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="surname">Soyad</label>
                <input v-model="user.surname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="surname" type="text" placeholder="Soyad">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">E-posta</label>
                <input v-model="user.email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="E-posta">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Güncelle</button>
            </div>
        </form>

        <h2 class="text-2xl font-semibold mb-4 mt-8">Şifre Değiştir</h2>
        <form @submit.prevent="updatePassword">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">Mevcut Şifre</label>
                <input v-model="passwords.current_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="current_password" type="password" placeholder="Mevcut Şifre">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">Yeni Şifre</label>
                <input v-model="passwords.new_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="new_password" type="password" placeholder="Yeni Şifre">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_new_password">Yeni Şifre (Tekrar)</label>
                <input v-model="passwords.confirm_new_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="confirm_new_password" type="password" placeholder="Yeni Şifre (Tekrar)">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Şifreyi Güncelle</button>
            </div>
        </form>

        <h2 class="text-2xl font-semibold mb-4 mt-8 text-red-600">Hesabı Sil</h2>
        <form @submit.prevent="deleteAccount">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="delete_password">Şifre</label>
                <input v-model="deletePassword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="delete_password" type="password" placeholder="Şifre">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Hesabı Sil</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from "@/components/Header/Header.vue";

const user = ref({ name: '', surname: '', email: '' });
const passwords = ref({ current_password: '', new_password: '', confirm_new_password: '' });
const deletePassword = ref('');

const getUserInfo = async () => {
    try {
        const response = await axios.get('/api/user');
        user.value = response.data;
    } catch (error) {
        console.error('Kullanıcı bilgileri alınırken hata oluştu:', error);
    }
};

const updateProfile = async () => {
    try {
        await axios.put('/api/user', user.value);
        alert('Profil başarıyla güncellendi!');
    } catch (error) {
        console.error('Profil güncellenirken hata oluştu:', error);
    }
};

const updatePassword = async () => {
    try {
        await axios.put('/api/user/password', passwords.value);
        alert('Şifre başarıyla güncellendi!');
        passwords.value.current_password = '';
        passwords.value.new_password = '';
        passwords.value.confirm_new_password = '';
    } catch (error) {
        console.error('Şifre güncellenirken hata oluştu:', error);
    }
};

const deleteAccount = async () => {
    try {
        await axios.delete('/api/user', {
            data: {
                password: deletePassword.value
            }
        });
        alert('Hesap başarıyla silindi!');
        window.location.href = '/login';
    } catch (error) {
        console.error('Hesap silinirken hata oluştu:', error);
    }
};

onMounted(() => {
    getUserInfo();
});
</script>

<style scoped>
/* Profil sayfası için ek stiller */
</style>
