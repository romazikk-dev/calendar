const state = () => ({
    cookieName: 'dashboard_calendar-filters',
    status: calendarHelper.filter.get('status'),
    hall: calendarHelper.filter.get('hall'),
    template: calendarHelper.filter.get('template'),
    worker: calendarHelper.filter.get('worker'),
    client: calendarHelper.filter.get('client'),
    duration: calendarHelper.filter.get('duration'),
});

// getters
const getters = {
    allFilters: (state) => {
        let newState = JSON.parse(JSON.stringify(state));
        delete newState.cookieName;
        return newState;
    },
    all: (state) => {
        return state;
    },
    status: (state) => {
        return state.status;
    },
    hall: (state) => {
        return state.hall;
    },
    template: (state) => {
        return state.template;
    },
    worker: (state) => {
        return state.worker;
    },
    client: (state) => {
        return state.client;
    },
    duration: (state) => {
        return state.duration;
    },
    listOfFilters: (state, getters) => {
        return Object.keys(getters.allFilters);
        // return state.listOfFilters;
    },
    urlSearchParams: (state, getters) => {
        
    },
    isAllEmpty: (state, getters) => {
        let allFiltersKeys = Object.keys(getters.allFilters);
        
        for(let idx in allFiltersKeys)
            if(typeof state[allFiltersKeys[idx]] !== 'undefined' && state[allFiltersKeys[idx]] !== null)
                return false;
                
        return true;
    },
    isAny: (state, getters) => {
        return state.hall !== null || state.template !== null || state.worker !== null ||
        state.client !== null || state.duration !== null;
    },
    countNotNullFilters: (state, getters) => {
        let arr = Object.values(getters.allFilters);
        arr = arr.filter(x => x !== null);
        return arr.length;
    },
}

