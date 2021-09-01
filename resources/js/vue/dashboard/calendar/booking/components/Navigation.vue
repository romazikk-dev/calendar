<template>
    <div class="for-navigation">
        <div class="navigation">
            
            <div class="left-part float-left">
                <button @click.prevent="goPrevious" type="button" class="btn btn-sm btn-primary float-left go-previous" :disabled="!canGoToPrevious">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </button>
                <button @click.prevent="goNext" type="button" class="btn btn-sm btn-primary float-left go-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
                <button @click.prevent="goToday" type="button" class="btn btn-sm btn-secondary float-left go-today" :disabled="!canGoToPrevious">
                    {{getText('text.today')}}
                </button>
                <button :id="toDateCalendar.initId" type="button" class="btn btn-sm btn-light float-left go-to-date">
                    {{getText('text.to_date')}}
                </button>
            </div>
            
            <div class="right-part float-right">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button v-for="item in views"
                        :class="{
                            active: item.toLowerCase() == view.toLowerCase(),
                            disabled: item.toLowerCase() == view.toLowerCase()
                        }"
                        :disabled="item.toLowerCase() == view.toLowerCase()"
                        @click.prevent="changeView(item)"
                        type="button"
                        class="btn btn-sm btn-secondary">{{getText('text.' + item)}}</button>
                </div>
            </div>
            
            <div class="calendar-title">
                <span class="first-letter-uppercase d-inline-block">
                    {{calendarTitle}}
                </span>
            </div>
            
        </div>
        <div class="clearfix"></div>
    </div>
</template>

