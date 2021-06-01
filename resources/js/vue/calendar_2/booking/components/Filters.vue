<template>
    <div>
        <!-- <loader v-show="showFilters" ref="loader" /> -->
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
                            <span>{{customTitle('hall')}}: </span>
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
                            :picked-template="pickedItmTemplate"
                            :specifics="templateSpecifics"
                            :specifics-as-id-key="templateSpecificsAsIdKey"
                            :picked-template-ids-trace="pickedTemplateIdsTrace"
                            v-if="templateSpecifics"
                            @change="change('template', $event)" />
                        <div v-else id="templateDropdown" class="dropdown">
                            <span>{{customTitle('template')}}:</span>
                            <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{pickedItmTemplate == null ? '---' : (pickedItmTemplate.title)}}
                            </a>
            
                            <div class="dropdown-menu">
                                <a @click.prevent="change('template', itm)" v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
                            </div>
                        </div>
                        
                        <div id="workerDropdown" class="dropdown">
                            <span>{{customTitle('worker')}}:</span>
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
        <div v-show="!showFilters" class="filters">
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
                        {{itmHallTitle}} <span class="badge badge-light">{{customTitle('hall')}}</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{itmTemplateTitle}} <span class="badge badge-light">{{customTitle('template')}}</span>
                    </button>
                </div>
                <div class="filter">
                    <button type="button" class="btn-filter btn btn-sm btn-info">
                        {{itmWorkerName}} <span class="badge badge-light">{{customTitle('worker')}}</span>
                    </button>
                </div>
                
                <div class="float-right">
                    <client-info></client-info>
                </div>
                    
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import ClientInfo from "./ClientInfo.vue";
    import Loader from "./Loader.vue";
    import ExtensiveTemplateFilterPicker from "./template/ExtensiveTemplateFilterPicker.vue";
    export default {
        name: 'filters',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
            
            if(!this.isCookieItmsEmpty){
                this.fillFilters();
                this.emitChange();
            }
        },
        // props: ['templateSpecifics','templateSpecificsAsIdKey'],
        data: function(){
            return {
                //Using to indicate current picked items
                pickedItmHall: null,
                pickedItmWorker: null,
                pickedItmTemplate: null,
                pickedItmView: 'month',
                
                //Currently picked template`s ids trace
                pickedTemplateIdsTrace: null,
                //Count how much were picked template when picked form is shown
                // pickTemplateTimesCount: 0,
                
                // views: ['month','week','day','list'],
                // search: null,
                
                //Workers fills for dropdown
                workers: null,
                //Workers fills for dropdown
                templates: null,
                
                //Switch between filters picker and top info bar which filters are applied
                showFilters: true,
            };
        },
        computed: {
            customTitle: function(){
                return (name) => {
                    return this.$store.getters['custom_titles/title'](name);
                }
            },
            templateSpecifics: function(){
                return this.$store.getters['specifics/templateSpecifics'];
            },
            templateSpecificsAsIdKey: function(){
                return this.$store.getters['specifics/templateSpecificsAsIdKey'];
            },
            halls: function(){
                return this.$store.getters['halls/all'];
            },
            views: function(){
                return this.$store.getters['filters/views'];
            },
            cookieItmHall: function(){
                return this.$store.getters['filters/hall'];
            },
            cookieItmTemplate: function(){
                return this.$store.getters['filters/template'];
            },
            cookieItmWorker: function(){
                return this.$store.getters['filters/worker'];
            },
            cookieItmView: function(){
                return this.$store.getters['filters/view'];
            },
            isPickedItmsFilled: function(){
                // console.log(this.pickedItmHall);
                return (this.pickedItmHall != null && this.pickedItmWorker != null && this.pickedItmTemplate != null);
            },
            // isCookieViewEmpty: function(){
            //     if(typeof this.cookieFilters === 'undefined' || this.cookieFilters === null || 
            //     typeof this.cookieFilters.view === 'undefined' || typeof this.cookieFilters.view === null)
            //         return true;
            //     return false;
            // },
            isCookieItmsEmpty: function(){
                return this.$store.getters['filters/isEmpty'];
            },
            itmHallTitle: function(){
                return this.cookieItmHall !== null ? this.cookieItmHall.title : null;
            },
            itmTemplateTitle: function(){
                return this.cookieItmTemplate !== null ? this.cookieItmTemplate.title : null;
            },
            itmWorkerName: function(){
                if(this.cookieItmWorker === null)
                    return '';
                
                let fullName = toCapitalCase(this.cookieItmWorker.first_name.toLowerCase().trim());
                if(typeof this.cookieItmWorker.last_name !== 'undefined' && this.cookieItmWorker.last_name !== null &&
                typeof this.cookieItmWorker.last_name === 'string')
                    fullName += toCapitalCase(this.cookieItmWorker.last_name.toLowerCase().trim());
                    
                return fullName;
                    
                function toCapitalCase(string){
                    return string.charAt(0).toUpperCase() + string.slice(1);
                };
            },
        },
        methods: {
            changeView: function(view){
                // console.log(view);
                // alert(1111);
                // this.choosedItmView = view;
                this.cookieItmView = view;
                this.putFiltersInCookie();
                // this.composeSearch();
                this.emitChange();
            },
            setPickedTemplateIdsTrace: function(){
                let itmTemplate = this.cookieItmTemplate;
                if(itmTemplate === null ||
                typeof itmTemplate.specific === 'undefined' || itmTemplate.specific === null)
                    return null;
                
                if(typeof itmTemplate.specific.ids_trace === 'undefined' || itmTemplate.specific.ids_trace === null)
                    return [itmTemplate.specific.id];
                
                let idsTraceString = JSON.parse(JSON.stringify(itmTemplate.specific.ids_trace));
                let idsTrace = idsTraceString.split(',').map((val) => parseInt(val));
                idsTrace.push(itmTemplate.specific.id);
                idsTrace.push(itmTemplate.id);
                
                // this.pickedTemplateIdsTrace = Object.freeze(idsTrace);
                this.pickedTemplateIdsTrace = idsTrace;
            },
            backToCalendar: function(){
                this.showFilters = false;
                this.$emit('showCalendar');
            },
            fullName: function(obj){
                return obj.first_name + ' ' + obj.last_name;
            },
            fillFilters: function(){
                if(this.cookieItmHall !== null){
                    this.change('hall', this.cookieItmHall);
                    if(this.cookieItmTemplate !== null){
                        this.change('template', this.cookieItmTemplate);
                        this.setPickedTemplateIdsTrace();
                        if(this.cookieItmWorker !== null){
                            this.change('worker', this.cookieItmWorker);
                        }
                    }
                }
            },
            showFiltersPicker: function(){
                this.showFilters = true;
                
                // if(this.cookieItmHall !== null && this.pickedItmHall === null){
                //     this.change('hall', this.cookieItmHall);
                //     if(this.cookieItmTemplate !== null && this.pickedItmTemplate === null){
                //         this.change('template', this.cookieItmTemplate);
                //         this.setPickedTemplateIdsTrace();
                //         if(this.cookieItmWorker !== null && this.pickedItmWorker === null){
                //             this.change('worker', this.cookieItmWorker);
                //         }
                //     }
                // }
                
                if(this.cookieItmView !== null)
                    this.pickedItmView = this.cookieItmView;
                
                // this.$refs.loader.fadeOut();
                
                this.$emit('hideCalendar');
            },
            putFiltersInCookie: function(){
                cookie.set('filters', {
                    hall: this.cookieItmHall.id,
                    worker: this.cookieItmWorker.id,
                    template: this.cookieItmTemplate.id,
                    view: this.cookieItmView,
                });
            },
            apply: function(){
                if(!this.isPickedItmsFilled){
                    alert('Please fill all fields');
                }else{
                    this.$store.commit('filters/changeFilters', {
                        hall: this.pickedItmHall,
                        template: this.pickedItmTemplate,
                        worker: this.pickedItmWorker,
                        view: this.pickedItmView,
                    });
                    
                    this.emitChange();
                }
            },
            emitChange: function(){
                this.showFilters = false;
                this.$emit('change');
                this.$emit('showCalendar');
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
                }
            },
        },
        components: {
            ClientInfo,
            TemplatePicker: ExtensiveTemplateFilterPicker,
            Loader,
        },
        watch: {
            showFilters: function(val){
                if(val === false){
                    // alert(this.isCookieItmsEmpty);
                    if(!this.isCookieItmsEmpty && !this.isPickedItmsFilled){
                        this.fillFilters();
                    }
                }
            },
            // cookieFilters: function(val){
            //     // console.log(JSON.parse(JSON.stringify(7272727272)));
            //     // console.log(JSON.parse(JSON.stringify(val)));
            // },
            pickedItmHall: function(val){
                if(val === null){
                    this.templates = null;
                    this.workers = null;
                }
                // console.log(JSON.parse(JSON.stringify(9999999)));
                // console.log(JSON.parse(JSON.stringify(val)));
            },
            // cookieItmTemplate: function(val){
            //     // console.log(JSON.parse(JSON.stringify(8181818181818)));
            // 
            //     if(val === null){
            //         this.workers = null;
            //     }else{
            //         // this.setPickedTemplateIdsTrace();
            //     }
            // 
            //     // return;
            // 
            //     if(val !== null && this.pickTemplateTimesCount == 0 && !this.isCookieFiltersEmpty){
            //         this.setPickedTemplateIdsTrace();
            //         console.log(JSON.parse(JSON.stringify(7373737373737)));
            //     }else{
            //         this.pickedTemplateIdsTrace = null;
            //     }
            // 
            //     console.log(JSON.parse(JSON.stringify(8181818181818)));
            //     console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
            //     console.log(JSON.parse(JSON.stringify(val)));
            // 
            //     if(val !== null && this.showFilters === true)
            //         this.pickTemplateTimesCount++;
            //     // console.log(JSON.parse(JSON.stringify(9999999)));
            //     // console.log(JSON.parse(JSON.stringify(val)));
            // },
            // showFilters: function(val){
            //     if(val === false){
            //         this.pickTemplateTimesCount = 0;
            //     }else{
            //         // let loader = this.$refs['loader'];
            //         // console.log(JSON.parse(JSON.stringify(loader)));
            //         console.log(JSON.parse(JSON.stringify('loader')));
            //         console.log(JSON.parse(JSON.stringify(this.$refs)));
            //     }
            // },
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