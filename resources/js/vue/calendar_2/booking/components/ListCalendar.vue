<template>
    <div>
        <navigation :can-go-to-previous="canGoToPrevious"
            :calendar-title="calendarTitle"
            @previous="previous"
            @next="next"
            @today="today"></navigation>
        
        <div class="for-table">
            <table>
                <tbody>
                    <!-- <template v-for="date in dates" v-if="date.bookable && !date.is_weekend && notPast(date)"> -->
                    <template v-for="date in dates" v-if="date.items && notPast(date)">
                        <tr class="day-title" :class="{'current-date': isCurrentDate(date)}">
                            <td colspan="2">
                                <span>{{getWeekdayTitleFromDateItem(date)}}</span>
                                <span v-if="isCurrentDate(date)" class="badge badge-info">
                                    {{capitalizeFirstLetter(getText('text.today'))}}
                                </span>
                                <span class="item-date">{{getDayTitleFromDateItem(date)}}</span>
                            </td>
                        </tr>
                        
                        <tr class="hour-item" v-for="item in date.items" :class="'hour-item-' + item.type">
                            <td>
                                <span>{{item.from + ' - ' + item.to}}</span>
                            </td>
                            <td>
                                <div v-if="item.type == 'free'" class="for-itm for-itm-free">
                                    <span class="background-text">
                                        {{getText('text.free_time').toUpperCase()}}
                                    </span>
                                    <span class="circle free"></span>
                                    {{capitalizeFirstLetter(getText('text.free_time'))}}
                                    <a href="#" @click.prevent="openModal(date, item)" class="btn-book">
                                        {{capitalizeFirstLetter(getText('text.book'))}}
                                    </a>
                                    <div v-if="item.not_approved_bookings" class="not-approved-bookings">
                                        <div v-for="notApprovedItem in item.not_approved_bookings">
                                            <span class="circle not-approved"></span>
                                            {{notApprovedItem.template_without_user_scope.title}}
                                            <a href="#" @click.prevent="cancelBook(notApprovedItem)" class="btn-book">
                                                {{capitalizeFirstLetter(getText('text.cancel'))}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="item.type == 'event'" class="for-itm for-itm-booked">
                                    <span class="background-text">YOUR BOOKED EVENT</span>
                                    <span class="circle booked"></span>
                                    <b>{{item.template_without_user_scope.title}}</b>
                                    <a href="#" @click.prevent="cancelBook(item)" class="btn-book">Cancel</a>
                                </div>
                            </td>
                        </tr>
                        
                    </template>
                    <template v-if="empty">
                        <tr>
                            <td colspan="2" class="text-center">
                                No free time available
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
                    :book-date="bookDate"
                    :book-time-period="bookTimePeriod"></modal-book-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-else></modal-auth-content>
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
    import Navigation from "./Navigation.vue";
    import WeekTimeCell from "./WeekTimeCell.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    // import ModalRequestedBookingsContent from "./ModalRequestedBookingsContent.vue";
    import WeekRequestedBookedCell from "./WeekRequestedBookedCell.vue";
    export default {
        name: 'listCalendar',
        mounted() {
            let initDate = moment(this.startDate).startOf('week').toDate();
            this.setDates(initDate);
            // console.log(moment(this.range.first_date).format('DD-MM-YYYY'));
            
            this.getData();
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
        },
        props: ['startDate'],
        data: function(){
            return {
                empty: true,
                
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
                
                workHours: null,
                bussinessHours: null,
                
                cancelBookData: null,
                
                componentApp: null,
            };
        },
        computed: {
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            search: function () {
                return this.$store.getters['filters/urlSearchPath'];
            },
            calendarTitle: function () {
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
                let start = typeof date.start === 'undefined' || date.start === null ? '00:00' : date.start;
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day + ' ' + start + ':00');
                let currentDateMoment = moment(this.currentDate);
                
                return dateMoment.diff(currentDateMoment) >= 0 ||
                dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD");
            },
            getWeekdayTitleFromDateItem: function (date) {
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day);
                return this.weekdaysList[dateMoment.isoWeekday() - 1];
            },
            getDayTitleFromDateItem: function (date) {
                let dateMoment = moment(date.year + '-' + date.month + '-' + date.day);
                let monthKey = 'text.' + dateMoment.format('MMMM').toLowerCase().trim();
                let month = this.capitalizeFirstLetter(this.getText(monthKey));
                return dateMoment.format('[' + month + '] DD, YYYY');
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
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                
                let dateOfPreviousWeek = moment(this.firstWeekday).subtract(7, 'days').toDate();
                this.setDates(dateOfPreviousWeek);
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
                this.getData();
            },
            today: function(){
                this.setDates(moment(new Date()).startOf('week').toDate());
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
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
                if(this.componentApp == null)
                    this.componentApp = this.getParentComponentByName(this, 'app');
                
                this.componentApp.getData(
                    moment(this.firstWeekday).format('YYYY-MM-DD'),
                    moment(this.lastWeekday).format('YYYY-MM-DD'),
                    (response) => {
                        this.dates = response.data.data;
                        this.bussinessHours = response.data.business_hours;
                        // console.log(JSON.parse(JSON.stringify(this.dates)));
                    },
                    () => {},
                    () => {
                        $('#cancelBookModal').modal('hide');
                    },
                );
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
            Navigation,
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
            },
            dataUpdater: function () {
                this.getData();
            },
            dates: function () {
                if(this.dates == null)
                    return false;
                let itemsCount = 0;
                this.dates.forEach((date, i) => {
                    // if(date.bookable && !date.is_weekend && this.notPast(date))
                    if(this.notPast(date))
                        itemsCount++;
                });
                // console.log(itemsCount);
                // if(itemsCount > 0)
                this.empty = itemsCount > 0 ? false : true;
            },
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