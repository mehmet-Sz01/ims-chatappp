
import './bootstrap';
import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/saga-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'



import router from './router/router.js';
import store from './store';

import AppComponent from "./components/AppComponent.vue";

const app = createApp({});
app.component("app",AppComponent);
app.use(router)
app.use(store)
app.use(PrimeVue);

app.config.globalProperties.$axios = axios;


app.mount('#app');
