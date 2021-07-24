<template>
    <div>
        <!-- <filters ref="filters"
                 @change="filterChange($event)"></filters> -->
        
        <!-- <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-sm btn-primary ac">All</button>
            <button type="button" class="btn btn-sm btn-primary">Middle</button>
        </div> -->
        
        <navbar ref="navbar" />
        <navigation ref="navigation" />
         
        <div class="container-fluid">
            <month-calendar v-if="isMonthView"
                ref="month_calendar"
                :start-date="startDateMonth"></month-calendar>
            <week-calendar v-if="isWeekView"
                ref="week_calendar"
                :start-date="startDateWeek"></week-calendar>
            <day-calendar v-if="isDayView"
                ref="day_calendar"
                :start-date="startDateDay"></day-calendar>
            <list-calendar v-if="isListView"
                ref="list_calendar"
                :start-date="startDateWeek"></list-calendar>
        </div>
        
        <time-picker-modal ref="time_picker_modal" />
        <modal-duration v-if="isMovingEvent" ref="modal_duration" />
        
        <modal-move-path-picker
            @pick_time="onModalMovePathPickerPickTime"
            ref="move_path_picker" />
            
        <modal-more-events ref="modal_more_events" />
            
        <transition name="fade">
            <moving-event-info-box v-if="movingEvent && showMovingEvent" />
        </transition>
        
        <transition name="fade">
            <new-event-info-box v-if="isNewEventMainFull && newEventShow" />
        </transition>
        
    </div>
</template>

