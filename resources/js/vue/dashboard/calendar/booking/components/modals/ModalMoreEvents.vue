<template>
    <div class="modal fade modal-custom-dark-header-footer modal-more-events"
        @click.prevent
        :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    
                    <div v-if="showTab == 'events'">
                        <h5 class="modal-title">
                            <b>{{date}}</b><br />
                            <div v-if="e && e.count_total" class="modal-counters">
                                <ul>
                                    <li>
                                        <span class="badge badge-pill tooltip-active badge-counter-total"
                                             data-placement="auto"
                                             title="<div class='small'>Total</div>">
                                             {{e.count_total}}
                                         </span>
                                    </li>
                                    <li v-if="e.count_approved">
                                        <span class="badge badge-pill tooltip-active badge-counter-approved"
                                             data-placement="auto"
                                             title="<div class='small'>Approved</div>">
                                             {{e.count_approved}}
                                         </span>
                                    </li>
                                    <li v-if="e.count_not_approved">
                                        <span class="badge badge-pill tooltip-active badge-counter-not-approved"
                                             data-placement="auto"
                                             title="<div class='small'>Not approved yet</div>">
                                             {{e.count_not_approved}}
                                         </span>
                                    </li>
                                </ul>
                            </div>
                        </h5>
                    </div>
                    
                    <div v-if="['edit', 'duration'].includes(showTab)">
                        <h5 class="modal-title">
                            Edit event
                            <span class="small" v-if="showTab == 'duration'">(duration)</span>
                            <br>
                            <a @click.prevent="backToEvents" href="#"
                                class="btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                back to events
                            </a>
                        </h5>
                    </div>
                    
                    <!-- <div v-if="showTab == 'duration'">
                        <h5 class="modal-title">
                            Edit event duration<br>
                            <a @click.prevent="backToEvents" href="#"
                                class="btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                back to events
                            </a>
                        </h5>
                    </div> -->
                    
                    <button @click.prevent="hide(true)"
                        type="button"
                        class="close">
                            <span aria-hidden="true">&times;</span>
                    </button>
                    
                </div>
                <div class="modal-body">
                    <!-- <loader ref="loader"></loader>   -->
                    
                    <!-- <edit v-if="movingEvent" /> -->
                    <edit ref="edit"
                        @change="onEditChange"
                        v-if="showTab == 'edit' && movingEvent" />
                    
                    <div v-if="showTab == 'events'">
                        <div class="modal-events" v-if="events">
                            <div v-for="(itm, index) in events"
                                :class="{
                                    'approved': itm.approved,
                                    'not-approved': !itm.approved,
                                }"
                                class="modal-event">
                                    <div class="badge-time badge badge-info">
                                        <b>{{itm.from}} - {{itm.to}}</b> |
                                        <span>Duration {{durationStrHoursAndMinutes(itm)}}</span>
                                    </div>
                                    <div class="event-itemm text-warning">
                                        <span>Client: </span><b>{{itm.client_without_user_scope.first_name}}</b>
                                    </div>
                                    <div class="event-itemm">
                                        <span>Hall: </span><b>{{itm.hall_without_user_scope.title}}</b>
                                    </div>
                                    <div class="event-itemm">
                                        <span>Template: </span><b>{{itm.template_without_user_scope.title}}</b>
                                    </div>
                                    <div class="event-itemm">
                                        <span>Worker: </span><b>{{itm.worker_without_user_scope.first_name}}</b>
                                    </div>
                                    
                                    <div class="for-actions">
                                        <actions :itm="itm" :ref="'actions_' + itm.id"
                                            :right-placed="true"
                                            @clickActionMove="onActionMove(itm)"
                                            @clickActionDuration="clickActionDuration(itm)"
                                            @clickActionDateTime="$emit('clickActionDateTime', itm)"
                                            @clickActionApprove="clickActionApprove(itm)"
                                            @clickActionRemove="onActionRemove(itm)"/>
                                    </div>
                                        
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="showTab == 'duration' && movingEvent">
                        <div class="alert alert-primary small" role="alert">
                            <div class="event-itemm">
                                <span>Client: </span>
                                <b>{{durationE.event.client_without_user_scope.first_name}}</b>
                                <template v-if="durationE.event.client_without_user_scope.email">
                                    <b> - {{durationE.event.client_without_user_scope.email}}</b>
                                </template>
                            </div>
                            <div class="event-itemm">
                                <span>Hall: </span><b>{{durationE.event.hall_without_user_scope.title}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Template: </span><b>{{durationE.event.template_without_user_scope.title}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Worker: </span><b>{{durationE.event.worker_without_user_scope.first_name}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Date: </span><b>{{date}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Time: </span><b>{{durationE.event.from}}</b>
                            </div>
                        </div>
                        <duration ref="duration"
                            @change="durationChanged($event)"
                            :e="durationE" />
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <div v-if="showTab == 'edit'">
                        <a v-if="showEditResetBtn"
                            @click.prevent="$refs.edit.reset()"
                            href="#"
                            class="btn btn-sm btn-warning btn-reset">
                                Reset
                        </a>
                        <a @click.prevent="pickMovingEventTime()"
                            href="#"
                            class="btn btn-sm btn-success btn-pick-time"
                            :class="{
                                'disabled': !showEditPickTimeBtn
                            }"
                            :disabled="!showEditPickTimeBtn">
                                Pick time
                        </a>
                    </div>
                    
                    <div v-if="showTab == 'duration'">
                        <button v-if="showDurationApplyBtn"
                            @click.prevent="$refs.duration.reset()"
                            class="btn btn-sm btn-warning">
                                Reset
                        </button>
                        <button @click.prevent="applyDurationAndBackToEvents($event)"
                            v-if="showDurationApplyBtn"
                            :disabled="!showDurationApplyBtn"
                            :class="{
                                'disabled': !showDurationApplyBtn
                            }"
                            class="btn btn-sm btn-primary">
                                Apply, and back to events
                        </button>
                        <button @click.prevent="applyDurationAndCloseModal($event)"
                            v-if="showDurationApplyBtn"
                            :disabled="!showDurationApplyBtn"
                            :class="{
                                'disabled': !showDurationApplyBtn
                            }"
                            class="btn btn-sm btn-success">
                                Apply, and close
                        </button>
                    </div>
                        
                    <button type="button"
                        @click.prevent="hide(true)"
                        class="btn btn-sm btn-secondary">
                            Close
                    </button>
                    <!-- <button @click.prevent="book"
                        type="button"
                        class="btn btn-success">Ok</button> -->
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    // import TimeBar from "./TimeBar.vue";
    // import TimeBarFill from "./TimeBarFill.vue";
    // import TimeBarNew from "./TimeBarNew.vue";
    import Loader from "../Loader.vue";
    import Actions from "../event/Actions.vue";
    import Edit from "../event/Edit.vue";
    import Duration from "../event/Duration.vue";
    export default {
        name: 'modalMoreEvents',
        updated() {
            // this.showTab = 'events';
            $('.tooltip-active').tooltip({
                html: true,
                // trigger: "click",
                trigger: "hover",
            });
        },
        mounted() {
            this.showTab = 'events';
            // if(this.app === null)
            //     this.app = this.getParentComponentByName(this, 'app');
                
            // console.log(111);
            // console.log(this.auth);
            // this.$refs['loader'].fadeOut(300);
            
            // this.setDuration();
            
            $("#" + this.modalId).on('show.bs.modal', () => {
                // this.setDuration();
                this.showTab = 'events';
            });
            
            $("#" + this.modalId).on('shown.bs.modal', () => {
                // this.$refs['loader'].fadeOut(300);
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                this.showTab = 'events';
            });
        },
        // props: ['bookDate'],
        data: function(){
            return {
                // app: null,
                showTab: 'events',
                
                modalId: 'moreEventsModal',
                
                // errorResponse: null,
                
                e: null,
                events: null,
                // choosenTime: null,
                
                showEditResetBtn: false,
                showEditPickTimeBtn: false,
                
                // For DURATION tab
                durationE: null,
                showDurationApplyBtn: false,
            };
        },
        computed: {
            // events: function () {
            //     if(this.e === null ||
            //     typeof this.e.items === 'undefined' || this.e.items === null ||
            //     !Array.isArray(this.e.items) || this.e.items.length == 0)
            //         return null;
            //     return this.e.items;
            // },
            date: function () {
                if(this.e == null ||
                typeof this.e.day === 'undefined' || this.e.day === null ||
                typeof this.e.month === 'undefined' || this.e.month === null ||
                typeof this.e.year === 'undefined' || this.e.year === null)
                    return null;
                let momentDate = moment(this.e.year + '-' + this.e.month + '-' + this.e.day);
                return momentDate.format('D MMMM YYYY, ddd');
                // return this.e.year + '-' + this.e.month + '-' + this.e.day;
            },
            isEDate: function () {
                if(this.e !== null ||
                typeof this.e.day !== 'undefined' || this.e.day !== null ||
                typeof this.e.month !== 'undefined' || this.e.month !== null ||
                typeof this.e.year !== 'undefined' || this.e.year !== null)
                    return true;
                return false;
            },
        },
        methods: {
            applyDurationAndBackToEvents: function (e) {
                if(!this.isMovingEvent)
                    return false;
                
                this.app.editEvent(this.movingEvent.id, {
                    duration: calendarHelper.time.composeHourMinuteTimeFromMinutes(this.$refs.duration.duration),
                }).then((data) => {
                    this.$store.dispatch('moving_event/reset');
                    return this.setEvents();
                }).finally(() => {
                    this.showTab = 'events';
                    this.$store.commit('updater/increaseCounter');
                });
            },
            applyDurationAndCloseModal: function (e) {
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
            eventDate: function () {
                if(this.e == null ||
                typeof this.e.day === 'undefined' || this.e.day === null ||
                typeof this.e.month === 'undefined' || this.e.month === null ||
                typeof this.e.year === 'undefined' || this.e.year === null)
                    return null;
                let momentDate = moment(this.e.year + '-' + this.e.month + '-' + this.e.day);
                return momentDate.format('D MMMM YYYY, ddd');
                // return this.e.year + '-' + this.e.month + '-' + this.e.day;
            },
            pickMovingEventTime: function () {
                this.$refs.edit.setMovingEventPickedItems();
                // this.setShowMovingEvent(true);
                // alert(this.showMovingEvent);
                // this.$store.dispatch('moving_event/setShow', true);
                this.$store.commit('updater/increaseCounter');
                this.$store.dispatch('moving_event/setShow', true);
                this.hide(false);
                // alert(11111);
            },
            setShowEditResetBtn: function () {
                if(typeof this.$refs.edit === 'undefined' || this.$refs.edit === null){
                    this.showEditResetBtn = false;
                }else{
                    this.showEditResetBtn = this.$refs.edit.isPickedItemsChanged;
                }
            },
            setShowEditPickTimeBtn: function () {
                if(typeof this.$refs.edit === 'undefined' || this.$refs.edit === null){
                    this.showEditPickTimeBtn = false;
                }else{
                    this.showEditPickTimeBtn = this.$refs.edit.isAllItemsPicked;
                }
            },
            onEditChange: function () {
                this.setShowEditResetBtn();
                this.setShowEditPickTimeBtn();
            },
            backToEvents: function () {
                this.$store.dispatch('moving_event/reset');
                // this.showTab = 'events';
                this.setEvents().finally(() => {
                    // $('#' + this.modalId).modal('show');
                    this.showTab = 'events';
                });
            },
            onActionMove: function (event) {
                this.app.setMovingEvent(event).then((data) => {
                    // console.log(JSON.parse(JSON.stringify(event)));
                    this.showTab = 'edit';
                });
            },
            clickActionApprove: function (event) {
                this.app.approveEvent(event.id).then((data) => {
                    return this.setEvents();
                }).finally(() => {
                    this.$store.commit('updater/increaseCounter');
                });
            },
            onActionRemove: function (event) {
                this.app.removeEvent(event.id).then((data) => {
                    return this.setEvents();
                }).finally(() => {
                    this.$store.commit('updater/increaseCounter');
                });
            },
            clickActionDuration: function (event) {
                this.app.setMovingEvent(event).then((data) => {
                    // console.log(JSON.parse(JSON.stringify(event)));
                    this.durationE = {
                        event: event,
                        nextEvent: this.getNextEventFromEvents(this.events, event),
                    }
                    this.showTab = 'duration';
                    // console.log(JSON.parse(JSON.stringify(event)));
                    console.log(JSON.parse(JSON.stringify(this.durationE)));
                });
            },
            durationStrHoursAndMinutes: function (event) {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(event.right_duration);
            },
            setEvents: function (){
                return new Promise((resolve, reject) => {
                    if(this.isEDate){
                        let date = this.e.year + '-' + this.e.month + '-' + this.e.day;
                        this.app.getData(date, date, {
                            type: 'all'
                        }).then((data) => {
                            // console.log(JSON.parse(JSON.stringify(data)));
                            // console.log(JSON.parse(JSON.stringify(data.data[0].items)));
                            this.events = data.data[0].items;
                            resolve();
                        });
                    }else{
                        this.events = null;
                        reject();
                    }
                });
            },
            show: function (e){
                console.log(JSON.parse(JSON.stringify(e)));
                this.e = e;
                this.setEvents().finally(() => {
                    $('#' + this.modalId).modal('show');
                });
            },
            hide: function (resetMovingEvent = true){
                $('#' + this.modalId).modal('hide');
                // alert(resetMovingEvent);
                if(resetMovingEvent === true){
                    setTimeout(() => {
                     	this.$store.dispatch('moving_event/reset');
                    }, 400);
                }
                // setTimeout(() => {
                //     this.showTab = 'events';
                // }, 1000);
                // this.showTab = 'events';
            },
            // For DURATION tab
            durationChanged: function (e){
                // console.log(this.$refs.duration.isDurationChanged);
                this.setShowDurationApplyBtn();
            },
            setShowDurationApplyBtn: function () {
                this.showDurationApplyBtn = typeof this.$refs.duration !== 'undefined' && this.$refs.duration.isDurationChanged;
            },
        },
        components: {
            // TimeBar,
            // TimeBarFill,
            // TimeBarNew,
            Loader,
            Actions,
            Edit,
            Duration,
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
    .for-actions{
        position: absolute;
        top: 2px;
        right: 4px;
    }
</style>