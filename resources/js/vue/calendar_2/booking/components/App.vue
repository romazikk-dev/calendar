<template>
    <div>
        <filters ref="filters"
                 @change="filterChange($event)"
                 @hideCalendar="showCalendar = false"
                 @showCalendar="showCalendar = true"></filters>
        
        {{showCalendar ? 'showCalendar' : 'notShowCalendar'}}
        
        <div v-show="showCalendar" class="container-fluid">
            <month-calendar v-if="monthView"
                :start-date="startDateMonth"></month-calendar>
            <week-calendar v-if="weekView"
                :start-date="startDateWeek"></week-calendar>
            <day-calendar v-if="dayView"
                :start-date="startDateDay"></day-calendar>
            <list-calendar v-if="listView"
                :start-date="startDateWeek"></list-calendar>
        </div>
    </div>
</template>

<script>
    import MonthCalendar from "./MonthCalendar.vue";
    import WeekCalendar from "./WeekCalendar.vue";
    import DayCalendar from "./DayCalendar.vue";
    import ListCalendar from "./ListCalendar.vue";
    import Filters from "./Filters.vue";
    import Loader from "./Loader.vue";
    export default {
        name: 'app',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
            // console.log(JSON.parse(JSON.stringify(this.token)));
            
            console.log(JSON.parse(JSON.stringify(777777777)));
            // console.log(JSON.parse(JSON.stringify(this.$store.getters['updater/clientInfo'])));
            this.$store.dispatch('client/increaseUpdaterCounter');
            
            this.setClient();
            
            if(this.cookieFilters !== null)
                this.showCalendar = true;
        },
        props: ['userId'],
        data: function(){
            return {
                // templateSpecifics: templateSpecifics,
                // 
                // templateSpecificsAsIdKey: (typeof templateSpecificsAsIdKey !== 'undefined' && templateSpecificsAsIdKey !== null) ?
                //     templateSpecificsAsIdKey : null,
                
                // showCalendar: false,
                showCalendar: true,
                views: ['month','week','day','list'],
                
                startDateMonth: new Date(),
                startDateWeek: new Date(),
                startDateDay: new Date(),
                
                // dataUpdater: 0,
            };
        },
        computed: {
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            cookieFilters: function () {
                return this.$store.getters['filters/all'];
            },
            clientInfo: function () {
                return this.$store.getters['client/info'];
            },
            token: function () {
                return this.$store.getters['client/token'];
            },
            view: function () {
                return this.$store.getters['filters/view'];
            },
            search: function () {
                return this.$store.getters['filters/urlSearchPath'];
            },
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
            }){
                if(this.tokenHeader == null)
                    return;
                    
                let url = routes.calendar.booking.book.create;
                
                url = url.replace(':hall_id', this.cookieFilters.hall.id);
                url = url.replace(':template_id', this.cookieFilters.template.id);
                url = url.replace(':worker_id', this.cookieFilters.worker.id);
                
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
                    
                    this.$store.commit('updater/increaseCounter');
                    // this.dataUpdater++;
                    this.setBookings();
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
                
                // url = url.replace(':hall_id', this.cookieFilters.hall.id);
                // url = url.replace(':template_id', this.cookieFilters.template.id);
                // url = url.replace(':worker_id', this.cookieFilters.worker.id);
                url = url.replace(':booking_id', booking.id);
                
                axios.delete(url, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    successCallback(response);
                    this.$store.commit('updater/increaseCounter');
                    this.setBookings();
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });
            },
            login: function(token){
                this.$store.commit('client/setToken', token);
                this.setClient();
                this.$store.commit('updater/increaseCounter');
            },
            logout: function(){
                this.$store.commit('client/logout');
                this.$store.commit('updater/increaseCounter');
            },
            setClient: function(){
                this.setClientInfo();
                this.setBookings();
            },
            setBookings: function(){
                if(this.token == null)
                    return null;
                    
                let url = routes.calendar.booking.book.all;
                let currentDate = moment(new Date()).format('YYYY-MM-DD_HH:mm:ss');
                url = url.replace(':from_date', currentDate);
                
                // console.log(url);
            
                axios.get(url, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    // handle success
                    // this.allBookings = response.data;
                    this.$store.commit('client/setBookings', response.data);
                    // console.log(this.clientInfo);
                    console.log(JSON.parse(JSON.stringify('setBookings 4444')));
                    console.log(JSON.parse(JSON.stringify(response.data)));
                })
                .catch(function (error) {
                    // handle error
                })
                .then(() => {
                    // always executed
                });
            },
            setClientInfo: function(){
                if(this.token == null)
                    return;
                
                // console.log(this.token);
                
                axios.get(routes.calendar.booking.client.info, {
                    headers: {
                        Authorization: this.tokenHeader,
                    }
                })
                .then((response) => {
                    // handle success
                    this.$store.commit('client/setInfo', response.data);
                })
                .catch((error) => {
                    // handle error
                    if(error.response.status == 401){
                        // alert(111);
                        // console.log(this.token);
                        this.$store.commit('client/setToken');
                        document.location.reload();
                        
                        // console.log(this.token);
                    }
                })
                .then(() => {
                    // always executed
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
            // onViewChange: function(event){
            //     // console.log(event);
            //     // this.$refs['filters'].changeView(event);
            // 
            //     this.$store.commit('filters/changeView', event);
            //     // this.view = null;
            // },
            // reset: function(){
            //     this.view = null;
            // },
            setCurrentDate: function(){
                this.currentDate = {
                    year: moment(this.currentDateObj).format('YYYY'),
                    month: moment(this.currentDateObj).format('MM'),
                    day: moment(this.currentDateObj).format('DD'),
                }
            },
            filterChange: function(e){
                // this.view = e.searchObj.view;
                // this.search = e.searchString;
            },
            // isAuth: function(){
            //     return this.token != null;
            // },
            setToken: function(token){
                // cookie.set('token', token);
                this.$store.commit('client/setToken', token);
                // this.token = token;
            },
            // setTokenFromCookie: function(){
            //     let token = cookie.get('token');
            //     // console.log(token);
            //     if(token)
            //         this.token = token;
            // },
            setFiltersFromCookie: function(){
                // if(typeof filters !== 'undefined' && filters != null)
                    // this.cookieFilters = cookie.get('filters');
                    // cookie.set('filters', {
                    //     hall: this.pickedItmHall.id,
                    //     worker: this.pickedItmWorker.id,
                    //     template: this.pickedItmTemplate.id,
                    //     view: this.pickedItmView,
                    // });
            },
        },
        components: {
            MonthCalendar,
            WeekCalendar,
            DayCalendar,
            ListCalendar,
            Filters,
            Loader
        },
        watch: {
            showCalendar: function (val) {
                // if(val === true){
                //     // this.setFiltersFromCookie();
                //     console.log(cookie.get('filters'));
                // }
                // console.log(this.view + ': ' + this.monthView);
            }
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