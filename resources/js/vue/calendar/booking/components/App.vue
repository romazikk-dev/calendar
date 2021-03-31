<template>
    <div>
        <filters :owner="owner"
                 :view="view"
                 :views="views"
                 :halls="halls"
                 :workers="workers"
                 :templates="templates"></filters>
        <month-calendar v-if="view == 'MONTH'" :dates="dates" :range="range" :view="view"></month-calendar>
        <week-calendar v-if="view == 'WEEK'" :dates="dates" :range="range" :view="view"></week-calendar>
        <day-calendar v-if="view == 'DAY'" :dates="dates" :range="range" :view="view"></day-calendar>
    </div>
</template>

<script>
    // require("../../../../ts/calendar_helper/enums/View").Helper;
    // import { View } from "../../../../ts/calendar_helper/enums/View";
    // console.log(View);
    import MonthCalendar from "./MonthCalendar.vue";
    import WeekCalendar from "./WeekCalendar.vue";
    import DayCalendar from "./DayCalendar.vue";
    import Filters from "./Filters.vue";
    export default {
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                range: helper.range.range,
                dates: helper.range.dates,
                view: helper.range.view,
                views: ['Month','Week','Day'],
                owner: owner,
                halls: halls,
                workers: workers,
                templates: templates
                // halls: 
            };
        },
        methods: {
            // openModal: function(){
            // 
            // },
        },
        components: {
            MonthCalendar,
            WeekCalendar,
            DayCalendar,
            Filters
        },
    }
</script>

<style scoped>
    
</style>