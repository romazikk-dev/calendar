const state = () => ({
    items: {
        hall: calendarBookingHelper.getFilter('hall'),
        template: calendarBookingHelper.getFilter('template'),
        worker: calendarBookingHelper.getFilter('worker'),
        client: calendarBookingHelper.getFilter('client'),
        view: calendarBookingHelper.getFilter('view') !== null ? calendarBookingHelper.getFilter('view') : 'month',
    },
    views: ['month','week','day','list'],
});

// getters
const getters = {
    views: (state) => {
        return state.views;
    },
    all: (state) => {
        return {
            hall: state.items.hall,
            template: state.items.template,
            worker: state.items.worker,
            client: state.items.client,
            view: state.items.view,
        };
    },
    hall: (state) => {
        return state.items.hall;
    },
    template: (state) => {
        return state.items.template;
    },
    worker: (state) => {
        return state.items.worker;
    },
    client: (state) => {
        return state.items.client;
    },
    view: (state) => {
        return state.items.view;
    },
    isEmpty: (state) => {
        return (
            state.items.hall === null || state.items.template === null ||
            state.items.worker === null || state.items.client === null
        );
    },
    urlSearchPath: (state) => {
        let search = '';
        
        if(state.items.hall != null && typeof state.items.hall.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'hall=' + state.items.hall.id;
        if(state.items.worker != null && typeof state.items.worker.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'worker=' + state.items.worker.id;
        if(state.items.template != null && typeof state.items.template.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'template=' + state.items.template.id;
        if(state.items.client != null && typeof state.items.client.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'client=' + state.items.client.id;
        if(state.items.view != null)
            search += (search == '' ? '' : '&') + 'view=' + state.items.view.toLowerCase().trim();
        
        if(search == '')
            return null;
            
        return search;
    },
}

// mutations
const mutations = {
    changeFilters: (state, newFilters) => {
        if(typeof newFilters === 'undefined')
            return;
            
        if(typeof newFilters.hall !== 'undefined' && typeof newFilters.hall !== null)
            state.items.hall = newFilters.hall;
            
        if(typeof newFilters.worker !== 'undefined' && typeof newFilters.worker !== null)
            state.items.worker = newFilters.worker;
            
        if(typeof newFilters.template !== 'undefined' && typeof newFilters.template !== null)
            state.items.template = newFilters.template;
            
        if(typeof newFilters.client !== 'undefined' && typeof newFilters.client !== null)
            state.items.client = newFilters.client;
            
        if(typeof newFilters.view !== 'undefined' &&
        typeof newFilters.view !== null &&
        state.views.includes(view.toLowerCase().trim()))
            state.items.view = newFilters.view;
            
        if((typeof newFilters.hall !== 'undefined' && typeof newFilters.hall !== null) ||
            (typeof newFilters.template !== 'undefined' && typeof newFilters.template !== null) ||
            (typeof newFilters.worker !== 'undefined' && typeof newFilters.worker !== null) ||
            (typeof newFilters.client !== 'undefined' && typeof newFilters.client !== null) ||
            (
                typeof newFilters.view !== 'undefined' &&
                newFilters.view !== null &&
                state.views.includes(view.toLowerCase().trim())
            )
        ){
            cookie.set('filters', {
                hall: state.items.hall.id,
                worker: state.items.worker.id,
                template: state.items.template.id,
                client: state.items.client.id,
                view: state.items.view,
            });
        }
    },
}

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  mutations
}