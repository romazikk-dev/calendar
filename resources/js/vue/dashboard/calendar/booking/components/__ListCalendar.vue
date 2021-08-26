<template>
    <div>
        
        <div class="list-calendar"> 
            <table class="contt" cellspacing="0">
                <tbody>
                    <template v-for="(i, index) in 7" v-if="getDayItem(index) && getDayItem(index).type == 'free'">
                        <tr class="title">
                            <td colspan="4">
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
                                <tr class="event free"
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
                                </tr>
                                <template v-if="item.not_approved_bookings">
                                    <tr v-for="itemm in item.not_approved_bookings" class="event"
                                    :class="{
                                        'approved': itemm.approved,
                                        'time-crossing': itemm.time_crossing,
                                    }">
                                        <td>
                                            {{itemm.from}} - {{itemm.to}}
                                            <div v-if="itemm.time_crossing"
                                                class="warning-sign text-warning tooltip-active float-right"
                                                data-placement="auto"
                                                title="<div class='small'>Event is crossing time one of other event</div>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                    </svg>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="status tooltip-active"
                                                data-placement="auto"
                                                :title="getStatusTooltipTitle(itemm)"></div>
                                        </td>
                                        <td colspan="2">
                                            <div class="infos">
                                                <div class="info-item">
                                                    <span class="vall vall-client">{{fullNameOfObj(itemm.client_without_user_scope)}}</span>
                                                    <span class="small labell">(client)</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="vall vall-hall">{{itemm.hall_without_user_scope.title}}</span>
                                                    <span class="small labell">(hall)</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="vall vall-template">{{itemm.template_without_user_scope.title}}</span>
                                                    <span class="small labell">(template)</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="vall vall-worker">{{fullNameOfObj(itemm.worker_without_user_scope)}}</span>
                                                    <span class="small labell">(worker)</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="vall vall-worker">{{getDurationStrHoursAndMinutes(itemm.right_duration)}}</span>
                                                    <span class="small labell">(duration)</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <tr v-if="item.type == 'event'" class="event"
                            :class="{
                                'approved': item.approved,
                                'time-crossing': item.time_crossing,
                            }">
                                <td>
                                    {{item.from}} - {{item.to}}
                                    <div v-if="item.time_crossing"
                                        class="warning-sign text-warning tooltip-active float-right"
                                        data-placement="auto"
                                        title="<div class='small'>Event is crossing time one of other event</div>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                            </svg>
                                    </div>
                                </td>
                                <td>
                                    <div class="status tooltip-active"
                                        data-placement="auto"
                                        :title="getStatusTooltipTitle(item)"></div>
                                </td>
                                <td colspan="2">
                                    <div class="infos">
                                        <div class="info-item">
                                            <span class="vall vall-client">{{fullNameOfObj(item.client_without_user_scope)}}</span>
                                            <span class="small labell">(client)</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="vall vall-hall">{{item.hall_without_user_scope.title}}</span>
                                            <span class="small labell">(hall)</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="vall vall-template">{{item.template_without_user_scope.title}}</span>
                                            <span class="small labell">(template)</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="vall vall-worker">{{fullNameOfObj(item.worker_without_user_scope)}}</span>
                                            <span class="small labell">(worker)</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="vall vall-worker">{{getDurationStrHoursAndMinutes(item.right_duration)}}</span>
                                            <span class="small labell">(duration)</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
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
                        <tr class="event" v-for="item in getDayItem(index).items"
                        :class="{
                            'approved': item.approved,
                            'time-crossing': item.time_crossing,
                        }">
                            <td>
                                {{item.from}} - {{item.to}}
                                <div v-if="item.time_crossing"
                                    class="warning-sign text-warning tooltip-active float-right"
                                    data-placement="auto"
                                    title="<div class='small'>Event is crossing time one of other event</div>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                </div>
                            </td>
                            <td>
                                <div class="status tooltip-active"
                                    data-placement="auto"
                                    :title="getStatusTooltipTitle(item)"></div>
                            </td>
                            <td>
                                <!-- <div v-if="item.time_crossing"
                                    class="warning-sign text-warning tooltip-active float-left"
                                    data-placement="auto"
                                    title="<div class='small'>Event is crossing time one of other event</div>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                </div> -->
                                <div class="infos">
                                    <div class="info-item">
                                        <span class="vall vall-client">{{fullNameOfObj(item.client_without_user_scope)}}</span>
                                        <span class="small labell">(client)</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="vall vall-hall">{{item.hall_without_user_scope.title}}</span>
                                        <span class="small labell">(hall)</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="vall vall-template">{{item.template_without_user_scope.title}}</span>
                                        <span class="small labell">(template)</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="vall vall-worker">{{fullNameOfObj(item.worker_without_user_scope)}}</span>
                                        <span class="small labell">(worker)</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="vall vall-worker">{{getDurationStrHoursAndMinutes(item.right_duration)}}</span>
                                        <span class="small labell">(duration)</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="float-right">
                                    <actions :event="item"
                                        :day-data="getDayItem(i-1)"
                                        :ref="'actions_' + item.id"
                                        action-color="#549fb7"
                                        size="middle"
                                        :without-hover-bg="true"
                                        :dropdown-to-left="true"
                                        tooltip-placement="top"
                                        :disabled-items-with-line-through="true"
                                        :disabled-drop-menu-items-with-line-through="true"/>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- <tr>
                        <td colspan="4">
                            <div>ddddd</div>
                        </td>
                    </tr> -->
                    
                    <!-- <template v-if="isAllDaysEmpty && isTypeEvents">
                        ds
                    </template> -->
                    
                </tbody>
            </table>
            
        </div>
        
    </div>
</template>

<script>
    import MonthCell from "./MonthCell.vue";
    import Actions from "./event/Actions.vue";
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
            // isCurrentDate: function(k){
            //     return this.currentIsoWeekday == k;
            // },
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
            Actions,
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