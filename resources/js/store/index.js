import Vuex from 'vuex';
import axios from "axios";
import router from "../router/router.js";

const state = {
    user: null,
    authenticated: false
};

const getters = {
    user: state => state.user,
    authenticated: state => state.authenticated
};

const actions = {
    async authenticate({ commit, state }) {
        if (!state.user) {
            try {
                const res = await axios.get('/api/user');
                commit('setAuthenticate', true);
                commit('setUser', res.data);
            } catch (err) {
                console.error('Authentication error:', err);
                commit('setAuthenticate', false);
                commit('setUser', null);
            }
        }
    },
    async login({ commit }, authData) {
        try {
            await axios.get('/sanctum/csrf-cookie');
            const res = await axios.post('/api/login', { ...authData });
            commit('setAuthenticate', true);
            commit('setUser', res.data);
            await router.push('/chat');
        } catch (err) {
            console.error('Login error:', err);
        }
    },
    async register({ commit }, registerData) {
        try {
            await axios.get('/sanctum/csrf-cookie');
            const res = await axios.post('/api/register', { ...registerData });
            commit('setAuthenticate', true);
            commit('setUser', res.data);
            await router.push('/login');
        } catch (err) {
            console.error('Register error:', err);
        }
    },
    async logout({ commit }) {
        try {
            await axios.get('/api/logout');
            commit('setUser', null);
            commit('setAuthenticate', false);
            await router.push('/login');
        } catch (err) {
            console.error('Logout error:', err);
        }
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
