// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    // minDurationSec: 600,
    minDuration: 10,
    // maxDurationSec: 10800,
    maxDuration: 180,
});

// getters
const getters = {
    minDuration: (state) => {
        return state.minDuration;
    },
    // minDurationMin: (state) => {
    //     return state.minDurationMin;
    // },
    maxDuration: (state) => {
        return state.maxDuration;
    },
    // maxDurationMin: (state) => {
    //     return state.maxDurationMin;
    // },
}

// mutations
// const mutations = {
//     increaseCounter: (state) => {
//         state.counter++;
//     },
// }

// const actions = {
//     clientInfo (context) {
//         // console.log(JSON.parse(JSON.stringify(context.rootState.client)));
//         // context.rootState;
//         // const savedCartItems = [...state.items]
//         // commit('setCheckoutStatus', null)
//         // // empty cart
//         // commit('setCartItems', { items: [] })
//         // shop.buyProducts(
//         //   products,
//         //   () => commit('setCheckoutStatus', 'successful'),
//         //   () => {
//         //     commit('setCheckoutStatus', 'failed')
//         //     // rollback to the cart saved before sending the request
//         //     commit('setCartItems', { items: savedCartItems })
//         //   }
//         // )
//     },
// 
// }

export default {
  namespaced: true,
  state,
  getters,
  // actions,
  // mutations
}