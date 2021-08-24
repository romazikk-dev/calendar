<template>
    
    <div class="event-info-alert alert alert-primary small" role="alert">
        <div class="badge-title badge badge-primary badge-pill">{{badgeTitle}}:</div>
        
        <div class="row">
            <div v-if="clientName" class="col col-12 col-sm-6">
                <span>Client(name): </span>
                <b>{{clientName}}</b>
            </div>
            <div v-if="clientEmail" class="col col-12 col-sm-6">
                <span>Client(email): </span>
                <b>{{clientEmail}}</b>
            </div>
            <div v-if="hallTitle" class="col col-12 col-sm-6">
                <span>Hall: </span>
                <b>{{hallTitle}}</b>
            </div>
            <div v-if="templateTitle" class="col col-12 col-sm-6">
                <span>Template: </span>
                <b>{{templateTitle}}</b>
            </div>
            <div v-if="workerName" class="col col-12 col-sm-6">
                <span>Worker: </span>
                <b>{{workerName}}</b>
            </div>
            <div v-if="eventDate" class="col col-12 col-sm-6">
                <span>Date(current): </span>
                <b>{{eventDate}}</b>
            </div>
            <div v-if="eventTime" class="col col-12 col-sm-6">
                <span>Time(current): </span>
                <b>{{eventTime}}</b>
            </div>
        </div>
        
    </div>
                        
</template>

<script>
    export default {
        name: 'eventInfoAlert',
        mounted() {},
        props: ['data','event'],
        data: function(){
            return {};
        },
        computed: {
            badgeTitle: function () {
                if(this.isProp(this.event))
                    return "Edit event";
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
                if(this.isProp(this.movingEvent))
                    return this.movingEvent.hall_without_user_scope.title;
                if(this.isNewEventMainFull)
                    return this.newEventMain.hall.title;
                return null;
            },
            templateTitle: function () {
                if(this.isProp(this.movingEvent))
                    return this.movingEvent.template_without_user_scope.title;
                if(this.isNewEventMainFull)
                    return this.newEventMain.template.title;
                return null;
            },
            workerName: function () {
                if(this.isProp(this.movingEvent))
                    return this.fullNameOfObj(this.movingEvent.worker_without_user_scope);
                if(this.isProp(this.isNewEventMainFull))
                    return this.fullNameOfObj(this.newEventMain.worker);
                return null;
            },
            eventDate: function () {
                if(this.isProp(this.movingEvent) && this.isProp(this.movingEvent.time))
                    return this.getHumanReadableDate(moment(this.movingEvent.time).toDate());
                return null;
            },
            eventTime: function () {
                if(this.isProp(this.movingEvent) && this.isProp(this.movingEvent.from))
                    return this.movingEvent.from;
                return null;
            },
        },
        methods: {
            // setShowDurationApplyBtn: function () {},
        },
        components: {},
        watch: {}
    }
</script>

<style scoped>
    /* .alert .event-itemm{
        line-height: 18px!important;
    } */
</style>