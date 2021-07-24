<template>
    <div>
        
        <div class="month-calendar">
            
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
                            <div class="cell-content">
                                <div class="day" :class="{'not-period': monthTwoDigits != getDate(i,k,'month')}">
                                    {{getDate(i,k,'day')}}
                                </div>
                                <template v-if="notPast(i,k)">
                                    <!-- <div v-if="getDate(i,k,'items')">
                                        dasdasda sda sdasd
                                    </div> -->
                                    <month-cell v-if="getDate(i,k,'items')"
                                        @clickMore="showModalMoreEvent($event)"
                                        @removed="onRemove($event)"
                                        :item="getDate(i,k)"></month-cell>
                                </template>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>
        
    </div>
</template>

<script>
    import MonthCell from "./MonthCell.vue";
    export default {
        name: 'monthCalendar',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
                // trigger: "click",
                trigger: "hover",
            });
        },
        mounted() {
            this.$store.dispatch('dates/setMonthDates', this.startDate);
            
            if(this.isNewEventMainFull){
                this.getData({
                    type: 'free',
                });
            }else if(this.movingEvent !== null){
                // alert(122);
                this.getData({
                    type: 'free',
                    exclude_ids: [this.movingEvent.id]
                });
            }else{
                this.getData();
            }
        },
        props: ['startDate'],
        data: function(){
            return {
                dates: null,
            };
        },
        computed: {
            monthTwoDigits: function(){
                return this.$store.getters['dates/month'].monthTwoDigits;
            },
            dataUpdater: function () {
                return this.$store.getters['updater/counter'];
            },
            search: function () {
                return this.$store.getters['filters/urlSearchPath'];
            },
        },
        methods: {
            showModalMoreEvent: function(e){
                // console.log(JSON.parse(JSON.stringify(e)));
                this.app.$refs.modal_more_events.show(e);
            },
            notPast: function(i,k){
                return true;
                
                let date = this.getDate(i,k);
                let dateMoment = moment(date.year+'-'+date.month+'-'+date.day, 'YYYY-MM-DD');
                let currentDateMoment = moment(this.currentDate);
                
                return dateMoment.diff(currentDateMoment, 'day') >= 0;
            },
            isCurrentDate: function(i,k){
                return this.currentYear == this.getDate(i,k,'year') &&
                this.currentMonth == this.getDate(i,k,'month') &&
                this.currentDay == this.getDate(i,k,'day');
            },
            getDate: function(i,k,type = null){
                let date = this.dates[(((i*7) + k))-8];
                if(type != null)
                    return date[type];
                return date;
            },
            getData: function(params = null){
                let startDate = moment(this.$store.getters['dates/interval'].firstDate).format('YYYY-MM-DD');
                let endDate = moment(this.$store.getters['dates/interval'].lastDate).format('YYYY-MM-DD');
                
                return this.app.getData(startDate, endDate, params).then((data) => {
                    this.dates = data.data;
                }).finally(() => {
                    $('#cancelBookModal').modal('hide');
                });
            },
        },
        components: {
            MonthCell,
        },
        watch: {
            dates: function () {
                
            },
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
    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>