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

window.moment = require('moment');

Vue.mixin({
    data: function(){
        return {
            // weekdaysList: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
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
        formatDataDateForDateChooser: function(dataDate){
            return moment(dataDate).format('DD-MM-YYYY');
        },
    },
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
import App from './components/App.vue';

window.app = new Vue({
// const businessHours = new Vue({
    el: '#workersList',
    render(h) {
        return h(App) 
    }
});

// alert(11);
