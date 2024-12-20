import './bootstrap';
import { createApp } from 'vue';
import VueAxios from "vue-axios";

import App from './App.vue';
import router from "./router.js";

import { CFormSelect } from '@coreui/vue';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

createApp(App)
    .use(router)
    .use(VueAxios, axios)
    .component('CFormSelect', CFormSelect)
    .component('Bootstrap5Pagination', Bootstrap5Pagination)
    .mount('#app')
