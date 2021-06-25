<template>
    <div>
        <div class="pb-3">
            <time-bar-new @change="" :durationInMinutes="20" :stopper="40"/>
        </div>
        <div class="pb-3">
            <time-bar-fill @change="" :durationInMinutes="42"/>
        </div>
        <navigation :can-go-to-previous="canGoToPrevious"
            :calendar-title="calendarTitle"
            @previous="previous"
            @next="next"
            @today="today"></navigation>
            
        <modal-move-path-picker
            @pick_time="pickTime()"
            ref="move_path_picker" />
        
        <div class="month-calendar">
            <transition name="fade">
                <moving-event v-if="movedEvent"
                    @close="resetMovedEvent"
                    @edit="openModalMovePathPicker" />
            </transition>
            
            <!-- <moving-event /> -->
            <table>
                <thead>
                    <tr>
                        <th v-for="weekday in weekdaysList">{{weekday}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="divider">
                        <td v-for="td in 7"></td>
                    </tr>
                    <tr v-if="dates" v-for="i in 6">
                        <td v-for="k in 7" :class="{'current-day': isCurrentDate(i,k)}">
                            <div class="cell-content">
                                <div class="day" :class="{'not-period': currentCalendarMonth != getDate(i,k,'month')}">
                                    {{getDate(i,k,'day')}}
                                </div>
                                <template v-if="notPast(i,k)">
                                    <month-cell v-if="getDate(i,k,'items')"
                                        @open-modal="openModal($event,i,k)"
                                        @cancel="cancelBook($event)"
                                        @move="moveEvent($event)"
                                        @showPickTimeModal="showPickTimeModal($event)"
                                        :item="getDate(i,k)"></month-cell>
                                </template>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</template>

<script>
    import Navigation from "./Navigation.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalMovePathPicker from "./ModalMovePathPicker.vue";
    import MonthMovingEvent from "./MonthMovingEvent.vue";
    import TimeBarFill from "./modals/TimeBarFill.vue";
    import TimeBarNew from "./modals/TimeBarNew.vue";
    // import MonthCellCounters from "./MonthCellCounters.vue";
    export default {
        name: 'monthCalendar',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
            });
        },
        mounted() {
            if(this.componentApp == null)
                this.componentApp = this.getParentComponentByName(this, 'app');
                
            // this.setDates(moment(new Date()).startOf('month').toDate());
            this.setDates(moment(this.startDate).startOf('month').toDate());
            
            if(this.movedEvent !== null){
                this.getData({type: 'free_time'});
            }else{
                this.getData();
            }
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
            
            // setTimeout(function(){
            //     $('.tooltip-active').tooltip({
            //         html: true,
            //     });
            // }, 500);
            // this.$nextTick(function(){
            //     $('.tooltip-active').tooltip({
            //         html: true,
            //     });
            // });
            // $('[data-toggle="tooltip"]').tooltip();
        },
        props: ['startDate'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                dates: null,
                bookDate: null,
                bookTimePeriod: null,
                currentDate: new Date(),
                yearOfCurrentDate: null,
                monthOfCurrentDate: null,
                dayOfCurrentDate: null,
                currentCalendarMonth: null,
                firstMonthDate: null,
                lastMonthDate: null,
                firstCalendarDate: null,
                lastCalendarDate: null,
                cancelBookData: null,
                
                componentApp: null,
            };
        },
        computed: {
            // movedEventClientName: function(){
            //     return componentApp.fullName(this.movedEventClient)
            // },
            // movedEventClientEmail: function(){
            //     return movedEventClient.email ? movedEventClient.email : '---';
            // },
            movedEvent: function(){
                return this.$store.getters['moving_event/event'];
            },
            movedEventClient: function(){
                return this.$store.getters['moving_event/client'];
            },
            halls: function(){
                return this.$store.getters['halls/all'];
            },
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            calendarTitle: function () {
                return moment(this.firstMonthDate).format('MMMM YYYY');
            },
            canGoToPrevious: function () {
                let firstMonthDay = moment(this.firstMonthDate).startOf('month');
                let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
                return firstMonthDay.isAfter(currentDateFirstMonthDay);
            },
            search: function () {
                return this.$store.getters['filters/urlSearchPath'];
            },
            // hasCurrentDate: function () {
            //     let firstMonthDay = moment(this.firstMonthDate).startOf('month');
            //     let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
            //     return firstMonthDay.diff(currentDateFirstMonthDay) == 0;
            // }
        },
        methods: {
            showPickTimeModal: function(e){
                this.$emit('showPickTimeModal', e);
                // alert(event);
            },
            openModalMovePathPicker: function(){
                // alert(111);
                this.$refs.move_path_picker.show();
            },
            resetMovedEvent: function(){
                this.$store.dispatch('moving_event/reset');
                this.getData();
            },
            pickTime: function(){
                if(this.movedEvent !== null){
                    // store.commit('move_event/setItems', items);
                    this.getData({type: 'free_time'});
                    // console.log(JSON.parse(JSON.stringify('movedEvent not null')));
                }
                // console.log(JSON.parse(JSON.stringify('pickTime')));
            },
            moveEvent: function(event){
                // console.log('moveEvent');
                // console.log(JSON.parse(JSON.stringify(event)));
                
                new Promise((resolve, reject) => {
                    this.componentApp.getClientInfo(event.client_id, (data) => {
                        if(data === null){
                            alert('Can`t get client info');
                            return;
                        }
                        resolve(data);
                    });
                }).then((data) => {
                    // this.$store.commit('moving_event/setItems', {
                    //     client: data,
                    //     event: event,
                    // });
                    
                    console.log(JSON.parse(JSON.stringify(event)));
                    
                    this.$store.dispatch('moving_event/setItems', {
                        client: data,
                        event: event,
                    });
                    this.$refs.move_path_picker.show();
                });
            },
            cancelBook: function(event){
                this.cancelBookData = event;
                $('#cancelBookModal').modal('show');
                // console.log(event);
            },
            isFirstItemOfTypeBooked: function(i,k){
                let dateFirstItem = (this.getDate(i, k, 'items'))[0];
                return dateFirstItem.type == 'booked' ? true : false;
            },
            next: function(){
                console.log('next');
                var dateOfNextMonth = moment(this.firstMonthDate).add(1, 'M');
                // console.log(dateOfNextMonth.toDate());
                this.setDates(dateOfNextMonth.toDate());
                this.$parent.setStartDate('month', new Date(this.firstMonthDate));
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                    
                // console.log('previous');
                var dateOfPreviousMonth = moment(this.firstMonthDate).subtract(1, 'M');
                this.setDates(dateOfPreviousMonth.toDate());
                this.$parent.setStartDate('month', new Date(this.firstMonthDate));
                this.getData();
            },
            today: function(){
                this.setDates(moment(new Date()).startOf('month').toDate());
                this.$parent.setStartDate('month', new Date(this.firstMonthDate));
                this.getData();
                // let interval = setInterval(() => {
                //     if(this.search != null){
                //         // console.log(11111);
                //         clearInterval(interval);
                //         this.getData();
                //     }
                // }, 300);
            },
            openModal: function(itm,i,k){
                // console.log(itm);
                // $parent.isAuth();
                // console.log(this.$parent.isAuth());
                this.bookDate = this.getDate(i,k);
                this.bookTimePeriod = itm;
                console.log(111);
                console.log(JSON.parse(JSON.stringify(this.bookTimePeriod)));
                $('#bookModal').modal('show');
            },
            notPast: function(i,k){
                return true;
                
                let date = this.getDate(i,k);
                let dateMoment = moment(date.year+'-'+date.month+'-'+date.day, 'YYYY-MM-DD');
                let currentDateMoment = moment(this.currentDate);
                // console.log(dateMoment.format("YYYY MM DD") + ' - ' + currentDateMoment.format("YYYY MM DD") + ': ' + (dateMoment.diff(currentDateMoment) >= 0 || dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")));
                // console.log(currentDateMoment.format("YYYY MM DD"));
                return dateMoment.diff(currentDateMoment, 'day') >= 0;
                // console.log(dateMoment < currentDateMoment);
                // console.log(moment(date).isAfter(moment('2014-03-24T01:14:000')));
                // return this.currentDate.year == this.getDate(i,k,'year') &&
                // this.currentDate.month == this.getDate(i,k,'month') &&
                // this.currentDate.day == this.getDate(i,k,'day');
            },
            isCurrentDate: function(i,k){
                return this.yearOfCurrentDate == this.getDate(i,k,'year') &&
                this.monthOfCurrentDate == this.getDate(i,k,'month') &&
                this.dayOfCurrentDate == this.getDate(i,k,'day');
            },
            getDate: function(i,k,type = null){
                let date = this.dates[(((i*7) + k))-8];
                // let month = this.dates[(((i*7) + k))-8].month;
                if(type != null)
                    return date[type];
                return date;
            },
            getData: function(){
                // alert(this.firstCalendarDate);
                // return;
                this.componentApp.getData(
                    moment(this.firstCalendarDate).format('DD-MM-YYYY'),
                    moment(this.lastCalendarDate).format('DD-MM-YYYY'),
                    (response) => {
                        // if(params !== null && typeof params.type !== 'undefined' && params.type == 'free'){
                        //     this.datesByType = response.data.data;
                        // }else{
                        //     this.dates = response.data.data;
                        // }
                        
                        // alert(333);
                        this.dates = response.data.data;
                        
                        // console.log(JSON.parse(JSON.stringify(666666)));
                        // console.log(JSON.parse(JSON.stringify(this.dates)));
                    },
                    () => {},
                    () => {
                        $('#cancelBookModal').modal('hide');
                    },
                );
            },
            setDates: function(firstCalendarMonthDate){
                if(this.yearOfCurrentDate == null)
                    this.yearOfCurrentDate = moment(this.currentDate).format('YYYY');
                if(this.monthOfCurrentDate == null)
                    this.monthOfCurrentDate = moment(this.currentDate).format('MM');
                if(this.dayOfCurrentDate == null)
                    this.dayOfCurrentDate = moment(this.currentDate).format('DD');
                
                // console.log([
                //     this.yearOfCurrentDate, this.monthOfCurrentDate, this.dayOfCurrentDate
                // ]);
                
                // this.monthOfCurrentDate = parseInt(moment(this.currentDate).format('MM')) + 1;
                // this.dayOfCurrentDate = parseInt(moment(this.currentDate).format('YYYY'));
                
                this.firstMonthDate = firstCalendarMonthDate;
                this.lastMonthDate = moment(firstCalendarMonthDate).endOf('month').toDate();
                
                // this.currentMonthIdx = firstCalendarMonthDate;
                this.currentCalendarMonth = moment(this.firstMonthDate).format('MM');
                
                let firstMonthDateWeekday = this.firstMonthDate.getDay();
                let totalDaysInMonth = this.lastMonthDate.getDate();                    
                
                let startOffset;
                if(firstMonthDateWeekday == 1){
                    startOffset = 7;
                }else if(firstMonthDateWeekday == 2){
                    startOffset = 8;
                }else if(firstMonthDateWeekday == 3){
                    startOffset = 9;
                }else if(firstMonthDateWeekday == 4){
                    startOffset = 3;
                }else if(firstMonthDateWeekday == 5){
                    startOffset = 4;
                }else if(firstMonthDateWeekday == 6){
                    startOffset = 5;
                }else if(firstMonthDateWeekday == 0){
                    startOffset = 6;
                }
                
                let firstDate = new Date(this.firstMonthDate);
                
                firstDate.setDate(firstDate.getDate() - startOffset);
                
                let lastDate = new Date(firstDate);
                lastDate.setDate(lastDate.getDate() + 41);
                
                this.firstCalendarDate = firstDate;
                this.lastCalendarDate = lastDate;
                // this.range = {
                //     first_date: firstDate,
                //     last_date: lastDate,
                // }
            },
        },
        components: {
            Navigation,
            MonthCell,
            ModalMovePathPicker,
            movingEvent: MonthMovingEvent,
            TimeBarFill,
            TimeBarNew,
        },
        watch: {
            dates: function () {
                
            },
            search: function () {
                // console.log(this.search);
                this.getData();
            },
            dataUpdater: function () {
                this.getData();
            },
        }
    }
</script>

<style lang="scss" scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>