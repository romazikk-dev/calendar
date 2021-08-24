/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import store from './store'
// import Vuex from 'vuex';
// 
// Vue.use(Vuex);
// 
// 
// const store = new Vuex.Store({
//   state: {
//     count: 0
//   },
//   mutations: {
//     increment (state) {
//       state.count++
//     }
//   }
// });
// 
// 
// console.log(store.state.count)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('dropdown-template-specifics', require('./components/template/DropdownTemplateSpecifics.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
// const Helper = require("../../../ts/calendar_helper/app").Helper;
// const ViewEnums = require("../../../ts/calendar_helper/enums/View").Helper;

window.moment = require('moment-timezone');
window.cookie = require('js-cookie');

// alert(timezone);
// alert(new Date());

// window.moment.tz.setDefault(timezone);
// window.moment.tz.setDefault('Europe/Kiev');
// window.moment.tz.setDefault('UTC');

// alert(moment().toTime());

// window.viewEnums = new ViewEnums();
// window.helper = new Helper();
// console.log(helper.my());
 
import App from './components/App.vue';

Vue.mixin({
    mounted: function(){
        // this.setApp();
        if(this.isMovingEvent)
            this.$store.dispatch('moving_event/setShow', true);
        //     this.showMovingEvent = true;
    },
    data: function(){
        return {
            // app: null,
            mainSettings: mainSettings,
            hoursList: [
                {
                    hour: '00',
                    minute: '00',
                },
                {
                    hour: '01',
                    minute: '00',
                },
                {
                    hour: '02',
                    minute: '00',
                },
                {
                    hour: '03',
                    minute: '00',
                },
                {
                    hour: '04',
                    minute: '00',
                },
                {
                    hour: '05',
                    minute: '00',
                },
                {
                    hour: '06',
                    minute: '00',
                },
                {
                    hour: '07',
                    minute: '00',
                },
                {
                    hour: '08',
                    minute: '00',
                },
                {
                    hour: '09',
                    minute: '00',
                },
                {
                    hour: '10',
                    minute: '00',
                },
                {
                    hour: '11',
                    minute: '00',
                },
                {
                    hour: '12',
                    minute: '00',
                },
                {
                    hour: '13',
                    minute: '00',
                },
                {
                    hour: '14',
                    minute: '00',
                },
                {
                    hour: '15',
                    minute: '00',
                },
                {
                    hour: '16',
                    minute: '00',
                },
                {
                    hour: '17',
                    minute: '00',
                },
                {
                    hour: '18',
                    minute: '00',
                },
                {
                    hour: '19',
                    minute: '00',
                },
                {
                    hour: '20',
                    minute: '00',
                },
                {
                    hour: '21',
                    minute: '00',
                },
                {
                    hour: '22',
                    minute: '00',
                },
                {
                    hour: '23',
                    minute: '00',
                },
            ],
            weekdaysList: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            calendarSettings: calendarSettings,
            statusData: {
                approved: {
                    key: 'approved',
                    label: 'Approved',
                },
                not_approved: {
                    key: 'not_approved',
                    label: 'Not approved',
                }
            }
        };
    },
    computed: {
        // Keys
        allKeys: function () {
            return this.$store.getters['keys/all'];
        },
        dataKey: function () {
            return this.$store.getters['keys/data'];
        },
        
        // durationRangeRestriction: function(){
        //     if(typeof templateMainSettings === 'undefined' || typeof templateMainSettings.duration_range === 'undefined' ||
        //     typeof templateMainSettings.duration_range.start === 'undefined' || typeof templateMainSettings.duration_range.end === 'undefined'){
        //         return {
        //             start: 10,
        //             end: 180
        //         }
        //     }else{
        //         return templateMainSettings.duration_range;
        //     }
        // },
        durationRangeMinMax: function(){
            if(typeof durationRange !== 'undefined')
                return durationRange;
            return {
                start: 10,
                end: 180,
            }
        },
        app: function(){
            return this.$root.$children[0];
        },
        calendar: function(){
            if(this.view.toLowerCase() === 'month' && typeof this.app.$refs.month_calendar !== 'undefined')
                return this.app.$refs.month_calendar;
            if(this.view.toLowerCase() === 'week' && typeof this.app.$refs.week_calendar !== 'undefined')
                return this.app.$refs.week_calendar;
            if(this.view.toLowerCase() === 'day' && typeof this.app.$refs.day_calendar !== 'undefined')
                return this.app.$refs.day_calendar;
            if(this.view.toLowerCase() === 'list' && typeof this.app.$refs.list_calendar !== 'undefined')
                return this.app.$refs.list_calendar;
            return null;
        },
        // calendarTitle: function(){
        // 
        // },
        navbar: function(){
            if(this.calendar !== null && typeof this.calendar.$refs.navbar !== 'undefined')
                return this.calendar.$refs.navbar;
            return null;
        },
        showMovingEvent: function () {
            return this.$store.getters['moving_event/show'];
        },
        view: function () {
            return this.$store.getters['view/view'];
        },
        views: function () {
            return this.$store.getters['view/views'];
        },
        canGoToPrevious: function(){
            if(this.view.toLowerCase() == 'month')
                return this.$store.getters['dates/canGoToPreviousMonth'];
            if(this.view.toLowerCase() == 'week')
                return this.$store.getters['dates/canGoToPreviousWeek'];
            if(this.view.toLowerCase() == 'day')
                return this.$store.getters['dates/canGoToPreviousDay'];
            if(this.view.toLowerCase() == 'list')
                return this.$store.getters['dates/canGoToPreviousList'];
                
            // alert(this.$store.dispatch('dates/canGoToPrevious'));
            // return this.$store.dispatch('dates/canGoToPrevious');
            // return this.$store.getters['dates/canGoToPrevious'];
            return false;
        },
        // Current dates
        currentDate: function(){
            return new Date(this.$store.getters['dates/current'].date);
        },
        currentYear: function(){
            return this.$store.getters['dates/current'].year;
        },
        currentMonth: function(){
            return this.$store.getters['dates/current'].month;
        },
        currentDay: function(){
            return this.$store.getters['dates/current'].day;
        },
        currentWeekday: function(){
            return this.$store.getters['dates/current'].weekday;
        },
        currentIsoWeekday: function(){
            return this.$store.getters['dates/current'].isoWeekday;
        },
        dateInterval: function(){
            return this.$store.getters['dates/interval'];
        },
        
        // Start dates
        startDates: function(){
            return this.$store.getters['dates/startDates'];
        },
        monthStartDate: function () {
            return this.startDates.month;
        },
        weekStartDate: function () {
            return this.startDates.week;
        },
        listStartDate: function () {
            return this.startDates.list;
        },
        dayStartDate: function () {
            return this.startDates.day;
        },
        
        // New event data of new_event `$store` module
        isNewEventFull: function(){
            return this.$store.getters['new_event/isFull'];
        },
        isNewEventMainFull: function(){
            return this.$store.getters['new_event/isMainFull'];
        },
        isNewEventClientFull: function(){
            return this.$store.getters['new_event/isClientFull'];
        },
        newEventAll: function(){
            return this.$store.getters['new_event/all'];
        },
        newEventMain: function(){
            return this.$store.getters['new_event/main'];
        },
        newEventMainHall: function(){
            return this.$store.getters['new_event/main'];
        },
        newEventClient: function(){
            return this.$store.getters['new_event/client'];
        },
        newEventShow: function(){
            return this.$store.getters['new_event/show'];
        },
        // newEventWithEvents: function(){
        //     return this.$store.getters['new_event/withEvents'];
        // },
        newEventUrlSearchParamsMain: function(){
            return this.$store.getters['new_event/urlSearchParamsMain'];
        },
        
        // Moving event data of moving_event `$store` module
        movingEventIsPickedFull: function(){
            return this.$store.getters['moving_event/isPickedFull'];
        },
        movingEventPicked: function(){
            return this.$store.getters['moving_event/picked'];
        },
        movingEvent: function(){
            return this.$store.getters['moving_event/event'];
        },
        // movingEventWithEvents: function(){
        //     return this.$store.getters['moving_event/withEvents'];
        // },
        isMovingEvent: function(){
            // return this.movingEvent !== null && typeof this.movingEvent.template_without_user_scope !== 'undefined';
            return typeof this.movingEvent !== 'undefined' && this.movingEvent !== null;
        },
        movingEventClient: function(){
            return this.$store.getters['moving_event/client'];
        },
        movingEventDateObj: function(){
            if(this.movingEvent === null)
                return null;
            return moment(this.movingEvent.time).toDate();
        },
        movingEventDateMoment: function(){
            if(this.movingEvent === null)
                return null;
            return moment(this.movingEvent.time);
        },
        movingEventDate: function(){
            if(this.movingEvent === null)
                return null;
            return calendarHelper.time.getEventDate(this.movingEvent);
        },
        
        // Specifics
        templateSpecifics: function(){
            return this.$store.getters['specifics/templateSpecifics'];
        },
        templateSpecificsAsIdKey: function(){
            return this.$store.getters['specifics/templateSpecificsAsIdKey'];
        },
        
        // All halls
        halls: function(){
            return this.$store.getters['halls/all'];
        },
        
        // Data updater
        storeDataUpdater: function () {
            return this.$store.getters['updater/counter'];
        },
        
        // Filters
        filters: function () {
            return this.$store.getters['filters/all'];
        },
        countAppliedFilters: function () {
            return this.$store.getters['filters/countNotNullFilters'];
        },
        statusFilter: function () {
            return this.$store.getters['filters/status'];
        },
        hallFilter: function () {
            return this.$store.getters['filters/hall'];
        },
        templateFilter: function () {
            return this.$store.getters['filters/template'];
        },
        workerFilter: function () {
            return this.$store.getters['filters/worker'];
        },
        clientFilter: function () {
            return this.$store.getters['filters/client'];
        },
        durationFilter: function () {
            return this.$store.getters['filters/duration'];
        },
        isFiltersEmpty: function () {
            return this.$store.getters['filters/isAllEmpty'];
        },
        isFiltersAny: function () {
            return this.$store.getters['filters/isAny'];
        },
        
        // Data when request is to get free slots
        freeHallTitle: function () {
            if(this.isMovingEvent)
                return this.movingEvent.hall_without_user_scope.title;
            if(this.isNewEventMainFull)
                return this.newEventMain.hall.title;
            return null;
        },
        freeWorkerName: function () {
            if(this.isMovingEvent)
                return this.fullNameOfObj(this.movingEvent.worker_without_user_scope);
            if(this.isNewEventMainFull)
                return this.fullNameOfObj(this.newEventMain.worker);
            return null;
        },
        
        // Free slots get data params
        freeWithEvents: function () {
            return this.$store.getters['free_get_data_params/withEvents'];
        },
        freeBookingAnyTime: function () {
            return this.$store.getters['free_get_data_params/bookingAnyTime'];
        }
    },
    methods: {
        // Switch to day view
        goToDayView: function (dayItem) {
            let date = moment(dayItem.year + '-' + dayItem.month + '-' + dayItem.day, 'YYYY-MM-DD').toDate();
            this.$store.dispatch('dates/setDates', date);
            this.$store.commit('view/setView', 'day');
        },
        
        getRandomInt: function (max) {
            return Math.floor(Math.random() * max);
        },
        parseToInteger: function (int) {
            return parseInt(int);
        },
        isProp: function (prop) {
            return typeof prop !== 'undefined' && prop !== null;
        },
        getHumanReadableDate: function (dateObj) {
            if(!this.isProp(dateObj))
                return null;
            return moment(dateObj).format('D MMMM YYYY, ddd');
        },
        
        // Calendar actions
        goNext: function(){
            let params = {};
            
            this.$store.dispatch('dates/goNext');
            
            if(this.isNewEventMainFull && this.newEventShow){
                params.type = 'free';
            }else if(this.movingEvent !== null){
                params.type = 'free';
                params.exclude_ids = [this.movingEvent.id];
            }
            
            this.calendar.getData(params);
        },
        goPrevious: function(){
            let params = {};
            
            this.$store.dispatch('dates/goPrevious');
            
            if(this.isNewEventMainFull && this.newEventShow){
                params.type = 'free';
            }else if(this.movingEvent !== null){
                params.type = 'free';
                params.exclude_ids = [this.movingEvent.id];
            }
            
            this.calendar.getData(params);
        },
        goToday: function(){
            let params = {};
            
            this.$store.dispatch('dates/goToday');
            
            if(this.isNewEventMainFull && this.newEventShow){
                params.type = 'free';
            }else if(this.movingEvent !== null){
                params.type = 'free';
                params.exclude_ids = [this.movingEvent.id];
            }
            
            this.calendar.getData(params);
        },
        
        resetMovingEvent: function(){
            this.$store.dispatch('moving_event/reset');
            this.calendar.getData();
        },
        resetNewEvent: function(){
            this.$store.dispatch('new_event/reset');
            this.calendar.getData();
        },
        closeTooltipOfEvent: function (e) {
            if($(e.target).hasClass('tooltip-active')){
                $(e.target).tooltip("hide");
            }else{
                $(e.target).closest('.tooltip-active').tooltip("hide");
            }
        },
        // Filter methods
        isFilter: function (filter) {
            let listOfFilters = this.$store.getters['filters/listOfFilters'];
            return listOfFilters.includes(filter) && this.isProp(this.filters[filter]);
        },
        getFiltersSearchParams: function (urlSearchParams = null) {
            if(urlSearchParams === null)
                urlSearchParams = new URLSearchParams();
            
            if(this.isFilter('status') && Array.isArray(this.filters.status) && this.filters.status.length > 0)
                for(let idx in this.filters.status)
                    urlSearchParams.append("status[]", this.filters.status[idx]);
                        
            if(this.isFilter('hall') && Array.isArray(this.filters.hall) && this.filters.hall.length > 0)
                for(let idx in this.filters.hall)
                    urlSearchParams.append("hall[]", this.filters.hall[idx].id);
            
            if(this.isFilter('template') && Array.isArray(this.filters.template) && this.filters.template.length > 0)
                for(let idx in this.filters.template)
                    urlSearchParams.append("template[]", this.filters.template[idx].id);
            
            if(this.isFilter('worker') && Array.isArray(this.filters.worker) && this.filters.worker.length > 0)
                for(let idx in this.filters.worker)
                    urlSearchParams.append("worker[]", this.filters.worker[idx].id);
                    
            if(this.isFilter('client') && Array.isArray(this.filters.client) && this.filters.client.length > 0)
                for(let idx in this.filters.client)
                    urlSearchParams.append("client[]", this.filters.client[idx].id);
            
            if(this.isFilter('duration') && typeof this.filters.duration.start !== 'undefined' && !isNaN(this.filters.duration.start))
                urlSearchParams.append("duration_start", parseInt(this.filters.duration.start));
            
            if(this.isFilter('duration') && this.filters.duration.end !== 'undefined' && !isNaN(this.filters.duration.end))
                urlSearchParams.append("duration_end", parseInt(this.filters.duration.end));
            
            // return JSON.parse(JSON.stringify(urlSearchParams));
            return urlSearchParams;
        },
        
        isDurationRangeFull: function (range) {
            return range.start === this.durationRangeMinMax.start && range.end === this.durationRangeMinMax.end;
        },
        getNextEventFromEvents: function (events, event) {
            let nextEvent = null;
            let isNext = false;
            for(let i = 0; i < events.length; i++){
                if(isNext && events[i].approved == 1){
                    nextEvent = events[i];
                    break;
                }
                if(event.id == events[i].id){
                    isNext = true;
                }
            }
            
            return nextEvent;
        },
        setApp: function(){
            if(this.app === null)
                this.app = this.$root.$children[0];
        },
        urlSearchParams: function(dataType = 'all', as_string = false){
            let urlSearchParams;
            
            if(this.isNewEventMainFull){
                if(dataType == 'free'){
                    // alert(111);
                    urlSearchParams = this.$store.getters['new_event/urlSearchParamsMain'];
                }else{
                    urlSearchParams = this.$store.getters['new_event/urlSearchParamsMain'];
                }
            }else if(this.isMovingEvent){
                if(dataType == 'free'){
                    // alert(111);
                    urlSearchParams = this.$store.getters['moving_event/urlSearchParamsFree'];
                }else{
                    urlSearchParams = this.$store.getters['moving_event/urlSearchParams'];
                }
            }
            
            urlSearchParams = new URLSearchParams(urlSearchParams);
            
            if(as_string)
                return urlSearchParams.toString();
            return urlSearchParams;
        },
        getParentComponentByName: function (_thisComponent, componentName) {
            let component = null;
            if(_thisComponent.$options.name === componentName)
                return _thisComponent;
            let parent = _thisComponent.$parent;
            while (parent && !component) {
                if (parent.$options.name === componentName) {
                  component = parent
                }
                parent = parent.$parent
            }
            return component;
        },
        isAuth: function () {
            let componentApp = this.$root.$children[0];
            return componentApp.clientAuthorized;
            // return false;
            // let componentApp = this.getParentComponentByName(_thisComponent, 'app');
            // if(componentApp)
            //     return componentApp.isAuth;
            // return false;
        },
        // parseSecondsToHourMinuteString: function (seconds) {
        //     // console.log(milliseconds);
        //     let minutes = seconds/60;
        //     // console.log('seconds:' + seconds);
        //     // let minutes = seconds/60;
        //     // console.log('minutes:' + minutes);
        //     let hours = parseInt(minutes/60);
        //     // console.log('hours:' + hours);
        //     // console.log(hours + ':' + minutes);
        //     if(hours > 0){
        //         minutes = minutes%60;
        //         hours = this.formatTimeItemToTwoDigitString(hours);
        //         minutes = this.formatTimeItemToTwoDigitString(minutes);
        //     }else{
        //         hours = '00';
        //         minutes = this.formatTimeItemToTwoDigitString(minutes);
        //     }
        //     // console.log(hours + ':' + minutes);
        //     return hours + ':' + minutes;
        // },
        // formatTimeItemToTwoDigitString: function (timeItem) {
        //     let timeItemInt = parseInt(timeItem);
        //     if(timeItemInt < 10)
        //         return '0' + timeItemInt;
        //     return '' + timeItemInt;
        // },
        
        // Person helpers
        fullNameOfObj: function (obj){
            return calendarHelper.person.fullName(obj);
        },
        // showChildren: function () {
        //     // console.log(this.$root.$children[0].$options.name);
        //     console.log(this.$root.$children[0].$options.name);
        // }
    },
});

window.app = new Vue({
// const businessHours = new Vue({
    el: '#calendarBooking',
    store,
    render(h) {
        return h(App, {
            props:{
                userId: document.querySelector("#calendarBooking").dataset.userId
            }
        }) 
    }
});

// alert(111);
// console.log(document.querySelector("#calendarBooking").dataset.userId);
