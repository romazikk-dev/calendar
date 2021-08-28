const state = () => ({
    nav: 'all',
    navs: ['all','book'],
});

// getters
const getters = {
    nav: (state) => {
        return state.nav;
    },
    navs: (state) => {
        return state.navs;
    },
}

const actions = {
    // getUrlSearchPath: ({state}, as_string = false) => {
    //     let search = new URLSearchParams();
    // 
    //     if(state.items.hall != null && typeof state.items.hall.id !== 'undefined')
    //         search.append("hall", state.items.hall.id);
    //     if(state.items.worker != null && typeof state.items.worker.id !== 'undefined')
    //         search.append("worker", state.items.worker.id);
    //     if(state.items.template != null && typeof state.items.template.id !== 'undefined')
    //         search.append("template", state.items.template.id);
    //     if(state.items.client != null && typeof state.items.client.id !== 'undefined')
    //         search.append("client", state.items.client.id);
    //     if(state.items.view != null)
    //         search.append("view", state.items.view.toLowerCase().trim());
    // 
    //     console.log(JSON.parse(JSON.stringify('search ggggggdsd s'))); 
    //     // console.log(JSON.parse(JSON.stringify(as_string)));
    //     // console.log(JSON.parse(JSON.stringify(search.toString())));
    // 
    //     // alert(as_string);
    //     // alert(search.toString());
    // 
    //     if(as_string === true)
    //         return search.toString();
    //     return search;
    // },
}

// mutations
const mutations = {
    setNav: (state, nav) => {
        state.nav = nav;
    },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}