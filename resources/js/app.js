import './bootstrap';
import { createApp } from 'vue';
import { createRouter , createWebHistory  } from 'vue-router';
import App from './App.vue';
import routes from './routes';
import api from './plugins/axios';
// Create Vue app instance
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'; 
const app = createApp(App);
app.config.globalProperties.$axios = axios;

//create router instance
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
//user router
app.use(router);
// Mount Vue app
app.mount("#app"); 

