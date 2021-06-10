// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    counter: 0
});

// getters
const getters = {
    counter: (state) => {
        return state.counter;
    },
    // clientInfo: (state) => {
    //     return this.context.getters['client/info'];
    // },
}

// mutations
const mutations = {
    increaseCounter: (state) => {
        state.counter++;
    },
}

const actions = {
    clientInfo (context) {
        // console.log(JSON.parse(JSON.stringify(context.rootState.client)));
        // context.rootState;
        // const savedCartItems = [...state.items]
        // commit('setCheckoutStatus', null)
        // // empty cart
        // commit('setCartItems', { items: [] })
        // shop.buyProducts(
        //   products,
        //   () => commit('setCheckoutStatus', 'successful'),
        //   () => {
        //     commit('setCheckoutStatus', 'failed')
        //     // rollback to the cart saved before sending the request
        //     commit('setCartItems', { items: savedCartItems })
        //   }
        // )
    },
  
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}