<!-- FriendDetailsModal.vue -->
<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-80">
            <h2 class="text-xl font-bold mb-4">Arkadaş Bilgileri</h2>
                <p><strong>İsim:</strong> {{ friend.name }} </p>
                <p><strong>Soyisim:</strong> {{ friend.surname }}</p>
                <p><strong>Email:</strong> {{ friend.email }}</p>
            <button @click="closeModal" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Kapat</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        friend: Object,
        show: Boolean
    },
    methods: {
        closeModal() {
            this.$emit('close');
        }
    }
}
</script>
