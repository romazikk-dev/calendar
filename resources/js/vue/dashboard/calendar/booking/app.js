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

window.moment = require('moment');
window.cookie = require('js-cookie');

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
        };
    },
    computed: {
        app: function(){
            return this.$root.$children[0];
        },
        calendar: function(){
            if(this.view.toLowerCase() === 'month' && typeof this.app.$refs.month_calendar !== 'undefined')
                return this.app.$refs.month_calendar;
            return null;
        },
        navbar: function(){
            if(this.calendar !== null && typeof this.calendar.$refs.navbar !== 'undefined')
                return this.calendar.$refs.navbar;
            return null;
        },
        showMovingEvent: function () {
            return this.$store.getters['moving_event/show'];
        },
        view: function () {
            // return 'month';
            return this.$store.getters['filters/view'];
        },
        canGoToPrevious: function(){
            if(this.view.toLowerCase() == 'month'){
                return this.$store.getters['dates/canGoToPreviousMonth'];
            }
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
        // Current filters
        currentEventFilter: function(){
            if(this.movingEvent !== null)
                return this.movingEvent;
            return null;
        },
        currentEventFilterDuration: function(){
            if(typeof this.currentEventFilter !== 'undefined' && this.currentEventFilter !== null &&
            typeof this.currentEventFilter.right_duration !== 'undefined' && this.currentEventFilter.right_duration !== null)
                return this.currentEventFilter.right_duration;
            return null;
        },
        currentHallFilter: function(){
            if(this.movingEvent !== null){
                if(this.movingEventIsPickedFull)
                    return this.movingEventPicked.hall;
                return this.movingEvent.hall_without_user_scope;
            }
            return null;
        },
        currentWorkerFilter: function(){
            if(this.movingEvent !== null){
                if(this.movingEventIsPickedFull)
                    return this.movingEventPicked.worker;
                return this.movingEvent.worker_without_user_scope;
            }
            return null;
        },
        currentTemplateFilter: function(){
            if(this.movingEvent !== null){
                if(this.movingEventIsPickedFull)
                    return this.movingEventPicked.template;
                return this.movingEvent.template_without_user_scope;
            }
            return null;
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
        // isMovingEventPickedItemsChanged: function(){
        //     return this.$store.getters['moving_event/isPickedItemsChanged'];
        // },
        movingEventDuration: function(){
            if(typeof this.currentEventFilter !== 'undefined' && this.currentEventFilter !== null &&
            typeof this.currentTemplateFilter !== 'undefined' && this.currentTemplateFilter !== null){
                if(typeof this.currentEventFilter.custom_duration !== 'undefined' && this.currentEventFilter.custom_duration !== null)
                    return this.currentEventFilter.custom_duration;
                return this.currentTemplateFilter.duration;
            }
            return null;
        },
        movingEvent: function(){
            return this.$store.getters['moving_event/event'];
        },
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
        
        // Store filters
        storeFilters: function () {
            return this.$store.getters['filters/all'];
        },
    },
    methods: {
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
        urlSearchParams: function(as_string = false){
            let urlSearchParams;
            
            if(this.isNewEventMainFull){
                urlSearchParams = this.$store.getters['new_event/urlSearchParamsMain'];
            }else if(this.isMovingEvent){
                urlSearchParams = this.$store.getters['moving_event/urlSearchParams'];
            }
            
            urlSearchParams = new URLSearchParams(urlSearchParams);
            
            if(as_string)
                return urlSearchParams.toString();
            return urlSearchParams;
        },
        // urlSearchParams: function(as_string = false){
        //     let urlSearchParams = this.isMovingEvent ? this.$store.getters['moving_event/urlSearchParams'] :
        //         this.$store.getters['filters/urlSearchParams'];
        // 
        //     urlSearchParams = new URLSearchParams(urlSearchParams);
        // 
        //     if(as_string)
        //         return urlSearchParams.toString();
        //     return urlSearchParams;
        // },
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
        parseSecondsToHourMinuteString: function (seconds) {
            // console.log(milliseconds);
            let minutes = seconds/60;
            // console.log('seconds:' + seconds);
            // let minutes = seconds/60;
            // console.log('minutes:' + minutes);
            let hours = parseInt(minutes/60);
            // console.log('hours:' + hours);
            // console.log(hours + ':' + minutes);
            if(hours > 0){
                minutes = minutes%60;
                hours = this.formatTimeItemToTwoDigitString(hours);
                minutes = this.formatTimeItemToTwoDigitString(minutes);
            }else{
                hours = '00';
                minutes = this.formatTimeItemToTwoDigitString(minutes);
            }
            // console.log(hours + ':' + minutes);
            return hours + ':' + minutes;
        },
        formatTimeItemToTwoDigitString: function (timeItem) {
            let timeItemInt = parseInt(timeItem);
            if(timeItemInt < 10)
                return '0' + timeItemInt;
            return '' + timeItemInt;
        },
        
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
