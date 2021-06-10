// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    all: halls
});

// getters
const getters = {
    all: (state) => {
        return state.all;
    },
}

// mutations
const mutations = {
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
}

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  // mutations
}