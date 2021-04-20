<template>
    <div>
        <div v-if="showFilters" class="filters-select">
            <!-- <h4>Select place, worker, service</h4> -->
            <div class="show-filters-title">
                <h4>Please select all filters:</h4>
            </div>
            <div class="filter-select-item">
                
                <div>
                    <div id="viewDropdown" class="dropdown">
                        <span>View: </span>
                        <a class="btn btn-sm btn-info dropdown-toggle" href="#" id="viewDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{choosedItmView}}
                        </a>
        
                        <div class="dropdown-menu" aria-labelledby="viewDropdownButton">
                            <a @click.prevent="change('view', itm)" v-for="itm in views" v-if="itm.toLowerCase() != choosedItmView.toLowerCase()" class="dropdown-item" href="#">{{itm}}</a>
                        </div>
                    </div>
                </div>
                
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">Filters</div>
                    <div class="card-body">
                        
                        <div id="hallDropdown" class="dropdown">
                            <span>Hall: </span>
                            <a class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown">
                                {{choosedItmHall == null ? '---' : choosedItmHall.title}}
                            </a>
        
                            <div class="dropdown-menu">
                                <a @click.prevent="change('hall', itm)"
                                    v-for="itm in halls"
                                    v-if="choosedItmHall == null || (choosedItmHall != null && itm.id != choosedItmHall.id)"
                                    class="dropdown-item"
                                    href="#">{{itm.title}}</a>
                            </div>
                        </div>
                        
                        <div id="templateDropdown" class="dropdown">
                            <span>Template:</span>
                            <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{choosedItmTemplate == null ? '---' : (choosedItmTemplate.title)}}
                            </a>
            
                            <div class="dropdown-menu">
                                <a @click.prevent="change('template', itm)" v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
                            </div>
                        </div>
                        
                        <div id="workerDropdown" class="dropdown">
                            <span>Worker:</span>
                            <a :class="{disabled: (workers == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{choosedItmWorker == null ? '---' : fullName(choosedItmWorker)}}
                            </a>
            
                            <div class="dropdown-menu">
                                <a @click.prevent="change('worker', itm)" v-for="itm in workers" class="dropdown-item" href="#">{{fullName(itm)}}</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="apply pt-2">
                    <a @click.prevent="apply" class="btn btn-sm btn-primary w-100" href="#">
                        Apply
                    </a>
                </div>
                
                <div class="back pt-2">
                    <a @click.prevent="backToCalendar" class="btn btn-sm btn-link w-100" href="#">
                        Back
                    </a>
                </div>
            </div>
            
        </div>
        <div v-else class="filters">
            <div class="container-fluid">
                <div class="filter">
                    <a @click.prevent="showFilterChooser()" href="#" role="button" data-toggle="dropdown" class="btn btn-sm btn-secondary">
                    <!-- <a @click.prevent="showFilters = true && $emit('reset')" href="#" role="button" data-toggle="dropdown" class="btn btn-sm btn-secondary"> -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                    </a>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmHall != null ? cookieItmHall.title : ''}} <span class="badge badge-light">Hall</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmTemplate != null ? cookieItmTemplate.title : ''}} <span class="badge badge-light">Template</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmWorker != null ? fullName(cookieItmWorker) : ''}} <span class="badge badge-light">Worker</span>
                    </button>
                </div>
                
                <div class="float-right">
                    <client-info :client-info="clientInfo"
                        :all-bookings="allBookings"
                        :user-id="owner.id"></client-info>
                </div>
                
                <!-- <div class="filter float-right mr-0">
                    <div id="viewDropdownCalendar" class="dropdown">
                        <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="viewDropdownCalendarButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{choosedItmView}}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="viewDropdownCalendarButton">
                            <a v-for="itm in views" @click.prevent="changeView(itm)" v-if="itm.toLowerCase() != choosedItmView.toLowerCase()" class="dropdown-item" href="#">{{itm}}</a>
                        </div>
                    </div>
                </div> -->
                    
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</template>

