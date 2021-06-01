<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Booking: <b>{{bookingDate}}</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <loader ref="loader"></loader>            
            <div>
                <div class="alert alert-info alert-arrow" role="alert">
                    <div class="row">
                        <div class="col-sm-8 col">
                            <div v-if="template.title">Title: <b>{{template.title}}</b></div>
                            <div v-if="template.duration">Duration: <b>{{templateDuration}}</b></div>
                            <div v-if="template.description">Description: <b>{{template.description}}</b></div>
                            <div>Book on: <b>{{bookOn}}</b></div>
                        </div>
                        <div class="col-sm-4 col">
                            <div class="small" v-html="hintText"></div>
                        </div>
                    </div>
                </div>
                <time-bar :free-time-perc="freeTimePerc"
                    v-if="!successfullyBooked"
                    ref="time_bar"
                    :start="barStart"
                    :end="barEnd"
                    :pre-end="barPreEnd"
                    :start-minutes="startPeriodDatetime"
                    :end-minutes="endPeriodDatetime"
                    :pre-end-minutes="preEndPeriodDatetime"
                    :duration="templateDuration"
                    :duration-minutes="templateDurationMinutes"
                    @change="timeBarChange($event)"
                    @slider_disabled='timeBarSliderDisabled'
                    @slider_enabled='timeBarSliderEnabled'></time-bar>
                <div v-if="successfullyBooked">
                    Your successfully requested to book you on choosen time, we will contact you for approving your booking. 
                </div>
                <div class="row">
                    
                    <!-- <div class="col-sm-12 col pt-2">
                        <vue-timepicker
                            format="HH:mm"
                            :minute-interval="5"
                            @input="input"
                            :hour-range="hourRange"
                            :minute-range="minuteRange"
                            :input-class="{'form-control': true}"
                            :input-style="{'width': '100%'}"
                            :hide-clear-button="true"
                            hide-disabled-items
                            v-model="initValue"></vue-timepicker>
                    </div> -->
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <template v-if="!successfullyBooked">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button @click.prevent="book"
                    type="button"
                    class="btn btn-success"
                    :disabled="bookButtonDisabled">Book</button>
            </template>
            <template v-if="successfullyBooked">
                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
            </template>
        </div>
    </div>
</template>

