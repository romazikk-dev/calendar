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
            <div class="calendar-title">
                {{calendarTile}}
            </div>
        </div>
        <div class="clearfix"></div>
        
        <div class="for-table">
            <table>
                <tbody>
                    <template v-for="date in dates" v-if="date.bookable && notPast(date)">
                    <!-- <template v-for="date in dates"> -->
                        
                        <tr class="day-title" :class="{'current-date': isCurrentDate(date)}">
                            <td colspan="2">
                                <span>{{getWeekdayTitleFromDateItem(date)}}</span>
                                <span v-if="isCurrentDate(date)" class="badge badge-info">Today</span>
                                <span class="item-date">{{getDayTitleFromDateItem(date)}}</span>
                            </td>
                        </tr>
                        
                        <tr class="hour-item" v-for="item in date.items" :class="'hour-item-' + item.type">
                            <td>
                                <span>{{item.from + ' - ' + item.to}}</span>
                            </td>
                            <td>
                                <div v-if="item.type == 'free'" class="for-itm for-itm-free">
                                    <span class="background-text">FREE TIME</span>
                                    <span class="circle free"></span>
                                    Free time
                                    <a href="#" @click.prevent="openModal(date, item)" class="btn-book">Book</a>
                                    <div v-if="item.not_approved_bookings" class="not-approved-bookings">
                                        <div v-for="notApprovedItem in item.not_approved_bookings">
                                            <span class="circle not-approved"></span>
                                            {{notApprovedItem.booking.template_without_user_scope.title}}
                                            <a href="#" @click.prevent="cancelBook(notApprovedItem.booking)" class="btn-book">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="item.type == 'booked'" class="for-itm for-itm-booked">
                                    <span class="background-text">YOUR BOOKED EVENT</span>
                                    <span class="circle booked"></span>
                                    <b>{{item.booking.template_without_user_scope.title}}</b>
                                    <a href="#" @click.prevent="cancelBook(item.booking)" class="btn-book">Cancel</a>
                                </div>
                            </td>
                        </tr>
                        
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="bookModal">
            <div class="modal-dialog">
                <!-- ModalAuthContent -->
                <modal-book-content v-if="$parent.isAuth()"
                    @booked="onBooked($event)"
                    :user-id="userId"
                    :book-date="bookDate"
                    :book-time-period="bookTimePeriod"></modal-book-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-else :user-id="userId"></modal-auth-content>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="cancelBookModal">
            <div class="modal-dialog">
                <modal-cancel-book-content @canceled="onCanceled($event)" :booking="cancelBookData"></modal-cancel-book-content>
            </div>
        </div>
        
    </div>
</template>