const actions = {
    removeAllFilters: function(context, e) {
        let filters = this.getters['filters/allFilters'];
        let filtersKeys = Object.keys(filters);
        let filtersToSet = {}
        for(let idx in filtersKeys){
            if(typeof context.state[filtersKeys[idx]] !== undefined)
                // context.state[filtersKeys[idx]] = null;
                filtersToSet[filtersKeys[idx]] = null;
        }
        this.dispatch('filters/setFilters', filtersToSet);
        
        // filters = this.getters['filters/allFilters'];
        // console.log(JSON.parse(JSON.stringify(Object.keys(filters))));
    },
    removeEntireFilter: function(context, type) {
        let filtersKeys = Object.keys(this.getters['filters/allFilters']);
        if(!filtersKeys.includes(type))
            return;
            
        let dispatchName = {client: 'filters/setClient', hall: 'filters/setHall', template: 'filters/setTemplate',
            worker: 'filters/setWorker', duration: 'filters/setDuration', status: 'filters/setStatus'}
        
        for(let idx in filtersKeys){
            if(type == filtersKeys[idx])
                this.dispatch(dispatchName[type]);
        }
    },
    //For array filters
    removeItemFromFilterByValue: function(context, e) {
        let dispatchName = {
            status: 'filters/setStatus',
        }
        
        if(typeof e === 'undefined' || e === null ||
        typeof e.type === 'undefined' || e.type === null || 
        typeof context.state[e.type] === 'undefined' || context.state[e.type] == null ||
        typeof e.value === 'undefined' || e.value === null ||
        !Array.isArray(context.state[e.type]) || context.state[e.type].length === 0)
            return;
        
        let filter = JSON.parse(JSON.stringify(context.state[e.type]));
        let index = filter.indexOf(e.value);
        
        if(index >= 0)
            filter.splice(index, 1);
        
        if(filter.length === 0){
            this.dispatch(dispatchName[e.type]);
        }else{
            this.dispatch(dispatchName[e.type], filter);
        }
    },
    //For object filters
    removeItemFromFilterById: function(context, e) {
        let dispatchName = {
            client: 'filters/setClient',
            hall: 'filters/setHall',
            template: 'filters/setTemplate',
            worker: 'filters/setWorker'
        }
        
        if(typeof e === 'undefined' || e === null ||
        typeof e.type === 'undefined' || e.type === null || !['client','hall','template','worker'].includes(e.type) ||
        typeof e.itemId === 'undefined' || e.itemId === null || isNaN(e.itemId) ||
        !Array.isArray(context.state[e.type]) || context.state[e.type].length === 0)
            return;
        
        // alert(333);
        
        let newFilter = context.state[e.type].filter(itm => itm.id != e.itemId);
        if(newFilter.length === 0){
            this.dispatch(dispatchName[e.type], null);
        }else{
            this.dispatch(dispatchName[e.type], newFilter);
        }
    },
    setFilters: function(context, filters) {
        if(typeof filters === 'undefined')
            return;
            
        if(typeof filters.status !== 'undefined' && filters.status !== null){
            this.commit('filters/setStatus', filters.status);
        }else{
            this.commit('filters/setStatus', null);
        }
        
        if(typeof filters.hall !== 'undefined' && filters.hall !== null){
            this.commit('filters/setHall', filters.hall);
        }else{
            this.commit('filters/setHall', null);
        }
            
        if(typeof filters.template !== 'undefined' && filters.template !== null){
            this.commit('filters/setTemplate', filters.template);
        }else{
            this.commit('filters/setTemplate', null);
        }
            
        if(typeof filters.worker !== 'undefined' && filters.worker !== null){
            this.commit('filters/setWorker', filters.worker);
        }else{
            this.commit('filters/setWorker', null);
        }
            
        if(typeof filters.client !== 'undefined' && filters.client !== null){
            this.commit('filters/setClient', filters.client);
        }else{
            this.commit('filters/setClient', null);
        }
            
        if(typeof filters.duration !== 'undefined'){
            this.commit('filters/setDuration', filters.duration);
        }else{
            this.commit('filters/setDuration', null);
        }
            
        this.dispatch('filters/setCookie');
    },
    setStatus: function(context, status) {
        this.commit('filters/setStatus', status);
        this.dispatch('filters/setCookie');
    },
    setHall: function(context, hall) {
        this.commit('filters/setHall', hall);
        this.dispatch('filters/setCookie');
    },
    setTemplate: function(context, template) {
        this.commit('filters/setTemplate', template);
        this.dispatch('filters/setCookie');
    },
    setWorker: function(context, worker) {
        this.commit('filters/setWorker', worker);
        this.dispatch('filters/setCookie');
    },
    setClient: function(context, client) {
        this.commit('filters/setClient', client);
        this.dispatch('filters/setCookie');
    },
    setDuration: function(context, duration) {
        this.commit('filters/setDuration', duration);
        this.dispatch('filters/setCookie');
    },
    setCookie: ({state}) => {
        // alert('setCookie');
        // console.log(JSON.parse(JSON.stringify(state)));
        cookie.set(state.cookieName, {
            status: state.status,
            hall: calendarHelper.filter.getOnlyIdsAsArrayFromFilterItem(state.hall),
            worker: calendarHelper.filter.getOnlyIdsAsArrayFromFilterItem(state.worker),
            template: calendarHelper.filter.getOnlyIdsAsArrayFromFilterItem(state.template),
            client: calendarHelper.filter.getOnlyIdsAsArrayFromFilterItem(state.client),
            duration: state.duration,
        });
    },
}

// mutations
const mutations = {
    setStatus: (state, status) => {
        state.status = typeof status !== 'undefined' && status !== null &&
        Array.isArray(status) && status.length > 0 ? status : null;
    },
    setHall: (state, hall) => {
        state.hall = typeof hall !== 'undefined' && hall !== null ? hall : null;
    },
    setTemplate: (state, template) => {
        state.template = typeof template !== 'undefined' && template !== null ? template : null;
    },
    setWorker: (state, worker) => {
        state.worker = typeof worker !== 'undefined' && worker !== null ? worker : null;
    },
    setClient: (state, client) => {
        state.client = typeof client !== 'undefined' && client !== null ? client : null;
        console.log(JSON.parse(JSON.stringify('setClient')));
        console.log(JSON.parse(JSON.stringify(state.client)));
    },
    setDuration: (state, duration) => {
        if(typeof duration === 'undefined' || duration === null){
            state.duration = null;
            return;
        }
            
        let composedDuration = {}
        if(typeof duration.start !== 'undefined' || duration.start !== null || isNaN(duration.start)){
            composedDuration.start = duration.start;
        }else{
            composedDuration.start = null;
        }
        
        if(typeof duration.end !== 'undefined' || duration.end !== null || isNaN(duration.end)){
            composedDuration.end = duration.end;
        }else{
            composedDuration.end = null;
        }
        
        state.duration = composedDuration;
    },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}