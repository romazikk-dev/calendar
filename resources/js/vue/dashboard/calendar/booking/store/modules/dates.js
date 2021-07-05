// import shop from '../../api/shop'

// initial state
// shape: [{ id, quantity }]
const state = () => ({
    current: {
        date: null,
        year: null,
        month: null,
        day: null,
        weekday: null,
        isoWeekday: null,
    },
    interval: {
        firstDate: null,
        lastDate: null,
    },
    month: {
        firstDate: null,
        lastDate: null,
        monthTwoDigits: null,
        totalDays: null,
        firstDateWeekday: null,
        firstDateIsoWeekday: null,
    },
});

// getters
const getters = {
    current: (state) => {
        return state.current;
    },
    interval: (state) => {
        return state.interval;
    },
    month: (state) => {
        return state.month;
    },
    canGoToPreviousMonth: function(state) {
            let firstMonthDay, firstDayOfCurrentMonth;
            firstMonthDay = moment(state.month.firstDate).startOf('month');
            firstDayOfCurrentMonth = moment(state.current.date).startOf('month');
            return firstMonthDay.isAfter(firstDayOfCurrentMonth);
    },
}

//actions
const actions = {
    goToday ({state}) {
        let view = this.getters['filters/view'];
        if(view.toLowerCase() == 'month')
            this.dispatch('dates/goTodayMonth');
            
        // console.log(JSON.parse(JSON.stringify(555566666)));
        console.log(JSON.parse(JSON.stringify(view)));
    },
    goNext ({state}) {
        let view = this.getters['filters/view'];
        if(view.toLowerCase() == 'month')
            this.dispatch('dates/goNextMonth');
            
        // console.log(JSON.parse(JSON.stringify(555566666)));
        console.log(JSON.parse(JSON.stringify(view)));
    },
    goPrevious ({state}) {
        let view = this.getters['filters/view'];
        if(view.toLowerCase() == 'month' && this.getters['dates/canGoToPreviousMonth'])
            this.dispatch('dates/goPreviousMonth');
    },
    setCurrentDateDates ({state}) {
        let currentDate = new Date();
        let momentCurrentDate = moment(currentDate);
        if(state.current.date == null)
		    state.current.date = currentDate;
        if(state.current.year == null)
		    state.current.year = momentCurrentDate.format('YYYY');
        if(state.current.month == null)
		    state.current.month = momentCurrentDate.format('MM');
        if(state.current.day == null)
		    state.current.day = momentCurrentDate.format('DD');
        if(state.current.weekday == null)
		    state.current.weekday = momentCurrentDate.weekday();
        if(state.current.isoWeekday == null)
            state.current.isoWeekday = momentCurrentDate.isoWeekday();
    },
    goPreviousMonth ({state}){
        let dateOfMonth = moment(state.month.firstDate).subtract(1, 'M').toDate();
        this.dispatch('dates/setMonthDates', dateOfMonth);
    },
    goNextMonth ({state}){
        let dateOfMonth = moment(state.month.firstDate).add(1, 'M').toDate();
        this.dispatch('dates/setMonthDates', dateOfMonth);
        // console.log(dateOfNextMonth.toDate());
        // this.setDates(dateOfNextMonth.toDate());
        // this.$parent.setStartDate('month', new Date(this.firstMonthDate));
        // this.getData();
    },
    goTodayMonth ({state}){
        // let dateOfMonth = moment(state.current.date).toDate();
        this.dispatch('dates/setMonthDates', state.current.date);
        // console.log(dateOfNextMonth.toDate());
        // this.setDates(dateOfNextMonth.toDate());
        // this.$parent.setStartDate('month', new Date(this.firstMonthDate));
        // this.getData();
    },
    setMonthDates ({state}, oneOfMonthDate) {
        this.dispatch('dates/setCurrentDateDates');
        let momentOneOfMonthDate, firstDate, lastDate;
    	
        momentOneOfMonthDate = moment(oneOfMonthDate);
    	state.month.firstDate = momentOneOfMonthDate.startOf('month').toDate();
    	state.month.lastDate = momentOneOfMonthDate.endOf('month').toDate();
    	state.month.monthTwoDigits = momentOneOfMonthDate.format('MM');
    	
    	state.month.firstDateWeekday = moment(state.month.firstDate).weekday();
        state.month.firstDateIsoWeekday = moment(state.month.firstDate).isoWeekday();
    	state.month.totalDays = state.month.lastDate.getDate();
        
        // alert(state.month.firstDateIsoWeekday);
    	
        firstDate = state.month.firstDateWeekday < 4 ?
            moment(state.month.firstDate).subtract(7, "days").startOf('week').add(1, 'days') : moment(state.month.firstDate).startOf('week').add(1, 'days');
        
        lastDate = new Date(firstDate);
        lastDate.setDate(lastDate.getDate() + 41);
    	
    	state.interval.firstDate = firstDate;
        state.interval.lastDate = lastDate;
        
        console.log(JSON.parse(JSON.stringify(state)));
        // console.log(JSON.parse(JSON.stringify(firstDate)));
        // console.log(JSON.parse(JSON.stringify(lastDate)));
    },
}

// mutations
const mutations = {
    // setInfo: (state, info) => {
    //     // alert(newFilters);
    //     // console.log(newFilters);
    //     if(typeof info !== 'undefined' && typeof info !== null){
    //         state.info = info;
    //     }else{
    //         state.info = null;
    //     }
    // },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  // mutations
}