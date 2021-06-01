// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
  items: {
      hall: calendarBookingHelper.getFilter('hall'),
      template: calendarBookingHelper.getFilter('template'),
      worker: calendarBookingHelper.getFilter('worker'),
      view: calendarBookingHelper.getFilter('view'),
  },
  views: ['month','week','day','list'],
  // urlSearchPath: null,
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
    view: (state) => {
        return state.items.view;
    },
    isEmpty: (state) => {
        return (state.items.hall === null || state.items.template === null || state.items.worker === null);
    },
    urlSearchPath: (state) => {
        let search = '';
        if(state.items.hall != null && typeof state.items.hall.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'hall=' + state.items.hall.id;
        if(state.items.worker != null && typeof state.items.worker.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'worker=' + state.items.worker.id;
        if(state.items.template != null && typeof state.items.template.id !== 'undefined')
            search += (search == '' ? '' : '&') + 'template=' + state.items.template.id;
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
        // alert(newFilters);
        // console.log(newFilters);
        if(typeof newFilters !== 'undefined' &&
        typeof newFilters.hall !== 'undefined' && typeof newFilters.hall !== null &&
        typeof newFilters.template !== 'undefined' && typeof newFilters.template !== null &&
        typeof newFilters.worker !== 'undefined' && typeof newFilters.worker !== null &&
        typeof newFilters.view !== 'undefined'){
            state.items.hall = newFilters.hall;
            state.items.template = newFilters.template;
            state.items.worker = newFilters.worker;
            state.items.view = newFilters.view;
            
            cookie.set('filters', {
                hall: state.items.hall.id,
                worker: state.items.worker.id,
                template: state.items.template.id,
                view: state.items.view,
            });
        }
    },
    changeView: (state, view) => {
        if(typeof view !== 'undefined' && view !== null &&
        state.views.includes(view.toLowerCase().trim())){
            state.items.view = view;
            // cookie.set('filters.view', view);
            cookie.set('filters', {
                hall: state.items.hall.id,
                worker: state.items.worker.id,
                template: state.items.template.id,
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