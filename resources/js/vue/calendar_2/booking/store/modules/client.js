// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    info: null,
    bookings: null,
    token: typeof token === 'undefined' || token === null ? null : token,
});

// getters
const getters = {
    info: (state) => {
        return state.info;
    },
    bookings: (state) => {
        return state.bookings;
    },
    token: (state) => {
        return state.token;
    },
}

//actions
const actions = {
    increaseUpdaterCounter (context) {
        context.rootState.updater.counter++;
        console.log(JSON.parse(JSON.stringify(context)));
    },
    // logout: (context) => {
    //     state.info = null;
    //     state.bookings = null;
    //     state.token = null;
    //     cookie.remove('token');
    // },
}

// mutations
const mutations = {
    setInfo: (state, info) => {
        // alert(newFilters);
        // console.log(newFilters);
        if(typeof info !== 'undefined' && typeof info !== null){
            state.info = info;
        }else{
            state.info = null;
        }
    },
    setToken: (state, token) => {
        if(typeof token !== 'undefined' && typeof token !== null){
            state.token = token;
        }else{
            state.token = null;
            cookie.remove('token');
        }
    },
    setBookings: (state, bookings) => {
        if(typeof bookings !== 'undefined' && typeof bookings !== null){
            state.bookings = bookings;
        }else{
            state.bookings = null;
        }
    },
    logout: (state) => {
        state.info = null;
        state.bookings = null;
        state.token = null;
        cookie.remove('token');
    },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}