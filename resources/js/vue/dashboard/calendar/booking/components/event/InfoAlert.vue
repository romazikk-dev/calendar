<template>
    
    <div class="event-info-alert alert alert-primary small" role="alert">
        <div class="badge-title badge badge-primary badge-pill">{{badgeTitle}}:</div>
        
        <div class="row">
            <div v-if="clientName" class="col col-12 col-sm-6">
                <span>{{capitalizeFirstLetter(getText('text.client'))}}(name): </span>
                <b>{{clientName}}</b>
            </div>
            <div v-if="clientEmail" class="col col-12 col-sm-6">
                <span>{{capitalizeFirstLetter(getText('text.client'))}}(email): </span>
                <b>{{clientEmail}}</b>
            </div>
            <div v-if="hallTitle" class="col col-12 col-sm-6">
                <span>{{capitalizeFirstLetter(getText('text.hall'))}}: </span>
                <b>{{hallTitle}}</b>
            </div>
            <div v-if="templateTitle" class="col col-12 col-sm-6">
                <span>{{capitalizeFirstLetter(getText('text.template'))}}: </span>
                <b>{{templateTitle}}</b>
            </div>
            <div v-if="workerName" class="col col-12 col-sm-6">
                <span>{{capitalizeFirstLetter(getText('text.worker'))}}: </span>
                <b>{{workerName}}</b>
            </div>
            <div v-if="eventDate" class="col col-12 col-sm-6">
                <span>
                    {{capitalizeFirstLetter(getText('text.date'))}}
                    ({{getText('text.current')}}):
                </span>
                <b>{{eventDate}}</b>
            </div>
            <div v-if="eventTime" class="col col-12 col-sm-6">
                <span>
                    {{capitalizeFirstLetter(getText('text.time'))}}
                    ({{getText('text.current')}}):
                </span>
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
                let editEvent = this.capitalizeFirstLetter(this.getText('text.edit_event'));
                let newEvent = this.capitalizeFirstLetter(this.getText('text.new_event'));
                if(this.isProp(this.event))
                    return editEvent;
                return this.isProp(this.movingEvent) ? editEvent : newEvent;
            },
            clientObj: function () {
                if(this.isProp(this.event))
                    return this.event.client_without_user_scope;
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
                if(this.isProp(this.event))
                    return this.event.hall_without_user_scope.title;
                if(this.isProp(this.movingEvent))
                    return this.movingEvent.hall_without_user_scope.title;
                if(this.isNewEventMainFull)
                    return this.newEventMain.hall.title;
                return null;
            },
            templateTitle: function () {
                if(this.isProp(this.event))
                    return this.event.template_without_user_scope.title;
                if(this.isProp(this.movingEvent))
                    return this.movingEvent.template_without_user_scope.title;
                if(this.isNewEventMainFull)
                    return this.newEventMain.template.title;
                return null;
            },
            workerName: function () {
                if(this.isProp(this.event))
                    return this.fullNameOfObj(this.event.worker_without_user_scope);
                if(this.isProp(this.movingEvent))
                    return this.fullNameOfObj(this.movingEvent.worker_without_user_scope);
                if(this.isProp(this.isNewEventMainFull))
                    return this.fullNameOfObj(this.newEventMain.worker);
                return null;
            },
            eventDate: function () {
                if(this.isProp(this.event))
                    return this.getHumanReadableDate(moment(this.event.time).toDate());
                if(this.isProp(this.movingEvent) && this.isProp(this.movingEvent.time))
                    return this.getHumanReadableDate(moment(this.movingEvent.time).toDate());
                return null;
            },
            eventTime: function () {
                if(this.isProp(this.event))
                    return this.event.from;
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