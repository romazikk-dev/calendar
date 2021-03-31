<template>
    <div class="filters">
        <div id="hallDropdown" class="dropdown show float-left pr-3">
            <span>Hall:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">All</a>
                <a v-for="itm in halls" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div>
        <div id="workerDropdown" class="dropdown show float-left pr-3">
            <span>Worker:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">All</a>
                <a v-for="itm in workers" class="dropdown-item" href="#">{{itm.first_name + ' ' + itm.last_name}}</a>
            </div>
        </div>
        <div id="templateDropdown" class="dropdown show float-left">
            <span>Template:</span>
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                All
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">All</a>
                <a v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div>
        <div id="viewDropdown" class="dropdown show float-right">
            <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{view}}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a v-for="itm in views" v-if="itm.toLowerCase() != view.toLowerCase()" class="dropdown-item" href="#">{{itm}}</a>
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
        },
        props: ['owner','halls','workers','templates','view','views'],
        data: function(){
            return {
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
            // openModal: function(){
            // 
            // },
        },
        components: {
            
        },
    }
</script>

<style scoped>
    .filters{
        padding: 4px 0px;
    }
</style>