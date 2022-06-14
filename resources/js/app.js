import './bootstrap';

import {createApp} from 'vue';
import * as VueRouter from 'vue-router';

import App from "./vue/App";
import {routes} from './routes'
const router = VueRouter.createRouter({
    // Because this isn't an SPA and there are routes outside Vue, we can't use `createWebHistory` below
    history: VueRouter.createWebHashHistory(),
    routes: routes
});

const app = createApp(App);
app.use(router);
app.mount('#app');

// assign global variables using `globalProperties`, to avoid polluting global namespace
app.config.globalProperties.$appName = 'TRC Resource Management';
