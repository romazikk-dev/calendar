// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    event: null,
    client: null,
    // template: calendarBookingHelper.getFilter('template'),
    // worker: calendarBookingHelper.getFilter('worker'),
    // urlSearchPath: null,
});

// getters
const getters = {
    all: (state) => {
        return {
            event: state.event,
            client: state.client,
            // template: state.template,
            // worker: state.worker,
        };
    },
    event: (state) => {
        return state.event;
    },
    client: (state) => {
        return state.client;
    },
}

// mutations
const mutations = {
    my: (state) => {
        alert(111);
    },
    setItems: (state, items) => {
        if(typeof items === 'undefined')
            return;
        // alert(newFilters);
        // console.log(newFilters);
        if(typeof items.event !== 'undefined' && typeof items.event !== null)
            state.event = items.event;
        if(typeof items.client !== 'undefined' && typeof items.client !== null)
            state.client = items.client;
        
        if((typeof items.event !== 'undefined' && typeof items.event !== null) ||
        (typeof items.client !== 'undefined' && typeof items.client !== null)){
            cookie.set('moved_event', {
                event: state.event.id,
                client: state.client.id,
            });
        }
    },
    // changeFilters: (state, newFilters) => {
    //     // alert(newFilters);
    //     // console.log(newFilters);
    //     if(typeof newFilters !== 'undefined' &&
    //     typeof newFilters.hall !== 'undefined' && typeof newFilters.hall !== null &&
    //     typeof newFilters.template !== 'undefined' && typeof newFilters.template !== null &&
    //     typeof newFilters.worker !== 'undefined' && typeof newFilters.worker !== null &&
    //     typeof newFilters.view !== 'undefined'){
    //         state.items.hall = newFilters.hall;
    //         state.items.template = newFilters.template;
    //         state.items.worker = newFilters.worker;
    //         state.items.view = newFilters.view;
    // 
    //         cookie.set('filters', {
    //             hall: state.items.hall.id,
    //             worker: state.items.worker.id,
    //             template: state.items.template.id,
    //             view: state.items.view,
    //         });
    //     }
    // },
    // changeView: (state, view) => {
    //     if(typeof view !== 'undefined' && view !== null &&
    //     state.views.includes(view.toLowerCase().trim())){
    //         state.items.view = view;
    //         // cookie.set('filters.view', view);
    //         cookie.set('filters', {
    //             hall: state.items.hall.id,
    //             worker: state.items.worker.id,
    //             template: state.items.template.id,
    //             view: state.items.view,
    //         });
    //     }
    // },
}

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  mutations
}