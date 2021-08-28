<template>
    <div class="modal fade modal-custom-dark-header-footer" :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="small">Free time on:</span><br />
                        <b>{{getHumanReadableDate(dateObj)}}</b>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <loader ref="loader"></loader>
                    <info-alert />
                    <div>
                        <div class="duration-display">
                            Duration:
                            <b>{{durationStrHoursAndMinutes}}</b><br />
                            <span class="small">
                                <i>{{realDdurationStrHoursAndMinutes}} (current duration)</i>
                            </span>
                        </div>
                        <div class="for-time-bar-fill pb-3">
                            <time-bar-fill ref="time_bar_duration"
                                :stopper="timeBarDurationStopper"
                                @change="timeBarDurationChange($event)"
                                :durationInMinutes="duration"
                                :realDuration="realDuration" />
                        </div>
                        <div>Time: <b>{{choosenTime}}</b></div>
                        <time-bar-new ref="time_bar_book"
                            @change="timeBarBookChange($event)"
                            :minInMinutes="startPeriodDatetime"
                            :maxInMinutes="endPeriodDatetime"
                            :stopper="duration"
                            :durationInMinutes="startPeriodDatetime" />
                            
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="onClickOkBtn"
                        type="button"
                        class="btn btn-sm btn-success">Ok</button>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    import InfoAlert from "../event/InfoAlert.vue";
    import TimeBar from "./TimeBar.vue";
    import TimeBarFill from "./TimeBarFill.vue";
    import TimeBarNew from "./TimeBarNew.vue";
    import Loader from "../Loader.vue";
    export default {
        name: 'modalBookContent',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
                trigger: "hover",
            });
        },
        mounted() {
            $("#" + this.modalId).on('show.bs.modal', () => {
                this.setDuration();
                this.setChoosenTime();
            });
            
            $("#" + this.modalId).on('shown.bs.modal', () => {
                this.$refs['loader'].fadeOut(300);
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                this.errorResponse = null;
                this.$refs['loader'].show();
                
                this.$refs.time_bar_duration.reset();
                this.$refs.time_bar_book.reset();
                
                $('#' + this.modalId).removeAttr('style');
            });
            
            $('#' + this.modalId).draggable({
                handle: ".modal-header, .modal-footer"
            });
        },
        // props: ['bookDate'],
        data: function(){
            return {
                // app: null,
                
                modalId: 'timePickerModal',
                
                errorResponse: null,
                
                duration: 100,
                realDuration: null,
                
                event: null,
                choosenTime: null,
            };
        },
        computed: {
            timeBarDurationStopper: function () {
                // return null;
                // return 550;
                if(this.startPeriodDatetime === null || this.endPeriodDatetime === null)
                    return null;
                // return 550;
                let stopper = this.endPeriodDatetime - this.startPeriodDatetime;
                stopper = Math.abs(parseInt(stopper));
                
                // console.log(JSON.parse(JSON.stringify('timeBarDurationStopper')));
                // console.log(JSON.parse(JSON.stringify(this.endPeriodDatetime)));
                // console.log(JSON.parse(JSON.stringify(this.startPeriodDatetime)));
                // console.log(JSON.parse(JSON.stringify(stopper)));
                
                return stopper;
            },
            dateObj: function () {
                if(!this.isProp(this.event) || !this.isProp(this.event.day))
                    return null;
                return moment(this.event.day.year + '-' + this.event.day.month + '-' + this.event.day.day).toDate();
            },
            date: function () {
                if(this.dateObj == null)
                    return null;
                return moment(this.dateObj).format('YYYY-MM-DD');
            },
            startPeriodDatetime: function () {
                if(this.event === null ||
                typeof this.event.item === 'undefined' || this.event.item === null ||
                typeof this.event.item.from === 'undefined' || this.event.item.from === null)
                    return 0;
                
                let fromArr, fromHours, fromMinutes;
                
                fromArr = this.event.item.from.split(':');
                if(fromArr.length != 2)
                    return 0;
                
                fromHours = parseInt(fromArr[0]);
                fromMinutes = parseInt(fromArr[1]);
                return fromHours < 0 ? 0 : (fromHours * 60) + fromMinutes;
            },
            endPeriodDatetime: function () {
                if(this.event === null ||
                typeof this.event.item === 'undefined' || this.event.item === null ||
                typeof this.event.item.to === 'undefined' || this.event.item.to === null)
                    return 0;
                
                let toArr, toHours, toMinutes;
                    
                toArr = this.event.item.to.split(':');
                if(toArr.length != 2)
                    return 0;
                    
                toHours = parseInt(toArr[0]);
                toMinutes = parseInt(toArr[1]);
                return toHours > 23 ? ((23 * 60) + 59) : (toHours * 60) + toMinutes;
            },
            durationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.duration);
            },
            realDdurationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.realDuration);
            },
        },
        methods: {
            setChoosenTime: function () {
                // console.log(JSON.parse(JSON.stringify(something)));
                this.choosenTime = this.$refs.time_bar_book.inputStrHoursAndMinutes;
                console.log(JSON.parse(JSON.stringify(this.choosenTime)));
                // return this.$refs.time_bar_book.inputStrHoursAndMinutes;
            },
            timeBarBookChange: function (e){
                this.choosenTime = e.time;
            },
            timeBarDurationChange: function (e){
                // console.log(e);
                this.duration = e;
            },
            setDuration: function (){
                if(this.isNewEventMainFull){
                    this.duration = this.newEventMain.template.duration
                }else if(this.isMovingEvent === true){
                    // console.log('this.isMovingEvent === true');
                    // if(this.movingEventIsPickedFull === true && this.movingEventPicked.template.id != this.movingEvent.template_id){
                    //     // console.log('this.movingEventIsPickedFull === true');
                    //     this.duration = this.movingEventPicked.template.duration;
                    // }else{
                    //     // console.log('this.movingEventIsPickedFull !== true');
                    //     // console.log(JSON.parse(JSON.stringify(this.movingEventPicked)));
                    //     this.duration = this.movingEvent.right_duration;
                    // }
                    this.duration = this.movingEvent.right_duration;
                }
                
                this.realDuration = this.duration;
                
                if(this.duration > this.timeBarDurationStopper)
                    this.duration = this.timeBarDurationStopper;
                
                // console.log('setDuration');
                // console.log(this.duration);
            },
            show: function (e){
                // alert(111);
                // console.log(JSON.parse(JSON.stringify('')));
                console.log(JSON.parse(JSON.stringify(e)));
                this.event = e;
                $('#' + this.modalId).modal('show');
            },
            hide: function (){
                // console.log(JSON.parse(JSON.stringify(e)));
                // this.event = e;
                $('#' + this.modalId).modal('hide');
            },
            onClickOkBtn: function (){
                if(this.isNewEventMainFull){
                    this.app.createEvent(this.date, this.choosenTime, this.durationStrHoursAndMinutes).then(() => {
                        this.$store.dispatch('new_event/reset');
                        return this.calendar.getData();
                    }).then(() => {
                        this.hide();
                    });
                }else if(this.isMovingEvent !== null){
                    this.app.editEvent(this.movingEvent.id, {
                        hall: this.movingEventIsPickedFull === true ? this.movingEventPicked.hall.id : this.movingEvent.hall_id,
                        worker: this.movingEventIsPickedFull === true ? this.movingEventPicked.worker.id : this.movingEvent.worker_id,
                        template: this.movingEventIsPickedFull === true ? this.movingEventPicked.template.id : this.movingEvent.template_id,
                        time: this.date + ' ' + this.choosenTime + ':00',
                        duration: this.durationStrHoursAndMinutes,
                    }).then(() => {
                        this.$store.dispatch('moving_event/reset');
                        return this.calendar.getData();
                    }).then(() => {
                        this.hide();
                    });
                }
            },
        },
        components: {
            TimeBar,
            TimeBarFill,
            TimeBarNew,
            Loader,
            InfoAlert,
        },
        watch: {
            // initValue: (newOne, oldOne) => {
            //     console.log(helper.parse(newOne));
        	// 	// console.log("Title changed from " + newOne + " to " + oldOne)
        	// },
    	}
    }
</script>

<style scoped>
    .duration-display{
        line-height: 16px;
    }
    .vue__time-picker, .vue__time-picker input{
        width: 100%!important;
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
        line-height: 1em;
    }
    .modal-title b{
        color: white;
    }
    .small{
        line-height: 1.2em!important;
    }
</style>