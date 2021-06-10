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
    data: function(){
        return {
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
        };
    },
    methods: {
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
        }
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
