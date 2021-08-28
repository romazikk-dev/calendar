// import shop from '../../api/shop'
import {GetDataFreeWithEventsParams} from '../../components/enums';

const state = () => ({
    // name: 'free_get_data_params',
    cookieName: 'dashboard_calendar-free_get_data_params',
    withEvents: calendarHelper.freeGetDataParams.getItem('withEvents'),
    bookingAnyTime: calendarHelper.freeGetDataParams.getItem('bookingAnyTime'),
});

// getters
const getters = {
    withEvents: (state) => {
        return state.withEvents;
    },
    bookingAnyTime: (state) => {
        return state.bookingAnyTime;
    },
}

// mutations
const mutations = {
    withEvents: (state, value) => {
        if(typeof value === 'undefined' || value === null ||
        !Object.values(GetDataFreeWithEventsParams).includes(value)){
            state.withEvents = null;
            return;
        }
        
        state.withEvents = value;
    },
    bookingAnyTime: (state, value) => {
        state.bookingAnyTime = value === true ? true : false;
    },
}

// actions
const actions = {
    setWithEvents: function(context, value) {
        this.commit('free_get_data_params/withEvents', value);
        this.dispatch('free_get_data_params/set_cookie');
    },
    setBookingAnyTime: function(context, value) {
        this.commit('free_get_data_params/bookingAnyTime', value);
        this.dispatch('free_get_data_params/set_cookie');
    },
    set_cookie: ({state}) => {
        let cookieData = {
            withEvents: null,
            bookingAnyTime: null,
        }
        
        if(state.withEvents !== null)
            cookieData.withEvents = state.withEvents;
        if(state.bookingAnyTime !== null)
            cookieData.bookingAnyTime = state.bookingAnyTime;
        
        if(cookieData.withEvents === null && cookieData.bookingAnyTime === null){
            cookie.remove(state.cookieName);
        }else{
            cookie.set(state.cookieName, cookieData);
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