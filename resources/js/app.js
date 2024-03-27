/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import PostsIndex from './components/PostsIndex.vue';
import { i18nVue } from 'laravel-vue-i18n'; 



/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const routes = [ 
    {
        path: '/posts',
        name: 'posts.index', 
        component: PostsIndex,
    },
    
    
] 
 
const router = createRouter({ 
    history: createWebHistory(),
    routes
}) 

createApp(App)
    .use(router) 
    .mount('#spa')


/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

