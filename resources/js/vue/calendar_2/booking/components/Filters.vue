<template>
    <div>
        <div v-show="showFilters" class="filters-select">
            <!-- <h4>Select place, worker, service</h4> -->
            <div class="show-filters-title">
                <h4>Please select all filters:</h4>
            </div>
            <div class="filter-select-item">
                
                <div>
                    <div id="viewDropdown" class="dropdown">
                        <span>View: </span>
                        <a class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{pickedItmView}}
                        </a>
        
                        <div class="dropdown-menu">
                            <a @click.prevent="change('view', itm)" v-for="itm in views" v-if="itm.toLowerCase() != pickedItmView.toLowerCase()" class="dropdown-item" href="#">{{itm}}</a>
                            <!-- <a @click.prevent="change('view', itm)" v-for="itm in views" class="dropdown-item" href="#">{{itm}}</a> -->
                        </div>
                    </div>
                </div>
                
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">Filters</div>
                    <div class="card-body">
                        
                        <div id="hallDropdown" class="dropdown">
                            <span>Hall: </span>
                            <a class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown">
                                {{pickedItmHall == null ? '---' : pickedItmHall.title}}
                            </a>
        
                            <div class="dropdown-menu">
                                <!-- <a @click.prevent="change('hall', itm)"
                                    v-for="itm in halls"
                                    v-if="pickedItmHall == null || (pickedItmHall != null && itm.id != pickedItmHall.id)"
                                    class="dropdown-item"
                                    href="#">{{itm.title}}</a> -->
                                <template v-if="halls.length">
                                    <a @click.prevent="change('hall', itm)"
                                        v-for="itm in halls"
                                        class="dropdown-item"
                                        href="#">{{itm.title}}</a>
                                </template>
                                <template v-else>
                                    <div class="small pl-1 pr-1">
                                        No items to choose ...
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <template-picker :templates="templates"
                            :picked-hall="pickedItmHall"
                            :specifics="templateSpecifics"
                            :specifics-as-id-key="templateSpecificsAsIdKey"
                            v-if="templateSpecifics"
                            @change="change('template', $event)" />
                        <div v-else id="templateDropdown" class="dropdown">
                            <span>Template:</span>
                            <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{pickedItmTemplate == null ? '---' : (pickedItmTemplate.title)}}
                            </a>
            
                            <div class="dropdown-menu">
                                <a @click.prevent="change('template', itm)" v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
                            </div>
                        </div>
                        
                        <div id="workerDropdown" class="dropdown">
                            <span>Worker:</span>
                            <a :class="{disabled: (workers == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{pickedItmWorker == null ? '---' : fullName(pickedItmWorker)}}
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
        <div  v-show="!showFilters" class="filters">
            <div class="container-fluid">
                <div class="filter">
                    <a @click.prevent="showFiltersPicker()" href="#" role="button" class="btn btn-sm btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                    </a>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmHallTitle}} <span class="badge badge-light">Hall</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmTemplateTitle}} <span class="badge badge-light">Template</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{cookieItmWorkerName}} <span class="badge badge-light">Worker</span>
                    </button>
                </div>
                
                <div class="float-right">
                    <client-info :client-info="clientInfo"
                        :all-bookings="allBookings"
                        :user-id="owner.id"></client-info>
                </div>
                    
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import ClientInfo from "./ClientInfo.vue";
    import ExtensiveTemplateFilterPicker from "./template/ExtensiveTemplateFilterPicker.vue";
    export default {
        name: 'filters',
        mounted() {
            console.log(JSON.parse(JSON.stringify(4444)));
            console.log(JSON.parse(JSON.stringify(this.templateSpecifics)));
            console.log(JSON.parse(JSON.stringify(this.halls)));
            
            // this.setFiltersFromCookie();
            // this.setFilters();
        },
        props: ['owner','halls','clientInfo','allBookings','cookieFilters','templateSpecifics','templateSpecificsAsIdKey'],
        data: function(){
            return {
                pickedItmHall: null,
                pickedItmWorker: null,
                pickedItmTemplate: null,
                pickedItmView: 'month',
                
                // cookieItmHall: null,
                // cookieItmWorker: null,
                // cookieItmTemplate: null,
                // cookieItmView: null,
                
                views: ['month','week','day','list'],
                search: null,
                
                workers: null,
                templates: null,
                
                showFilters: true,
            };
        },
        computed: {
            // isTemplateSpecifics: function(){
            //     return this.templateSpecifics !== null;
            // },
            isCookieFiltersEmpty: function(){
                if(typeof this.cookieFilters === 'undefined' || this.cookieFilters === null || 
                this.cookieFilters.hall === null || this.cookieFilters.template === null ||
                this.cookieFilters.worker === null)
                    return true;
                return false;
            },
            cookieItmHallTitle: function(){
                return !this.isCookieFiltersEmpty ? this.cookieFilters.hall.title : '';
            },
            cookieItmTemplateTitle: function(){
                return !this.isCookieFiltersEmpty ? this.cookieFilters.template.title : '';
            },
            cookieItmWorkerName: function(){
                return !this.isCookieFiltersEmpty ?
                    this.cookieFilters.worker.first_name + this.cookieFilters.worker.last_name : '';
            },
        },
        methods: {
            backToCalendar: function(){
                this.showFilters = false;
                this.$emit('showCalendar');
            },
            fullName: function(obj){
                return obj.first_name + ' ' + obj.last_name;
            },
            showFiltersPicker: function(){
                this.showFilters = true;
                
                if(this.cookieFilters !== null && this.cookieFilters.hall !== null){
                    this.change('hall', this.cookieFilters.hall);
                    if(this.cookieFilters.template !== null){
                        this.change('template', this.cookieFilters.template);
                        if(this.cookieFilters.worker !== null){
                            this.change('worker', this.cookieFilters.worker);
                        }
                    }
                }
                
                // this.change('hall', (this.cookieItmHall == null ? filters.hall : this.cookieItmHall));
                // this.change('hall', (this.cookieItmHall == null ? filters.hall : this.cookieItmHall));
                // this.change('template', (this.cookieItmTemplate == null ? filters.template : this.cookieItmTemplate));
                // this.change('worker', (this.cookieItmWorker == null ? filters.worker : this.cookieItmWorker));
                
                this.$emit('hideCalendar');
            },
            putFiltersInCookie: function(){
                this.cookieItmHall = this.pickedItmHall;
                this.cookieItmWorker = this.pickedItmWorker;
                this.cookieItmTemplate = this.pickedItmTemplate;
                this.cookieItmView = this.pickedItmView;
                
                cookie.set('filters', {
                    hall: this.pickedItmHall.id,
                    worker: this.pickedItmWorker.id,
                    template: this.pickedItmTemplate.id,
                    view: this.pickedItmView,
                });
            },
            // filtersHasRightData: function(){
            //     if(filters == null)
            //         return false;
            // 
            //     if(filters.hall == null)
            //         return false;
            // },
            setFilters: function(){
                // console.log(filters);
                if(this.filters === null)
                    return;
                    
                this.cookieItmHall = this.filters.hall;
                this.cookieItmWorker = this.filters.worker;
                this.cookieItmTemplate = this.filters.template;
                this.cookieItmView = this.filters.view;
                
                this.pickedItmHall = this.filters.hall;
                this.pickedItmWorker = this.filters.worker;
                this.pickedItmTemplate = this.filters.template;
                this.pickedItmView = this.filters.view;
                
                this.showFilters = false;
                
                this.composeLink();
                this.emitChange();
            },
            // setFiltersFromCookie: function(){
            //     // console.log(filters);
            //     if(filters != null){
            //         this.cookieItmHall = filters.hall;
            //         this.cookieItmWorker = filters.worker;
            //         this.cookieItmTemplate = filters.template;
            //         this.cookieItmView = filters.view;
            // 
            //         this.pickedItmHall = filters.hall;
            //         this.pickedItmWorker = filters.worker;
            //         this.pickedItmTemplate = filters.template;
            //         this.pickedItmView = filters.view;
            // 
            //         this.showFilters = false;
            // 
            //         this.composeLink();
            //         this.emitChange();
            //     }
            // },
            filtersFilled: function(){
                // console.log(this.pickedItmHall);
                return (this.pickedItmHall != null && this.pickedItmWorker != null && this.pickedItmTemplate != null);
            },
            apply: function(){
                // return;
                console.log(JSON.parse(JSON.stringify(this.search)));
                
                if(!this.filtersFilled()){
                    alert('Please fill all fields');
                }else{
                    this.putFiltersInCookie();
                    // console.log(this.search);
                    this.composeLink();
                    console.log(this.search);
                    this.emitChange();
                    // setTimeout(() => {
                     	this.$emit('showCalendar');
                    // }, 2000);
                }
            },
            emitChange: function(){
                this.showFilters = false;
                // console.log('emitChange');
                this.$emit('change', {
                    searchString: this.search,
                    searchObj: {
                        hall: this.pickedItmHall,
                        worker: this.pickedItmWorker,
                        template: this.pickedItmTemplate,
                        view: this.pickedItmView,
                    }
                });
            },
            resetPickedItems: function(items = null){
                if(items !== null && !Array.isArray(items))
                    return;
                    
                console.log('resetPickedItems');
                    
                if(items === null || items.includes("hall"))
                    this.pickedItmHall = null;
                    
                if(items === null || items.includes("template"))
                    this.pickedItmTemplate = null;
                    
                if(items === null || items.includes("worker"))
                    this.pickedItmWorker = null;
            },
            change: function(type, itm){
                itm = JSON.parse(JSON.stringify(itm));
                // console.log(itm);
                switch(type) {
                    case 'hall':
                        this.resetPickedItems();
                        axios.get(routes.calendar.booking.template.index + '?hall=' + itm.id)
                            .then((response) => {
                                let templates = [];
                                response.data.templates.forEach((item, i) => {
                                    templates.push(item);
                                });
                                this.templates = templates;
                                this.pickedItmHall = itm;
                                // console.log(JSON.parse(JSON.stringify(this.workers)));
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                            })
                            .then(function () {
                                // always executed
                            });
                        break;
                    case 'template':
                        this.resetPickedItems(['template','worker']);
                        // lo(222);
                        // console.log('5555555');
                        // console.log(this.pickedItmTemplate);
                        if(itm === null)
                            return;
                        // alert(111);
                        axios.get(routes.calendar.booking.worker.index + '?template=' + itm.id)
                            .then((response) => {
                                let workers = [];
                                response.data.workers.forEach((item, i) => {
                                    workers.push(item);
                                });
                                this.workers = workers;
                                this.pickedItmTemplate = itm;
                                // console.log(JSON.parse(JSON.stringify(this.templates)));
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                            })
                            .then(function () {
                                // always executed
                            });
                        break;
                    case 'worker':
                        this.pickedItmWorker = (itm ?? null);
                        break;
                    case 'view':
                        this.pickedItmView = (itm ?? null);
                        break;
                    default:
                    // code block
                }
                // this.composeLink();
            },
            composeLink: function(){
                let search = '';
                if(this.pickedItmHall != null)
                    search += (search == '' ? '' : '&') + 'hall=' + this.pickedItmHall.id;
                if(this.pickedItmWorker != null)
                    search += (search == '' ? '' : '&') + 'worker=' + this.pickedItmWorker.id;
                if(this.pickedItmTemplate != null)
                    search += (search == '' ? '' : '&') + 'template=' + this.pickedItmTemplate.id;
                if(this.pickedItmView != null)
                    search += (search == '' ? '' : '&') + 'view=' + this.pickedItmView.toLowerCase();
                this.search = search;
                // console.log(search);
            }
        },
        components: {
            ClientInfo,
            TemplatePicker: ExtensiveTemplateFilterPicker,
        },
        watch: {
            pickedItmHall: function(val){
                if(val === null){
                    this.templates = null;
                    this.workers = null;
                }
                // console.log(JSON.parse(JSON.stringify(9999999)));
                // console.log(JSON.parse(JSON.stringify(val)));
            },
            pickedItmTemplate: function(val){
                if(val === null)
                    this.workers = null;
                // console.log(JSON.parse(JSON.stringify(9999999)));
                // console.log(JSON.parse(JSON.stringify(val)));
            },
        },
    }
</script>

<style lang="scss" scoped>
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
        padding: 0px 10px;
        max-width: 600px;
        margin: auto;
    }
    .filters-select .dropdown>span{
        width: 100%!important;
        display: block;
    }
    .filters-select .dropdown-menu{
        // width: 300px;
        width: 100%!important;
    }
    .filters-select .dropdown-toggle{
        // width: 100%!important;
        // max-width: 300px;
        // width: 500px;
        width: 100%!important;
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