<template>
    <div class="modal fade modal-custom-dark-header-footer modal-more-events"
        @click.prevent
        :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Edit duration:</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="e">
                    <!-- <loader ref="loader"></loader>   -->
                    <info-alert :event="e.event" />
                    <duration ref="duration"
                        @change="durationChanged($event)"
                        :e="e" />
                            
                </div>
                <div class="modal-footer">
                    
                    <button v-if="showDurationApplyBtn"
                        @click.prevent="$refs.duration.reset()"
                        class="btn btn-sm btn-warning">
                            Reset
                    </button>
                    <button @click.prevent="onClickOkBtn($event)"
                        v-if="showDurationApplyBtn"
                        :disabled="!showDurationApplyBtn"
                        :class="{
                            'disabled': !showDurationApplyBtn
                        }"
                        class="btn btn-sm btn-primary">
                            Ok
                    </button>
                    <button type="button"
                        @click.prevent="hide(true)"
                        class="btn btn-sm btn-secondary">
                            Close
                    </button>
                    
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    import Loader from "../Loader.vue";
    import Duration from "../event/Duration.vue";
    import InfoAlert from "../event/InfoAlert.vue";
    export default {
        name: 'modalMoreEvents',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
                // trigger: "click",
                trigger: "hover",
            });
        },
        mounted() {
            $("#" + this.modalId).on('show.bs.modal', () => {});
            $("#" + this.modalId).on('shown.bs.modal', () => {});
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                this.$store.dispatch('moving_event/reset');
            });
        },
        // props: ['bookDate'],
        data: function(){
            return {
                modalId: 'durationModal',
                e: null,
                showDurationApplyBtn: false,
            };
        },
        computed: {
            dateObj: function () {
                if(this.e == null ||
                typeof this.e.day === 'undefined' || this.e.day === null ||
                typeof this.e.day.day === 'undefined' || this.e.day.day === null ||
                typeof this.e.day.month === 'undefined' || this.e.day.month === null ||
                typeof this.e.day.year === 'undefined' || this.e.day.year === null)
                    return null;
                return moment(this.e.day.year + '-' + this.e.day.month + '-' + this.e.day.day).toDate();
                // return momentDate.format('D MMMM YYYY, ddd');
            },
            date: function () {
                if(this.e == null ||
                typeof this.e.day === 'undefined' || this.e.day === null ||
                typeof this.e.day.day === 'undefined' || this.e.day.day === null ||
                typeof this.e.day.month === 'undefined' || this.e.day.month === null ||
                typeof this.e.day.year === 'undefined' || this.e.day.year === null)
                    return null;
                let momentDate = moment(this.e.day.year + '-' + this.e.day.month + '-' + this.e.day.day);
                return momentDate.format('D MMMM YYYY, ddd');
            },
        },
        methods: {
            onClickOkBtn: function (e) {
                if(!this.isMovingEvent)
                    return false;
                
                this.app.editEvent(this.movingEvent.id, {
                    duration: calendarHelper.time.composeHourMinuteTimeFromMinutes(this.$refs.duration.duration),
                }).then((data) => {
                    this.$store.dispatch('moving_event/reset');
                    this.hide(true);
                    this.$store.commit('updater/increaseCounter');
                });
            },
            durationStrHoursAndMinutes: function (event) {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(event.right_duration);
            },
            show: function (e){
                console.log(JSON.parse(JSON.stringify(e)));
                this.e = e;
                $('#' + this.modalId).modal('show');
            },
            hide: function (resetMovingEvent = true){
                $('#' + this.modalId).modal('hide');
                // alert(resetMovingEvent);
                if(resetMovingEvent === true){
                    setTimeout(() => {
                     	this.$store.dispatch('moving_event/reset');
                    }, 400);
                }
            },
            durationChanged: function (e){
                this.setShowDurationApplyBtn();
            },
            setShowDurationApplyBtn: function () {
                this.showDurationApplyBtn = typeof this.$refs.duration !== 'undefined' && this.$refs.duration.isDurationChanged;
            },
        },
        components: {
            Loader,
            Duration,
            InfoAlert,
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