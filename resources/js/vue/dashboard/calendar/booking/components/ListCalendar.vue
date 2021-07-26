<template>
    <div>
        
        <div class="list-calendar">
            
            <table class="contt" cellspacing="0">
                <tbody>
                    
                    <template v-for="(i, index) in 7" v-if="getDayItem(index) && getDayItem(index).type == 'free'">
                        <tr class="title">
                            <td colspan="4">
                                <a href="#" @click.prevent
                                    class="event-weekday">
                                        {{weekdaysList[index]}}
                                </a>
                                <a href="#" @click.prevent
                                    class="event-date">
                                        {{datesPerWeekday[index]}}
                                </a>
                            </td>
                        </tr>
                        <tr class="event free" v-for="item in getDayItem(i-1).items">
                            <td>
                                {{item.from}} - {{item.to}}
                            </td>
                            <td>
                                <div class="status"></div>
                            </td>
                            <td>
                                <a href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Free time</a>
                            </td>
                            <td>
                                <div class="float-right">
                                    <a href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Pick</a>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    
                    
                    <template v-for="(i, index) in 7" v-if="isDayItem(index, 'events')">
                        <tr class="title">
                            <td colspan="4">
                                <a href="#" @click.prevent
                                    class="event-weekday">
                                        {{weekdaysList[index]}}
                                </a>
                                <a href="#" @click.prevent
                                    class="event-date">
                                        {{datesPerWeekday[index]}}
                                </a>
                            </td>
                        </tr>
                        <tr class="event" v-for="item in getDayItem(index).items"
                        :class="{
                            'approved': item.approved
                        }">
                            <td>
                                {{item.from}} - {{item.to}}
                            </td>
                            <td>
                                <div class="status"></div>
                            </td>
                            <td>
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
                                        :dropdown-to-left="true"/>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
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
            
            if(this.isNewEventMainFull){
                this.getData({
                    type: 'free',
                });
            }else if(this.movingEvent !== null){
                this.getData({
                    type: 'free',
                    exclude_ids: [this.movingEvent.id]
                });
            }else{
                this.getData();
            }
        },
        updated: function () {},
        // props: ['startDate'],
        data: function(){
            return {
                data: null,
            };
        },
        computed: {
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
            isDayItem: function (index, type = 'events') {
                let dayItem = this.getDayItem(index);
                return ['events','free'].includes(type) && dayItem !== null &&
                this.isProp(dayItem.items) && typeof dayItem.type !== 'undefined' &&
                dayItem.type.toLowerCase() == type.toLowerCase();
            },
            onClickPickFree: function (dayData, event) {
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