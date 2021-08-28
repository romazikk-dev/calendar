// initial state
const state = () => ({
    data: null,
});

// getters
const getters = {
    all: (state) => {
        return state;
    },
    data: (state) => {
        return state.data;
    },
}

// mutations
const mutations = {
    changeData: (state) => {
        state.data = calendarHelper.numericHelper.getRandomInt();
    },
}

// actions
// const actions = {
//     changedata: function(context) {
//         this.commit('moving_event/resetPicked');
//         this.dispatch('moving_event/set_cookie');
//     },
// }

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  mutations
}