<script>
    // require("../../../../ts/calendar_helper/enums/View").Helper;
    // import { View } from "../../../../ts/calendar_helper/enums/View";
    // console.log(View);
    // import MonthCalendar from "./MonthCalendar.vue";
    // import WeekCalendar from "./WeekCalendar.vue";
    // import DayCalendar from "./DayCalendar.vue";
    import ClientInfo from "./ClientInfo.vue";
    export default {
        name: 'filters',
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.showModal();
            // this.change('hall', null);
            // console.log(cookie.get('filters'));
            // console.log(JSON.parse(JSON.stringify(this.owner)));
            this.setFiltersFromCookie();
        },
        props: ['owner','halls','clientInfo','allBookings'],
        data: function(){
            return {
                choosedItmHall: null,
                choosedItmWorker: null,
                choosedItmTemplate: null,
                choosedItmView: 'month',
                cookieItmHall: null,
                cookieItmWorker: null,
                cookieItmTemplate: null,
                cookieItmView: null,
                views: ['month','week','day','list'],
                search: null,
                workers: null,
                templates: null,
                showFilters: true,
            };
        },
        methods: {
            changeView: function(view){
                // console.log(view);
                this.choosedItmView = view;
                this.putFiltersInCookie();
                this.composeLink();
                this.emitChange();
            },
            backToCalendar: function(){
                this.showFilters = false;
                this.$emit('showCalendar');
            },
            fullName: function(obj){
                return obj.first_name + ' ' + obj.last_name;
            },
            showFilterChooser: function(){
                this.showFilters = true;
                // console.log(JSON.parse(JSON.stringify([
                //     (this.choosedItmHall == null ? filters.hall : this.choosedItmHall),
                //     (this.choosedItmTemplate == null ? filters.template : this.choosedItmTemplate),
                //     (this.choosedItmWorker == null ? filters.worker : this.choosedItmWorker)
                // ])));
                // let oldItms = JSON.parse(JSON.stringify({
                //     choosedItmHall: this.cookieItmHall,
                //     choosedItmTemplate: this.cookieItmTemplate,
                //     choosedItmWorker: this.cookieItmWorker,
                // }));
                this.change('hall', (this.cookieItmHall == null ? filters.hall : this.cookieItmHall));
                this.change('template', (this.cookieItmTemplate == null ? filters.template : this.cookieItmTemplate));
                this.change('worker', (this.cookieItmWorker == null ? filters.worker : this.cookieItmWorker));
                // this.change('template', this.choosedItmTemplate == null ? filters.template : this.choosedItmTemplate);
                this.$emit('hideCalendar');
                // this.change('template', this.choosedItmTemplate);
                // this.$emit('reset');
            },
            putFiltersInCookie: function(){
                this.cookieItmHall = this.choosedItmHall;
                this.cookieItmWorker = this.choosedItmWorker;
                this.cookieItmTemplate = this.choosedItmTemplate;
                this.cookieItmView = this.choosedItmView;
                cookie.set('filters', {
                    hall: this.choosedItmHall.id,
                    worker: this.choosedItmWorker.id,
                    template: this.choosedItmTemplate.id,
                    view: this.choosedItmView,
                });
                // this.token = token;
                // let token = cookie.get('token');
                // if(token)
                //     this.token = token;
            },
            setFiltersFromCookie: function(){
                // console.log(filters);
                if(filters != null){
                    this.cookieItmHall = filters.hall;
                    this.cookieItmWorker = filters.worker;
                    this.cookieItmTemplate = filters.template;
                    this.cookieItmView = filters.view;
                    // console.log(111);
                    // if(this.choosedItmHall == null)
                        this.choosedItmHall = filters.hall;
                    // if(this.choosedItmWorker == null)
                        this.choosedItmWorker = filters.worker;
                    // if(this.choosedItmTemplate == null)
                        this.choosedItmTemplate = filters.template;
                    // if(this.choosedItmView == null)
                        this.choosedItmView = filters.view;
                    this.showFilters = false;
                    // console.log(this.search);
                    this.composeLink();
                    this.emitChange();
                }
            },
            filtersFilled: function(){
                // console.log(this.choosedItmHall);
                // console.log(this.choosedItmWorker);
                // console.log(this.choosedItmTemplate);
                return (this.choosedItmHall != null && this.choosedItmWorker != null && this.choosedItmTemplate != null);
            },
            apply: function(){
                if(!this.filtersFilled()){
                    alert('Please fill all fields');
                }else{
                    this.putFiltersInCookie();
                    this.composeLink();
                    this.emitChange();
                    this.$emit('showCalendar');
                }
            },
            emitChange: function(){
                this.showFilters = false;
                // console.log('emitChange');
                this.$emit('change', {
                    searchString: this.search,
                    searchObj: {
                        hall: this.choosedItmHall,
                        worker: this.choosedItmWorker,
                        template: this.choosedItmTemplate,
                        view: this.choosedItmView,
                    }
                });
            },
            change: function(type, itm){
                itm = JSON.parse(JSON.stringify(itm));
                // console.log(itm);
                switch(type) {
                    case 'hall':
                        this.choosedItmHall = null;
                        this.choosedItmTemplate = null;
                        this.choosedItmWorker = null;
                        axios.get(routes.calendar.booking.template.index + '?hall=' + itm.id)
                            .then((response) => {
                                let templates = [];
                                response.data.templates.forEach((item, i) => {
                                    // workers.push({
                                    //     id: item.id,
                                    //     name: item.first_name + ' ' + item.last_name,
                                    // });
                                    templates.push(item);
                                });
                                this.templates = templates;
                                this.choosedItmHall = itm;
                                // handle success
                                // console.log(JSON.parse(JSON.stringify(this.workers)));
                                // console.log(this.workers);
                                // console.log(response);
                            })
                            .catch(function (error) {
                            // handle error
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });
                        // this.choosedItmHall = (itm ?? null);
                        // this.choosedItmWorker = null;
                        // this.choosedItmTemplate = null;
                        break;
                    case 'template':
                        this.choosedItmTemplate = null;
                        this.choosedItmWorker = null;
                        axios.get(routes.calendar.booking.worker.index + '?template=' + itm.id)
                            .then((response) => {
                                let workers = [];
                                response.data.workers.forEach((item, i) => {
                                    // templates.push({
                                    //     id: item.id,
                                    //     title: item.title,
                                    // });
                                    workers.push(item);
                                });
                                this.workers = workers;
                                this.choosedItmTemplate = itm;
                                // handle success
                                // console.log(111);
                                // console.log(JSON.parse(JSON.stringify(this.templates)));
                                // console.log(this.workers);
                                // console.log(response);
                            })
                            .catch(function (error) {
                            // handle error
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });
                        break;
                    // case 'worker':
                    //     this.choosedItmWorker = null;
                    //     this.choosedItmTemplate = null;
                    //     axios.get(routes.calendar.booking.template.index + '?worker=' + itm.id)
                    //         .then((response) => {
                    //             let templates = [];
                    //             response.data.templates.forEach((item, i) => {
                    //                 // templates.push({
                    //                 //     id: item.id,
                    //                 //     title: item.title,
                    //                 // });
                    //                 templates.push(item);
                    //             });
                    //             this.templates = templates;
                    //             this.choosedItmWorker = itm;
                    //             // handle success
                    //             // console.log(111);
                    //             // console.log(JSON.parse(JSON.stringify(this.templates)));
                    //             // console.log(this.workers);
                    //             // console.log(response);
                    //         })
                    //         .catch(function (error) {
                    //         // handle error
                    //         console.log(error);
                    //         })
                    //         .then(function () {
                    //         // always executed
                    //         });
                    //     break;
                    case 'worker':
                        // this.choosedItmTemplate = null;
                        this.choosedItmWorker = itm;
                        // console.log(this.choosedItmTemplate);
                        break;
                    // case 'template':
                    //     // this.choosedItmTemplate = null;
                    //     this.choosedItmTemplate = itm;
                    //     // console.log(this.choosedItmTemplate);
                    //     break;
                    case 'view':
                        this.choosedItmView = (itm ?? null);
                        break;
                    default:
                    // code block
                }
                this.composeLink();
                
                // console.log(itm);
                // console.log(JSON.parse(JSON.stringify([
                //     this.choosedItmHall,
                //     this.choosedItmTemplate,
                //     this.choosedItmWorker
                // ])));
                
                // this.$emit('change', {
                //     searchString: this.search,
                //     searchObj: {
                //         hall: this.choosedItmHall,
                //         worker: this.choosedItmWorker,
                //         template: this.choosedItmTemplate,
                //         view: this.choosedItmView,
                //     }
                // });
                // console.log(type);
                // console.log(helper.parse(itm));
            },
            composeLink: function(){
                let search = '';
                if(this.choosedItmHall != null)
                    search += (search == '' ? '' : '&') + 'hall=' + this.choosedItmHall.id;
                if(this.choosedItmWorker != null)
                    search += (search == '' ? '' : '&') + 'worker=' + this.choosedItmWorker.id;
                if(this.choosedItmTemplate != null)
                    search += (search == '' ? '' : '&') + 'template=' + this.choosedItmTemplate.id;
                if(this.choosedItmView != null)
                    search += (search == '' ? '' : '&') + 'view=' + this.choosedItmView.toLowerCase();
                this.search = search;
                // console.log(search);
            }
        },
        components: {
            ClientInfo
        },
    }
