<template>
    <div>
        <filters ref="filters"
                 :owner="owner"
                 :halls="halls"
                 :workers="workers"
                 :templates="templates"
                 :client-info="clientInfo"
                 :all-bookings="allBookings"
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
                :search="search"
                :data-updater="dataUpdater"></month-calendar>
            <week-calendar v-if="weekView"
                @view_changed="onViewChange($event)"
                :start-date="startDateWeek"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"
                :data-updater="dataUpdater"></week-calendar>
            <day-calendar v-if="dayView"
                @view_changed="onViewChange($event)"
                :start-date="startDateDay"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"
                :data-updater="dataUpdater"></day-calendar>
            <list-calendar v-if="listView"
                @view_changed="onViewChange($event)"
                :start-date="startDateWeek"
                :view="view"
                :views="views"
                :user-id="userId"
                :search="search"
                :data-updater="dataUpdater"></list-calendar>
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
            this.getBookings();
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
                allBookings: null,
                
                showCalendar: true,
                views: ['month','week','day','list'],
                
                startDateMonth: new Date(),
                startDateWeek: new Date(),
                startDateDay: new Date(),
                
                dataUpdater: 0,
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
            clientAuthorized: function(){
                return this.token != null;
            },
            tokenHeader: function(){
                if(this.token == null)
                    return null;
                return 'Bearer ' + this.token;
            },
        },
        methods: {
            getData: function(startDate, endDate, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            },){
                
                if(this.isAuth()){
                    var url = routes.calendar.booking.range.client;
                    var headers = {
                        headers: {
                            Authorization: this.tokenHeader,
                        }
                    }
                }else{
                    var url = routes.calendar.booking.range.guest;
                    var headers = {}
                }
                
                url = url.replace(':start', startDate);
                url = url.replace(':end', endDate);
                
                url += '?' + this.search;
                
                axios.get(url, headers)
                .then((response) => {
                    // handle success
                    successCallback(response);
                    // console.log(JSON.parse(JSON.stringify(this.dates)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                    finalCallback();
                    // $('#cancelBookModal').modal('hide');
                });
                
            },
            bookOn: function(bookOnDate, bookOnTime, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            },){
                if(this.tokenHeader == null)
                    return;
                    
                let url = routes.calendar.booking.book.create;
                
                url = url.replace(':hall_id', filters.hall.id);
                url = url.replace(':template_id', filters.template.id);
                url = url.replace(':worker_id', filters.worker.id);
                
                axios.post(url, {
                    book_on_date: bookOnDate,
                    book_on_time: bookOnTime,
                }, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    successCallback(response);
                    this.dataUpdater++;
                    this.getBookings();
                    // this.dataUpdater++;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    finalCallback();
                });
            },
            cancelBooking: function(booking, successCallback = () => {
                console.log('success');
            }){
                if(this.tokenHeader == null)
                    return;
                
                let url = routes.calendar.booking.book.cancel;
                
                url = url.replace(':hall_id', filters.hall.id);
                url = url.replace(':template_id', filters.template.id);
                url = url.replace(':worker_id', filters.worker.id);
                url = url.replace(':booking_id', booking.id);
                
                // console.log(url);
                // return;
                
                axios.delete(url, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    // handle success
                    // this.dates = response.data.data;
                    successCallback(response);
                    this.dataUpdater++;
                    this.getBookings();
                    // console.log('success');
                    // this.onCancel(response.data);
                    // console.log(JSON.parse(JSON.stringify(response)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                    console.log('always');
                    // this.$refs['loader'].fadeOut(300);
                    // setTimeout(() => {
                    //     this.successfullyBooked = true;
                    //     this.bookButtonDisabled = false;
                    // }, 300);
                
                });
            },
            login: function(token){
                // cookie.remove('token');
                // this.token = null;
                // this.clientInfo = null;
                this.setToken(token);
                this.getClientInfo();
                this.getBookings();
                this.dataUpdater++;
            },
            logout: function(){
                cookie.remove('token');
                this.token = null;
                this.clientInfo = null;
                this.dataUpdater++;
            },
            getBookings: function(){
                if(this.token == null)
                    return null;
                    
                let url = routes.calendar.booking.book.all;
                let currentDate = moment(new Date()).format('YYYY-MM-DD_HH:mm:ss');
                url = url.replace(':from_date', currentDate);
            
                axios.get(url, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    // handle success
                    this.allBookings = response.data;
                    // console.log(this.clientInfo);
                    console.log(JSON.parse(JSON.stringify(this.allBookings)));
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
            getClientInfo: function(){
                if(this.token == null)
                    return;
                
                axios.get(routes.calendar.booking.client.info, {
                    headers: {
                        Authorization: this.tokenHeader,
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