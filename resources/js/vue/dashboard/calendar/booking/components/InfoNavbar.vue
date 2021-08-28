<template>
    <div>
        
        <div class="alert info-navbarr"
        :class="{
            'bg-approved': isNewEventMainFull || (isProp(movingEvent) && movingEvent.approved),
            'bg-not-approved': isProp(movingEvent) && !movingEvent.approved,
            'new-event': isNewEventMainFull,
        }">
            <div class="btn-actions">
                <a @click.prevent="onClickClose"
                    href="#"
                    type="button"
                    class="btn-action">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x-lg">
                            <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"></path>
                        </svg>
                </a>
                <a @click.prevent="onClickEdit"
                    href="#"
                    type="button"
                    class="btn-action">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-pencil">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                        </svg>
                </a>
            </div>
            <div class="top-title badge"
            :class="{
                'badge-primary': (isProp(movingEvent) && movingEvent.approved) || isNewEventMainFull,
                'badge-warning': isProp(movingEvent) && !movingEvent.approved,
            }">
                {{badgeTitle}}: 
                <span v-if="isProp(movingEvent) && !movingEvent.approved">not approved</span>
            </div>
            <div class="row">
                <div v-for="item in dataItems" v-if="item.render" class="col col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    {{item.title}}:
                    <b>{{item.val}}</b>
                </div>
                <div class="col col-12 pt-3">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button"
                            @click="switchWithEvents($event, GetDataFreeWithEventsParams.ALL)"
                            class="btn btn-sm btn-info"
                            :class="{
                                'active': withEvents == GetDataFreeWithEventsParams.ALL
                            }">With all events</button>
                        <button type="button"
                            @click="switchWithEvents($event, GetDataFreeWithEventsParams.PER_CLIENT)"
                            class="btn btn-sm btn-info"
                            :class="{
                                'active': withEvents == GetDataFreeWithEventsParams.PER_CLIENT
                            }">With events per client</button>
                        <button type="button"
                            @click="switchWithEvents($event, null)"
                            class="btn btn-sm btn-info"
                            :class="{
                                'active': withEvents == null
                            }">Only free slots</button>
                    </div>
                    
                    <div class="float-right mt-1" v-if="mainSettings.enable_booking_on_any_time">
                        <checkbox label="Enable booking on any date, time"
                            :checked="freeBookingAnyTime"
                            @change="onEnableBookingAnyTime($event)" />
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import {GetDataFreeWithEventsParams} from './enums';
    import Checkbox from './elements/Checkbox.vue';
    export default {
        name: 'infoNavbar',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
            
            this.resetEnableBookingAnyTimeIfDisabled();
            // this.$refs.modal_filter.show();
        },
        // props: ['templateSpecifics','templateSpecificsAsIdKey'],
        data: function(){
            return {
                GetDataFreeWithEventsParams: GetDataFreeWithEventsParams,
            };
        },
        computed: {
            withEvents: function () {
                return this.$store.getters['free_get_data_params/withEvents'];
                // if(this.isProp(this.movingEvent))
                //     return this.movingEventWithEvents;
                // if(this.isNewEventMainFull)
                //     return this.newEventWithEvents;
                // return null;
            },
            dataItems: function () {
                return [
                    {
                        title: "Client(name)",
                        val: this.clientName,
                        render: this.isProp(this.clientName),
                    },
                    {
                        title: "Client(email)",
                        val: this.clientEmail,
                        render: this.isProp(this.clientEmail),
                    },
                    {
                        title: "Hall",
                        val: this.hallTitle,
                        render: this.isProp(this.hallTitle),
                    },
                    {
                        title: "Template",
                        val: this.templateTitle,
                        render: this.isProp(this.templateTitle),
                    },
                    {
                        title: "Worker",
                        val: this.workerName,
                        render: this.isProp(this.workerName),
                    },
                    {
                        title: "Duration(current)",
                        val: this.eventDuration,
                        render: this.isProp(this.eventDuration),
                    },
                    {
                        title: "Date(current)",
                        val: this.eventDate,
                        render: this.isProp(this.eventDate),
                    },
                    {
                        title: "Time(current)",
                        val: this.eventTime,
                        render: this.isProp(this.eventTime),
                    },
                ];
            },
            badgeTitle: function () {
                return this.isProp(this.movingEvent) ? "Edit event" : "New event";
            },
            clientObj: function () {
                if(this.isProp(this.movingEventClient))
                    return this.movingEventClient;
                if(this.isProp(this.newEventClient))
                    return this.newEventClient;
                return null;
            },
            clientName: function () {
                return this.isProp(this.clientObj) ? this.fullNameOfObj(this.clientObj) : null;
            },
            clientEmail: function () {
                return this.isProp(this.clientObj) && this.isProp(this.clientObj.email) ? this.clientObj.email : null;
            },
            hallTitle: function () {
                if(this.isProp(this.movingEvent)){
                    if(this.movingEventIsPickedFull){
                        return this.movingEventPicked.hall.title;
                    }else{
                        return this.movingEvent.hall_without_user_scope.title;
                    }
                }
                if(this.isNewEventMainFull)
                    return this.newEventMain.hall.title;
                return null;
            },
            templateTitle: function () {
                if(this.isProp(this.movingEvent)){
                    if(this.movingEventIsPickedFull){
                        return this.movingEventPicked.template.title;
                    }else{
                        return this.movingEvent.template_without_user_scope.title;
                    }
                }
                if(this.isNewEventMainFull)
                    return this.newEventMain.template.title;
                return null;
            },
            workerName: function () {
                if(this.isProp(this.movingEvent)){
                    if(this.movingEventIsPickedFull){
                        return this.fullNameOfObj(this.movingEventPicked.worker);
                    }else{
                        return this.fullNameOfObj(this.movingEvent.worker_without_user_scope);
                    }
                }
                if(this.isNewEventMainFull)
                    return this.fullNameOfObj(this.newEventMain.worker);
                return null;
            },
            eventDate: function () {
                if(this.isProp(this.movingEvent) && this.isProp(this.movingEvent.time)){
                    if(this.movingEventIsPickedFull)
                        return null;
                    return this.getHumanReadableDate(moment(this.movingEvent.time).toDate());
                }
                return null;
            },
            eventTime: function () {
                if(this.isProp(this.movingEvent) && this.isProp(this.movingEvent.from)){
                    if(this.movingEventIsPickedFull)
                        return null;
                    return this.movingEvent.from;
                }
                return null;
            },
            eventDuration: function () {
                // let duration;
                if(this.isProp(this.movingEvent))
                    return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.movingEvent.right_duration);
                if(this.isNewEventMainFull)
                    return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.newEventMain.template.duration);
                return null;
            },
        },
        methods: {
            resetEnableBookingAnyTimeIfDisabled: function(){
                if(this.isProp(this.mainSettings) &&
                    (
                        !this.isProp(this.mainSettings.enable_booking_on_any_time) ||
                        this.mainSettings.enable_booking_on_any_time == false
                    ) && this.freeBookingAnyTime === true
                ){
                    this.$store.dispatch('free_get_data_params/setBookingAnyTime', false);
                }
            },
            onEnableBookingAnyTime: function(e){
                if(e == this.freeBookingAnyTime)
                    return;
                this.$store.dispatch('free_get_data_params/setBookingAnyTime', e);
                this.calendar.getData();
            },
            switchWithEvents: function(e, val){
                if(val == this.freeWithEvents)
                    return;
                this.$store.dispatch('free_get_data_params/setWithEvents', val);
                this.calendar.getData();
            },
            onClickEdit: function(e){
                this.closeTooltipOfEvent(e);
                
                if(this.isProp(this.movingEvent))
                    this.app.$refs.move_path_picker.show(this.movingEventComposedWithPicked);
                if(this.isNewEventMainFull)
                    this.app.$refs.modal_book.show();
            },
            onClickClose: function(e){
                this.closeTooltipOfEvent(e);
                
                if(this.isProp(this.movingEvent))
                    this.resetMovingEvent();
                if(this.isNewEventMainFull)
                    this.resetNewEvent();
                
                this.calendar.getData();
            },
        },
        components: {
            Checkbox,
        },
        watch: {
            // isFiltersEmpty: function(newVal, oldVal){
            //     if(newVal === false && oldVal === true){
            //         this.showAppliedFilters = true;
            //     }
            // },
        },
    }
</script>

<style lang="scss" scoped>
    
</style>