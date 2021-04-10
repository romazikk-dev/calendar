<template>
    <div>
        <div class="handles">
            <button @click.prevent="previous" type="button" class="btn btn-sm btn-primary float-left" :disabled="!canGoToPrevious">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
                Previous
            </button>
            <button @click.prevent="next" type="button" class="btn btn-sm btn-primary float-right">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
            <div class="current-month">
                {{calendarTile}}
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="for-table">
            <table>
                <thead>
                    <tr>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="dates" v-for="i in 6">
                        <td v-for="k in 7">
                            <div class="cont" :class="{'current-day': isCurrentDate(i,k)}">
                                <div class="day" :class="{'not-period': currentCalendarMonth != getDate(i,k,'month')}">
                                    {{dates[(((i*7) + k))-8].day}}
                                </div>
                                <month-cell v-if="notPast(i,k)" @open-modal="openModal($event,i,k)" :free="getDate(i,k,'free')"></month-cell>
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
                <modal-book-content v-if="$parent.isAuth()"
                    :user-id="userId"
                    :book-date="bookDate"
                    :book-time-period="bookTimePeriod"></modal-book-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-else :user-id="userId"></modal-auth-content>
            </div>
        </div>
        
    </div>
</template>

<script>
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    export default {
        mounted() {
            this.setDates(moment(new Date()).startOf('month').toDate());
            // console.log(this.range.first_date);
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
        props: ['userId','search'],
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
                // firstMonthDate: moment(this.currentDateObj).startOf('month').toDate(),
            };
        },
        computed: {
            calendarTile: function () {
                return moment(this.firstMonthDate).format('MMMM YYYY');
            },
            canGoToPrevious: function () {
                let firstMonthDay = moment(this.firstMonthDate).startOf('month');
                let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
                return firstMonthDay.isAfter(currentDateFirstMonthDay);
            }
        },
        methods: {
            next: function(){
                console.log('next');
                var dateOfNextMonth = moment(this.firstMonthDate).add(1, 'M');
                // console.log(dateOfNextMonth.toDate());
                this.setDates(dateOfNextMonth.toDate());
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                    
                // console.log('previous');
                var dateOfPreviousMonth = moment(this.firstMonthDate).subtract(1, 'M');
                this.setDates(dateOfPreviousMonth.toDate());
                this.getData();
            },
            openModal: function(itm,i,k){
                // console.log(itm);
                // $parent.isAuth();
                // console.log(this.$parent.isAuth());
                this.bookDate = this.getDate(i,k);
                this.bookTimePeriod = itm;
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
            getData: function(){
                // console.log(JSON.parse(JSON.stringify(this.range)));
                // routes.calendar.booking.range
                let url = routes.calendar.booking.range;
                // url = url.replace(':start', moment(this.range.first_date).format('DD-MM-YYYY'));
                // console.log(moment(this.range.first_date).format('DD-MM-YYYY'));
                // console.log(moment(this.range.last_date).format('DD-MM-YYYY'));
                
                // return;
                
                url = url.replace(':start', moment(this.firstCalendarDate).format('DD-MM-YYYY'));
                url = url.replace(':end', moment(this.lastCalendarDate).format('DD-MM-YYYY'));
                
                url += '?' + this.search;
                
                // return;
                // url = url.replace(':start', '28-03-2021');
                // url = url.replace(':end', '08-05-2021');
                
                // console.log(url);
                // console.log(routes.calendar.booking.range);
                axios.get(url)
                .then((response) => {
                    // handle success
                    this.dates = response.data.data;
                    // console.log(response.data[0]);
                    // console.log(JSON.parse(JSON.stringify(this.dates)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
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
            MonthCell,
            ModalAuthContent,
            ModalBookContent
        },
        watch: {
            search: function () {
                // console.log(this.search);
                this.getData();
            }
        }
    }
</script>

<style scoped>
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
    .current-month{
        text-align: center;
    }
</style>