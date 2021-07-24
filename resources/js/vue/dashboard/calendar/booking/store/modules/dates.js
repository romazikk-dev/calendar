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
    week: {
        firstDate: null,
        lastDate: null,
        // monthTwoDigits: null,
        // totalDays: null,
        // firstDateWeekday: null,
        // firstDateIsoWeekday: null,
        
        // firstWeekday: null,
        // lastWeekday: null,
    },
    day: {
        date: null,
        weekday: null,
        isoWeekday: null,
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
    week: (state) => {
        return state.week;
    },
    day: (state) => {
        return state.day;
    },
    canGoToPreviousMonth: function(state) {
            let firstMonthDay, firstDayOfCurrentMonth;
            firstMonthDay = moment(state.month.firstDate).startOf('month');
            firstDayOfCurrentMonth = moment(state.current.date).startOf('month');
            return firstMonthDay.isAfter(firstDayOfCurrentMonth);
    },
    canGoToPreviousWeek: function(state) {
            let firstWeekDay, firstDayOfCurrentWeek;
            firstWeekDay = moment(state.week.firstDate);
            firstDayOfCurrentWeek = moment(state.current.date).startOf('week').add(1, 'days');
            return firstWeekDay.isAfter(firstDayOfCurrentWeek);
    },
    canGoToPreviousDay: function(state) {
            let momentDayDate, momentCurrentDate;
            momentDayDate = moment(state.day.date);
            momentCurrentDate = moment(state.current.date);
            return momentDayDate.isAfter(momentCurrentDate);
    },
}

//actions
const actions = {
    goToday ({state}) {
        let view = this.getters['view/view'];
        if(view.toLowerCase() == 'month')
            this.dispatch('dates/goTodayMonth');
        if(view.toLowerCase() == 'week')
            this.dispatch('dates/goTodayWeek');
        if(view.toLowerCase() == 'day')
            this.dispatch('dates/goTodayDay');
        if(view.toLowerCase() == 'list')
            this.dispatch('dates/goTodayList');
            
        // console.log(JSON.parse(JSON.stringify(555566666)));
        console.log(JSON.parse(JSON.stringify(view)));
    },
    goNext ({state}) {
        let view = this.getters['view/view'];
        if(view.toLowerCase() == 'month')
            this.dispatch('dates/goNextMonth');
        if(view.toLowerCase() == 'week')
            this.dispatch('dates/goNextWeek');
        if(view.toLowerCase() == 'day')
            this.dispatch('dates/goNextDay');
            
        // console.log(JSON.parse(JSON.stringify(555566666)));
        console.log(JSON.parse(JSON.stringify(view)));
    },
    goPrevious ({state}) {
        let view = this.getters['view/view'];
        if(view.toLowerCase() == 'month' && this.getters['dates/canGoToPreviousMonth'])
            this.dispatch('dates/goPreviousMonth');
        if(view.toLowerCase() == 'week' && this.getters['dates/canGoToPreviousWeek'])
            this.dispatch('dates/goPreviousWeek');
        if(view.toLowerCase() == 'day' && this.getters['dates/canGoToPreviousDay'])
            this.dispatch('dates/goPreviousDay');
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
    goPreviousWeek ({state}){
        let dateOfWeek = moment(state.week.firstDate).subtract(7, 'days').toDate();
        // alert(dateOfWeek);
        this.dispatch('dates/setWeekDates', dateOfWeek);
    },
    goPreviousDay ({state}){
        let dayDate = moment(state.day.date).subtract(1, 'days').toDate();
        this.dispatch('dates/setDayDates', dayDate);
    },
    goNextMonth ({state}){
        let dateOfMonth = moment(state.month.firstDate).add(1, 'M').toDate();
        this.dispatch('dates/setMonthDates', dateOfMonth);
        // console.log(dateOfNextMonth.toDate());
        // this.setDates(dateOfNextMonth.toDate());
        // this.$parent.setStartDate('month', new Date(this.firstMonthDate));
        // this.getData();
    },
    goNextWeek ({state}){
        let dateOfWeek = moment(state.week.firstDate).add(7, 'days').toDate();
        this.dispatch('dates/setWeekDates', dateOfWeek);
    },
    goNextDay ({state}){
        let date = moment(state.day.date).add(1, 'days').toDate();
        this.dispatch('dates/setDayDates', date);
    },
    goTodayMonth ({state}){
        this.dispatch('dates/setMonthDates', state.current.date);
    },
    goTodayWeek ({state}){
        this.dispatch('dates/setWeekDates', state.current.date);
    },
    goTodayDay ({state}){
        this.dispatch('dates/setDayDates', state.current.date);
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
    setWeekDates ({state}, oneOfWeekDate) {
        this.dispatch('dates/setCurrentDateDates');
        let momentOneOfWeekDate, firstDate, lastDate;
    	
        momentOneOfWeekDate = moment(oneOfWeekDate);
    	state.week.firstDate = momentOneOfWeekDate.startOf('week').add(1, 'days').toDate();
    	state.week.lastDate = moment(state.week.firstDate).add(6, 'days').toDate();
    	
    	state.interval.firstDate = state.week.firstDate;
        state.interval.lastDate = state.week.lastDate;
        
        console.log(JSON.parse(JSON.stringify(state)));
    },
    setDayDates ({state}, dayDate) {
        this.dispatch('dates/setCurrentDateDates');
        let momentDayDate = moment(dayDate);
    	
    	state.day.date = momentDayDate.toDate();
        state.day.weekday = momentDayDate.day();
        state.day.isoWeekday = momentDayDate.isoWeekday();
    	
    	state.interval.firstDate = state.day.date;
        state.interval.lastDate = state.day.date;
        
        // console.log(JSON.parse(JSON.stringify(state)));
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