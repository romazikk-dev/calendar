<template>
    <div class="filters">
        <div id="hallDropdown" class="dropdown show float-left pr-3">
            <span>Hall:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a @click.prevent="change('hall', null)" class="dropdown-item" href="#">All</a>
                <a @click.prevent="change('hall', itm)" v-for="itm in halls" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div>
        <div id="workerDropdown" class="dropdown show float-left pr-3">
            <span>Worker:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a @click.prevent="change('worker', null)" class="dropdown-item" href="#">All</a>
                <a @click.prevent="change('worker', itm)" v-for="itm in workers" class="dropdown-item" href="#">{{itm.first_name + ' ' + itm.last_name}}</a>
            </div>
        </div>
        <div id="templateDropdown" class="dropdown show float-left">
            <span>Template:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a @click.prevent="change('template', null)" class="dropdown-item" href="#">All</a>
                <a @click.prevent="change('template', itm)" v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div>
        <div id="viewDropdown" class="dropdown show float-right">
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{choosedItmView}}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a @click.prevent="change('view', itm)" v-for="itm in views" v-if="itm.toLowerCase() != choosedItmView.toLowerCase()" class="dropdown-item" href="#">{{itm}}</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</template>

<script>
    // require("../../../../ts/calendar_helper/enums/View").Helper;
    // import { View } from "../../../../ts/calendar_helper/enums/View";
    // console.log(View);
    // import MonthCalendar from "./MonthCalendar.vue";
    // import WeekCalendar from "./WeekCalendar.vue";
    // import DayCalendar from "./DayCalendar.vue";
    // import Filters from "./Filters.vue";
    export default {
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            this.change('hall', null);
        },
        props: ['owner','halls','workers','templates'],
        data: function(){
            return {
                choosedItmHall: null,
                choosedItmWorker: null,
                choosedItmTemplate: null,
                choosedItmView: 'month',
                views: ['month','week','day'],
                search: null,
                // range: helper.range.range,
                // dates: helper.range.dates,
                // view: helper.range.view,
                // views: ['Month','Week','Day'],
                // owner: owner,
                // halls: halls,
                // workers: workers,
                // templates: templates
                // halls: 
            };
        },
        methods: {
            change: function(type, itm){
                switch(type) {
                    case 'hall':
                        this.choosedItmHall = (itm ?? null);
                        this.choosedItmWorker = null;
                        this.choosedItmTemplate = null;
                        break;
                    case 'worker':
                        this.choosedItmWorker = (itm ?? null);
                        this.choosedItmTemplate = null;
                        break;
                    case 'template':
                        this.choosedItmTemplate = (itm ?? null);
                        break;
                    case 'view':
                        this.choosedItmView = (itm ?? null);
                        break;
                    default:
                    // code block
                }
                this.composeLink();
                this.$emit('change', {
                    searchString: this.search,
                    searchObj: {
                        hall: this.choosedItmHall,
                        worker: this.choosedItmWorker,
                        template: this.choosedItmTemplate,
                        view: this.choosedItmView,
                    }
                });
                // console.log(type);
                // console.log(helper.parse(itm));
            },
            composeLink: function(){
                let search = '';
                if(this.choosedItmHall != null)
                    search += 'hall=' + this.choosedItmHall.id;
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
            
        },
    }
</script>

<style scoped>
    .filters{
        padding: 4px 0px;
    }
    .dropdown-toggle, .dropdown-item{
        text-transform: capitalize;
    }
</style>