<script>
    import VueTimepicker from 'vue2-timepicker'
    import 'vue2-timepicker/dist/VueTimepicker.css'
    import TimeBar from "./TimeBar.vue";
    import Loader from "./Loader.vue";
    export default {
        name: 'modalBookContent',
        mounted() {
            
            console.log(111);
            console.log(this.auth);
            // this.$refs['loader'].fadeOut(300);
            
            this.createStyleForArrow();
            this.setStyleForArrow();
            
            
            
            // console.log('dasda:' + this.bookDate);
            // let interval = setInterval(() => {
            //     console.log(11);
            //     if(this.bookTimePeriod != null){
            //         clearInterval(interval);
            //         this.setInitValue();
            //     }
            // }, 500);
            
            // JSON.parse(JSON.stringify(filters.template));
            
            // console.log(JSON.parse(JSON.stringify(this.bookDate)));
            // console.log(JSON.parse(JSON.stringify(this.bookDate)));
            // if(!this.successfullyBooked)
            //     this.$refs['loader'].show();
            
            // $("#bookModal").on('show.bs.modal', () => {
            //     this.$refs['loader'].show();
            // });
            
            $("#bookModal").on('shown.bs.modal', () => {
                if(this.successfullyBooked)
                    this.$refs['loader'].fadeOut(300);
                    
                this.setInitValue();
                this.$refs['time_bar'].recalculate();
                
                // this.$refs['loader'].show();
                // console.log(JSON.parse(JSON.stringify(this.template)));
                // console.log(this.bookDate);
                // console.log(JSON.parse(JSON.stringify(this.bookDate)));
                // console.log(JSON.parse(JSON.stringify(this.bookTimePeriod)));
                // this.startPeriodDate = new Date(
                //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
                // );
                // let startPeriodDate = moment(
                //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
                // );
                // let timezoneOffset = Math.abs(this.startPeriodDate.getTimezoneOffset());
                // this.startPeriodDate = startPeriodDate.add(timezoneOffset, 'minutes').toDate();
                // console.log(JSON.parse(JSON.stringify(this.bookTimePeriod)));
                this.bookOn = this.bookTimePeriod.from;
                // this.modalOpened = true
                this.$refs['loader'].fadeOut(300);
                setTimeout(() => {
                    this.bookButtonDisabled = false;
                }, 300);
                // console.log(this.$refs['time_bar'].sliderDisabled);
                // console.log(this.startPeriodDate.getTimezoneOffset());
            });
            
            $("#bookModal").on('hidden.bs.modal', () => {
                // this.modalOpened = false
                this.successfullyBooked = false;
                this.bookButtonDisabled = true;
                this.$refs['loader'].show();
                this.arrowPosition = 10;
                this.setStyleForArrow();
            });
            
            
            
        },
        props: ['bookDate','bookTimePeriod'],
        data: function(){
            return {
                // modalOpened: false,
                bookButtonDisabled: true,
                successfullyBooked: false,
                bookOn: null,
                template: filters.template,
                choosedH: null,
                choosedM: null,
                initValue: {
                    HH: '',
                    mm: '',
                },
                startPeriodDatetime: null,
                endPeriodDatetime: null,
                preEndPeriodDatetime: null,
                timeBarChangeTimeout: null,
                s: null,
                arrowPosition: 10,
                hintText: 'Move slider to choose time for booking.'
            };
        },
        computed: {
            // userId: function(){
            //     return this.$store.getters['owner/ownerId'];
            // },
            auth: function () {
                return this.isAuth();
            },
            templateDuration: function () {
                let durationInMin = parseInt(this.template.duration/60);
                return this.composeHourMinuteTimeFromMinutes(durationInMin);
            },
            templateDurationMinutes: function () {
                return parseInt(this.template.duration/60);
            },
            barStart: function () {
                return this.bookTimePeriod != null ? this.bookTimePeriod.from : '';
            },
            barEnd: function () {
                return this.bookTimePeriod != null ? this.bookTimePeriod.to : '';
            },
            barPreEnd: function () {
                // return 1;
                if(this.preEndPeriodDatetime == null)
                    return '';
                
                let preEndPeriodDatetime = this.preEndPeriodDatetime;
                return this.composeHourMinuteTimeFromMinutes(preEndPeriodDatetime);
                // let minutes = preEndPeriodDatetime%60;
                // let hours = parseInt(preEndPeriodDatetime/60);
                // 
                // if(minutes <= 0){
                //     minutes = '00'
                // }else if(minutes > 0 && minutes < 10){
                //     minutes = '0' + minutes;
                // }else{
                //     minutes = minutes;
                // }
                // 
                // if(hours <= 0){
                //     hours = '00'
                // }else if(hours > 0 && hours < 10){
                //     hours = '0' + hours;
                // }else{
                //     hours = hours;
                // }
                // 
                // return hours + ':' + minutes;
            },
            freeTimePerc: function () {
                let onePerc = parseInt(this.endPeriodDatetime/100);
                let percents = parseInt(this.preEndPeriodDatetime/onePerc);
                return percents; 
            },
            bookingDate: function () {
                if(this.bookDate != null){
                    return this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day;
                }
            },
            hourRange: function () {
                if(this.bookTimePeriod){
                    let fromArr = this.bookTimePeriod.from.split(':');
                    let toArr = this.bookTimePeriod.to.split(':');
                    let from = parseInt(fromArr[0]);
                    let to = parseInt(toArr[0]);
                    if(parseInt(toArr[1]) == 0)
                        to--;
                    
                    // if(parseInt(toArr[1]) == 0)
                    //     toH--;
                        
                    return [[from, to]];
                }else{
                    return [[0,24]];
                }
            },
            minuteRange: function () {
                // return [[0,60]];
                if(this.bookTimePeriod){
                    let fromArr = this.bookTimePeriod.from.split(':');
                    let toArr = this.bookTimePeriod.to.split(':');
                    let fromH = parseInt(fromArr[0]);
                    let fromM = parseInt(fromArr[1]);
                    let toH = parseInt(toArr[0]);
                    let toM = parseInt(toArr[1]) == 0 ? 59 : parseInt(toArr[1]);
                    
                    // if(parseInt(toArr[1]) == 0)
                    //     to--;
                    if(toH != this.choosedH && fromH != toH)
                        toM = 59;
                        
                    return [[fromM, toM]];
                }else{
                    return [[0,60]];
                }
            },
        },
        methods: {
            book: function (){
                let componentApp = this.getParentComponentByName(this, 'app');
                
                this.bookButtonDisabled = true;
                this.$refs['loader'].showTranparent();
                
                componentApp.bookOn(this.bookingDate, this.bookOn, (response) => {
                    // this.onBooked(response);
                }, () => {}, () => {
                    
                    console.log('always');
                    this.$refs['loader'].fadeOut(300);
                    setTimeout(() => {
                        this.successfullyBooked = true;
                        this.bookButtonDisabled = false;
                    }, 300);
                    
                });
            },
            timeBarSliderEnabled: function (){
                // console.log('timeBarSliderEnabled');
                this.hintText = 'Move slider to choose time for booking.';
            },
            timeBarSliderDisabled: function (){
                // console.log('timeBarSliderDisabled');
                this.hintText = 'In this period you can only book on <b>' + this.bookTimePeriod.from + '</b>.';
            },
            createStyleForArrow: function (){
                this.s = document.createElement("style");
                document.head.appendChild(this.s);
            },
            setStyleForArrow: function (){
                // console.log(33333);
                this.s.textContent = `
                    .alert-arrow:after {
                        left: ` + this.arrowPosition + `px!important;
                    }
                `;
            },
            timeBarChange: function (event) {
                clearTimeout(this.timeBarChangeTimeout);
                this.timeBarChangeTimeout = setTimeout(() => {
                    // console.log(event.sliderValue);
                    let minPerc = event.freeMinutes/event.freeMinutesForStart;
                    let minutes = Math.round(event.sliderValue/minPerc)
                    this.bookOn = this.composeHourMinuteTimeFromMinutes(this.startPeriodDatetime + minutes);
                    
                    let widthOnePerc = (event.sliderWidth-event.sliderThumbWidth)/100;
                    let minutesOnePerc = event.freeMinutesForStart/100;
                    let minutesPerc = minutes/minutesOnePerc;
                    let offset = widthOnePerc*minutesPerc;
                    // console.log(offset);
                    this.arrowPosition = offset + 10;
                    this.setStyleForArrow();
                }, 0);
                
                // this.$emit('change', {
                //     freeMinutes: this.freeMinutes,
                //     freeMinutesForStart: this.freeMinutesForStart,
                //     sliderValue: sliderValue,
                //     sliderWidth: this.sliderWidth,
                //     sliderThumbWidth: this.sliderThumbWidth,
                // });
                
                
                // let minPerc = event.freeMinutes/event.freeMinutesForStart;
                // let minutes = Math.round(event.sliderValue/minPerc)
                // console.log(this.composeHourMinuteTimeFromMinutes(this.startPeriodDatetime + minutes));
                // this.$emit('change', {
                //     freeMinutes: this.freeMinutes,
                //     freeMinutesForStart: this.freeMinutesForStart,
                // });
            },
            composeHourMinuteTimeFromMinutes: function (mins) {
                let minutes = mins%60;
                let hours = parseInt(mins/60);
                
                if(minutes <= 0){
                    minutes = '00'
                }else if(minutes > 0 && minutes < 10){
                    minutes = '0' + minutes;
                }else{
                    minutes = minutes;
                }
                
                if(hours <= 0){
                    hours = '00'
                }else if(hours > 0 && hours < 10){
                    hours = '0' + hours;
                }else{
                    hours = hours;
                }
                
                return hours + ':' + minutes;
            },
            input: function(val){
                if(val.HH != '')
                    this.choosedH = val.HH;
                if(val.mm != '')
                    this.choosedM = val.mm;
                // console.log(val);
            },
            setInitValue: function(){
                let fromArr = this.bookTimePeriod.from.split(':');
                let toArr = this.bookTimePeriod.to.split(':');
                this.initValue.HH = fromArr[0];
                this.initValue.mm = fromArr[1];
                
                // this.startPeriodDate = new Date(
                //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
                // );
                // let fromArr = this.bookTimePeriod.from.split(':');
                let fromHour = parseInt(fromArr[0]);
                let fromMinutes = parseInt(fromArr[1]);
                let startPeriodDatetime = (fromHour <= 0) ? 0 : fromHour*60;
                startPeriodDatetime += fromMinutes;
                this.startPeriodDatetime = startPeriodDatetime;
                
                let toHour = parseInt(toArr[0]);
                let toMinutes = parseInt(toArr[1]);
                let endPeriodDatetime = (toHour <= 0) ? 0 : toHour*60;
                endPeriodDatetime += toMinutes;
                this.endPeriodDatetime = endPeriodDatetime;
                this.preEndPeriodDatetime = this.endPeriodDatetime - parseInt(filters.template.duration/60);
                
                // console.log(this.startPeriodDatetime);
                // console.log(this.endPeriodDatetime);
                // console.log(this.preEndPeriodDatetime);
                
                // console.log(this.startPeriodDatetime);
                // console.log(JSON.parse(JSON.stringify(filters.template.duration)));
                
                // let startPeriodDatetime = 
                // let startPeriodDate = new Date(
                //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
                // );
                // let startPeriodDateMoment = moment(startPeriodDate);
                // let timezoneOffset = Math.abs(startPeriodDate.getTimezoneOffset());
                // this.startPeriodDate = startPeriodDateMoment.add(timezoneOffset, 'minutes').toDate();
                // console.log(JSON.parse(JSON.stringify(this.startPeriodDate)));
            },
        },
        components: {
            VueTimepicker,
            TimeBar,
            Loader
        },
        watch: {
            auth: function(newOne, oldOne) {
                console.log('auth changed: ' + auth);
                this.$refs['loader'].show();
                setTimeout(() => {
                    this.$refs['loader'].fadeOut(300);
                }, 200);
                // this.$refs['loader'].fadeOut(300);
                // console.log(this.auth);
        		// console.log("Title changed from " + newOne + " to " + oldOne)
        	},
            // initValue: (newOne, oldOne) => {
            //     console.log(helper.parse(newOne));
        	// 	// console.log("Title changed from " + newOne + " to " + oldOne)
        	// },
    	}
    }
