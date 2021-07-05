<template>
    <div>
        
        <div v-if="currentTemplateFilter.duration">Duration: <b>{{durationStrHoursAndMinutes}}</b></div>
        <div class="for-time-bar-fill pb-3">
            <time-bar-fill ref="time_bar_duration"
                :stopper="stopper"
                @change="timeBarDurationChange($event)"
                :durationInMinutes="duration" />
        </div>
        
    </div>
</template>

<script>
    // import TimeBar from "./TimeBar.vue";
    import TimeBarFill from "../modals/TimeBarFill.vue";
    // import TimeBarNew from "./TimeBarNew.vue";
    // import Loader from "../Loader.vue";
    export default {
        name: 'duration',
        mounted() {
            this.setDuration();
        },
        /*
        *   e = {event, nextEvent}
        */
        props: ['e'],
        data: function(){
            return {
                errorResponse: null,
                
                duration: 100,
                // choosenTime: null,
            };
        },
        computed: {
            isDurationChanged: function () {
                // console.log(this.currentEventFilterDuration);
                // console.log(this.isE);
                // console.log(this.duration);
                return this.isE && this.duration != this.currentEventFilterDuration;
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
                
                // console.log(eventStartMinutes, nextEventStartMinutes);
                // console.log(nextEventStartMinutes - nextEventStartMinutes);
                
                return nextEventStartMinutes - eventStartMinutes;
            },
            durationStrHoursAndMinutes: function () {
                // return '111';
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.duration);
            },
            bookData: function () {
                if(this.currentHallFilter === null || this.currentWorkerFilter === null ||
                this.currentTemplateFilter === null)
                    return null;
                    
                let data = {
                    // hall: this.currentHallFilter.id,
                    // worker: this.currentWorkerFilter.id,
                    // template: this.currentTemplateFilter.id,
                    // time: this.date + ' ' + this.choosenTime + ':00',
                    duration: this.durationStrHoursAndMinutes,
                }
                
                return data;
            }
        },
        methods: {
            // setChoosenTime: function () {
            //     // console.log(JSON.parse(JSON.stringify(something)));
            //     this.choosenTime = this.$refs.time_bar_book.inputStrHoursAndMinutes;
            //     console.log(JSON.parse(JSON.stringify(this.choosenTime)));
            //     // return this.$refs.time_bar_book.inputStrHoursAndMinutes;
            // },
            reset: function (){
                this.setDuration();
            },
            timeBarBookChange: function (e){
                this.choosenTime = e.time;
            },
            timeBarDurationChange: function (e){
                // console.log(e);
                this.duration = e;
                this.emitChange();
            },
            emitChange: function (){
                this.$emit('change', {
                    duration: this.duration
                });
            },
            setDuration: function (e){
                if(this.currentEventFilterDuration !== null)
                    this.duration = this.currentEventFilterDuration;
            },
            // show: function (e){
            //     console.log(JSON.parse(JSON.stringify(e)));
            //     this.event = e;
            //     $('#' + this.modalId).modal('show');
            // },
            edit: function (){
                if(this.currentEventFilter === null || this.eventData === null)
                    return;
                    
                // let componentApp = this.getParentComponentByName(this, 'app');
                // let data;
                // this.bookButtonDisabled = true;
                this.$refs['loader'].showTransparent();
                
                // data = {
                // 
                // }
                
                this.app.bookEdit(this.currentEventFilter.id, this.bookData, (response) => {
                    // this.onBooked(response);
                    // alert(111);
                    // errorResponse
                    // let data = response.data;
                    // if(typeof response.data.error === 'undefined'){
                    //     setTimeout(() => {
                    //         this.successfullyBooked = true;
                    //     }, 300);
                    // }else{
                    //     setTimeout(() => {
                    //         this.errorResponse = response.data.error;
                    //     }, 300);
                    // }
                    console.log(response);
                }, () => {}, () => {
                
                    // console.log('always');
                    this.$refs['loader'].fadeOut(300);
                    // setTimeout(() => {
                    //     // this.successfullyBooked = true;
                    //     this.bookButtonDisabled = false;
                    // }, 300);
                
                });
            },
            book: function (){
                if(this.currentEventFilter === null || this.bookData === null)
                    return;
                    
                // let componentApp = this.getParentComponentByName(this, 'app');
                let data;
                // this.bookButtonDisabled = true;
                this.$refs['loader'].showTransparent();
                
                // data = {
                // 
                // }
                
                this.app.bookEdit(this.currentEventFilter.id, this.bookData, (response) => {
                    // this.onBooked(response);
                    // alert(111);
                    // errorResponse
                    // let data = response.data;
                    // if(typeof response.data.error === 'undefined'){
                    //     setTimeout(() => {
                    //         this.successfullyBooked = true;
                    //     }, 300);
                    // }else{
                    //     setTimeout(() => {
                    //         this.errorResponse = response.data.error;
                    //     }, 300);
                    // }
                    console.log(response);
                }, () => {}, () => {
                
                    // console.log('always');
                    this.$refs['loader'].fadeOut(300);
                    // setTimeout(() => {
                    //     // this.successfullyBooked = true;
                    //     this.bookButtonDisabled = false;
                    // }, 300);
                
                });
            },
        },
        components: {
            // TimeBar,
            TimeBarFill,
            // TimeBarNew,
            // Loader,
        },
        watch: {
            e: (newValue, oldValue) => {
                this.setDuration();
        	},
    	}
    }
</script>

<style scoped>
    
</style>