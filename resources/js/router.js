import { createRouter, createWebHistory } from 'vue-router';

import Index from "./components/Index.vue";
import Create from "./components/Create.vue";
import Edit from "./components/Edit.vue";
import Types from "./components/Types.vue";

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
    },
    {
        name: 'Types',
        path: '/types/',
        component: Types
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
