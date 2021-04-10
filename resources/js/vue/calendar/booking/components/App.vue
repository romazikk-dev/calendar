<template>
    <div>
        <filters :owner="owner"
                 :halls="halls"
                 :workers="workers"
                 :templates="templates"
                 @change="filterChange($event)"
                 @reset="reset"
                 @hideCalendar="showCalendar = false"
                 @showCalendar="showCalendar = true"></filters>
        <div v-show="showCalendar" class="container-fluid">
            <month-calendar v-if="monthView"
                :user-id="userId"
                :search="search"></month-calendar>
            <week-calendar v-if="weekView"
                :user-id="userId"
                :search="search"></week-calendar>
        </div>
        <!-- <week-calendar v-if="view == 'WEEK'" :dates="dates" :range="range" :view="view"></week-calendar>
        <day-calendar v-if="view == 'DAY'" :dates="dates" :range="range" :view="view"></day-calendar> -->
    </div>
</template>

<script>
    // var date = new Date();
    // date.setDate(date.getDate() - 2);

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
            // this.getDataForCalendar();
            // console.log(this.userId);
            this.setTokenFromCookie();
            // this.setDates();
            // this.setRange();
            
            // console.log(this.$refs['month_calendar']);
        },
        props: ['userId'],
        data: function(){
            return {
                view: null,
                search: null,
                owner: owner,
                halls: halls,
                workers: workers,
                templates: templates,
                token: null,
                showCalendar: true
            };
        },
        computed: {
            monthView: function () {
                return (this.view != null && this.view.toLowerCase() == 'month') ? true : false;
            },
            weekView: function () {
                return this.view != null && this.view.toLowerCase() == 'week';
            },
            dayView: function () {
                return this.view != null && this.view.toLowerCase() == 'day';
            },
        },
        methods: {
            reset: function(){
                this.view = null;
            },
            setCurrentDate: function(){
                this.currentDate = {
                    year: moment(this.currentDateObj).format('YYYY'),
                    month: moment(this.currentDateObj).format('MM'),
                    day: moment(this.currentDateObj).format('DD'),
                }
            },
            filterChange: function(e){
                this.view = e.searchObj.view;
                this.search = e.searchString;
            },
            isAuth: function(){
                return this.token != null;
            },
            setToken: function(token){
                cookie.set('token', token);
                this.token = token;
            },
            setTokenFromCookie: function(){
                let token = cookie.get('token');
                if(token)
                    this.token = token;
            }
        },
        components: {
            MonthCalendar,
            WeekCalendar,
            DayCalendar,
            Filters
        },
        watch: {
            // view: function () {
            //     console.log(this.view + ': ' + this.monthView);
            // }
        }
    }
</script>

<style scoped>
    
</style>