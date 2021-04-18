<template>
    <div>
        <filters ref="filters"
                 :owner="owner"
                 :halls="halls"
                 :workers="workers"
                 :templates="templates"
                 :client-info="clientInfo"
                 @change="filterChange($event)"
                 @reset="reset"
                 @hideCalendar="showCalendar = false"
                 @showCalendar="showCalendar = true"></filters>
        <div v-show="showCalendar" class="container-fluid">
            <month-calendar v-if="monthView"
                @view_changed="onViewChange($event)"
                :start-date="startDateMonth"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"></month-calendar>
            <week-calendar v-if="weekView"
                @view_changed="onViewChange($event)"
                :start-date="startDateWeek"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"></week-calendar>
            <day-calendar v-if="dayView"
                @view_changed="onViewChange($event)"
                :start-date="startDateDay"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"></day-calendar>
            <list-calendar v-if="listView"
                @view_changed="onViewChange($event)"
                :start-date="startDateWeek"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"></list-calendar>
        </div>
    </div>
</template>

<script>
    import MonthCalendar from "./MonthCalendar.vue";
    import WeekCalendar from "./WeekCalendar.vue";
    import DayCalendar from "./DayCalendar.vue";
    import ListCalendar from "./ListCalendar.vue";
    import Filters from "./Filters.vue";
    export default {
        name: 'app',
        mounted() {
            // console.log(222222299999999999999);
            // this.showChildren();
            // console.log(this.$options.name);
            this.setTokenFromCookie();
            this.getClientInfo();
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
                clientInfo: null,
                
                showCalendar: true,
                views: ['month','week','day','list'],
                
                startDateMonth: new Date(),
                startDateWeek: new Date(),
                startDateDay: new Date(),
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
            listView: function () {
                return this.view != null && this.view.toLowerCase() == 'list';
            },
            authorized: function(){
                return this.token != null;
                // return false;
            },
        },
        methods: {
            logout: function(){
                cookie.remove('token');
                this.token = null;
                this.clientInfo = null;
            },
            getClientInfo: function(){
                if(this.token == null)
                    return;
                let token = 'Bearer ' + this.token;
                console.log(token);
                let url = routes.calendar.booking.client.info;
                // let config = {
                //     headers: {
                //         Authorization: token,
                //     }
                // }
                // let data = {}
                axios.get(url, {
                    headers: {
                        Authorization: token,
                    }
                })
                .then((response) => {
                    // handle success
                    this.clientInfo = response.data;
                    // console.log(this.clientInfo);
                    // console.log(JSON.parse(JSON.stringify(this.dates)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                    console.log('always');
                    // if(from == 'cancel_book'){
                    //     console.log('from: cancel_book');
                    //     $('#cancelBookModal').modal('hide');
                    // }
                });
            },
            setStartDate: function(fromView, date){
                let dateMoment = moment(date);
                let currentDateMoment = moment(new Date());
                
                if(dateMoment.diff(currentDateMoment) >= 0){
                    if(fromView == 'month'){
                        this.startDateMonth = date;
                        this.startDateWeek = date;
                        this.startDateDay = date;
                    }
                    if(fromView == 'week'){
                        // console.log(date);
                        this.startDateWeek = date;
                        this.startDateDay = date;
                        let dateMomentOfStartMonth = dateMoment.startOf('month').format('YYYYMMDD');
                        let startDateMonthOfStartMonth = moment(this.startDateMonth).startOf('month').format('YYYYMMDD');
                        if(dateMomentOfStartMonth != startDateMonthOfStartMonth){
                            this.startDateMonth = date;
                        }
                    }
                    if(fromView == 'day'){
                        this.startDateDay = date;
                        let dateMoment1 = dateMoment.clone();
                        let dateMoment2 = dateMoment.clone();
                        
                        let dateMomentOfStartWeek = dateMoment1.subtract(1, 'days').startOf('week').format('YYYYMMDD');
                        let startDateMonthOfStartWeek = moment(this.startDateWeek).startOf('week').format('YYYYMMDD');
                        if(dateMomentOfStartWeek != startDateMonthOfStartWeek){
                            this.startDateWeek = date;
                        }
                        
                        let dateMomentOfStartMonth = dateMoment2.startOf('month').format('YYYYMMDD');
                        let startDateMonthOfStartMonth = moment(this.startDateMonth).startOf('month').format('YYYYMMDD');
                        if(dateMomentOfStartMonth != startDateMonthOfStartMonth){
                            this.startDateMonth = date;
                        }
                    }
                    this.startDate = date;
                }else{
                    this.startDateMonth = currentDateMoment.toDate();
                    this.startDateWeek = currentDateMoment.toDate();
                    this.startDateDay = currentDateMoment.toDate();
                }
                // console.log(this.startDate);
            },
            onViewChange: function(event){
                console.log(event);
                this.$refs['filters'].changeView(event);
                // this.view = null;
            },
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
            // isAuth: function(){
            //     return this.token != null;
            // },
            setToken: function(token){
                cookie.set('token', token);
                this.token = token;
            },
            setTokenFromCookie: function(){
                let token = cookie.get('token');
                console.log(token);
                if(token)
                    this.token = token;
            }
        },
        components: {
            MonthCalendar,
            WeekCalendar,
            DayCalendar,
            ListCalendar,
            Filters
        },
        watch: {
            // view: function () {
            //     console.log(this.view + ': ' + this.monthView);
            // }
        }
    }
</script>

<style>
    .calendar-title{
        /* font-weight: bold; */
        text-align: center;
        font-size: 22px;
    }
</style>