</script>

<style scoped>
    .filters, .show-filters-title{
        padding: 12px 0px 4px;
        background-color: #f1f1f1;
        margin-bottom: 12px;
        -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
        -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
        box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
    }
    .show-filters-title{
        text-align: center;
        padding: 14px 0px 6px;
    }
    #viewDropdown .dropdown-toggle,
    #viewDropdownCalendar .dropdown-toggle{
        text-transform: capitalize;
    }
    .dropdown-item{
        text-transform: capitalize;
    }
    .filter-select-item{
        max-width: 300px;
        margin: auto;
    }
    .filters-select .dropdown>span{
        width: 300px;
        display: block;
    }
    .filters-select .dropdown-menu{
        width: 300px;
    }
    .filters-select .dropdown-toggle{
        width: 300px;
        text-align: left;
    }
    .filters-select .dropdown-toggle::after {
        display: inline-block;
        margin-left: .255em;
        margin-top: 8px;
        vertical-align: .255em;
        content: "";
        border-top: .3em solid;
        border-right: .3em solid transparent;
        border-bottom: 0;
        border-left: .3em solid transparent;
        float: right;
    }
    .dropdown{
        margin-bottom: 10px;
    }
    .dropdown:last-child(){
        
    }
    
    .card-body .dropdown-toggle{
        width: 100%;
        text-align: left;
    }
    .card-body .dropdown-menu{
        width: 100%;
    }
    .filters .filter{
        float: left;
        margin-right: 10px;
    }
    .filters .filter .btn.btn-filter{
        cursor: default;
    }
    .filters .filter .btn.btn-filter:hover,
    .filters .filter .btn.btn-filter:focus {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .filters .filter .btn.btn-filter:focus {
        outline: none;
        box-shadow: none;
    }
</style>