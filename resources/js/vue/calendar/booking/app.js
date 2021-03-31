/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
const Helper = require("../../../ts/calendar_helper/app").Helper;
// const ViewEnums = require("../../../ts/calendar_helper/enums/View").Helper;

// window.viewEnums = new ViewEnums();
window.helper = new Helper();
// console.log(helper.my());
 
import App from './components/App.vue';

window.app = new Vue({
// const businessHours = new Vue({
    el: '#calendarBooking',
    render(h) {
        return h(App) 
    }
});

// alert(11);
