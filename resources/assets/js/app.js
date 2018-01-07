require('./app/bootstrap');

// Load Vue based dependencies
import Vue from 'vue';
import Vuetify from 'vuetify';
import { store } from './store/index';

// Register Vue based dependencies
Vue.use(Vuetify);

// Set up Router
import Router from './app/router';

/** Register Vue filters */
import MoneyFilter from './filters/money';
Vue.filter('money', MoneyFilter);

// Register Vue components
Vue.component('dashboard', require('./Dashboard.vue'));

const app = new Vue({
    el: '#app',
    router: Router,
    store: store
});
