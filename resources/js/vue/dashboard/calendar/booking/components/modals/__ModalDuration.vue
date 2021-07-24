<template>
    <div class="modal fade modal-custom-dark-header-footer" :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Day: <b>{{date}}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <loader ref="loader"></loader>            
                    <div>
                        <div class="alert alert-info alert-arrow" role="alert">
                            <div class="row">
                                <div class="col-sm-12 col">
                                    <div v-if="currentTemplateFilter.title">Title: <b>{{currentTemplateFilter.title}}</b></div>
                                    <div v-if="currentTemplateFilter.description">Description: <b>{{currentTemplateFilter.description}}</b></div>
                                </div>
                                <!-- <div class="col-sm-4 col">
                                    <div class="small" v-html="hintText"></div>
                                </div> -->
                            </div>
                        </div>
                        
                        <div v-if="currentTemplateFilter.duration">Duration: <b>{{durationStrHoursAndMinutes}}</b></div>
                        <div class="for-time-bar-fill pb-3">
                            <time-bar-fill ref="time_bar_duration"
                                :stopper="stopper"
                                @change="timeBarDurationChange($event)"
                                :durationInMinutes="duration" />
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="onClickOkBtn"
                        type="button"
                        class="btn btn-success">Ok</button>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    // import TimeBar from "./TimeBar.vue";
    import TimeBarFill from "./TimeBarFill.vue";
    // import TimeBarNew from "./TimeBarNew.vue";
    import Loader from "../Loader.vue";
    export default {
        name: 'modalDuration',
        mounted() {
            // if(this.app === null)
            //     this.app = this.getParentComponentByName(this, 'app');
                
            // console.log(111);
            // console.log(this.auth);
            // this.$refs['loader'].fadeOut(300);
            
            // this.setDuration();
            
            $("#" + this.modalId).on('show.bs.modal', () => {
                // this.$refs.time_bar_duration.reset();
                // this.$refs['loader'].show();
                this.setDuration();
            });
            
            $("#" + this.modalId).on('shown.bs.modal', () => {
                this.$refs['loader'].fadeOut(300);
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                // this.errorResponse = null;
                // this.$refs['loader'].show();
                // this.$refs.time_bar_duration.reset();
            });
            
            
            
        },
        props: ['bookDate'],
        data: function(){
            return {
                modalId: 'durationPickerModal',
                
                errorResponse: null,
                
                duration: 100,
                event: null,
                choosenTime: null,
            };
        },
        computed: {
            stopper: function () {
                if(this.event === null || typeof this.event.event === 'undefined' || this.event.event === null ||
                typeof this.event.nextEvent === 'undefined' || this.event.nextEvent === null)
                    return null;
                
                let eventStartMinutes, nextEventStartMinutes;
                
                eventStartMinutes = calendarHelper.time.parseStringHourMinutesToMinutes(this.event.event.from);
                nextEventStartMinutes = calendarHelper.time.parseStringHourMinutesToMinutes(this.event.nextEvent.from);
                
                // console.log(eventStartMinutes, nextEventStartMinutes);
                // console.log(nextEventStartMinutes - nextEventStartMinutes);
                
                return nextEventStartMinutes - eventStartMinutes;
            },
            date: function () {
                if(this.event == null ||
                typeof this.event.day === 'undefined' || this.event.day === null)
                    return null;
                return this.event.day.year + '-' + this.event.day.month + '-' + this.event.day.day;
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
                // return '111';
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.duration);
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
                if(this.isMovingEvent)
                    this.duration = this.movingEvent.right_duration;
            },
            show: function (e){
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
                if(!this.isMovingEvent){
                    console.error('movingEvent not exists, in ModalDuration.edit');
                    return;
                }
                
                this.$refs['loader'].showTransparent();
                
                this.app.editEvent(this.movingEvent.id, {
                    duration: this.durationStrHoursAndMinutes,
                }).then(() => {
                    this.$refs['loader'].fadeOut(300);
                    this.hide();
                    this.$store.dispatch('moving_event/reset');
                    this.calendar.getData();
                });
            },
        },
        components: {
            // TimeBar,
            TimeBarFill,
            // TimeBarNew,
            Loader,
        },
        watch: {
            // initValue: (newOne, oldOne) => {
            //     console.log(helper.parse(newOne));
        	// },
    	}
    }
</script>

<style scoped>
    
</style>