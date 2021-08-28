<template>
    <div>
        
        <div class="list-calendar">
            
            <table class="contt" cellspacing="0">
                <tbody>
                    
                    <template v-for="(i, index) in 7" v-if="
                        getDayItem(index) && getDayItem(index).type == 'free' && getDayItem(index).items
                    ">
                        <tr class="title">
                            <td colspan="4" :class="{'current-day': isCurrentDate(index)}">
                                <a href="#" @click.prevent="goToDayView(getDayItem(index))"
                                    class="event-weekday">
                                        {{weekdaysList[index]}}
                                </a>
                                <a href="#" @click.prevent="goToDayView(getDayItem(index))"
                                    class="event-date">
                                        {{datesPerWeekday[index]}}
                                </a>
                            </td>
                        </tr>
                        
                        <template v-for="item in getDayItem(i-1).items">
                            <template v-if="item.type == 'free'">
                                
                                <event-row v-if="item.not_approved_bookings"
                                    :hide-actions="true"
                                    :day-item="getDayItem(index)"
                                    v-for="itemm in item.not_approved_bookings"
                                    :item="itemm" :key="itemm.id" />
                                
                                <free-row :day-item="getDayItem(index)"
                                    @click_free="onClickPickFree(getDayItem(index), item)"
                                    :item="item"/>
                                        
                                <!-- <tr class="event free"
                                :class="{
                                    'too-short': item.too_short,
                                }">
                                    <td>
                                        {{item.from}} - {{item.to}}
                                    </td>
                                    <td>
                                        <div class="status"></div>
                                    </td>
                                    <td>
                                        <a class="free-time-btn" href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Free time</a>
                                    </td>
                                    <td>
                                        <div class="float-right">
                                            <a class="pick-btn" href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Pick</a>
                                        </div>
                                        <span v-if="item.too_short" class="small float-right">
                                            Too short!
                                        </span>
                                    </td>
                                </tr> -->
                                    
                            </template>
                            <event-row v-if="item.type == 'event'"
                                :hide-actions="true"
                                :day-item="getDayItem(index)"
                                :item="item" :key="item.id" />
                        </template>
                    </template>
                    
                    
                    
                    <template v-for="(i, index) in 7" v-if="isDayItem(index, 'events')">
                        <tr class="title">
                            <td colspan="4">
                                <a href="#"
                                    @click.prevent="goToDayView(getDayItem(index))"
                                    class="event-weekday">
                                        {{weekdaysList[index]}}
                                </a>
                                <a href="#"
                                    @click.prevent="goToDayView(getDayItem(index))"
                                    class="event-date">
                                        {{datesPerWeekday[index]}}
                                </a>
                            </td>
                        </tr>
                        
                        <event-row v-for="item in getDayItem(index).items"
                            :day-item="getDayItem(index)"
                            :item="item" :key="item.id" />
                    </template>
                    
                </tbody>
            </table>
            
            <div class="cell-placeholder cell-placeholder-with-bg" v-if="isAllDaysEmpty && isTypeEvents">{{noEventsText}}</div>
            
        </div>
        
    </div>
</template>

