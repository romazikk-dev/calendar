<template>
    <div>
        
        <div class="duration-display">
            Duration: <b>{{durationStrHoursAndMinutes}}</b><br />
            <span class="small">
                <i>{{initDurationStrHoursAndMinutes}} (current duration)</i>
            </span>
        </div>
        <div class="for-time-bar-fill pb-3">
            <time-bar-fill ref="time_bar_duration"
                :stopper="stopper"
                @change="timeBarDurationChange($event)"
                :durationInMinutes="duration" />
        </div>
        
    </div>
</template>

<script>
    import TimeBarFill from "../modals/TimeBarFill.vue";
    export default {
        name: 'duration',
        mounted() {
            // console.log('duration component');
            // console.log(JSON.parse(JSON.stringify(this.e)));
            // console.log(this.e);
            // alert(11);
            // durationRangeRestriction
            if(typeof this.defaultDuration !== 'undefined' && !isNaN(this.defaultDuration)){
                if(this.defaultDuration > 180){
                    this.duration = 180;
                }else if(this.defaultDuration < 10){
                    this.duration = 10;
                }else{
                    this.duration = this.defaultDuration; 
                }
            }
                
            this.setDuration();
        },
        /*
        *   e = {event, nextEvent}
        */
        props: ['e','defaultDuration'],
        data: function(){
            return {
                duration: 100,
                initDuration: 100,
            };
        },
        computed: {
            isDurationChanged: function () {
                return this.isE && this.duration != this.e.event.right_duration;
            },
            isE: function () {
                return typeof this.e !== 'undefined' && this.e !== null;
            },
            event: function () {
                if(this.isE && typeof this.e.event !== 'undefined' && this.e.event !== null)
                    return this.e.event;
                return null;
            },
            nextEvent: function () {
                if(this.isE && typeof this.e.nextEvent !== 'undefined' && this.e.nextEvent !== null)
                    return this.e.nextEvent;
                return null;
            },
            stopper: function () {
                if(this.event === null || this.nextEvent === null)
                    return null;
                
                let eventStartMinutes, nextEventStartMinutes;
                
                eventStartMinutes = calendarHelper.time.parseStringHourMinutesToMinutes(this.event.from);
                nextEventStartMinutes = calendarHelper.time.parseStringHourMinutesToMinutes(this.nextEvent.from);
                return nextEventStartMinutes - eventStartMinutes;
            },
            durationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.duration);
            },
            initDurationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.initDuration);
            },
        },
        methods: {
            reset: function (){
                this.setDuration();
            },
            timeBarDurationChange: function (e){
                this.duration = e;
                this.$emit('change', {
                    duration: this.duration
                });
            },
            setDuration: function (){
                if(this.event !== null)
                    this.duration = this.event.right_duration;
                this.initDuration = this.duration;
            },
        },
        components: {
            TimeBarFill,
        },
        watch: {
            e: (newValue, oldValue) => {
                this.setDuration();
        	},
    	}
    }
</script>

<style scoped>
    .duration-display{
        line-height: 16px;
    }
</style>