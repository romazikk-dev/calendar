// initial state
const state = () => ({
    templateSpecifics: templateSpecifics,
    templateSpecificsAsIdKey: (typeof templateSpecificsAsIdKey !== 'undefined' && templateSpecificsAsIdKey !== null) ?
        templateSpecificsAsIdKey : null,
});

// getters
const getters = {
    templateSpecifics: (state) => {
        return state.templateSpecifics;
    },
    templateSpecificsAsIdKey: (state) => {
        return state.templateSpecificsAsIdKey;
    },
}

//actions
const actions = {
    // increaseUpdaterCounter (context) {
    //     context.rootState.updater.counter++;
    //     console.log(JSON.parse(JSON.stringify(context)));
    // },
}

// mutations
const mutations = {
    // setInfo: (state, info) => {
    //     // alert(newFilters);
    //     // console.log(newFilters);
    //     if(typeof info !== 'undefined' && typeof info !== null){
    //         state.info = info;
    //     }else{
    //         state.info = null;
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