<script>
    export default {
        name: 'navigation',
        mounted() {
            this.regToDateCalendar();
        },
        // props: ['calendarTitle'],
        data: function(){
            return {
                // toDateCalendar: null,
                // toDateCalendarId: 'goToDateBtn',
                // toDateCalendarDate: null,
                // toDateCalendarMonth: null,
                toDateCalendar: {
                    instance: null,
                    pickedDate: null,
                    initId: 'goToDateBtn',
                    initIdHash: '#goToDateBtn',
                    day: null,
                    month: null,
                    bgClass: "to-date-calendar-background",
                    format: "YYYY-MM-DD",
                }
            };
        },
        computed: {
            calendarTitle: function () {
                if(this.view == 'month'){
                    let titleMoment = moment(this.$store.getters['dates/month'].firstDate);
                    let key = 'text.' + titleMoment.format('MMMM').toLowerCase().trim();
                    return this.capitalizeFirstLetter(this.getText(key)) + titleMoment.format(' YYYY');
                }else if(['week','list'].includes(this.view)){
                    let firstWeekdayMonth = new Date(this.dateInterval.firstDate).getMonth();
                    let lastWeekdayMonth = new Date(this.dateInterval.lastDate).getMonth();
                    if(firstWeekdayMonth == lastWeekdayMonth){
                        let firstWeekdayDay = moment(this.dateInterval.firstDate).format('D');
                        let lastWeekdayDay = moment(this.dateInterval.lastDate).format('D');
                        let key = 'text.' + moment(this.dateInterval.firstDate).format('MMMM').toLowerCase().trim();
                        let monthTitle = this.capitalizeFirstLetter(this.getText(key));
                        return firstWeekdayDay + ' - ' + lastWeekdayDay + ' ' + monthTitle;
                    }else{
                        let firstWeekdayMoment = moment(this.dateInterval.firstDate);
                        let lastWeekdayMoment = moment(this.dateInterval.lastDate);
                        let firstMonthKey = 'text.' + firstWeekdayMoment.format('MMMM').toLowerCase().trim();
                        let lastMonthKey = 'text.' + lastWeekdayMoment.format('MMMM').toLowerCase().trim();
                        let firstWeekdayMonthTitle = moment(this.dateInterval.firstDate).format('D ') +
                            this.capitalizeFirstLetter(this.getText(firstMonthKey));
                        let lastWeekdayMonthTitle = moment(this.dateInterval.lastDate).format('D ') +
                            this.capitalizeFirstLetter(this.getText(lastMonthKey));
                        return firstWeekdayMonthTitle + ' - ' + lastWeekdayMonthTitle;
                    }
                }else if(this.view == 'day' || this.view == 'list'){
                    let dayMoment = moment(this.dateInterval.firstDate)
                    let monthKey = 'text.' + dayMoment.format('MMM').toLowerCase().trim();
                    return this.capitalizeFirstLetter(this.getText(monthKey)) + dayMoment.format(' D, YYYY');
                }
            },
        },
        methods: {
            regToDateCalendar: function(){
                
                // alert(this.currentDate);
                let calendar;
                let _this = this;
                // alert(this.currentDay);
                this.toDateCalendar.day = parseInt(moment().format('D'));
                this.toDateCalendar.month = parseInt(moment().format('M'));
                // let pickedDate = null;
                // let toDateCalendarBgClass = "to-date-calendar-background";
                this.toDateCalendar.instance = MCDatepicker.create({
                    el: this.toDateCalendar.initIdHash,
                    dateFormat: this.toDateCalendar.format,
                    minDate: new Date(),
                    // selectedDate: new Date(),
                });
                
                // calendar = this.toDateCalendar.instance;
                
                this.toDateCalendar.instance.onOpen(() => {
                    // alert(this.toDateCalendar.day);
                    // setTimeout(() => {
                    //     this.toDateCalendar.instance.setMonth(this.toDateCalendar.month);
                    //     this.toDateCalendar.instance.setDate(this.toDateCalendar.day);
                    // }, 1000);
                    
                    var backgroundElement = document.createElement("div");
                    backgroundElement.className = this.toDateCalendar.bgClass;
                    // backgroundElement.setAttribute("id", "toDateCalendarBackground");
                    $("body").append(backgroundElement);
                    $(this.toDateCalendar.initIdHash).blur();
                });
                
                this.toDateCalendar.instance.onClose(() => {
                    // this.toDateCalendar.instance.setMonth(this.toDateCalendar.month);
                    // this.toDateCalendar.instance.setDate(this.toDateCalendar.day);
                    onCloseToDateCalendarModal();
                });
                
                $("body").on('click', '.' + this.toDateCalendar.bgClass, () => {
                    // this.toDateCalendar.close();
                    // this.toDateCalendar.setFullDate(null);
                    // this.toDateCalendar.reset();
                    // this.toDateCalendar.setDate(5);
                    this.toDateCalendar.instance.close();
                    onCloseToDateCalendarModal();
                });
                
                this.toDateCalendar.instance.onSelect((date, formatedDate) => this.goToDate(date, formatedDate));
                
                function onCloseToDateCalendarModal(){
                    $("body").find('.' + _this.toDateCalendar.bgClass).remove();
                    // _this.toDateCalendar.destroy();
                }
            },
            changeView: function(view){
                this.$store.commit('view/setView', view);
            },
            goToDate: function(date, formatedDate){
                // console.log(JSON.parse(JSON.stringify(moment(formatedDate).toDate())));
                this.$store.dispatch('dates/setDates', date);
                // this.$store.dispatch('dates/setAllDatesToOneDate', date);
                this.calendar.getData();
                // this.app.$refs.modal_to_date.show();
            },
        },
        components: {},
        watch: {
            // monthStartDate: function (val) {
            //     // this.toDateCalendar.setDate(moment(val).format('D'));
            //     let day = moment(val).format('D');
            //     let month = moment(val).format('M');
            //     console.log(JSON.parse(JSON.stringify(month)));
            //     this.toDateCalendar.day = parseInt(day);
            //     this.toDateCalendar.month = parseInt(month) - 1;
            // 
            //     // this.toDateCalendar.instance.setMonth(this.toDateCalendar.month);
            //     // this.toDateCalendar.instance.setDate(this.toDateCalendar.day);
            //     // this.toDateCalendar.setDate(parseInt(day));
            //     // console.log(JSON.parse(JSON.stringify('monthStartDate')));
            //     // console.log(parseInt(moment(val).format('D')));
            // }
        }
    }
</script>

<style lang="scss">
    .to-date-calendar-background{
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0, 0.4);
        z-index: 100;
    }
</style>

<style lang="scss" scoped>
    #viewDropdown{
        .dropdown-toggle{
            text-transform: capitalize;
        }
        .dropdown-item{
            text-transform: capitalize;
        }
    }

    .navigation{
        .left-part{
            button{
                // display: none;
                &.go-previous{
                    border-radius: .2rem 0 0 .2rem!important;
                }
                &.go-next{
                    border-radius: 0 .2rem .2rem 0;
                }
                &.go-today{
                    margin-left: 6px;
                    margin-right: 6px;
                }
            }
        }
    }
</style>