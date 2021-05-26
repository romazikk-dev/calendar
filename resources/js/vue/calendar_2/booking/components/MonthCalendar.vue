<template>
    <div>
        <navigation :view="view"
            :views="views"
            :can-go-to-previous="canGoToPrevious"
            :calendar-title="calendarTitle"
            @previous="previous"
            @next="next"
            @today="today"
            @change_view="changeView($event)"></navigation>
        
        <div class="for-table">
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
                            <div class="cont">
                                <div class="day" :class="{'not-period': currentCalendarMonth != getDate(i,k,'month')}">
                                    <!-- {{dates[(((i*7) + k))-8].day}} -->
                                    {{getDate(i,k,'day')}}
                                </div>
                                <month-cell v-if="notPast(i,k)"
                                    @open-modal="openModal($event,i,k)"
                                    @cancel="cancelBook($event)"
                                    :items="getDate(i,k,'items')"></month-cell>
                                <!-- <a @click.prevent="openModal(i,k)" v-if="notPast(i,k)" class="booking" href="#">book...</a> -->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="bookModal">
            <div class="modal-dialog">
                <!-- ModalAuthContent -->
                <modal-book-content v-show="isAuth()"
                    :user-id="userId"
                    :book-date="bookDate"
                    :book-time-period="bookTimePeriod"></modal-book-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-show="!isAuth()" :user-id="userId"></modal-auth-content>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="cancelBookModal">
            <div class="modal-dialog">
                <modal-cancel-book-content :booking="cancelBookData"></modal-cancel-book-content>
            </div>
        </div>
        
    </div>
</template>

<script>
    import Navigation from "./Navigation.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    export default {
        name: 'monthCalendar',
        mounted() {
            // if(!this.isAuth())
                
                
            // this.setDates(moment(new Date()).startOf('month').toDate());
            this.setDates(moment(this.startDate).startOf('month').toDate());
            
            // console.log(this.startDate);
            // console.log(moment(this.range.first_date).format('DD-MM-YYYY'));
            // console.log(this.currenyViewIdx);
            
            // console.log(this.currentDate);
            // console.log(this.firstMonthDate);
            // console.log(this.lastMonthDate);
            // console.log(this.firstCalendarDate);
            // console.log(this.lastCalendarDate);
            
            let interval = setInterval(() => {
                if(this.search != null){
                    // console.log(11111);
                    clearInterval(interval);
                    this.getData();
                }
            }, 300);
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
            
            // console.log(this.firstMonthDate);
            // console.log(this.currentDateObj);
        },
        props: ['userId','search','views','view','startDate','dataUpdater'],
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
                // firstMonthDate: moment(this.currentDateObj).startOf('month').toDate(),
            };
        },
        computed: {
            // views: function () {
            //     // console.log(this.$parent.views);
            //     // return null;
            //     return this.$parent.views;
            // },
            calendarTitle: function () {
                return moment(this.firstMonthDate).format('MMMM YYYY');
            },
            canGoToPrevious: function () {
                let firstMonthDay = moment(this.firstMonthDate).startOf('month');
                let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
                return firstMonthDay.isAfter(currentDateFirstMonthDay);
            },
            // hasCurrentDate: function () {
            //     let firstMonthDay = moment(this.firstMonthDate).startOf('month');
            //     let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
            //     return firstMonthDay.diff(currentDateFirstMonthDay) == 0;
            // }
        },
        methods: {
            changeView: function(view){
                this.$emit('view_changed', view);
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
                let date = this.getDate(i,k);
                let dateMoment = moment(date.year+'-'+date.month+'-'+date.day);
                let currentDateMoment = moment(this.currentDate);
                // console.log(dateMoment.format("YYYY MM DD") + ' - ' + currentDateMoment.format("YYYY MM DD") + ': ' + (dateMoment.diff(currentDateMoment) >= 0 || dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")));
                // console.log(currentDateMoment.format("YYYY MM DD"));
                return (
                    dateMoment.diff(currentDateMoment) >= 0 ||
                    dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")
                ) && date.bookable;
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
            getData: function(from = null){
                // return;
                if(this.componentApp == null)
                    this.componentApp = this.getParentComponentByName(this, 'app');
                
                this.componentApp.getData(
                    moment(this.firstCalendarDate).format('DD-MM-YYYY'),
                    moment(this.lastCalendarDate).format('DD-MM-YYYY'),
                    (response) => {
                        this.dates = response.data.data;
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
            // setRange: function(){
            //     // this.currentViewIdx = parseInt(this.currentDateObj.getMonth()) + 1;
            // 
            //     let firstMonthDay = new Date(this.currentDat.getFullYear(), this.currentDat.getMonth(), 1);
            //     let lastMonthDay = new Date(this.currentDateObj.getFullYear(), this.currentDateObj.getMonth() + 1, 0);
            //     let firstMonthDayWeekday = firstMonthDay.getDay();
            //     let totalDaysInMonth = lastMonthDay.getDate();                    
            // 
            //     let startOffset;
            //     if(firstMonthDayWeekday == 1){
            //         startOffset = 7;
            //     }else if(firstMonthDayWeekday == 2){
            //         startOffset = 8;
            //     }else if(firstMonthDayWeekday == 3){
            //         startOffset = 9;
            //     }else if(firstMonthDayWeekday == 4){
            //         startOffset = 3;
            //     }else if(firstMonthDayWeekday == 5){
            //         startOffset = 4;
            //     }else if(firstMonthDayWeekday == 6){
            //         startOffset = 5;
            //     }else if(firstMonthDayWeekday == 0){
            //         startOffset = 6;
            //     }
            // 
            //     let firstDate = new Date(firstMonthDay);
            // 
            //     firstDate.setDate(firstDate.getDate() - startOffset);
            // 
            //     let lastDate = new Date(firstDate);
            //     lastDate.setDate(lastDate.getDate() + 41);
            // 
            //     this.range = {
            //         first_date: firstDate,
            //         last_date: lastDate,
            //     }
            // },
        },
        components: {
            Navigation,
            MonthCell,
            ModalAuthContent,
            ModalBookContent,
            ModalCancelBookContent
        },
        watch: {
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
    #viewDropdown{
        .dropdown-toggle{
            text-transform: capitalize;
        }
        .dropdown-item{
            text-transform: capitalize;
        }
    }
    
    .handles{
        .left-part{
            button{
                // display: none;
                &:nth-child(1){
                    border-radius: .2rem 0 0 .2rem;
                }
                &:nth-child(2){
                    border-radius: 0 .2rem .2rem 0;
                }
                &:nth-child(3){
                    margin-left: 10px;
                }
            }
        }
    }
    .for-table{
        padding-top: 10px!important;
    }
    table{
        width: 100%;
        /* margin-top: 20px!important; */
    }
    table th{
        text-align: center;
    }
    table td, table th{
        border: 1px solid #ccc;
    }
    table td{
        width: 14.25%;
        vertical-align: top;
    }
    table td .cont{
        min-height: 80px!important;
        position: relative;
    }
    table td .day{
        position: absolute;
        top: 0px;
        left: 0px;
        font-weight: bold;
    }
    table tr.divider td{
        height: 10px;
        background-color: #6c757d;
    }
    .day.not-period{
        color: #999;
    }
    .current-day{
        background-color: rgba(8,232,222, 0.3); 
    }
    .booking{
        position: absolute;
        bottom: 0px;
        right: 0px;
    }
    // .current-month{
    //     text-align: center;
    // }
</style>