<script>
    import WeekTimeCell from "./WeekTimeCell.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    // import ModalRequestedBookingsContent from "./ModalRequestedBookingsContent.vue";
    import WeekRequestedBookedCell from "./WeekRequestedBookedCell.vue";
    export default {
        mounted() {
            let initDate = moment(new Date()).startOf('week').toDate();
            this.setDates(initDate);
            // console.log(this.range.first_date);
            // console.log(moment(this.range.first_date).format('DD-MM-YYYY'));
            // console.log(this.currenyViewIdx);
            
            // console.log(initDate);
            // console.log(this.currentDateMoment.format('YYYY MM DD'));
            // console.log(this.currentDateMoment.format('YYYY-MM-DD HH:mm:ss'));
            
            // console.log(this.currentDate);
            // console.log(this.firstMonthDate);
            // console.log(this.lastMonthDate);
            // console.log(this.firstCalendarDate);
            // console.log(this.lastCalendarDate);
            
            // setTimeout(() => {
            //     console.log(moment(this.currentDate).format('YYYY MM DD'));
            //     console.log(this.currentDateMoment.format('YYYY-MM-DD HH:mm:ss'));
            // }, 10);
            
            let interval = setInterval(() => {
                if(this.search != null){
                    // console.log(11111);
                    clearInterval(interval);
                    this.getData();
                }
            }, 300);
            
            // let intervalDates = setInterval(() => {
            //     if(this.dates != null){
            //         clearInterval(intervalDates);
            //         this.placeItems();
            //         this.regModalOpenButtons();
            //     }
            // }, 300);
            
            // this.regModalOpenButtons();
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
            
            // console.log(JSON.parse(JSON.stringify([dateItem, hourItem])));
            // console.log(this.firstMonthDate);
            // console.log(this.weekdayOfCurrentDate);
        },
        props: ['userId','search'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                weekdays: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                
                dates: null,
                bookDate: null,
                bookTimePeriod: null,
                requestedBookings: null,
                currentDate: new Date(),
                // currentDateMoment: null,
                yearOfCurrentDate: null,
                monthOfCurrentDate: null,
                dayOfCurrentDate: null,
                weekdayOfCurrentDate: null,
                firstWeekday: null,
                lastWeekday: null, 
                
                // mondayDate: null,
                // tuesdayDate: null,
                // wednesdayDate: null,
                // thursdayDate: null,
                // fridayDate: null,
                // saturdayDate: null,
                // sundayDate: null,
                
                workHours: null,
                bussinessHours: null,
                
                hours: [
                    {
                        hour: '00',
                        minute: '00',
                    },
                    {
                        hour: '01',
                        minute: '00',
                    },
                    {
                        hour: '02',
                        minute: '00',
                    },
                    {
                        hour: '03',
                        minute: '00',
                    },
                    {
                        hour: '04',
                        minute: '00',
                    },
                    {
                        hour: '05',
                        minute: '00',
                    },
                    {
                        hour: '06',
                        minute: '00',
                    },
                    {
                        hour: '07',
                        minute: '00',
                    },
                    {
                        hour: '08',
                        minute: '00',
                    },
                    {
                        hour: '09',
                        minute: '00',
                    },
                    {
                        hour: '10',
                        minute: '00',
                    },
                    {
                        hour: '11',
                        minute: '00',
                    },
                    {
                        hour: '12',
                        minute: '00',
                    },
                    {
                        hour: '13',
                        minute: '00',
                    },
                    {
                        hour: '14',
                        minute: '00',
                    },
                    {
                        hour: '15',
                        minute: '00',
                    },
                    {
                        hour: '16',
                        minute: '00',
                    },
                    {
                        hour: '17',
                        minute: '00',
                    },
                    {
                        hour: '18',
                        minute: '00',
                    },
                    {
                        hour: '19',
                        minute: '00',
                    },
                    {
                        hour: '20',
                        minute: '00',
                    },
                    {
                        hour: '21',
                        minute: '00',
                    },
                    {
                        hour: '22',
                        minute: '00',
                    },
                    {
                        hour: '23',
                        minute: '00',
                    },
                ],
                // currentCalendarMonth: null,
                // firstMonthDate: null,
                // lastMonthDate: null,
                // firstCalendarDate: null,
                // lastCalendarDate: null,
                cancelBookData: null,
                
                
                // firstMonthDate: moment(this.currentDateObj).startOf('month').toDate(),
            };
        },
        computed: {
            calendarTile: function () {
                if(this.firstWeekday == null)
                    return '';
                
                let firstWeekdayMoment = moment(this.firstWeekday);
                let lastWeekdayMoment = moment(this.lastWeekday);
                
                let firstWeekdayMonth = this.firstWeekday.getMonth();
                let lastWeekdayMonth = this.lastWeekday.getMonth();
                
                if(firstWeekdayMonth == lastWeekdayMonth){
                    let firstWeekdayDay = firstWeekdayMoment.format('DD');
                    let lastWeekdayDay = lastWeekdayMoment.format('DD');
                    let monthTitle = firstWeekdayMoment.format('MMMM');
                    return firstWeekdayDay + ' - ' + lastWeekdayDay + ' ' + monthTitle;
                }
                
                let firstWeekdayMonthTitle = firstWeekdayMoment.format('DD MMMM');
                let lastWeekdayMonthTitle = lastWeekdayMoment.format('DD MMMM');
                return firstWeekdayMonthTitle + ' - ' + lastWeekdayMonthTitle;
            },
            canGoToPrevious: function () {
                if(this.firstWeekday == null)
                    return '';
                
                let firstWeekDayOfCurrentDate = moment(this.currentDate).startOf('week').add(1, 'days');
                let firstWeekdayMoment = moment(this.firstWeekday);
                return firstWeekdayMoment.diff(firstWeekDayOfCurrentDate) > 0;
            }
        },
        methods: {
            notPast: function(date){
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day + ' ' + date.start + ':00');
                let currentDateMoment = moment(this.currentDate);
                
                return dateMoment.diff(currentDateMoment) >= 0 ||
                dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD");
            },
            getWeekdayTitleFromDateItem: function (date) {
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day);
                return this.weekdays[dateMoment.isoWeekday() - 1];
            },
            getDayTitleFromDateItem: function (date) {
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day);
                return dateMoment.format('MMMM DD, YYYY');
            },
            // getBussinessHourPerWeekday: function(i){
            //     let bussinessHours = this.bussinessHours[i];
            //     return bussinessHours.start + ' - ' + bussinessHours.end;
            // },
            cancelBook: function(event){
                this.cancelBookData = event;
                $('#cancelBookModal').modal('show');
                // console.log(event);
            },
            onBooked: function(){
                this.getData();
            },
            onCanceled: function(){
                this.getData('cancel_book');
            },
            next: function(){
                let dateOfNextWeek = moment(this.firstWeekday).add(7, 'days').toDate();
                this.setDates(dateOfNextWeek);
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                
                let dateOfPreviousWeek = moment(this.firstWeekday).subtract(7, 'days').toDate();
                this.setDates(dateOfPreviousWeek);
                this.getData();
            },
            openModal: function(dateItem, hourItem){
                this.bookDate = dateItem;
                this.bookTimePeriod = hourItem;
                
                $('#bookModal').modal('show');
            },
            isCurrentDate: function(date){
                let currentDateMoment = moment(this.currentDate);
                return currentDateMoment.format('YYYYMMDD') == date.year + date.month + date.day;
            },
            getData: function(from = null){
                // return;
                
                // console.log(this.currentDateMoment.format('YYYY MM DD'));
                
                let url = routes.calendar.booking.range;
                
                url = url.replace(':start', moment(this.firstWeekday).format('DD-MM-YYYY'));
                url = url.replace(':end', moment(this.lastWeekday).format('DD-MM-YYYY'));
                
                url += '?' + this.search;
                
                // return;
                axios.get(url)
                .then((response) => {
                    // handle success
                    // console.log(response.data.data);
                    this.dates = response.data.data;
                    this.bussinessHours = response.data.business_hours;
                    // this.setWorkHours();
                    // console.log(JSON.parse(JSON.stringify(4444444444444444)));
                    // console.log(this.currentDateMoment.format('YYYY MM DD'));
                    // setTimeout(() => {
                    //     // this.placeItems();
                    //     console.log(this.currentDateMoment.format('YYYY MM DD'));
                    // }, 100);
                    console.log(JSON.parse(JSON.stringify(this.dates)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    if(from == 'cancel_book'){
                        console.log('from: cancel_book');
                        $('#cancelBookModal').modal('hide');
                    }
                });
            },
            setDates: function(firstCalendarMonthDate){
                if(this.yearOfCurrentDate == null || this.monthOfCurrentDate == null ||
                this.dayOfCurrentDate == null || this.weekdayOfCurrentDate == null){
                    let currentDateMoment = moment(this.currentDate);
                    if(this.yearOfCurrentDate == null)
                        this.yearOfCurrentDate = currentDateMoment.format('YYYY');
                    if(this.monthOfCurrentDate == null)
                        this.monthOfCurrentDate = currentDateMoment.format('MM');
                    if(this.dayOfCurrentDate == null)
                        this.dayOfCurrentDate = currentDateMoment.format('DD');
                    if(this.weekdayOfCurrentDate == null)
                        this.weekdayOfCurrentDate = currentDateMoment.day();
                }
                
                // console.log(this.weekdayOfCurrentDate);
                
                this.firstWeekday = moment(firstCalendarMonthDate).startOf('week').add(1,'days').toDate();
                this.lastWeekday = moment(firstCalendarMonthDate).endOf('week').add(1,'days').toDate();
            },
        },
        components: {
            MonthCell,
            ModalAuthContent,
            ModalBookContent,
            WeekTimeCell,
            // ModalRequestedBookingsContent,
            WeekRequestedBookedCell,
            ModalCancelBookContent
        },
        watch: {
            search: function () {
                // console.log(this.search);
                this.getData();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .calendar-title{
        text-align: center;
    }
    .for-table{
        padding-top: 10px!important;
        // font-size: 14px;
        table{
            width: 100%;
            // min-width: 700px;
            tr{
                &.day-title{
                    // background-color: #6c757d;
                    background-color: #3b3f4b;
                    // background-color: #999;
                    // background-color: #6c757d;
                    // color: white;
                    color: #f9f9f9;
                    // &.current-date{
                    //     background-color: rgba(8,232,222, 0.3)!important;
                    // }
                    // background-color: hsla(0,0%,81.6%,.3);
                    td{
                        // padding-top: 10px;
                        // padding-bottom: 5px;
                        font-weight: bold;
                        // border: 0px;
                            span{
                                // float: left;
                                &.item-date{
                                    float: right;
                                    padding-right: 10px;
                                }
                            }
                        // }
                        // &:last-child{
                        //     text-align: right;
                        // }
                    }
                }
                &.hour-item{
                    .background-text{
                        position: absolute;
                        top: 5px;
                        right: 10px;
                        font-weight: bold;
                        color: #ccc;
                        cursor: default;
                    }
                    &.hour-item-booked{
                        td{
                            background-color: rgba(114,51,128, 0.05)!important;
                            // color: white;
                        }
                    }
                    &.hour-item-free{
                        td{
                            background-color: rgba(144,238,144, 0.05)!important;
                        }
                    }
                    &:hover{
                        td{
                            background-color: #f1f1f1;
                        }
                    }
                    .circle{
                        width: 10px;
                        height: 10px;
                        display: inline-block;
                        background-color: red;
                        border-radius: 50%;
                        margin-right: 5px;
                        &.free{
                            background-color: rgba(144,238,144, 1)!important;
                        }
                        &.booked{
                            background-color: rgba(114,51,128, 1)!important;
                        }
                        &.not-approved{
                            background-color: #cf582c!important;
                        }
                    }
                    .btn-book{
                        margin-left: 10px;
                        display: inline-block;
                    }
                    .not-approved-bookings{
                        padding-left: 40px;
                    }
                    td{
                        &:first-child{
                            // padding-top: 10px;
                            font-weight: bold;
                            color: #999;
                            // font-size: 14px;
                        }
                    }
                }
                td{
                    border: 1px solid #ccc;
                    vertical-align: top;
                    position: relative;
                    transition: background-color 0.3s ease;
                    // min-height: 40px!important;
                    // padding-left: 10px;
                    // .contt{
                    //     min-height: 40px!important;
                    // }
                    padding: 6px 10px;
                    &:first-child{
                        width: 120px;
                        border-right: 0px!important;
                        padding-right: 0px;
                    }
                    &:last-child{
                        border-left: 0px!important;
                    }
                    
                }
            }
        }
    }
    
    .current-day{
        background-color: rgba(8,232,222, 0.3)!important; 
    }
</style>