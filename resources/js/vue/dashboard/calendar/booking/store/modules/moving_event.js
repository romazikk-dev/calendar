// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    event: calendarHelper.movingE.getItem('event'),
    client: calendarHelper.movingE.getItem('client'),
    picked: {
        hall: calendarHelper.movingE.getItem('picked.hall'),
        worker: calendarHelper.movingE.getItem('picked.worker'),
        template: calendarHelper.movingE.getItem('picked.template'),
    },
    // template: calendarBookingHelper.getFilter('template'),
    // worker: calendarBookingHelper.getFilter('worker'),
    // urlSearchPath: null,
});

// getters
const getters = {
    all: (state) => {
        return state;
        // return {
        //     event: state.event,
        //     client: state.client,
        //     picked: state.picked,
        // };
    },
    event: (state) => {
        return state.event;
    },
    client: (state) => {
        return state.client;
    },
    picked: (state) => {
        return state.picked;
    },
    pickedHall: (state) => {
        return state.picked.hall;
    },
    pickedWorker: (state) => {
        return state.picked.worker;
    },
    pickedTemplate: (state) => {
        return state.picked.template;
    },
}

// mutations
const mutations = {
    reset: (state) => {
        state.event = null;
        state.client = null;
        state.picked = {
            hall: null,
            worker: null,
            template: null,
        }
    },
    setPicked: (state, picked) => {
        if(typeof picked === 'undefined')
            return;
        
        if(typeof picked.hall !== 'undefined' && typeof picked.hall !== null)
            state.picked.hall = picked.hall;
        if(typeof picked.worker !== 'undefined' && typeof picked.worker !== null)
            state.picked.worker = picked.worker;
        if(typeof picked.template !== 'undefined' && typeof picked.template !== null)
            state.picked.template = picked.template;
        
        this.dispatch('moving_event/set_cookie');
    },
    setItems: (state, items) => {
        if(typeof items === 'undefined')
            return;
        
        // console.log(context);
        
        if(typeof items.event !== 'undefined' && typeof items.event !== null)
            state.event = items.event;
        if(typeof items.client !== 'undefined' && typeof items.client !== null)
            state.client = items.client;
        
        // actions.set_cookie();
    },
}

// actions
const actions = {
    reset: function(context, picked) {
        this.commit('moving_event/reset');
        this.dispatch('moving_event/set_cookie');
    },
    setPicked: function(context, picked) {
        this.commit('moving_event/setPicked', picked);
        this.dispatch('moving_event/set_cookie');
    },
    setItems: function(context, items) {
        this.commit('moving_event/setItems', items);
        this.dispatch('moving_event/set_cookie');
        // console.log(this);
        // console.log(JSON.parse(JSON.stringify(context)));
    },
    set_cookie: ({state}) => {
        let cookieData = {
            event: null,
            client: null,
            picked: {
                hall: null,
                worker: null,
                template: null,
            },
        }
        
        if(state.event !== null && typeof state.event.id !== 'undefined')
            cookieData.event = state.event.id;
        if(state.client !== null && typeof state.client.id !== 'undefined')
            cookieData.client = state.client.id;
            
        if(state.picked.hall !== null && typeof state.picked.hall.id !== 'undefined')
            cookieData.picked.hall = state.picked.hall.id;
        if(state.picked.worker !== null && typeof state.picked.worker.id !== 'undefined')
            cookieData.picked.worker = state.picked.worker.id;
        if(state.picked.template !== null && typeof state.picked.template.id !== 'undefined')
            cookieData.picked.template = state.picked.template.id;
        
        if(cookieData.event === null && cookieData.client === null &&
        cookieData.picked.hall === null && cookieData.picked.worker === null && cookieData.picked.template === null){
            cookie.remove('moving_event');
        }else{
            cookie.set('moving_event', cookieData);
        }
    },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}