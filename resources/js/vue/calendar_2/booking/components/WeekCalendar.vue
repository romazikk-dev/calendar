<template>
    <div>
        <navigation :can-go-to-previous="canGoToPrevious"
            :calendar-title="calendarTitle"
            @previous="previous"
            @next="next"
            @today="today"></navigation>
            
        <div class="for-table">
            
            <table cellspacing="0">
                <thead>
                    <tr>
                        <!-- <th></th> -->
                        <th :class="{'current-day': isCurrentDate(1)}">{{weekdaysList[0]}}<span>{{mondayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(2)}">{{weekdaysList[1]}}<span>{{tuesdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(3)}">{{weekdaysList[2]}}<span>{{wednesdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(4)}">{{weekdaysList[3]}}<span>{{thursdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(5)}">{{weekdaysList[4]}}<span>{{fridayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(6)}">{{weekdaysList[5]}}<span>{{saturdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(7)}">{{weekdaysList[6]}}<span>{{sundayDate}}</span></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr class="divider">
                        <td :class="{'current-day': isCurrentDate(1)}"></td>
                        <td :class="{'current-day': isCurrentDate(2)}"></td>
                        <td :class="{'current-day': isCurrentDate(3)}"></td>
                        <td :class="{'current-day': isCurrentDate(4)}"></td>
                        <td :class="{'current-day': isCurrentDate(5)}"></td>
                        <td :class="{'current-day': isCurrentDate(6)}"></td>
                        <td :class="{'current-day': isCurrentDate(7)}"></td>
                    </tr> -->
                    <tr>
                        <!-- <td>dasd</td> -->
                        <td v-for="i in 7" :data-weekday="i" :class="{'current-day': isCurrentDate(i)}">
                            <week-requested-booked-cell
                                v-if="weekDayNotPast(i)"
                                @cancel="cancelBook($event)"
                                :dates="dates"
                                :weekday-index="i"></week-requested-booked-cell>
                        </td>
                    </tr>
                    <tr class="divider">
                        <td :class="{'current-day': isCurrentDate(1)}"></td>
                        <td :class="{'current-day': isCurrentDate(2)}"></td>
                        <td :class="{'current-day': isCurrentDate(3)}"></td>
                        <td :class="{'current-day': isCurrentDate(4)}"></td>
                        <td :class="{'current-day': isCurrentDate(5)}"></td>
                        <td :class="{'current-day': isCurrentDate(6)}"></td>
                        <td :class="{'current-day': isCurrentDate(7)}"></td>
                    </tr>
                </tbody>
            </table>
        
            <table cellspacing="0">
                <tbody>
                    <template v-if="dates" v-for="i in workHours">
                        <tr>
                            <td v-for="k in 7"
                                class="hour-cell"
                                :data-weekday="k"
                                :data-hour="hoursList[i].hour">
                                    <div class="faded-time">
                                        {{ hoursList[i].hour }}:{{ hoursList[i].minute }}
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
                    :book-date="bookDate"
                    :book-time-period="bookTimePeriod"></modal-book-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-else></modal-auth-content>
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
    import WeekTimeCell from "./WeekTimeCell.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    // import ModalRequestedBookingsContent from "./ModalRequestedBookingsContent.vue";
    import WeekRequestedBookedCell from "./WeekRequestedBookedCell.vue";
    export default {
        name: 'weekCalendar',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(434343434)));
            // console.log(JSON.parse(JSON.stringify(this.workHours)));
            // this.setDates(moment(new Date()).startOf('week').toDate());
            this.setDates(moment(this.startDate).startOf('week').toDate());
            
            // let interval = setInterval(() => {
            //     if(this.search != null){
            //         // console.log(11111);
            //         clearInterval(interval);
            //         this.getData();
            //         // console.log(JSON.parse(JSON.stringify(434343434)));
            //         // console.log(JSON.parse(JSON.stringify(this.workHours)));
            //     }
            // }, 300);
            
            this.getData();
            
            this.regModalOpenButtons();
            
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
        },
        updated: function () {
            let _this = this;
            // this.$nextTick(function(){
                let interval = setInterval(function(){
                    if(_this.dates !== null){
            	       _this.placeItems();
                       clearInterval(interval);
                    }
                }, 100);
            // });
        },
        props: ['startDate'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                
                dates: null,
                bookDate: null,
                bookTimePeriod: null,
                requestedBookings: null,
                currentDate: new Date(),
                yearOfCurrentDate: null,
                monthOfCurrentDate: null,
                dayOfCurrentDate: null,
                weekdayOfCurrentDate: null,
                firstWeekday: null,
                lastWeekday: null,
                
                mondayDate: null,
                tuesdayDate: null,
                wednesdayDate: null,
                thursdayDate: null,
                fridayDate: null,
                saturdayDate: null,
                sundayDate: null,
                
                workHours: null,
                bussinessHours: null,
                
                cancelBookData: null,
                
                componentApp: null,
                // firstMonthDate: moment(this.currentDateObj).startOf('month').toDate(),
            };
        },
        computed: {
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            search: function () {
                return this.$store.getters['filters/urlSearchPath'];
            },
            weekDayNotPast: function(){
                return (i) => {
                    if(this.firstWeekday == null)
                        return false;
                    let firstWeekdayMoment = moment(this.firstWeekday);
                    let currentDateMoment = moment(this.currentDate);
                    return i >= this.weekdayOfCurrentDate || currentDateMoment.diff(firstWeekdayMoment) <= 0;
                }
            },
            calendarTitle: function () {
                if(this.firstWeekday == null)
                    return '';
                
                // console.log(this.lastWeekday);
                // return this.firstWeekday.getMonth();
                let firstWeekdayMonth = this.firstWeekday.getMonth();
                let lastWeekdayMonth = this.lastWeekday.getMonth();
                if(firstWeekdayMonth == lastWeekdayMonth){
                    let firstWeekdayDay = moment(this.firstWeekday).format('DD');
                    let lastWeekdayDay = moment(this.lastWeekday).format('DD');
                    let key = 'text.' + moment(this.firstWeekday).format('MMMM').toLowerCase().trim();
                    let monthTitle = this.capitalizeFirstLetter(this.getText(key));
                    return firstWeekdayDay + ' - ' + lastWeekdayDay + ' ' + monthTitle;
                }else{
                    let firstWeekdayMoment = moment(this.firstWeekday);
                    let lastWeekdayMoment = moment(this.lastWeekday);
                    let firstMonthKey = 'text.' + firstWeekdayMoment.format('MMMM').toLowerCase().trim();
                    let lastMonthKey = 'text.' + lastWeekdayMoment.format('MMMM').toLowerCase().trim();
                    let firstWeekdayMonthTitle = moment(this.firstWeekday).format('D ') +
                        this.capitalizeFirstLetter(this.getText(firstMonthKey));
                    let lastWeekdayMonthTitle = moment(this.lastWeekday).format('D ') +
                        this.capitalizeFirstLetter(this.getText(lastMonthKey));
                    return firstWeekdayMonthTitle + ' - ' + lastWeekdayMonthTitle;
                }
                // return moment(this.firstMonthDate).format('MMMM YYYY');
            },
            canGoToPrevious: function () {
                let firstWeekDayOfCurrentDate = moment(this.currentDate).startOf('week');
                let firstWeekDay = moment(this.firstWeekday).startOf('week');
                // if(firstWeekDay)
                // let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
                return firstWeekDay.isAfter(firstWeekDayOfCurrentDate);
            }
        },
        methods: {
            getBussinessHourPerWeekday: function(i){
                let bussinessHours = this.bussinessHours[i];
                return bussinessHours.start + ' - ' + bussinessHours.end;
            },
            cancelBook: function(event){
                this.cancelBookData = event;
                // console.log(this.cancelBookData);
                $('#cancelBookModal').modal('show');
                // console.log(event);
            },
            onBooked: function(){
                this.getData();
            },
            // onCanceled: function(){
            //     this.getData('cancel_book');
            // },
            regModalOpenButtons: function(){
                $(document).off('click', '.free-calendar-item');
                $(document).on('click', '.free-calendar-item', (event) => {
                    // console.log(event);
                    let dateItemIndex = $(event.target).attr('date-item-index');
                    let hourItemIndex = $(event.target).attr('hour-item-index');
                    
                    // console.log(dateItemIndex);
                    // console.log(hourItemIndex);
                    
                    this.openModal(dateItemIndex, hourItemIndex);
                });
            },
            placeItems: function(){
                // console.log(JSON.parse(JSON.stringify(this.workHours)));
                // return;
                // console.log(555555555555555555555555555555);
                let _this = this;
                
                $('.calendar-item').remove();
                
                this.dates.forEach((dateItem, i) => {
                    // let weekday = dateItem.weekday;
                    if(dateItem.is_weekend){
                        placeClosedDateItem(dateItem);
                        return;
                    }
                    
                    placeBeginningClosedDateItem(dateItem);
                    placeEndClosedDateItem(dateItem);
                    
                    dateItem.items.forEach((hourItem, ii) => {
                        if(this.notPast(i, ii))
                            // console.log(111);
                            placeItem(dateItem, hourItem, i, ii);
                        // console.log(JSON.parse(JSON.stringify(freeItem)));
                    });
                    
                });
                
                function placeBeginningClosedDateItem(dateItem){
                    // return;
                    let startDateItemMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + dateItem.start + ':00');
                    let startWorkHour = _this.workHours[0];
                    if(startWorkHour < 10)
                        startWorkHour = '0' + startWorkHour;
                    // let bussinessHours = _this.bussinessHours[dateItem.weekday - 1];
                    let startWorkHourMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + startWorkHour + ':00:00');
                    // console.log(startDateItemMoment.diff(startWorkHourMoment));
                    // console.log(JSON.parse(JSON.stringify(dateItem)));
                    if(startDateItemMoment.diff(startWorkHourMoment) > 0){
                        let fromHour = startWorkHourMoment.format('HH');
                        let fromMinute = startWorkHourMoment.format('mm');
                        
                        let toHour = startDateItemMoment.format('HH');
                        let toMinute = startDateItemMoment.format('mm');
                        
                        placeClosedItem(dateItem, fromHour, fromMinute, toHour, toMinute);
                    }
                }
                
                function placeEndClosedDateItem(dateItem){
                    // return true;
                    let endDateItemMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + dateItem.end + ':00');
                    let endWorkHour = _this.workHours[_this.workHours.length - 1] + 1;
                    if(endWorkHour < 10)
                        endWorkHour = '0' + endWorkHour;
                    // let bussinessHours = _this.bussinessHours[dateItem.weekday - 1];
                    let endWorkHourMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + endWorkHour + ':00:00');
                    // console.log(startDateItemMoment.diff(startWorkHourMoment));
                    // console.log(JSON.parse(JSON.stringify(dateItem)));
                    if(endDateItemMoment.diff(endWorkHourMoment) < 0){
                        let fromHour = endDateItemMoment.format('HH');
                        let fromMinute = endDateItemMoment.format('mm');
                        // console.log(fromHour);
                        let toHour = endWorkHourMoment.format('HH');
                        let toMinute = endWorkHourMoment.format('mm');
                        
                        placeClosedItem(dateItem, fromHour, fromMinute, toHour, toMinute);
                    }
                }
                
                function placeClosedItem(dateItem, fromHour, fromMinute, toHour, toMinute){
                    let weekDay = dateItem.weekday;
                    let beginCell = $('.hour-cell[data-hour="' + fromHour + '"][data-weekday="' + weekDay + '"]');
                    
                    let div = document.createElement('div');
                    $(div).addClass('calendar-item').addClass('closed-calendar-item');
                    $(div).html('Closed');
                    
                    let diffHours = parseInt(toHour) - parseInt(fromHour);
                    let cellHeight = beginCell.outerHeight();
                    
                    let divHeight = diffHours*cellHeight;
                    
                    if(parseInt(fromMinute) != 0){
                        let topShift = getPercValueOfMnutesToHour(fromMinute, cellHeight);
                        $(div).css({'top': topShift + 'px'});
                        divHeight -= topShift;
                    }
                    
                    if(parseInt(toMinute) != 0){
                        let bottomShift = getPercValueOfMnutesToHour(toMinute, cellHeight);
                        divHeight += bottomShift;
                    }
                    
                    divHeight = divHeight - 2;
                    $(div).css({'height':divHeight + 'px'});
                    
                    beginCell.prepend(div);
                }
                
                function placeClosedDateItem(dateItem){
                    let lengthCells = $('.hour-cell[data-weekday="' + dateItem.weekday + '"]').length;
                    let beginCell = $('.hour-cell[data-weekday="' + dateItem.weekday + '"]').eq(0);
                    
                    let div = document.createElement('div');
                    $(div).addClass('calendar-item').addClass('closed-calendar-item');
                    $(div).html('Closed');
                    
                    let cellHeight = beginCell.outerHeight();
                    let divHeight = lengthCells*cellHeight;
                    divHeight = divHeight - 2;
                    
                    $(div).css({'height':divHeight + 'px'});
                    
                    beginCell.prepend(div);
                }
                
                // function placeDayClosedItems(dateItem, dateItemIndex){
                //     let items = Object.values(dateItem.items);
                //     let firstItem = items[0];
                //     // console.log(firstItem);
                //     console.log(JSON.parse(JSON.stringify(dateItem)));
                // }
                
                function placeItem(dateItem, hourItem, dateItemIndex, hourItemIndex){
                    if(hourItem.type != 'free')
                        return;
                    
                    let fromArr = hourItem.from.split(':');
                    let fromHour = fromArr[0];
                    let fromMinute = fromArr[1];
                    let toArr = hourItem.to.split(':');
                    let toHour = toArr[0];
                    let toMinute = toArr[1];
                    let toHourInt = parseInt(toHour);
                    let lastWorkHour = _this.workHours.slice(-1).pop();
                    let weekDay = dateItem.weekday;
                    let beginCell = $('.hour-cell[data-hour="' + fromHour + '"][data-weekday="' + weekDay + '"]');
                    
                    let div = document.createElement('div');
                    $(div).attr('date-item-index', dateItemIndex);
                    $(div).attr('hour-item-index', hourItemIndex);
                    $(div).addClass('calendar-item').addClass('free-calendar-item');
                    
                    $(div).html('Free time:');
                    
                    let diffHours = parseInt(toHour) - parseInt(fromHour);
                    // let cellHeight = parseInt(beginCell.outerHeight());
                    let cellHeight = beginCell.outerHeight();
                    
                    // diffHours++;
                    // console.log(cellHeight);
                    
                    let divHeight = diffHours*cellHeight;
                    // let divHeight = cellHeight;
                    if(parseInt(fromMinute) != 0){
                        let topShift = getPercValueOfMnutesToHour(fromMinute, cellHeight);
                        $(div).css({'top': topShift + 'px'});
                        divHeight -= topShift;
                        // console.log(topShift);
                    }
                    
                    if(parseInt(toMinute) != 0){
                        let bottomShift = getPercValueOfMnutesToHour(toMinute, cellHeight);
                        divHeight += bottomShift;
                        // console.log(bottomShift);
                    }
                    
                    divHeight = divHeight - 2;
                    
                    $(div).css({'height':divHeight + 'px'});
                    
                    beginCell.prepend(div);
                }
                
                function getPercValueOfMnutesToHour(minutes, cellHeight){
                    // console.log(cellHeight);
                    // cellHeight++;
                    let oneMinutePerc = 60/100;
                    // let onePixelPerc = cellHeight/100;
                    // let minutesPercents = minutes/oneMinutePerc;
                    let minutesPercents = parseInt(minutes/oneMinutePerc);
                    
                    let shift = (cellHeight/100) * minutesPercents;
                    
                    return shift;
                }
            },
            next: function(){
                // console.log('next');
                let dateOfNextWeek = moment(this.firstWeekday).add(7, 'days');
                // console.log(dateOfNextWeek.toDate());
                this.setDates(dateOfNextWeek.toDate());
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
                // console.log(dateOfNextWeek.toDate());
                // console.log(this.firstWeekday);
                // console.log(this.lastWeekday);
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                
                let dateOfPreviousWeek = moment(this.firstWeekday).subtract(7, 'days');
                // console.log('previous');
                // var dateOfPreviousMonth = moment(this.firstMonthDate).subtract(1, 'M');
                this.setDates(dateOfPreviousWeek.toDate());
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
                this.getData();
            },
            today: function(){
                this.setDates(moment(new Date()).startOf('week').toDate());
                this.$parent.setStartDate('week', new Date(this.firstWeekday));
                this.getData();
            },
            openRequestedBookingsModal: function(i, ii){
                this.bookDate = this.dates[i];
                this.bookTimePeriod = this.bookDate.items[ii];
                if(typeof this.bookTimePeriod.not_approved_bookings != 'undefined' && this.bookTimePeriod.not_approved_bookings != null){
                    this.requestedBookings = Object.values(this.bookTimePeriod.not_approved_bookings);
                    // console.log(this.requestedBookings);
                    $('#requestedBookingsModal').modal('show');
                }
            },
            openModal: function(i, ii){
                // console.log(itm);
                // $parent.isAuth();
                // console.log(this.$parent.isAuth());
                
                this.bookDate = this.dates[i];
                this.bookTimePeriod = this.bookDate.items[ii];
                
                // console.log(JSON.parse(JSON.stringify(this.bookDate)));
                // console.log();
                // console.log(i);
                // console.log(JSON.parse(JSON.stringify(this.dates)));
                
                // return;
                // if(this.bookTimePeriod.type == 'free' && typeof this.bookTimePeriod.not_approved_bookings != 'undefined'){
                //     console.log(JSON.parse(JSON.stringify(this.bookTimePeriod.not_approved_bookings)));
                // }
                    
                // console.log(i);
                // console.log(ii);
                // console.log(dayItem);
                // console.log(freeItem);
                // this.bookDate = this.getDate(i,k);
                // this.bookTimePeriod = itm;
                $('#bookModal').modal('show');
            },
            notPast: function(dateItemIndex, hourItemIndex){
                // console.log(dayItemIndex, freeItemIndex);
                let dateItem = this.dates[dateItemIndex];
                let hourItem = dateItem.items[hourItemIndex];
                
                let dateItemMoment = moment(dateItem.year+'-'+dateItem.month+'-'+dateItem.day);
                let currentDateMoment = moment(this.currentDate);
                
                return (
                    dateItemMoment.diff(currentDateMoment) >= 0 ||
                    dateItemMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")
                ) && dateItem.bookable;
            },
            isCurrentDate: function(k){
                // console.log(moment(this.currentDate).startOf('week').format('DD'));
                // console.log(moment(this.firstWeekday).startOf('week').format('DD'));
                return this.weekdayOfCurrentDate == k &&
                moment(this.currentDate).startOf('week').format('DD') == moment(this.firstWeekday).startOf('week').format('DD');
                // return this.yearOfCurrentDate == this.getDate(i,k,'year') &&
                // this.monthOfCurrentDate == this.getDate(i,k,'month') &&
                // this.dayOfCurrentDate == this.getDate(i,k,'day');
            },
            getDate: function(i,k,type = null){
                let date = this.dates[(((i*7) + k))-8];
                // let month = this.dates[(((i*7) + k))-8].month;
                if(type != null)
                    return date[type];
                return date;
            },
            getData: function(from = null){
                if(this.componentApp == null)
                    this.componentApp = this.getParentComponentByName(this, 'app');
                
                // console.log(JSON.parse(JSON.stringify(434343434)));
                // console.log(JSON.parse(JSON.stringify(moment(this.firstWeekday).format('DD-MM-YYYY'))));
                
                this.componentApp.getData(
                    moment(this.firstWeekday).format('YYYY-MM-DD'),
                    moment(this.lastWeekday).format('YYYY-MM-DD'),
                    (response) => {
                        // alert(4444);
                        // handle success
                        // console.log(response.data.data);
                        this.dates = response.data.data;
                        // this.setWorkHours(response.data.start, response.data.end);
                        // console.log(response.data.business_hours);
                        this.bussinessHours = response.data.business_hours;
                        // alert(4444);
                        this.setWorkHours();
                        
                        // console.log(JSON.parse(JSON.stringify(434343434)));
                        // console.log(JSON.parse(JSON.stringify(this.workHours)));
                        // console.log(JSON.parse(JSON.stringify(this.bussinessHours)));
                        
                        // console.log(JSON.parse(JSON.stringify(4444444444444444)));
                        // setTimeout(() => {
                            // this.placeItems();
                        // }, 100);
                        // this.$nextTick(() => {
                        //     this.$nextTick(() => {
                        //         this.placeItems();
                        //     });
                        // });
                        // this.placeItems();
                        // console.log(response.data[0]);
                        // console.log(JSON.parse(JSON.stringify(this.dates)));
                    },
                    () => {},
                    () => {
                        $('#cancelBookModal').modal('hide');
                    },
                );
            },
            // setWorkHours: function(startHour, endHour){
            setWorkHours: function(){
                // alert(111);
                let start = null;
                let end = null;
                // let first;
                // return;
                // console.log(JSON.parse(JSON.stringify(777777777)));
                // console.log(this.bussinessHours);
                // alert(111);
                
                Object.values(this.bussinessHours).forEach((item, i) => {
                    if(typeof item.is_weekend !== 'undefined')
                        return;
                        
                    let start_hour = parseInt(item.start_hour.split(':')[0]);
                    let end_hour = parseInt(item.end_hour.split(':')[0]);
                    
                    if(start_hour < start || start === null){
                        start = start_hour;
                    }
                    
                    if(end_hour > end || end === null){
                        end = end_hour;
                    }
                    
                        
                    // if(end_hour < end)
                    //     end = end_hour;
                    
                    // alert(111);
                    // if(typeof item.start === 'undefined' || typeof item.end === 'undefined')
                    // if(start == null){
                    //     // start = item.start;
                    //     start = moment('1970-01-01 ' + item.start_hour + ':00');
                    //     first = true;
                    // }
                    // if(end == null){
                    //     end = moment('1970-01-01 ' + item.end_hour + ':00');
                    //     first = true;
                    // }
                    // if(typeof first != 'undefined' && first == true)
                    //     return;
                    // let itemStart = moment('1970-01-01 ' + item.start_hour + ':00');
                    // let itemEnd = moment('1970-01-01 ' + item.end_hour + ':00');
                    // if(itemStart.diff(start) < 0)
                    //     start = itemStart;
                    // if(itemStart.diff(start) >= 0)
                    //     end = itemEnd;
                });
                
                // alert(end);
                
                start = moment('1970-01-01 ' + ((start > 9) ? start : "0" + start) + ':00');
                end = moment('1970-01-01 ' + ((end > 9) ? end : "0" + end) + ':00');
                // alert(111);
                // 
                // return;
                // 
                let startHour = start.format('HH:mm');
                let endHour = end.format('HH:mm');
                // console.log(endHourArr);
                // 
                // // console.log(moment('1970-01-01 00:00:00').toDate());
                // // let start = '00:00'
                let startHourArr = startHour.split(':');
                let endHourArr = endHour.split(':');
                let startHourInt = parseInt(startHourArr[0]);
                let endHourInt = parseInt(endHourArr[0]);
                this.workHours = [];
                for(let i = startHourInt; i < endHourInt; i++){
                    this.workHours.push(i);
                }
                
                // alert(111);
                console.log(JSON.parse(JSON.stringify(777777777)));
                console.log(this.workHours);
                
                // console.log(JSON.parse(JSON.stringify(777777777)));
                // console.log(JSON.parse(JSON.stringify(this.workHours)));
            },
            setDates: function(firstCalendarMonthDate){
                let currentDateMoment = moment(this.currentDate);
                if(this.yearOfCurrentDate == null)
                    this.yearOfCurrentDate = currentDateMoment.format('YYYY');
                if(this.monthOfCurrentDate == null)
                    this.monthOfCurrentDate = currentDateMoment.format('MM');
                if(this.dayOfCurrentDate == null)
                    this.dayOfCurrentDate = currentDateMoment.format('DD');
                if(this.weekdayOfCurrentDate == null)
                    this.weekdayOfCurrentDate = currentDateMoment.day();
            
                this.firstWeekday = moment(firstCalendarMonthDate).startOf('week').add(1,'days').toDate();
                this.lastWeekday = moment(firstCalendarMonthDate).endOf('week').add(1,'days').toDate();
                
                // console.log([
                //     this.firstWeekday, this.lastWeekday
                // ]);
            
                let weekDayFormat = 'D/M';
                this.mondayDate = moment(this.firstWeekday).startOf('week').add(1,'days').format(weekDayFormat);
                this.tuesdayDate = moment(this.firstWeekday).startOf('week').add(2,'days').format(weekDayFormat);
                this.wednesdayDate = moment(this.firstWeekday).startOf('week').add(3,'days').format(weekDayFormat);
                this.thursdayDate = moment(this.firstWeekday).startOf('week').add(4,'days').format(weekDayFormat);
                this.fridayDate = moment(this.firstWeekday).startOf('week').add(5,'days').format(weekDayFormat);
                this.saturdayDate = moment(this.firstWeekday).startOf('week').add(6,'days').format(weekDayFormat);
                this.sundayDate = moment(this.firstWeekday).startOf('week').add(7,'days').format(weekDayFormat);
            
                // console.log([
                //     this.firstWeekday, this.lastWeekday, this.weekdayOfCurrentDate
                // ]);
            
                // this.monthOfCurrentDate = parseInt(moment(this.currentDate).format('MM')) + 1;
                // this.dayOfCurrentDate = parseInt(moment(this.currentDate).format('YYYY'));
            
                // this.firstMonthDate = firstCalendarMonthDate;
                // this.lastMonthDate = moment(firstCalendarMonthDate).endOf('month').toDate();
                // 
                // // this.currentMonthIdx = firstCalendarMonthDate;
                // this.currentCalendarMonth = moment(this.firstMonthDate).format('MM');
                // 
                // let firstMonthDateWeekday = this.firstMonthDate.getDay();
                // let totalDaysInMonth = this.lastMonthDate.getDate();                    
                // 
                // let startOffset;
                // if(firstMonthDateWeekday == 1){
                //     startOffset = 7;
                // }else if(firstMonthDateWeekday == 2){
                //     startOffset = 8;
                // }else if(firstMonthDateWeekday == 3){
                //     startOffset = 9;
                // }else if(firstMonthDateWeekday == 4){
                //     startOffset = 3;
                // }else if(firstMonthDateWeekday == 5){
                //     startOffset = 4;
                // }else if(firstMonthDateWeekday == 6){
                //     startOffset = 5;
                // }else if(firstMonthDateWeekday == 0){
                //     startOffset = 6;
                // }
                // 
                // let firstDate = new Date(this.firstMonthDate);
                // 
                // firstDate.setDate(firstDate.getDate() - startOffset);
                // 
                // let lastDate = new Date(firstDate);
                // lastDate.setDate(lastDate.getDate() + 41);
                // 
                // this.firstCalendarDate = firstDate;
                // this.lastCalendarDate = lastDate;
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
        min-width: 700px;
        // border-collapse: separate;
        // border-right: 1px solid #ccc;
        // border-bottom: 1px solid #ccc;
        // border-spacing: 0;
        /* margin-top: 20px!important; */
    }
    table th{
        text-align: center;
    }
    table th span{
        display: block;
        font-size: 16px;
        font-weight: normal;
        position: relative;
        top: -3px;
    }
    table td, table th{
        border: 1px solid #ccc;
        // background-image: url('/imgs/week-calendar-table-odd-row-bg.jpg');
        // border-left: 1px solid #ccc;
        // border-top: 1px solid #ccc;
    }
    /* table td:first-child{
        width: 8%;
    } */
    table td{
        width: 14.2%;
        vertical-align: top;
        position: relative;
        /* height: 20px; */
    }
    table tr:nth-child(odd) td{
        // background-color: #f4f4f4;
        background-image: url('/imgs/week-calendar-table-odd-row-bg.jpg');
        // background-color: #fff;
        // border-right: 1px solid #ccc;
    }
    table tr.bookings td{
        height: auto;
    }
    table tr.divider td{
        height: 10px;
        background-color: #6c757d!important;
        text-align: center;
        font-weight: bold;
        color: #f6f6f6;
        /* color: #d4f4c9; */
        /* font-size: 14px; */
    }
    @media only screen and (max-width: 900px) {
        table tr.divider td{
            font-size: 14px;
        }
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
    table td.hour-cell:first-child{
        font-weight: bold;
    }
    table td.hour-cell{
        padding-right: 10px;
        text-align: right;
    }
    /* table td{
        font-weight: bold;
    } */
    .day.not-period{
        color: #999;
    }
    .current-day{
        background-color: rgba(8,232,222, 0.3)!important; 
    }
    .booking{
        position: absolute;
        bottom: 0px;
        right: 0px;
    }
</style>

<style>
    .calendar-item{
        /* width: 96%;
        position: absolute;
        left: 2%;
        background-color: rgba(144,238,144, 0.5);
        border-radius: 4px;
        z-index: 9;
        cursor: pointer;
        line-height: 1em;
        padding: 2px; */
        
        position: absolute;
        /* display: block; */
        width: 96%;
        left: 2%;
        /* background-color: rgba(144,238,144, 0.5); */
        /* background-color: #d4f4c9; */
        margin-bottom: 3px;
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: black;
        text-decoration: none;
        transition: background .2s ease;
        z-index: 10;
        /* line-height: 1.2em; */
        cursor: pointer;
        font-weight: bold;
    }
    
    .free-calendar-item{
        /* background-color: #d4f4c9!important; */
        background-color: rgba(144,238,144, 0.5);
    }
    .free-calendar-item:hover{
        background-color: rgba(144,238,144, 0.8);
    }
    
    /* .booked-calendar-item:hover{
        background-color: rgba(114,51,128, 1)!important;
    } */
    .faded-time{
        font-weight: bold;
        color: #ccc;
    }
</style>