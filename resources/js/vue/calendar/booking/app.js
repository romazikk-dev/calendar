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
 
// const Helper = require("../../../ts/calendar_helper/app").Helper;
// const ViewEnums = require("../../../ts/calendar_helper/enums/View").Helper;

window.moment = require('moment');
window.cookie = require('js-cookie');

// window.viewEnums = new ViewEnums();
// window.helper = new Helper();
// console.log(helper.my());
 
import App from './components/App.vue';

Vue.mixin({
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
            return componentApp.authorized;
            // return false;
            // let componentApp = this.getParentComponentByName(_thisComponent, 'app');
            // if(componentApp)
            //     return componentApp.isAuth;
            // return false;
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
