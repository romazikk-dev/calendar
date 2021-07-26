<template>
    <div>
        
        <!-- <modal-more-events ref="modal_more_events" /> -->
            
        <div class="for-table day-calendar">
            
            <table cellspacing="0">
                <thead>
                    <tr>
                        <th :class="{'current-day': isCurrentDate}">
                            {{weekdayTitle}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                    <tr class="divider">
                        <td :class="{'current-day': isCurrentDate}"></td>
                    </tr>
                </tbody>
            </table>
            
            <table cellspacing="0">
                <tbody>
                    <tr>
                        <td :class="{'current-day': isCurrentDate}">
                            <month-cell v-if="date"
                                :counters-dis-transparency="true"
                                :counters-to-top="true"
                                :counters-bigger="true"
                                :right-placed-actions="true"
                                :events-in-row="true"
                                @clickMore="showModalMoreEvent($event)"
                                @cancel="cancelBook($event)"
                                :item="date"></month-cell>
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
        name: 'dayCalendar',
        mounted() {
            this.$store.dispatch('dates/setDayDates', this.startDates.day);
            
            // console.log(JSON.parse(JSON.stringify(2121212)));
            // console.log(JSON.parse(JSON.stringify(this.$store.getters['dates/day'])));
            // console.log(this.$store.getters['dates/day']);
            // return;
            
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
                date: null,
            };
        },
        computed: {
            dates: function(){
                return this.$store.getters['dates/day'];
            },
            weekdayTitle: function(){
                return this.weekdaysList[this.dates.isoWeekday - 1];
            },
            isCurrentDate: function(){
                return this.currentIsoWeekday == this.dates.isoWeekday;
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
            showModalMoreEvent: function(e){
                this.app.$refs.modal_more_events.show(e);
            },
            getDateItem: function(index){
                if(this.dates === null || typeof this.dates[index] === 'undefined')
                    return null;
                return this.dates[index];
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
            getDate: function(i,k,type = null){
                let date = this.dates[(((i*7) + k))-8];
                // let month = this.dates[(((i*7) + k))-8].month;
                if(type != null)
                    return date[type];
                return date;
            },
            getData: function(params = null){
                let startDate = moment(this.$store.getters['dates/interval'].firstDate).format('YYYY-MM-DD');
                let endDate = moment(this.$store.getters['dates/interval'].lastDate).format('YYYY-MM-DD');
                
                return this.app.getData(startDate, endDate, params).then((data) => {
                    // console.log(JSON.parse(JSON.stringify(data.data)));
                    if(typeof data.data !== 'undefined' && Array.isArray(data.data) && data.data.length === 1)
                        this.date = data.data[0];
                    // console.log(JSON.parse(JSON.stringify(this.date)));
                });
            },
        },
        components: {
            MonthCell,
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
        // min-width: 700px;
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