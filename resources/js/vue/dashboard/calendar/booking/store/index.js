import Vue from 'vue';
import Vuex from 'vuex';

import filters from './modules/filters';
import owner from './modules/owner';
import client from './modules/client';
import halls from './modules/halls';
import updater from './modules/updater';
import specifics from './modules/specifics';
import custom_titles from './modules/custom_titles';
import moving_event from './modules/moving_event';
import template from './modules/template';


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
        moving_event,
        template,
    },
    // strict: debug,
    // plugins: debug ? [createLogger()] : []
});