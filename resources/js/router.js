import { createRouter, createWebHistory } from 'vue-router';

import Index from "./components/Index.vue";
import Create from "./components/Create.vue";
import Edit from "./components/Edit.vue";

const routes = [
    {
        name: 'Index',
        path: '/',
        component: Index
    },
    {
        name: 'Create',
        path: '/create',
        component: Create
    },
    {
        name: 'Edit',
        path: '/edit/:id',
        component: Edit
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
