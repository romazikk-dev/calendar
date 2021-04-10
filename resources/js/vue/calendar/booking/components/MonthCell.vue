<template>
    <div>
        <ul>
            <li v-for="itm in free">
                <a @click.prevent="$emit('open-modal', itm)" href="#">
                    {{itm.from}} - {{itm.to}}
                </a>
            </li>
        </ul>
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
            // this.getDataForCalendar();
            // console.log(this.free);
        },
        props: ['free'],
        data: function(){
            return {
                // range: helper.range.range,
                // dates: null,
                // view: helper.range.view,
                // curreny_view_idx: helper.range.currenyViewIdx,
                // currentDate: {
                //     year: moment(helper.range.currentDate).format('YYYY'),
                //     month: moment(helper.range.currentDate).format('MM'),
                //     day: moment(helper.range.currentDate).format('DD'),
                // },
                // views: ['Month','Week','Day'],
                // owner: owner,
                // halls: halls,
                // workers: workers,
                // templates: templates
                // halls: 
            };
        },
        methods: {
            
        },
        components: {
            
        },
    }
</script>

<style scoped>
    ul{
        padding: 0px;
        margin: 0px;
        width: 100%;
        padding-left: 30px;
        padding-right: 4px;
        padding-top: 4px;
    }
    ul li{
        list-style: none;
        /* background-color: red; */
    }
    ul li a{
        background-color: red;
        display: block;
        width: 100%;
        background-color: rgba(144,238,144, 0.5);
        margin-bottom: 3px;
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: black;
        text-decoration: none;
        transition: background .2s ease;
    }
    ul li a:hover{
        background-color: rgba(144,238,144, 1);
    }
</style>