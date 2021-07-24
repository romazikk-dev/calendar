const state = () => ({
    cookieName: 'dashboard_calendar-view',
    view: calendarHelper.view.get(),
    views: calendarHelper.view.all(),
});

// getters
const getters = {
    view: (state) => {
        return state.view;
    },
    views: (state) => {
        return state.views;
    },
}

// mutations
const mutations = {
    setView: (state, view) => {
        view = view.toLowerCase();
        if(!state.views.includes(view))
            return;
        
        state.view = view
        cookie.set(state.cookieName, state.view);
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations
}