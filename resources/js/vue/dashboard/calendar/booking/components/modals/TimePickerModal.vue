<template>
    <div class="modal fade" :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Day: <b>{{currentEventFilter.date}}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{duration}}
                    <loader ref="loader"></loader>            
                    <div>
                        <div class="alert alert-info alert-arrow" role="alert">
                            <div class="row">
                                <div class="col-sm-12 col">
                                    <div v-if="currentTemplateFilter.title">Title: <b>{{currentTemplateFilter.title}}</b></div>
                                </div>
                                <!-- <div class="col-sm-4 col">
                                    <div class="small" v-html="hintText"></div>
                                </div> -->
                            </div>
                        </div>
                        
                        <div v-if="currentTemplateFilter.duration">Duration: <b>{{templateDuration}}</b></div>
                        <div class="for-time-bar-fill pb-3">
                            <time-bar-fill ref="time_bar_duration"
                                @change="timeBarDurationChange($event)"
                                :durationInMinutes="duration" />
                        </div>
                        <div v-if="currentTemplateFilter.description">Description: <b>{{currentTemplateFilter.description}}</b></div>
                        <div>Time: <b>{{bookOn}}</b></div>
                        
                        <time-bar-new ref="time_bar_book"
                            :minInMinutes="startPeriodDatetime"
                            :maxInMinutes="endPeriodDatetime"
                            :stopper="duration"
                            :durationInMinutes="duration" />
                        
                        <time-bar :free-time-perc="freeTimePerc"
                            v-if="!successfullyBooked && !errorResponse"
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
                        
                        <!-- <div class="small">Book time:</div>
                        <time-bar :free-time-perc="freeTimePerc"
                            v-if="!successfullyBooked && !errorResponse"
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
                            @slider_enabled='timeBarSliderEnabled'></time-bar> -->
                            
                        <div v-if="successfullyBooked">
                            Your successfully requested to book you on choosen time, we will contact you for approving your booking. 
                        </div>
                        <div v-if="errorResponse" class="small text-danger">
                            {{errorResponse}}
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
                    <template v-if="!successfullyBooked && !errorResponse">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button @click.prevent="book"
                            type="button"
                            class="btn btn-success"
                            :disabled="bookButtonDisabled">Ok</button>
                    </template>
                    <template v-if="successfullyBooked || errorResponse">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </template>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    // import VueTimepicker from 'vue2-timepicker'
    // import 'vue2-timepicker/dist/VueTimepicker.css'
    import TimeBar from "./TimeBar.vue";
    import TimeBarFill from "./TimeBarFill.vue";
    import TimeBarNew from "./TimeBarNew.vue";
    import Loader from "../Loader.vue";
    export default {
        name: 'modalBookContent',
        mounted() {
            
            // console.log(111);
            // console.log(this.auth);
            // this.$refs['loader'].fadeOut(300);
            
            // this.createStyleForArrow();
            // this.setStyleForArrow();
            // this.setDuration();
            
            $("#" + this.modalId).on('show.bs.modal', () => {
                this.setDuration();
            });
            
            $("#" + this.modalId).on('shown.bs.modal', () => {
                // alert(111);
                // if(this.successfullyBooked)
                //     this.$refs['loader'].fadeOut(300);
                    
                // this.setInitValue();
                // this.$refs['time_bar'].recalculate();
                // console.log(JSON.parse(JSON.stringify(this.template)));
                this.bookOn = this.currentEventFilter.from;
                // this.modalOpened = true
                this.$refs['loader'].fadeOut(300);
                setTimeout(() => {
                    this.bookButtonDisabled = false;
                }, 300);
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                // this.modalOpened = false
                this.successfullyBooked = false;
                this.bookButtonDisabled = true;
                this.errorResponse = null;
                this.$refs['loader'].show();
                this.arrowPosition = 10;
                // this.setStyleForArrow();
                
                this.$refs.time_bar_duration.reset();
            });
            
            
            
        },
        // updated() {
        //     // alert(111);
        //     this.setDuration();
        //     // alert(this.duration);
        // },
        props: ['bookDate'],
        data: function(){
            return {
                modalId: 'timePickerModal',
                // modalOpened: false,
                bookButtonDisabled: true,
                successfullyBooked: false,
                bookOn: null,
                // template: filters.template,
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
                hintText: 'Move slider to pick a time.',
                errorResponse: null,
                
                barStart: null,
                barEnd: null,
                
                duration: 100,
            };
        },
        computed: {
            cookieItmTemplate: function(){
                return this.$store.getters['filters/template'];
            },
            // auth: function () {
            //     return this.isAuth();
            // },
            templateDuration: function () {
                let durationInMin = parseInt(this.currentTemplateFilter.duration/60);
                // alert(durationInMin);
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(durationInMin);
            },
            templateDurationMinutes: function () {
                return parseInt(this.currentTemplateFilter.duration/60);
            },
            // barStart: function () {
            //     return "09:00";
            //     return this.currentEventFilter != null ? this.currentEventFilter.from : '';
            // },
            // barEnd: function () {
            //     return "20:00";
            //     return this.currentEventFilter != null ? this.currentEventFilter.to : '';
            // },
            barPreEnd: function () {
                // return 1;
                if(this.preEndPeriodDatetime == null)
                    return '';
                
                let preEndPeriodDatetime = this.preEndPeriodDatetime;
                // alert(preEndPeriodDatetime);
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(preEndPeriodDatetime);
            },
            freeTimePerc: function () {
                let onePerc = parseInt(this.endPeriodDatetime/100);
                let percents = parseInt(this.preEndPeriodDatetime/onePerc);
                return percents; 
            },
            // bookingDate: function () {
            //     if(this.bookDate != null){
            //         return this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day;
            //     }
            // },
            hourRange: function () {
                if(this.currentEventFilter){
                    let fromArr = this.currentEventFilter.from.split(':');
                    let toArr = this.currentEventFilter.to.split(':');
                    let from = parseInt(fromArr[0]);
                    let to = parseInt(toArr[0]);
                    if(parseInt(toArr[1]) == 0)
                        to--;
                        
                    return [[from, to]];
                }else{
                    return [[0,24]];
                }
            },
            minuteRange: function () {
                // return [[0,60]];
                if(this.currentEventFilter){
                    let fromArr = this.currentEventFilter.from.split(':');
                    let toArr = this.currentEventFilter.to.split(':');
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
            timeBarDurationChange: function (e){
                console.log(e);
                this.duration = e;
            },
            setDuration: function (e){
                if(typeof this.currentTemplateFilter !== 'undefined' && this.currentTemplateFilter !== null){
                    this.duration = Math.floor(this.currentTemplateFilter.duration/60);
                }
                // alert(this.duration);
                // this.$refs.time_bar_duration.reset();
                // return this.$store.getters['filters/template'];
            },
            show: function (e){
                this.barStart = e.from;
                this.barEnd = e.to;
                this.setInitValue(e);
                this.$refs['time_bar'].recalculate();
                $('#' + this.modalId).modal('show');
            },
            book: function (){
                let componentApp = this.getParentComponentByName(this, 'app');
                
                this.bookButtonDisabled = true;
                this.$refs['loader'].showTranparent();
                
                componentApp.bookOn(this.bookingDate, this.bookOn, (response) => {
                    // this.onBooked(response);
                    // alert(111);
                    // errorResponse
                    // let data = response.data;
                    if(typeof response.data.error === 'undefined'){
                        setTimeout(() => {
                            this.successfullyBooked = true;
                        }, 300);
                    }else{
                        setTimeout(() => {
                            this.errorResponse = response.data.error;
                        }, 300);
                    }
                    // console.log(response);
                }, () => {}, () => {
                    
                    // console.log('always');
                    this.$refs['loader'].fadeOut(300);
                    setTimeout(() => {
                        // this.successfullyBooked = true;
                        this.bookButtonDisabled = false;
                    }, 300);
                    
                });
            },
            timeBarSliderEnabled: function (){
                // console.log('timeBarSliderEnabled');
                this.hintText = 'Move slider to pick a time.';
            },
            timeBarSliderDisabled: function (){
                // console.log('timeBarSliderDisabled');
                this.hintText = 'In this period you can only book on <b>' + this.currentEventFilter.from + '</b>.';
            },
            // createStyleForArrow: function (){
            //     this.s = document.createElement("style");
            //     document.head.appendChild(this.s);
            // },
            // setStyleForArrow: function (){
            //     // console.log(33333);
            //     this.s.textContent = `
            //         .alert-arrow:after {
            //             left: ` + this.arrowPosition + `px!important;
            //         }
            //     `;
            // },
            timeBarChange: function (event) {
                clearTimeout(this.timeBarChangeTimeout);
                this.timeBarChangeTimeout = setTimeout(() => {
                    // console.log(event.sliderValue);
                    let minPerc = event.freeMinutes/event.freeMinutesForStart;
                    let minutes = Math.round(event.sliderValue/minPerc)
                    this.bookOn = calendarHelper.time.composeHourMinuteTimeFromMinutes(this.startPeriodDatetime + minutes);
                    
                    let widthOnePerc = (event.sliderWidth-event.sliderThumbWidth)/100;
                    let minutesOnePerc = event.freeMinutesForStart/100;
                    let minutesPerc = minutes/minutesOnePerc;
                    let offset = widthOnePerc*minutesPerc;
                    // console.log(offset);
                    // this.arrowPosition = offset + 10;
                    this.arrowPosition = offset + 7;
                    // this.setStyleForArrow();
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
                // console.log(calendarHelper.time.composeHourMinuteTimeFromMinutes(this.startPeriodDatetime + minutes));
                // this.$emit('change', {
                //     freeMinutes: this.freeMinutes,
                //     freeMinutesForStart: this.freeMinutesForStart,
                // });
            },
            // composeHourMinuteTimeFromMinutes: function (mins) {
            //     let minutes = mins%60;
            //     let hours = parseInt(mins/60);
            // 
            //     if(minutes <= 0){
            //         minutes = '00'
            //     }else if(minutes > 0 && minutes < 10){
            //         minutes = '0' + minutes;
            //     }else{
            //         minutes = minutes;
            //     }
            // 
            //     if(hours <= 0){
            //         hours = '00'
            //     }else if(hours > 0 && hours < 10){
            //         hours = '0' + hours;
            //     }else{
            //         hours = hours;
            //     }
            // 
            //     return hours + ':' + minutes;
            // },
            input: function(val){
                if(val.HH != '')
                    this.choosedH = val.HH;
                if(val.mm != '')
                    this.choosedM = val.mm;
                // console.log(val);
            },
            setInitValue: function(e){
                // alert(this.currentEventFilter.from);
                let fromArr = e.from.split(':');
                let toArr = e.to.split(':');
                this.initValue.HH = fromArr[0];
                this.initValue.mm = fromArr[1];
                
                let fromHour = parseInt(fromArr[0]);
                let fromMinutes = parseInt(fromArr[1]);
                let startPeriodDatetime = (fromHour <= 0) ? 0 : fromHour*60;
                startPeriodDatetime += fromMinutes;
                this.startPeriodDatetime = startPeriodDatetime;
                
                // alert(this.startPeriodDatetime);
                
                let toHour = parseInt(toArr[0]);
                let toMinutes = parseInt(toArr[1]);
                let endPeriodDatetime = (toHour <= 0) ? 0 : toHour*60;
                endPeriodDatetime += toMinutes;
                this.endPeriodDatetime = endPeriodDatetime;
                this.preEndPeriodDatetime = this.endPeriodDatetime - parseInt(this.currentTemplateFilter.duration/60);
            },
        },
        components: {
            // VueTimepicker,
            TimeBar,
            TimeBarFill,
            TimeBarNew,
            Loader,
        },
        watch: {
            // auth: function(newOne, oldOne) {
            //     // console.log('auth changed: ' + auth);
            //     this.$refs['loader'].show();
            //     setTimeout(() => {
            //         this.$refs['loader'].fadeOut(300);
            //     }, 200);
            //     // this.$refs['loader'].fadeOut(300);
            //     // console.log(this.auth);
        	// 	// console.log("Title changed from " + newOne + " to " + oldOne)
        	// },
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
    	left: 10px;
    	border: solid transparent;
    	content: "";
    	height: 0;
    	width: 0;
    	position: absolute;
    	pointer-events: none;
    	border-color: rgba(190, 229, 235, 0);
    	border-top-color: #d1ecf1;
    	border-width: 6px;
    	margin-left: -6px;
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