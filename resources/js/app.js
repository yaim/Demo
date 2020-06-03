/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueMoment from 'vue-moment'

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueMoment)

import App from './pages/App'
import Register from './pages/auth/Register'
import Login from './pages/auth/Login'
import Logout from './pages/auth/Logout'
import Dashboard from './pages/Dashboard'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/auth/login',
            name: 'login',
            component: Login
        },
        {
            path: '/auth/register',
            name: 'register',
            component: Register,
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
        },
        {
            path: '*',
            redirect: '/dashboard'
        },
    ],
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const axios = require('axios');

const store = new Vuex.Store({
    state: {
        user: null
    },
    mutations: {
        fetchUser (state) {
            const token = localStorage.getItem('token');

            axios.get('/api/auth/user', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                state.user = response.data.data
            })
            .catch(error => {
                state.user = null
            });
        },
        logout (state) {
            localStorage.removeItem('token');

            state.user = null
        },
    }
})

store.commit('fetchUser');

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
});
