<template>
    <div>
        <navigation :can-go-to-previous="canGoToPrevious"
            :calendar-title="calendarTitle"
            @previous="previous"
            @next="next"
            @today="today"></navigation>
            
        <div class="for-table">
            
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th :class="{'current-day': currentDay}">{{calendarWeekdayTitle}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="booked-requested-items">
                        <td></td>
                        <td :class="{'current-day': currentDay}">
                            <day-requested-booked-cell
                                @cancel="cancelBook($event)"
                                :date="date"></day-requested-booked-cell>
                        </td>
                    </tr>
                    <tr class="divider">
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            
            <table>
                <tbody>
                    <template v-if="date" v-for="i in workHours">
                        <tr>
                            <td v-for="k in 2"
                                :class="{'title-hour-cell': k == 1, 'hour-cell': k == 2}"
                                :data-hour="hoursList[i].hour">
                                    <div v-if="k == 1">
                                        {{ hoursList[i].hour }}:{{ hoursList[i].minute }}
                                    </div>
                                    <div v-else class="faded-time">
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
                    :book-date="date"
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
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    import DayRequestedBookedCell from "./DayRequestedBookedCell.vue";
    export default {
        name: 'dayCalendar',
        mounted() {
            if(this.startDate != null)
                this.currentChoosenDateMoment = moment(this.startDate);
            
            // this.setDates();
            
            // let interval = setInterval(() => {
            //     if(this.search != null){
            //         // console.log(11111);
            //         clearInterval(interval);
            //         this.getData();
            //     }
            // }, 300);
            
            this.getData();
            
            this.regModalOpenButtons();
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
            
            // console.log(JSON.parse(JSON.stringify([dateItem, hourItem])));
            // console.log(this.firstMonthDate);
            // console.log(this.weekdayOfCurrentDate);
        },
        updated: function () {
            let _this = this;
            let interval = setInterval(function(){
                if(_this.date !== null){
        	       _this.placeItems();
                   clearInterval(interval);
                }
            }, 100);
        },
        props: ['startDate'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                date: null,
                // bookDate: null,
                bookTimePeriod: null,
                requestedBookings: null,
                currentDate: new Date(),
                currentDateMoment: moment(new Date()),
                currentChoosenDateMoment: moment(new Date()),
                
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
            currentDay: function () {
                // if(this.currentDate == null)
                //     return true;
                let currentDate = moment(currentDate).format('YYYYMMDD');
                let currentChoosenDate = this.currentChoosenDateMoment.format('YYYYMMDD');
                return currentDate == currentChoosenDate;
            },
            calendarTitle: function () {
                if(this.currentChoosenDateMoment == null)
                    return '';
                return this.currentChoosenDateMoment.format('MMMM DD, YYYY');
            },
            calendarWeekdayTitle: function () {
                if(this.currentChoosenDateMoment == null)
                    return '';
                // console.log(this.currentChoosenDateMoment.isoWeekday() - 1);
                return this.weekdaysList[this.currentChoosenDateMoment.isoWeekday() - 1];
            },
            canGoToPrevious: function () {
                if(this.currentChoosenDateMoment == null)
                    return false;
                let currentDateMoment = this.currentDateMoment.clone();
                let currentChoosenDateMoment = this.currentChoosenDateMoment.clone();
                currentDateMoment.startOf('day');
                currentChoosenDateMoment.startOf('day');
                
                return currentDateMoment.diff(currentChoosenDateMoment) < 0;
                // return this.currentDateMoment.diff(this.currentChoosenDateMoment) < 0;
                
                // let firstWeekDayOfCurrentDate = moment(this.currentDate).startOf('week');
                // let firstWeekDay = moment(this.firstWeekday).startOf('week');
                // // if(firstWeekDay)
                // // let currentDateFirstMonthDay = moment(this.currentDate).startOf('month');
                // return firstWeekDay.isAfter(firstWeekDayOfCurrentDate);
            }
        },
        methods: {
            setDates: function(){
                this.currentDate = new Date();
                this.currentDateMoment = moment(this.currentDate);
                this.currentChoosenDateMoment = this.currentDateMoment;
            },
            // weekDayNotPast: function(i){
            //     // console.log(this.currentDate);
            // 
            //     // mondayWeekday = this.mondayDate.getDay();
            //     let mondayDateMoment = moment(this.mondayDate);
            //     let currentDateMoment = moment(this.currentDate);
            //     return i >= this.weekdayOfCurrentDate;
            //     // return i >= this.weekdayOfCurrentDate || this.mondayDate != null;
            //     // console.log(currentDateMoment.diff(mondayDateMoment) > 0);
            //     // console.log(this.mondayDate);
            //     // 
            //     // return currentDateMoment.diff(mondayDateMoment) > 0;
            // },
            getBussinessHourPerWeekday: function(i){
                let bussinessHours = this.bussinessHours[i];
                return bussinessHours.start + ' - ' + bussinessHours.end;
            },
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
            regModalOpenButtons: function(){
                $(document).off('click', '.free-calendar-item');
                $(document).on('click', '.free-calendar-item', (event) => {
                    // console.log(event);
                    // let dateItemIndex = $(event.target).attr('date-item-index');
                    let hourItemIndex = $(event.target).attr('hour-item-index');
                    
                    // console.log(dayItemIndex);
                    // console.log(freeItemIndex);
                    
                    this.openModal(hourItemIndex);
                });
            },
            placeItems: function(){
                // console.log(555555555555555555555555555555);
                let _this = this;
                
                $('.calendar-item').remove();
                
                // this.dates.forEach((dateItem, i) => {
                    // let weekday = dateItem.weekday;
                    // placeDayClosedItems(dateItem, i);
                if(this.date.is_weekend){
                    placeClosedItem();
                    return;
                }
                
                placeBeginningClosedDateItem();
                placeEndClosedDateItem();
                
                if(this.date.bookable)
                    this.date.items.forEach((hourItem, i) => {
                        // if()
                        placeItem(hourItem, i);
                        // console.log(JSON.parse(JSON.stringify(freeItem)));
                    });
                
                
                function placeBeginningClosedDateItem(){
                    // return true;
                    let startDateItemMoment = moment(_this.date.year + '-' + _this.date.month + '-' + _this.date.day + ' ' + _this.date.start + ':00');
                    let startWorkHour = _this.workHours[0];
                    if(startWorkHour < 10)
                        startWorkHour = '0' + startWorkHour;
                    // let bussinessHours = _this.bussinessHours[dateItem.weekday - 1];
                    let startWorkHourMoment = moment(_this.date.year + '-' + _this.date.month + '-' + _this.date.day + ' ' + startWorkHour + ':00:00');
                    // console.log(startDateItemMoment.diff(startWorkHourMoment));
                    // console.log(JSON.parse(JSON.stringify(dateItem)));
                    if(startDateItemMoment.diff(startWorkHourMoment) > 0){
                        let fromHour = startWorkHourMoment.format('HH');
                        let fromMinute = startWorkHourMoment.format('mm');
                        // console.log(fromHour);
                        // console.log(fromMinute);
                        let toHour = startDateItemMoment.format('HH');
                        let toMinute = startDateItemMoment.format('mm');
                        // console.log(toHour);
                        // console.log(toMinute);
                        placePartClosedItem(fromHour, fromMinute, toHour, toMinute);
                    }
                }
                
                function placeEndClosedDateItem(){
                    // return true;
                    let endDateItemMoment = moment(_this.date.year + '-' + _this.date.month + '-' + _this.date.day + ' ' + _this.date.end + ':00');
                    let endWorkHour = _this.workHours[_this.workHours.length - 1] + 1;
                    if(endWorkHour < 10)
                        endWorkHour = '0' + endWorkHour;
                    // let bussinessHours = _this.bussinessHours[dateItem.weekday - 1];
                    let endWorkHourMoment = moment(_this.date.year + '-' + _this.date.month + '-' + _this.date.day + ' ' + endWorkHour + ':00:00');
                    // console.log(startDateItemMoment.diff(startWorkHourMoment));
                    // console.log(JSON.parse(JSON.stringify(dateItem)));
                    if(endDateItemMoment.diff(endWorkHourMoment) < 0){
                        let fromHour = endDateItemMoment.format('HH');
                        let fromMinute = endDateItemMoment.format('mm');
                        // console.log(fromHour);
                        let toHour = endWorkHourMoment.format('HH');
                        let toMinute = endWorkHourMoment.format('mm');
                        
                        placePartClosedItem(fromHour, fromMinute, toHour, toMinute);
                    }
                }
                
                function placePartClosedItem(fromHour, fromMinute, toHour, toMinute){
                    // let weekDay = _this.date.weekday;
                    // console.log(weekDay);
                    let beginCell = $('.hour-cell[data-hour="' + fromHour + '"]');
                    
                    let div = document.createElement('div');
                    $(div).addClass('calendar-item').addClass('closed-calendar-item');
                    $(div).html('Closed');
                    
                    let diffHours = parseInt(toHour) - parseInt(fromHour);
                    // console.log(diffHours);
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
                
                    // console.log(item);
                // });
                
                // function placeDayClosedItems(dateItem, dateItemIndex){
                //     let items = Object.values(dateItem.items);
                //     let firstItem = items[0];
                //     // console.log(firstItem);
                //     console.log(JSON.parse(JSON.stringify(dateItem)));
                // }
                
                function placeClosedItem(){
                    let lengthCells = $('.hour-cell').length;
                    let beginCell = $('.hour-cell').eq(0);
                    
                    let div = document.createElement('div');
                    $(div).addClass('calendar-item').addClass('closed-calendar-item');
                    $(div).html('Closed');
                    
                    let cellHeight = beginCell.outerHeight();
                    let divHeight = lengthCells*cellHeight;
                    divHeight = divHeight - 2;
                    
                    $(div).css({'height':divHeight + 'px'});
                    
                    beginCell.prepend(div);
                }
                
                function placeItem(hourItem, hourItemIndex){
                    if(hourItem.type != 'free')
                        return;
                        
                    // let bussinessHours = _this.bussinessHours[dateItem.weekday - 1];
                    // let startBussinessHourMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + bussinessHours.start + ':00');
                    // let endBussinessHourMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + bussinessHours.end + ':00');
                    
                    // console.log(_this.workHours);
                    // console.log(bussinessHours);
                    // console.log(JSON.parse(JSON.stringify(bussinessHours)));
                    
                    // let startItemMoment = moment(dateItem.year + '-' + dateItem.month + '-' + dateItem.day + ' ' + hourItem.from + ':00');
                    
                    // console.log(startItemMoment->format('HH:mm'));
                    // console.log(startBussinessHourMoment->format('HH:mm'));
                    
                    // if(startItemMoment.diff(startBussinessHourMoment) > 0){
                    //     console.log(startItemMoment.format('HH:mm'));
                    //     console.log(startBussinessHourMoment.format('HH:mm'));
                    //     console.log(hourItemIndex);
                    //     console.log('-----------------------');
                    //     // console.log(JSON.parse(JSON.stringify('ddddd')));
                    // }
                    
                    let fromArr = hourItem.from.split(':');
                    let fromHour = fromArr[0];
                    let fromMinute = fromArr[1];
                    let toArr = hourItem.to.split(':');
                    let toHour = toArr[0];
                    let toMinute = toArr[1];
                    let toHourInt = parseInt(toHour);
                    let lastWorkHour = _this.workHours.slice(-1).pop();
                    // let weekDay = dateItem.weekday;
                    let beginCell = $('.hour-cell[data-hour="' + fromHour + '"]');
                    
                    let div = document.createElement('div');
                    // $(div).attr('date-item-index', dateItemIndex);
                    $(div).attr('hour-item-index', hourItemIndex);
                    $(div).addClass('calendar-item').addClass('free-calendar-item');
                    
                    $(div).html(_this.capitalizeFirstLetter(_this.getText('text.free_time')) + ':');
                    
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
                    
                    // console.log(topShift);
                    // console.log(bottomShift);
                    // console.log(divHeight);
                    // console.log('-----------------');
                    
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
                    // console.log(minutes);
                    // console.log(minutesPercents);
                    // console.log(shift);
                    
                    // console.log(onePixelPerc);
                    // let perc = parseInt(minutes*oneMinutePerc);
                    // let perc = minutes*oneMinutePerc;
                    // let onePercCellPixel = cellHeight/100;
                    // let topShift = onePercCellPixel*perc;
                    // // console.log(topShift);
                    // return topShift;
                }
                
                // function getPercValueOfMnutesToHour(minutes, cellHeight){
                //     // console.log(cellHeight);
                //     // cellHeight++;
                //     let oneMinutePerc = 100/60;
                //     // let perc = parseInt(minutes*oneMinutePerc);
                //     let perc = minutes*oneMinutePerc;
                //     let onePercCellPixel = cellHeight/100;
                //     let topShift = onePercCellPixel*perc;
                //     // console.log(topShift);
                //     return topShift;
                // }
            },
            next: function(){
                // this.currentChoosenDateMoment = this.currentChoosenDateMoment.add(1, 'days');
                let currentChoosenDateMoment = this.currentChoosenDateMoment;
                this.currentChoosenDateMoment = null;
                currentChoosenDateMoment.add(1, 'days');
                this.currentChoosenDateMoment = currentChoosenDateMoment;
                this.$parent.setStartDate('day', new Date(this.currentChoosenDateMoment.toDate()));
                console.log(this.currentChoosenDateMoment.format('YYYY MM DD'));
                // this.currentChoosenDateMoment.format();
                this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                    
                let currentChoosenDateMoment = this.currentChoosenDateMoment;
                this.currentChoosenDateMoment = null;
                currentChoosenDateMoment.subtract(1, 'days');
                this.currentChoosenDateMoment = currentChoosenDateMoment;
                this.$parent.setStartDate('day', new Date(this.currentChoosenDateMoment.toDate()));
                // console.log(this.currentChoosenDateMoment.format('YYYY MM DD'));
                // this.currentChoosenDateMoment.format();
                this.getData();
                
                // let dateOfPreviousWeek = moment(this.firstWeekday).subtract(7, 'days');
                // // console.log('previous');
                // // var dateOfPreviousMonth = moment(this.firstMonthDate).subtract(1, 'M');
                // this.setDates(dateOfPreviousWeek.toDate());
                // this.getData();
            },
            today: function(){
                this.currentChoosenDateMoment = null;
                // currentChoosenDateMoment.subtract(1, 'days');
                this.currentChoosenDateMoment = moment(new Date());
                this.$parent.setStartDate('day', new Date(this.currentChoosenDateMoment.toDate()));
                // console.log(this.currentChoosenDateMoment.format('YYYY MM DD'));
                // this.currentChoosenDateMoment.format();
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
            openModal: function(i){
                // console.log(itm);
                // $parent.isAuth();
                // console.log(this.$parent.isAuth());
                // this.bookDate = this.dates[i];
                this.bookTimePeriod = this.date.items[i];
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
                
                // console.log(moment(this.firstWeekday).format('DD-MM-YYYY'));
                // console.log(moment(this.lastWeekday).format('DD-MM-YYYY'));
                
                // moment(this.firstWeekday).format('DD-MM-YYYY'),
                // moment(this.lastWeekday).format('DD-MM-YYYY'),
                // let date = this.currentChoosenDateMoment.format('DD-MM-YYYY');
                let date = this.currentChoosenDateMoment.format('YYYY-MM-DD');
                this.componentApp.getData(
                    date,
                    date,
                    (response) => {
                        this.date = response.data.data[0];
                        this.bussinessHours = response.data.business_hours;
                        this.setWorkHours();
                        // console.log(JSON.parse(JSON.stringify(4444444444444444)));
                        // setTimeout(() => {
                        //     this.placeItems();
                        // }, 100);
                    },
                    () => {},
                    () => {
                        $('#cancelBookModal').modal('hide');
                    },
                );
            },
            // setWorkHours: function(startHour, endHour){
            setWorkHours: function(){
                let start = null;
                let end = null;
                
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
                });
                
                start = moment('1970-01-01 ' + ((start > 9) ? start : "0" + start) + ':00');
                end = moment('1970-01-01 ' + ((end > 9) ? end : "0" + end) + ':00');
                
                // return;
                // console.log(business_hours);
                // this.bussinessHours.forEach((item, i) => {
                //     if(start == null){
                //         // start = item.start;
                //         start = moment('1970-01-01 ' + item.start_hour + ':00');
                //         let first = true;
                //     }
                //     if(end == null){
                //         end = moment('1970-01-01 ' + item.end_hour + ':00');
                //         let first = true;
                //     }
                //     if(typeof first != 'undefined' && first == true)
                //         return;
                //     let itemStart = moment('1970-01-01 ' + item.start_hour + ':00');
                //     let itemEnd = moment('1970-01-01 ' + item.end_hour + ':00');
                //     if(itemStart.diff(start) < 0)
                //         start = itemStart;
                //     if(itemStart.diff(start) >= 0)
                //         end = itemEnd;
                // });
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
                // console.log(this.workHours);
            },
        },
        components: {
            Navigation,
            ModalAuthContent,
            ModalBookContent,
            ModalCancelBookContent,
            DayRequestedBookedCell
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
        /* margin-top: 20px!important; */
    }
    table td, table th{
        border: 1px solid #ccc;
        vertical-align: top;
        position: relative;
        height: 20px;
        padding-left: 10px;
    }
    table th{
        text-align: center;
        font-weight: normal;
    }
    table td:first-child{
        width: 100px;
    }
    table td:last-child{
        text-align: center;
    }
    table tr:nth-child(odd) td{
        background-color: #f4f4f4;
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
    /* table td.hour-cell:first-child{
        font-weight: bold;
        
    } */
    table td.title-hour-cell{
        font-weight: bold;
        text-align: right;
        padding-right: 10px;
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
    table tr.booked-requested-items td{
        height: auto;
        background-color: #f4f4f4;
    }
    table tr.booked-requested-items td:last-child{
        padding-left: 4px;
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
        width: calc(100% - 8px);
        left: 4px;
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
        text-align: left;
    }
    
    .free-calendar-item{
        /* background-color: #d4f4c9!important; */
        background-color: rgba(144,238,144, 0.5);
    }
    .free-calendar-item:hover{
        background-color: rgba(144,238,144, 0.8);
    }
    
    .closed-calendar-item{
        background-color: rgba(255, 0, 0, 0.1);
        cursor: default;
    }
    
    /* .booked-calendar-item:hover{
        background-color: rgba(114,51,128, 1)!important;
    } */
    .faded-time{
        font-weight: bold;
        color: #ccc;
    }
</style>