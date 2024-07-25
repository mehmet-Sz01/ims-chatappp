import { createRouter, createWebHistory } from 'vue-router';
import Login from "../Auth/Login.vue";
import Register from "../Auth/Register.vue";
import store from "../store/index.js";
import AppComponent from "../components/AppComponent.vue";
import Show from "../Chat/Show.vue";
import Profile from "../components/Header/Profile.vue";

const routes = [
    {
        path: '/chat',
        component: AppComponent,
        children: [
            {
                path: '/chat',
                component: Show,
                name: 'chat',
            },
            {
                path: '/profile',
                component: Profile,
                name: 'profile',
            },
        ]
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
    },
    {
        path: '/register',
        component: Register,
        name: 'register',
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


 router.beforeEach(async (to, from, next) => {
    try {
      await store.dispatch('authenticate');
       if (store.getters.authenticated || to.name === 'login' || to.name === 'register') {
           next();
      } else {
           next({ name: 'login' });
      }
   } catch (error) {
      console.error('Authentication error:', error);
  next({ name: 'login' });
  }
 });

export default router;
