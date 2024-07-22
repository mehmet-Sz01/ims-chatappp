<script setup>
import { ref } from 'vue';
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import { useStore } from "vuex";
import { useRouter } from 'vue-router';

const user = ref({
    email: null,
    password: null,
});
const store = useStore();
const router = useRouter();

const login = async () => {
    try {
        await store.dispatch('login', user.value);
    } catch (error) {
        console.error('Login error:', error);
    }
};

const register = () => {
    router.push({ name: 'register' });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen min-w-screen overflow-hidden bg-gray-100">
        <div class="flex flex-col items-center justify-center">
            <div class="rounded-3xl p-2 bg-gradient-to-b from-blue-500 via-blue-300 to-transparent">
                <div class="w-full bg-white py-8 px-5 sm:px-8 rounded-3xl shadow-md">
                    <div class="text-center mb-5">
                        <div class="text-gray-900 text-3xl font-medium mb-3">
                            <img src="/image/IMS-Logo-Picton-Blue.png" alt="IMS Logo" class="mx-auto mb-3 w-30 h-14">
                        </div>
                    </div>
                    <div>
                        <label for="email1" class="block text-gray-900 text-xl font-medium mb-2">E-posta</label>
                        <InputText id="email1" type="text" placeholder="E-posta Adresi" class="w-full md:w-30rem mb-5 p-4 border border-gray-300 rounded-lg" v-model="user.email" />

                        <label for="password1" class="block text-gray-900 font-medium text-xl mb-2">Şifre</label>
                        <Password id="password1" v-model="user.password" placeholder="Şifre" :toggleMask="true" class="w-full mb-3" inputClass="w-full border border-gray-300 rounded-lg" :inputStyle="{ padding: '1rem' }"></Password>

                        <Button @click="login" label="Giriş Yap" class="w-full py-3 text-xl bg-green-500 hover:bg-green-600 text-white rounded-lg mt-4 text-center" />

                        <Button @click="register" label="Kayıt ol" class="w-full py-3 text-xl bg-blue-500 hover:bg-blue-600 text-white rounded-lg mt-4 text-center" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
