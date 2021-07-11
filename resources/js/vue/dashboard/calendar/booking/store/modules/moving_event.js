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
    pickedTime: null,
    show: calendarHelper.movingE.getItem('show'),
});

// getters
const getters = {
    show: (state) => {
        return state.show;
    },
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
    pickedTime: (state) => {
        return state.pickedTime;
    },
    isPickedFull: (state) => {
        return typeof state.picked !== 'undefined' && state.picked !== null &&
        typeof state.picked.hall !== 'undefined' && state.picked.hall !== null &&
        typeof state.picked.worker !== 'undefined' && state.picked.worker !== null &&
        typeof state.picked.template !== 'undefined' && state.picked.template !== null;
    },
    // isPickedItemsChanged: (state, getters) => {
    //     if(getters.pickedHall !== null && )
    //     if(getters.pickedHall !== null && getters.pickedWorker !== null)
    //     return getters.pickedHall !== null && getters.pickedHall !== null
    //     typeof state.picked.hall !== 'undefined' && state.picked.hall !== null &&
    //     typeof state.picked.worker !== 'undefined' && state.picked.worker !== null &&
    //     typeof state.picked.template !== 'undefined' && state.picked.template !== null;
    // },
    pickedHall: (state) => {
        return state.picked.hall;
    },
    pickedWorker: (state) => {
        return state.picked.worker;
    },
    pickedTemplate: (state) => {
        return state.picked.template;
    },
    urlSearchParams: (state, as_string = false) => {
        let urlSearchParams = new URLSearchParams();
        
        if(state.event !== null){
            if(typeof state.picked !== 'undefined' && state.picked !== null &&
            typeof state.picked.hall !== 'undefined' && state.picked.hall !== null &&
            typeof state.picked.worker !== 'undefined' && state.picked.worker !== null &&
            typeof state.picked.template !== 'undefined' && state.picked.template !== null){
                urlSearchParams.append("hall", state.picked.hall.id);
                urlSearchParams.append("worker", state.picked.worker.id);
                urlSearchParams.append("template", state.picked.template.id);
            }else{
                urlSearchParams.append("hall", state.event.hall_id);
                urlSearchParams.append("worker", state.event.worker_id);
                urlSearchParams.append("template", state.event.template_id);
            }
        }
            
        return urlSearchParams;
    },
}

// mutations
const mutations = {
    resetPicked: (state) => {
        state.picked = {
            hall: null,
            worker: null,
            template: null,
        }
        state.pickedTime = null;
    },
    reset: (state) => {
        state.event = null;
        state.client = null;
        state.picked = {
            hall: null,
            worker: null,
            template: null,
        }
        state.pickedTime = null;
        state.show = false;
    },
    setPickedTime: (state, pickedTime) => {
        if(typeof pickedTime !== 'undefined' && pickedTime !== null)
            state.pickedTime = pickedTime;
    },
    setShow: (state, newValue) => {
        if(typeof newValue === 'undefined')
            return;
        if(newValue === true){
            state.show = true;
        }else{
            state.show = false;
        }
    },
    setPicked: (state, picked) => {
        if(typeof picked === 'undefined')
            return;
        
        if(typeof picked.hall !== 'undefined' && picked.hall !== null)
            state.picked.hall = picked.hall;
        if(typeof picked.worker !== 'undefined' && picked.worker !== null)
            state.picked.worker = picked.worker;
        if(typeof picked.template !== 'undefined' && picked.template !== null)
            state.picked.template = picked.template;
    },
    setItems: (state, items) => {
        if(typeof items === 'undefined')
            return;
        
        if(typeof items.event !== 'undefined' && typeof items.event !== null)
            state.event = items.event;
        if(typeof items.client !== 'undefined' && typeof items.client !== null)
            state.client = items.client;
    },
}

// actions
const actions = {
    resetPicked: function(context, picked) {
        this.commit('moving_event/resetPicked');
        this.dispatch('moving_event/set_cookie');
    },
    reset: function(context, picked) {
        this.commit('moving_event/reset');
        this.dispatch('moving_event/set_cookie');
    },
    setPicked: function(context, picked) {
        this.commit('moving_event/setPicked', picked);
        this.dispatch('moving_event/set_cookie');
    },
    setPickedTime: function(context, pickedTime) {
        this.commit('moving_event/setPickedTime', pickedTime);
        this.dispatch('moving_event/set_cookie');
    },
    setShow: function(context, value) {
        this.commit('moving_event/setShow', value);
        this.dispatch('moving_event/set_cookie');
    },
    setItems: function(context, items) {
        this.commit('moving_event/setItems', items);
        this.dispatch('moving_event/set_cookie');
        // console.log(this);
        // console.log(JSON.parse(JSON.stringify(3333333333)));
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
            pickedTime: null,
            show: false,
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
            
        if(state.pickedTime !== null)
            cookieData.pickedTime = state.pickedTime;
        
        cookieData.show = state.show === true ? true : false;
        
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