</script>

<style scoped>
    .vue__time-picker, .vue__time-picker input{
        width: 100%!important;
    }
    .alert-arrow {
    	position: relative;
    }
    .alert-arrow:after {
    	top: 100%;
    	left: 20px;
    	border: solid transparent;
    	content: "";
    	height: 0;
    	width: 0;
    	position: absolute;
    	pointer-events: none;
    	border-color: rgba(190, 229, 235, 0);
    	border-top-color: #d1ecf1;
    	border-width: 10px;
    	margin-left: -10px;
    }
    .modal-body{
        position: relative;
    }
    .modal-header, .modal-footer{
        background-color: #6c757d;
        color: white;
    }
    .modal-header{
        position: relative;
    }
    .close{
        font-size: 60px;
        color: #fff;
        opacity: .7;
        transition: opacity .3s ease;
        line-height: .8em;
        padding: 0px;
        margin: 0px;
        position: absolute;
        top: 0px;
        right: 0px;
        height: 60px;
        width: 60px;
    }
    .close span{
        position: absolute;
        top: 0px;
        right: 0px;
        height: 60px;
        width: 60px;
    }
    .close:hover{
        color: #fff;
        opacity: 1!important;
    }
    .modal-title{
        font-weight: normal;
        color: #f4f4f4;
    }
    .modal-title b{
        color: white;
    }
    .small{
        line-height: 1.2em!important;
    }
</style>