<script>
    import Navigation from "./Navigation.vue";
    import Navbar from "./navbar/Navbar.vue";
    import MonthCalendar from "./MonthCalendar.vue";
    import WeekCalendar from "./WeekCalendar.vue";
    import DayCalendar from "./DayCalendar.vue";
    import ListCalendar from "./ListCalendar.vue";
    // import Filters from "./Filters.vue";
    import Loader from "./Loader.vue";
    import TimePickerModal from "./modals/TimePickerModal.vue";
    import ModalDuration from "./modals/ModalDuration.vue";
    import ModalMovePathPicker from "./ModalMovePathPicker.vue";
    import MovingEventInfoBox from "./MovingEventInfoBox.vue";
    import NewEventInfoBox from "./NewEventInfoBox.vue";
    import ModalMoreEvents from "./modals/ModalMoreEvents.vue";
    export default {
        name: 'app',
        mounted() {
            // alert(22222);
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
            
            // console.log(JSON.parse(JSON.stringify(777777777)));
            // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
            
            // if(this.cookieFilters !== null)
            //     this.showCalendar = true;
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
                // showCalendar: true,
                
                startDateMonth: new Date(),
                startDateWeek: new Date(),
                startDateDay: new Date(),
                
                lastGetDataType: null,
            };
        },
        computed: {
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            isMonthView: function () {
                return (this.view != null && this.view.toLowerCase() == 'month') ? true : false;
            },
            isWeekView: function () {
                return this.view != null && this.view.toLowerCase() == 'week';
            },
            isDayView: function () {
                return this.view != null && this.view.toLowerCase() == 'day';
            },
            isListView: function () {
                return this.view != null && this.view.toLowerCase() == 'list';
            },
        },
        methods: {
            /*
            *   param: event
            *   return: Promise
            */
            // onMovingEventInfoBoxClose: function (){
            //     this.resetMovingEvent();
            // },
            onModalMovePathPickerPickTime: function (){
                this.calendar.getData();
            },
            setMovingEvent: function (event){
                return new Promise((resolve, reject) => {
                    this.getClients(event.client_id).then((clients) => {
                        if(clients === null)
                            reject('Can`t get client info');
                        resolve(clients[0]);
                    });
                }).then((client) => {
                    this.$store.dispatch('moving_event/setItems', {
                        client: client,
                        event: event,
                    });
                    return {
                        client: client,
                        event: event,
                    };
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
            removeEvent: function(id){
                let url = new URL(routes.calendar.booking.booking.delete.replace(':id', id));
                
                return new Promise((resolve, reject) => {
                    axios.post(url.toString())
                    .then((response) => {
                        resolve(response.data);
                    }).catch(function (error) {
                        // handle error
                        // console.log(error);
                        reject(error);
                    }).then(function () {
                        // always executed
                    });
                });
            },
            // removeEvent: function(id, successCallback = () => {
            //     console.log('success');
            // }, errorCallback = () => {
            //     console.log('error');
            // }, finalCallback = () => {
            //     console.log('final');
            // },){
            //     let url;
            //     url = new URL(routes.calendar.booking.booking.delete.replace(':id', id));
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
            getTemplates: function(params = null){
                let url, urlParams;
                url = new URL(routes.calendar.booking.template.get);
                
                if(params !== null){
                    urlParams = new URLSearchParams();
                    if(isParam('id'))
                        urlParams.append('id', params.id);
                    if(isParam('hall_id'))
                        urlParams.append('hall_id', params.hall_id);
                    if(isParam('worker_id'))
                        urlParams.append('worker_id', params.worker_id);
                    url.search = urlParams;
                }
                
                return new Promise((resolve, reject) => {
                    axios.get(url.toString())
                    .then((response) => {
                        if(typeof response.data.templates !== 'undefined' && Array.isArray(response.data.templates) &&
                        response.data.templates.length > 0){
                            resolve(response.data.templates);
                        }
                        reject('No templates');
                    })
                    .catch(function (error) {
                        reject(error);
                    });
                });
                
                function isParam(param){
                    return params !== null && typeof params[param] !== 'undefined' && !isNaN(params[param]) && params[param] > 0;
                }
            },
            getWorkers: function(params = null){
                let url, urlParams;
                url = new URL(routes.calendar.booking.worker.get);
                
                if(params !== null){
                    urlParams = new URLSearchParams();
                    if(isParam('id'))
                        urlParams.append('id', params.id);
                    if(isParam('hall_id'))
                        urlParams.append('hall_id', params.hall_id);
                    if(isParam('template_id'))
                        urlParams.append('template_id', params.template_id);
                    if(isParam('with'))
                        urlParams.append('with', params.with);
                    url.search = urlParams;
                }
                
                return new Promise((resolve, reject) => {
                    axios.get(url.toString())
                    .then((response) => {
                        if(typeof response.data.workers !== 'undefined' && Array.isArray(response.data.workers) &&
                        response.data.workers.length > 0){
                            resolve(response.data.workers);
                        }
                        reject('No templates');
                    })
                    .catch(function (error) {
                        reject(error);
                    });
                });
                
                function isParam(param){
                    if(params === null || typeof params[param] === 'undefined')
                        return false;
                    if(param == 'with' && Array.isArray(params[param]))
                        return true;
                    return !isNaN(params[param]) && params[param] > 0;
                }
            },
            getClients: function(id = null){
                let url, urlParams;
                url = new URL(routes.calendar.booking.client.get);
                
                if(id !== null && !isNaN(id) && id > 0){
                    urlParams = new URLSearchParams();
                    urlParams.append('id', id);
                    url.search = urlParams;
                }
                
                return new Promise((resolve, reject) => {
                    axios.get(url.toString())
                    .then((response) => {
                        // if(id !== null){
                        if(typeof response.data.clients !== 'undefined' && Array.isArray(response.data.clients) &&
                        response.data.clients.length > 0){
                            // if(response.data.clients.length == 1){
                            //     resolve(response.data.clients[0]);
                            // }else{
                            //     resolve(response.data.clients);
                            // }
                            resolve(response.data.clients);
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        // console.log(error);
                        reject('No clients');
                    })
                    .then(function () {
                        // always executed
                    });
                });
            },
            // getClientInfo: function(id, successCallback = () => {
            //     console.log('success');
            // }, errorCallback = () => {
            //     console.log('error');
            // }, finalCallback = () => {
            //     console.log('final');
            // },){
            //     let url, urlParams;
            //     url = new URL(routes.calendar.booking.client.get);
            //     urlParams = new URLSearchParams();
            //     urlParams.append('id', id);
            //     url.search = urlParams;
            // 
            //     axios.get(url.toString())
            //         .then((response) => {
            //             let client = null;
            //             if(typeof response.data.clients !== 'undefined' && Array.isArray(response.data.clients) &&
            //             response.data.clients.length == 1)
            //                 client = response.data.clients[0];
            // 
            //             successCallback(client);
            //         })
            //         .catch(function (error) {
            //             // handle error
            //             // console.log(error);
            //         })
            //         .then(function () {
            //             // always executed
            //         });
            // },
            getData: function(startDate, endDate, params = null){
                let _this = this;
                let lastGetDataType = 'all';
                
                if(params === null || (params !== null && typeof params.test === 'undefined'))
                    return new Promise((resolve, reject) => {
                        axios.get(getUrl())
                        .then((response) => {
                            // handle success
                            resolve(response.data);
                        }).catch((error) => {
                            // handle error
                            reject(error);
                        }).then(() => {
                            // always executed
                            this.lastGetDataType = lastGetDataType;
                        });
                    });
                
                function getUrl(){
                    let url, urlSearchParams;
                    
                    // if(_this.isMovingEvent){
                    if(isParam('type') && params.type == 'free'){
                        
                        // url = routes.calendar.booking.booking.byType.replace(':type', 'free');
                        // url = routes.calendar.booking.booking.byType.replace(':type', params.type);
                        // if(params.type === 'free')
                        lastGetDataType = 'free';
                        url = routes.calendar.booking.booking.free;
                    }else{
                        url = routes.calendar.booking.booking.all;
                    }
                        
                    url = url.replace(':start', startDate);
                    url = url.replace(':end', endDate);
                    
                    url = new URL(url);
                    // console.log(JSON.parse(JSON.stringify('Params 8888')));
                    
                    if(isParam('urlSearchParams')){
                        urlSearchParams = params.urlSearchParams;
                    }else{
                        urlSearchParams = _this.urlSearchParams(lastGetDataType);
                    }
                    
                    urlSearchParams = _this.getFiltersSearchParams(urlSearchParams);
                    
                    console.log(JSON.parse(JSON.stringify('urlSearchParams 3333')));
                    console.log(JSON.parse(JSON.stringify(urlSearchParams)));
                    
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
            createEvent: function(date, time, duration){
                if(!this.isNewEventMainFull || !this.isNewEventClientFull){
                    console.error('isNewEventMainFull = false || isNewEventClientFull = false in App.createEvent');
                    return;
                }
                // alert(111);
                // return;
                
                let url = routes.calendar.booking.booking.create;
                url = url.replace(':client', this.newEventClient.id);
                url = url.replace(':hall', this.newEventMain.hall.id);
                url = url.replace(':template', this.newEventMain.template.id);
                url = url.replace(':worker', this.newEventMain.worker.id);
                
                return new Promise((resolve, reject) => {
                    axios.post(url, {
                        date: date,
                        time: time,
                        duration: duration,
                    })
                    .then((response) => {
                        resolve(response.data);
                        // this.$store.commit('updater/increaseCounter');
                    })
                    .catch(function (error) {
                        // handle error
                        reject(error);
                    });
                });
            },
            editEvent: function(id, data){
                let url = routes.calendar.booking.booking.edit.replace(':id', id);
                
                return new Promise((resolve, reject) => {
                    axios.post(url, data)
                    .then((response) => {
                        resolve(response.data);
                    })
                    .catch(function (error) {
                        // handle error
                        // console.log(error);
                        reject(error);
                    })
                    .then(() => {
                        // finalCallback();
                    });
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
            // Filters,
            Loader,
            TimePickerModal,
            ModalDuration,
            ModalMovePathPicker,
            Navbar,
            MovingEventInfoBox,
            NewEventInfoBox,
            Navigation,
            ModalMoreEvents,
        },
        watch: {
            // showCalendar: function (val) {}
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