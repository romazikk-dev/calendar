// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    events: null,
});

// getters
const getters = {
    events: (state) => {
        return state.events;
    },
}

//actions
// const actions = {
//     setEvents ({state}, events) {
//         state.events = events;
//     },
// }

// mutations
const mutations = {
    setEvents: (state, events) => {
        state.events = events;
    },
}

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  mutations
}