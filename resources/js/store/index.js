import Vuex from 'vuex';
import axios from "axios";
import router from "../router/router.js";

const state = {
    user: null,
    authenticated: null
};

const getters = {
    user: state => state.user,
    authenticated: state => state.authenticated
};

const actions = {
    async authenticate({ commit }) {
        if (!state.user) {
            return axios.get('/api/user').then(res => {
                commit('setAuthenticate', true);
                commit('setUser', res.data);
            }).catch(err => {
                console.error('Authentication error:', err);
            });
        }
    },
    async login({ commit, state }, authData) {
        await axios.get('/sanctum/csrf-cookie').then(() => {
            axios.post('/api/login', { ...authData })
                .then(async res => {
                    commit('setAuthenticate', true);
                    commit('setUser', res.data.data);
                    await router.push('/chat');
                }).catch(err => {
                console.error('Login error:', err);
            });
        });
    },
    async register({ commit }, registerData) {
        await axios.get('/sanctum/csrf-cookie').then(() => {
            axios.post('/api/register', { ...registerData })
                .then(async res => {
                    commit('setAuthenticate', true);
                    commit('setUser', res.data.data);
                    await router.push('/login');
                }).catch(err => {
                console.error('Register error:', err);
            });
        });
    },
    async logout({ commit }) {
        await axios.get('/api/logout').then(async () => {
            commit('setUser', null);
            commit('setAuthenticate', false);
            await router.push('login');
        }).catch(err => {
            console.error('Logout error:', err);
        });
    }
};

const mutations = {
    setAuthenticate(state, value) {
        state.authenticated = value;
    },
    setUser(state, value) {
        state.user = value;
    }
};

const store = new Vuex.Store({
    state,
    getters,
    actions,
    mutations
});

export default store;
