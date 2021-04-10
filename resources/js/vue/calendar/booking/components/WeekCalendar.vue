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
                        <th></th>
                        <th :class="{'current-day': isCurrentDate(2)}">Monday<span>{{mondayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(3)}">Tuesday<span>{{tuesdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(4)}">Wednesday<span>{{wednesdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(5)}">Thursday<span>{{thursdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(6)}">Friday<span>{{fridayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(7)}">Saturday<span>{{saturdayDate}}</span></th>
                        <th :class="{'current-day': isCurrentDate(8)}">Sunday<span>{{sundayDate}}</span></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="dates" v-for="i in workHours">
                        <tr>
                            <td v-for="k in 8"
                                class="hour-cell"
                                :class="{'current-day': isCurrentDate(k)}"
                                :data-weekday="k != 1 && k - 1"
                                :data-hour="hours[i].hour">
                                    <template v-if="k == 1">
                                        <week-time-cell :index='i'></week-time-cell>
                                    </template>
                                    <template v-else>
                                        <!-- <div v-if="k == 3 && i == 9" class="free-box">111</div> -->
                                    </template>
                                <!-- <div class="cont" :class="{'current-day': isCurrentDate(i,k)}">
                                    <div class="day" :class="{'not-period': currentCalendarMonth != getDate(i,k,'month')}">
                                        {{dates[(((i*7) + k))-8].day}}
                                    </div>
                                    <month-cell v-if="notPast(i,k)" @open-modal="openModal($event,i,k)" :free="getDate(i,k,'free')"></month-cell>
                                </div> -->
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
    import WeekTimeCell from "./WeekTimeCell.vue";
    import MonthCell from "./MonthCell.vue";
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalBookContent from "./ModalBookContent.vue";
    export default {
        mounted() {
            this.setDates(moment(new Date()).startOf('week').toDate());
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
            
            let intervalDates = setInterval(() => {
                if(this.dates != null){
                    clearInterval(intervalDates);
                    this.placeItems();
                    this.regModalOpenButtons();
                }
            }, 300);
            
            // this.getData();
            $("#bookModal").on('hidden.bs.modal', () => {
                this.bookDate = null;
                // console.log(this.bookDate);
            });
            
            // console.log(this.firstMonthDate);
            // console.log(this.weekdayOfCurrentDate);
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
                
                
                // firstMonthDate: moment(this.currentDateObj).startOf('month').toDate(),
            };
        },
        computed: {
            calendarTile: function () {
                if(this.firstWeekday == null)
                    return '';
                
                // console.log(this.lastWeekday);
                // return this.firstWeekday.getMonth();
                let firstWeekdayMonth = this.firstWeekday.getMonth();
                let lastWeekdayMonth = this.lastWeekday.getMonth();
                if(firstWeekdayMonth == lastWeekdayMonth){
                    let firstWeekdayDay = moment(this.firstWeekday).format('DD');
                    let lastWeekdayDay = moment(this.lastWeekday).format('DD');
                    let monthTitle = moment(this.firstWeekday).format('MMMM');
                    return firstWeekdayDay + ' - ' + lastWeekdayDay + ' ' + monthTitle;
                }else{
                    let firstWeekdayMonthTitle = moment(this.firstWeekday).format('DD MMMM');
                    let lastWeekdayMonthTitle = moment(this.lastWeekday).format('DD MMMM');
                    return firstWeekdayMonthTitle + ' - ' + lastWeekdayMonthTitle;
                }
                return moment(this.firstMonthDate).format('MMMM YYYY');
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
            regModalOpenButtons: function(){
                $(document).on('click', '.calendar-item', (event) => {
                    // console.log(event);
                    let dayItemIndex = $(event.target).attr('day-item-index');
                    let freeItemIndex = $(event.target).attr('free-item-index');
                    
                    // console.log(dayItemIndex);
                    // console.log(freeItemIndex);
                    
                    this.openModal(dayItemIndex, freeItemIndex);
                });
            },
            placeItems: function(){
                let _this = this;
                
                this.dates.forEach((dayItem, i) => {
                    let weekday = dayItem.weekday;
                    dayItem.free.forEach((freeItem, ii) => {
                        if(this.notPast(i, ii))
                            placeItem(dayItem, freeItem, i, ii);
                        // console.log(JSON.parse(JSON.stringify(freeItem)));
                    });
                    // console.log(item);
                });
                
                function placeItem(dayItem, freeItem, dayItemIndex, freeItemIndex){
                    let fromArr = freeItem.from.split(':');
                    let fromHour = fromArr[0];
                    let fromMinute = fromArr[1];
                    let toArr = freeItem.to.split(':');
                    let toHour = toArr[0];
                    let toMinute = toArr[1];
                    let toHourInt = parseInt(toHour);
                    let lastWorkHour = _this.workHours.slice(-1).pop();
                    let weekDay = dayItem.weekday;
                    let beginCell = $('.hour-cell[data-hour="' + fromHour + '"][data-weekday="' + weekDay + '"]');
                    
                    let div = document.createElement('div');
                    // $(div).addClass('calendar-item').html('Free time:<br>' + freeItem.from + ' - ' + freeItem.to);
                    $(div).attr('day-item-index', dayItemIndex);
                    $(div).attr('free-item-index', freeItemIndex);
                    $(div).addClass('calendar-item').html(freeItem.from + ' - ' + freeItem.to);
                    
                    let diffHours = parseInt(toHour) - parseInt(fromHour);
                    let cellHeight = parseInt(beginCell.outerHeight());
                    // let
                    // this.workHours.forEach((item, i) => {
                    // 
                    // });
                    
                    let divHeight = diffHours*cellHeight;
                    if(parseInt(fromMinute) != 0){
                        let topShift = getPercValueOfMnutesToHour(fromMinute, cellHeight);
                        $(div).css({'top': topShift + 'px'});
                        divHeight -= topShift;
                    }
                    
                    if(parseInt(toMinute) != 0){
                        let bottomShift = getPercValueOfMnutesToHour(toMinute, cellHeight);
                        // $(div).css({'top': topShift + 'px'});
                        divHeight += bottomShift;
                    }
                    
                    $(div).css({'height':(divHeight - 2) + 'px'});
                    
                    beginCell.prepend(div);
                    
                    // let width = getPercValue(parseInt(beginCell.width()));
                    // console.log(JSON.parse(JSON.stringify(width)));
                    // if()
                    // console.log(JSON.parse(JSON.stringify(cellHeight)));
                    // console.log(JSON.parse(JSON.stringify(diffHours)));
                    // console.log(JSON.parse(JSON.stringify(divHeight)));
                    // console.log(_this.workHours.slice(-1).pop());
                }
                
                function getPercValueOfMnutesToHour(minutes, cellHeight){
                    let oneMinutePerc = 100/60;
                    let perc = parseInt(minutes*oneMinutePerc);
                    let onePercCellPixel = cellHeight/100;
                    let topShift = onePercCellPixel*perc;
                    // console.log(topShift);
                    return topShift;
                    // console.log(minutes + ': ' + perc);
                    // console.log(oneMinutePerc);
                    // let onePixelCelPerc = 60/100;
                }
            },
            next: function(){
                console.log('next');
                let dateOfNextWeek = moment(this.firstWeekday).add(7, 'days');
                // console.log(dateOfNextWeek.toDate());
                this.setDates(dateOfNextWeek.toDate());
                // console.log(dateOfNextWeek.toDate());
                // console.log(this.firstWeekday);
                // console.log(this.lastWeekday);
                // this.getData();
            },
            previous: function(){
                if(!this.canGoToPrevious)
                    return;
                
                let dateOfPreviousWeek = moment(this.firstWeekday).subtract(7, 'days');
                // console.log('previous');
                // var dateOfPreviousMonth = moment(this.firstMonthDate).subtract(1, 'M');
                this.setDates(dateOfPreviousWeek.toDate());
                // this.getData();
            },
            openModal: function(i, ii){
                // console.log(itm);
                // $parent.isAuth();
                // console.log(this.$parent.isAuth());
                this.bookDate = this.dates[i];
                this.bookTimePeriod = this.bookDate.free[ii];
                // console.log(i);
                // console.log(ii);
                // console.log(dayItem);
                // console.log(freeItem);
                // this.bookDate = this.getDate(i,k);
                // this.bookTimePeriod = itm;
                $('#bookModal').modal('show');
            },
            notPast: function(dayItemIndex, freeItemIndex){
                // console.log(dayItemIndex, freeItemIndex);
                let dayItem = this.dates[dayItemIndex];
                let freeItem = dayItem.free[freeItemIndex];
                
                
                // this.bookTimePeriod = this.bookDate.free[ii];
                // let date = this.getDate(i,k);
                let itemDateMoment = moment(dayItem.year+'-'+dayItem.month+'-'+dayItem.day);
                let currentDateMoment = moment(this.currentDate);
                // console.log(dateMoment.format("YYYY MM DD") + ' - ' + currentDateMoment.format("YYYY MM DD") + ': ' + (dateMoment.diff(currentDateMoment) >= 0 || dateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")));
                // console.log(currentDateMoment.format("YYYY MM DD"));
                return (
                    itemDateMoment.diff(currentDateMoment) >= 0 ||
                    itemDateMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")
                ) && dayItem.bookable;
                // console.log(dateMoment < currentDateMoment);
                // console.log(moment(date).isAfter(moment('2014-03-24T01:14:000')));
                // return this.currentDate.year == this.getDate(i,k,'year') &&
                // this.currentDate.month == this.getDate(i,k,'month') &&
                // this.currentDate.day == this.getDate(i,k,'day');
            },
            isCurrentDate: function(k){
                // console.log(moment(this.currentDate).startOf('week').format('DD'));
                // console.log(moment(this.firstWeekday).startOf('week').format('DD'));
                return this.weekdayOfCurrentDate == (k - 1) &&
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
            getData: function(){
                // console.log(JSON.parse(JSON.stringify(this.range)));
                // routes.calendar.booking.range
                // let url = JSON.parse(JSON.stringify(routes.calendar.booking.range));
                let url = routes.calendar.booking.range;
                // url = url.replace(':start', moment(this.range.first_date).format('DD-MM-YYYY'));
                // console.log(moment(this.range.first_date).format('DD-MM-YYYY'));
                // console.log(moment(this.range.last_date).format('DD-MM-YYYY'));
                // console.log(url);
                // return;
                
                // url = url.replace(':start', moment(this.firstWeekday).format('DD-MM-YYYY'));
                url = url.replace(':start', moment(this.currentDate).format('DD-MM-YYYY'));
                url = url.replace(':end', moment(this.lastWeekday).format('DD-MM-YYYY'));
                
                url += '?' + this.search;
                
                // return;
                // url = url.replace(':start', '28-03-2021');
                // url = url.replace(':end', '08-05-2021');
                
                // console.log(url);
                // console.log(routes.calendar.booking.range);
                axios.get(url)
                .then((response) => {
                    // handle success
                    // console.log(response.data);
                    this.dates = response.data.data;
                    this.setWorkHours(response.data.start, response.data.end);
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
            setWorkHours: function(startHour, endHour){
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
                
                let weekDayFormat = 'D/M';
                this.mondayDate = moment(this.firstWeekday).startOf('week').add(1,'days').format(weekDayFormat);
                this.tuesdayDate = moment(this.firstWeekday).startOf('week').add(2,'days').format(weekDayFormat);
                this.wednesdayDate = moment(this.firstWeekday).startOf('week').add(3,'days').format(weekDayFormat);
                this.thursdayDate = moment(this.firstWeekday).startOf('week').add(4,'days').format(weekDayFormat);
                this.fridayDate = moment(this.firstWeekday).startOf('week').add(5,'days').format(weekDayFormat);
                this.saturdayDate = moment(this.firstWeekday).startOf('week').add(6,'days').format(weekDayFormat);
                this.sundayDate = moment(this.firstWeekday).startOf('week').add(7,'days').format(weekDayFormat);
                
                console.log([
                    this.firstWeekday, this.lastWeekday, this.weekdayOfCurrentDate
                ]);
                
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
            MonthCell,
            ModalAuthContent,
            ModalBookContent,
            WeekTimeCell
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
    table th span{
        display: block;
        font-size: 16px;
        font-weight: normal;
        position: relative;
        top: -3px;
    }
    table td, table th{
        border: 1px solid #ccc;
    }
    table td:first-child{
        width: 8%;
    }
    table td{
        width: 13.1%;
        vertical-align: top;
        position: relative;
        height: 40px;
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

<style>
    .calendar-item{
        width: 96%;
        position: absolute;
        left: 2%;
        background-color: rgba(144,238,144, 0.5);
        border-radius: 4px;
        z-index: 9;
        cursor: pointer;
        line-height: 1em;
        padding: 2px;
    }
    .calendar-item:hover{
        background-color: rgba(144,238,144, 1);
    }
</style>