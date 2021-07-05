<template>
    <div>
        <!-- <filters ref="filters"
                 @change="filterChange($event)"></filters> -->
        
        {{showCalendar ? 'showCalendar 1' : 'notShowCalendar'}}
        
        <div class="container-fluid">
            <month-calendar v-if="monthView"
                ref="month_calendar"
                :start-date="startDateMonth"></month-calendar>
            <week-calendar v-if="weekView"
                ref="week_calendar"
                :start-date="startDateWeek"></week-calendar>
            <day-calendar v-if="dayView"
                ref="day_calendar"
                :start-date="startDateDay"></day-calendar>
            <list-calendar v-if="listView"
                ref="list_calendar"
                :start-date="startDateWeek"></list-calendar>
        </div>
        
        <time-picker-modal v-if="currentEventFilter" ref="time_picker_modal" />
        <modal-duration v-if="currentEventFilter" ref="modal_duration" />
        
    </div>
</template>

<script>
    import MonthCalendar from "./MonthCalendar.vue";
    import WeekCalendar from "./WeekCalendar.vue";
    import DayCalendar from "./DayCalendar.vue";
    import ListCalendar from "./ListCalendar.vue";
    import Filters from "./Filters.vue";
    import Loader from "./Loader.vue";
    import TimePickerModal from "./modals/TimePickerModal.vue";
    import ModalDuration from "./modals/ModalDuration.vue";
    export default {
        name: 'app',
        mounted() {
            // alert(22222);
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
            
            console.log(JSON.parse(JSON.stringify(777777777)));
            console.log(JSON.parse(JSON.stringify(this.movingEvent)));
            // console.log(JSON.parse(JSON.stringify(this.$store.getters['filters/all'])));
            
            if(this.cookieFilters !== null)
                this.showCalendar = true;
        },
        // updated() {
        //     $('.tooltip-active').tooltip({
        //         html: true,
        //     });
        // },
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
            };
        },
        computed: {
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            cookieFilters: function () {
                return this.$store.getters['filters/all'];
            },
            // view: function () {
            //     return 'month';
            //     // return this.$store.getters['filters/view'];
            // },
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
        },
        methods: {
            /*
            *   param: event
            *   return: Promise
            */
            setMovingEvent: function (event){
                return new Promise((resolve, reject) => {
                    this.getClientInfo(event.client_id, (data) => {
                        if(data === null){
                            alert('Can`t get client info');
                            return;
                        }
                        resolve(data);
                    });
                }).then((client) => {
                    return new Promise((resolve, reject) => {
                        this.$store.dispatch('moving_event/setItems', {
                            client: client,
                            event: event,
                        });
                        resolve({
                            client: client,
                            event: event,
                        });
                    });
                });
            },
            showModalDuration: function (e){
                // console.log(JSON.parse(JSON.stringify('showPickTimeModal')));
                // console.log(e);
                // console.log(JSON.parse(JSON.stringify(e)));
                // this.$refs.modal_duration.show(e);
                // alert(2222);
                this.$nextTick(() => {
                    // console.log(this.$refs);
                    this.$refs.modal_duration.show(e);
                });
                // console.log(JSON.parse(JSON.stringify(this.$refs)));
            },
            showPickTimeModal: function (e){
                // console.log(JSON.parse(JSON.stringify('showPickTimeModal')));
                // console.log(e);
                // console.log(JSON.parse(JSON.stringify(e)));
                this.$refs.time_picker_modal.show(e);
                // alert(2222);
            },
            fullName: function (obj){
                if(obj === null ||
                typeof obj.first_name === 'undefined' ||
                typeof obj.last_name === 'undefined')
                    return null;
                    
                let fullName = obj.first_name;
                
                if(obj.last_name !== null && typeof obj.last_name === 'string' &&
                obj.last_name.length > 0)
                    fullName += ' ' + obj.last_name;
                    
                return fullName;
            },
            approveEvent: function(id){
                let url, urlParams;
                url = new URL(routes.calendar.booking.booking.approve.replace(':id', id));
                
                return new Promise((resolve, reject) => {
                    axios.post(url.toString())
                    .then((response) => {    
                        resolve(response.data);
                    })
                    .catch(function (error) {
                        // handle error
                        // console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                });
            },
            // approveEvent: function(id, successCallback = () => {
            //     console.log('success');
            // }, errorCallback = () => {
            //     console.log('error');
            // }, finalCallback = () => {
            //     console.log('final');
            // },){
            //     let url, urlParams;
            //     url = new URL(routes.calendar.booking.booking.approve.replace(':id', id));
            // 
            //     axios.post(url.toString())
            //         .then((response) => {
            //             // let client = null;
            //             // if(typeof response.data.status !== 'undefined' && Array.isArray(response.data.clients) &&
            //             // response.data.clients.length == 1)
            //             //     client = response.data.clients[0];
            // 
            //             successCallback(response.data);
            //         })
            //         .catch(function (error) {
            //             // handle error
            //             // console.log(error);
            //         })
            //         .then(function () {
            //             // always executed
            //         });
            // },
            removeEvent: function(id, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            },){
                let url;
                url = new URL(routes.calendar.booking.booking.delete.replace(':id', id));
                
                axios.post(url.toString())
                    .then((response) => {
                        // let client = null;
                        // if(typeof response.data.status !== 'undefined' && Array.isArray(response.data.clients) &&
                        // response.data.clients.length == 1)
                        //     client = response.data.clients[0];
                            
                        successCallback(response.data);
                    })
                    .catch(function (error) {
                        // handle error
                        // console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            getClientInfo: function(id, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            },){
                let url, urlParams;
                url = new URL(routes.calendar.booking.client.get);
                urlParams = new URLSearchParams();
                urlParams.append('id', id);
                url.search = urlParams;
                
                axios.get(url.toString())
                    .then((response) => {
                        let client = null;
                        if(typeof response.data.clients !== 'undefined' && Array.isArray(response.data.clients) &&
                        response.data.clients.length == 1)
                            client = response.data.clients[0];
                            
                        successCallback(client);
                    })
                    .catch(function (error) {
                        // handle error
                        // console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            // getData: function(startDate, endDate, params = null, successCallback = () => {
            getData: function(params = null, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            },){
                let _this = this;
                // console.log(JSON.parse(JSON.stringify('Params 8888')));
                console.log(JSON.parse(JSON.stringify(getUrl())));
                
                // if(isParam('exceptIds') && Array.isArray(params.exceptIds) && params.exceptIds.length > 0)
                //     for(let i = 0; i < params.exceptIds.length; i++){
                //         urlSearchParams.append("except_ids[]", params.exceptIds[i]);
                //     }
                
                // this.$store.dispatch('dates/goNext');
                
                let startDate = moment(this.$store.getters['dates/interval'].firstDate).format('DD-MM-YYYY');
                let endDate = moment(this.$store.getters['dates/interval'].lastDate).format('DD-MM-YYYY');
                
                axios.get(getUrl())
                .then((response) => {
                    // handle success
                    successCallback(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                    finalCallback();
                });
                
                function getUrl(){
                    let url, urlSearchParams;
                    
                    if(_this.isMovingEvent){
                        url = routes.calendar.booking.booking.byType.replace(':type', 'free');
                    }else{
                        url = routes.calendar.booking.booking.all;
                    }
                        
                    url = url.replace(':start', startDate);
                    url = url.replace(':end', endDate);
                    
                    url = new URL(url);
                    // console.log(JSON.parse(JSON.stringify('Params 8888')));
                    
                    urlSearchParams = _this.urlSearchParams();
                    if(!_this.isMovingEvent){
                        urlSearchParams.append("with[]", 'templateWithoutUserScope.specific');
                        urlSearchParams.append("with[]", 'workerWithoutUserScope');
                        urlSearchParams.append("with[]", 'hallWithoutUserScope');
                        urlSearchParams.append("with[]", 'clientWithoutUserScope');
                    }else{
                        // console.log(params.exceptIds);
                        // console.log(isParam('exceptIds'));
                        if(isParam('exclude_ids') && Array.isArray(params.exclude_ids) && params.exclude_ids.length > 0)
                            for(let i = 0; i < params.exclude_ids.length; i++){
                                urlSearchParams.append("exclude_ids[]", params.exclude_ids[i]);
                            }
                    }
                    
                    url.search = urlSearchParams;
                    
                    return url.toString();
                }
                
                function isParam(param){
                    return params !== null && typeof params[param] !== 'undefined' && params[param] !== null;
                }
            },
            bookOn: function(bookOnDate, bookOnTime, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            }){
                let url = routes.calendar.booking.book.create;
                
                url = url.replace(':hall_id', this.cookieFilters.hall.id);
                url = url.replace(':template_id', this.cookieFilters.template.id);
                url = url.replace(':worker_id', this.cookieFilters.worker.id);
                
                axios.post(url, {
                    book_on_date: bookOnDate,
                    book_on_time: bookOnTime,
                })
                .then((response) => {
                    successCallback(response);
                    
                    this.$store.commit('updater/increaseCounter');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    finalCallback();
                });
            },
            bookEdit: function(bookId, data, successCallback = () => {
                console.log('success');
            }, errorCallback = () => {
                console.log('error');
            }, finalCallback = () => {
                console.log('final');
            }){
                // if(this.currentEventFilter === null)
                //     return;
                    
                // console.log(JSON.parse(JSON.stringify('bookEdit')));
                // return;
                    
                let url = routes.calendar.booking.booking.edit;
                url = url.replace(':id', bookId);
                
                // console.log(JSON.parse(JSON.stringify('bookEdit')));
                // console.log(JSON.parse(JSON.stringify(url)));
                // 
                // return;
                
                axios.post(url, data)
                .then((response) => {
                    successCallback(response);
                    
                    // this.$store.commit('updater/increaseCounter');
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
                let url = routes.calendar.booking.book.cancel;
                
                // url = url.replace(':hall_id', this.cookieFilters.hall.id);
                // url = url.replace(':template_id', this.cookieFilters.template.id);
                // url = url.replace(':worker_id', this.cookieFilters.worker.id);
                url = url.replace(':booking_id', booking.id);
                
                axios.delete(url)
                .then((response) => {
                    successCallback(response);
                    this.$store.commit('updater/increaseCounter');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
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
            Loader,
            TimePickerModal,
            ModalDuration
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