<script>
    import MonthCell from "../MonthCell.vue";
    // import Actions from "../event/Actions.vue";
    import EventRow from "./ListCalendarEventRow.vue";
    import FreeRow from "./ListCalendarFreeRow.vue";
    export default {
        name: 'weekCalendar',
        mounted() {
            this.$store.dispatch('dates/setListDates', this.startDates.list);
            this.getData();
        },
        updated: function () {},
        // props: ['startDate'],
        data: function(){
            return {
                data: null,
            };
        },
        computed: {
            isTypeEvents: function () {
                let firstDayItem = this.getDayItem(0);
                if(!this.isProp(firstDayItem))
                    return false;
                return firstDayItem.type == 'events';
            },
            isAllDaysEmpty: function () {
                for(let i = 0; i < 7; i++){
                    let dayItem = this.getDayItem(i);
                    if(this.isProp(dayItem) && dayItem.count_total > 0)
                        return false;
                }
                return true;
            },
            datesPerWeekday: function () {
                let initDateMoment;
                let weekDayFormat = 'MMMM D, YYYY';
                
                if(typeof this.dateInterval !== 'undefined' && this.dateInterval !== null &&
                typeof this.dateInterval.firstDate !== 'undefined' && this.dateInterval.firstDate !== null){
                    initDateMoment = moment(this.dateInterval.firstDate);
                }else{
                    initDateMoment = moment(this.currentDate).startOf('week').add(1, 'days');
                }
                
                return [
                    initDateMoment.format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                    initDateMoment.add(1,'days').format(weekDayFormat),
                ];
            },
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
        },
        methods: {
            getStatusTooltipTitle: function (item) {
                if(item.approved)
                    return "<div class='small'>Approved</div>";
                return "<div class='small'>Not approved</div>";
            },
            getDurationStrHoursAndMinutes: function (duration) {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(duration);
            },
            isDayItem: function (index, type = 'events') {
                let dayItem = this.getDayItem(index);
                return ['events','free'].includes(type) && dayItem !== null &&
                this.isProp(dayItem.items) && typeof dayItem.type !== 'undefined' &&
                dayItem.type.toLowerCase() == type.toLowerCase();
            },
            onClickPickFree: function (dayData, event) {
                if(event.too_short)
                    return;
                this.app.showPickTimeModal({
                    day: dayData,
                    item: event,
                });
            },
            getDayItem: function(index){
                if(this.data === null || typeof this.data[index] === 'undefined')
                    return null;
                return this.data[index];
            },
            notPast: function(dateItemIndex, hourItemIndex){
                // console.log(dayItemIndex, freeItemIndex);
                let dateItem = this.data[dateItemIndex];
                let hourItem = dateItem.items[hourItemIndex];
                
                let dateItemMoment = moment(dateItem.year+'-'+dateItem.month+'-'+dateItem.day);
                let currentDateMoment = moment(this.currentDate);
                
                return (
                    dateItemMoment.diff(currentDateMoment) >= 0 ||
                    dateItemMoment.format("YYYY MM DD") == currentDateMoment.format("YYYY MM DD")
                ) && dateItem.bookable;
            },
            isCurrentDate: function(index){
                let dayItem = this.getDayItem(index);
                if(!this.isProp(dayItem))
                    return false;
                    
                let dayItemDate = moment(dayItem.year+'-'+dayItem.month+'-'+dayItem.day).format("YYYY-MM-DD");
                let currentDate = moment(this.currentDate).format("YYYY-MM-DD");
                
                // console.log(JSON.parse(JSON.stringify('isCurrentDate')));
                // console.log(JSON.parse(JSON.stringify(dayItemDate == currentDate)));
                // console.log(JSON.parse(JSON.stringify(dayItemMoment.format("YYYY-MM-DD"))));
                // console.log(JSON.parse(JSON.stringify(currentDateMoment.format("YYYY-MM-DD"))));
                // console.log(JSON.parse(JSON.stringify(dayItemMoment.diff(currentDateMoment))));
                
                return dayItemDate == currentDate;
            },
            getData: function(params = null){
                let startDate = moment(this.$store.getters['dates/interval'].firstDate).format('YYYY-MM-DD');
                let endDate = moment(this.$store.getters['dates/interval'].lastDate).format('YYYY-MM-DD');
                
                return this.app.getData(startDate, endDate, params).then((data) => {
                    this.data = data.data;
                });
            },
        },
        components: {
            MonthCell,
            EventRow,
            FreeRow,
        },
        watch: {
            search: function () {
                this.getData();
            },
            dataUpdater: function () {
                this.getData();
            },
        }
    }
</script>

<style lang="scss" scoped>
    
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