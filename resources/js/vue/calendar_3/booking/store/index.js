import Vue from 'vue';
import Vuex from 'vuex';

import filters from './modules/filters';
import owner from './modules/owner';
import client from './modules/client';
import halls from './modules/halls';
import updater from './modules/updater';
import specifics from './modules/specifics';
import custom_titles from './modules/custom_titles';
import template from './modules/template';
import dates from './modules/dates';
import navbar from './modules/navbar';
import view from './modules/view';
import keys from './modules/keys';
// import free_get_data_params from './modules/free_get_data_params';


Vue.use(Vuex);

// const debug = process.env.NODE_ENV !== 'production'


export default new Vuex.Store({
    modules: {
        filters,
        owner,
        client,
        halls,
        updater,
        specifics,
        custom_titles,
        template,
        dates,
        navbar,
        view,
        keys,
    },
});