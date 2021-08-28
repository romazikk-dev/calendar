// initial state
const state = () => ({
    titles: typeof customTitles !== 'undefined' ? customTitles : null,
});

// getters
const getters = {
    // title: (state, name) => {
    //     if(state.titles === null || typeof state.titles[name] === 'undefined')
    //         return null;
    //     return state.titles[name].values.en.title;
    // },
    title: (state) => (name) => {
        if(state.titles === null || typeof state.titles[name] === 'undefined')
            return null;
        if(state.titles[name].values.en.value !== null)
            return state.titles[name].values.en.value;
            
        if(state.titles[name].title !== null)
            return state.titles[name].title;
            
        return null;
    },
}

export default {
  namespaced: true,
  state,
  getters,
}