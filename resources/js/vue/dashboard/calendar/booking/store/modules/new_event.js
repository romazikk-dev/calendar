// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    cookieName: 'dashboard_calendar-new_event',
    client: calendarHelper.newE.getItem('client'),
    main: {
        hall: calendarHelper.newE.getItem('main.hall'),
        worker: calendarHelper.newE.getItem('main.worker'),
        template: calendarHelper.newE.getItem('main.template'),
    },
    show: calendarHelper.newE.getItem('show'),
});

// getters
const getters = {
    all: (state) => {
        return state;
    },
    main: (state) => {
        return state.main;
    },
    client: (state) => {
        return state.client;
    },
    hall: (state) => {
        return state.main.hall;
    },
    worker: (state) => {
        return state.main.worker;
    },
    template: (state) => {
        return state.main.template;
    },
    show: (state) => {
        return state.show;
    },
    isMainFull: (state) => {
        return typeof state.main !== 'undefined' && state.main !== null &&
        typeof state.main.hall !== 'undefined' && state.main.hall !== null &&
        typeof state.main.worker !== 'undefined' && state.main.worker !== null &&
        typeof state.main.template !== 'undefined' && state.main.template !== null;
    },
    isClientFull: (state) => {
        return typeof state.client !== 'undefined' && state.client !== null;
    },
    isFull: (state, getters) => {
        return getters.isMainFull && getters.isClientFull;
    },
    // urlSearchParamsMain: (state, as_string = false) => {
    urlSearchParamsFree: (state, getters) => {
        let urlSearchParams = new URLSearchParams();
        
        if(getters.isMainFull === true){
            // urlSearchParams.append("hall", state.main.hall.id);
            urlSearchParams.append("worker", state.main.worker.id);
            // urlSearchParams.append("template", state.main.template.id);
        }
            
        return urlSearchParams;
    },
    urlSearchParamsMain: (state, getters) => {
        let urlSearchParams = new URLSearchParams();
        
        if(getters.isMainFull === true){
            urlSearchParams.append("hall", state.main.hall.id);
            urlSearchParams.append("worker", state.main.worker.id);
            urlSearchParams.append("template", state.main.template.id);
        }
            
        return urlSearchParams;
    },
}

// mutations
const mutations = {
    reset: (state) => {
        state.client = null;
        state.main = {
            hall: null,
            worker: null,
            template: null,
        }
        state.show = false;
    },
    setMain: (state, main) => {
        if(typeof main !== 'undefined' && main !== null &&
        typeof main.hall !== 'undefined' && main.hall !== null &&
        typeof main.worker !== 'undefined' && main.worker !== null &&
        typeof main.template !== 'undefined' && main.template !== null){
            state.main = main;
        }
    },
    setClient: (state, client) => {
        if(typeof client !== 'undefined' && client !== null)
            state.client = client;
    },
    setShow: (state, show) => {
        if(typeof show === 'undefined')
            return;
        
        state.show = show === true ? true : false;
    },
}

// actions
const actions = {
    reset: function(context, picked) {
        this.commit('new_event/reset');
        this.dispatch('new_event/set_cookie');
    },
    setMain: function(context, main) {
        this.commit('new_event/setMain', main);
        this.dispatch('new_event/set_cookie');
    },
    setClient: function(context, client) {
        this.commit('new_event/setClient', client);
        this.dispatch('new_event/set_cookie');
    },
    setShow: function(context, show) {
        this.commit('new_event/setShow', show);
        this.dispatch('new_event/set_cookie');
    },
    set_cookie: function({state}) {
        let cookieData = {
            client: null,
            main: {
                hall: null,
                worker: null,
                template: null,
            },
            show: false,
        }
        
        if(state.client !== null && typeof state.client.id !== 'undefined' && !isNaN(state.client.id))
            cookieData.client = state.client.id;
        
        if(this.getters['new_event/isMainFull'] &&
        typeof state.main.hall.id !== 'undefined' && !isNaN(state.main.hall.id) &&
        typeof state.main.worker.id !== 'undefined' && !isNaN(state.main.worker.id) &&
        typeof state.main.template.id !== 'undefined' && !isNaN(state.main.template.id)){
            cookieData.main.hall = state.main.hall.id;
            cookieData.main.worker = state.main.worker.id;
            cookieData.main.template = state.main.template.id;
        }
        
        cookieData.show = state.show === true ? true : false;
        
        if(cookieData.main.hall === null || cookieData.main.worker === null || cookieData.main.template === null){
            cookie.remove(state.cookieName);
        }else{
            cookie.set(state.cookieName, cookieData);
        }
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}