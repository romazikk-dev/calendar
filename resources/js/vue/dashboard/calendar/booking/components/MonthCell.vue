<template>
    <div>
        <div v-if="item.count_total" class="counters" :class="{
            'counters-to-top': countersToTop,
            'counters-dis-transparency': countersDisTransparency,
            'counters-bigger': countersBigger,
        }">
            <ul>
                <li>
                    <span class="badge badge-pill tooltip-active badge-counter-total"
                         data-placement="auto"
                         title="<div class='small'>Total per day</div>">
                         {{item.count_total}}
                     </span>
                </li>
                <li v-if="item.count_approved">
                    <span class="badge badge-pill tooltip-active badge-counter-approved"
                         data-placement="auto"
                         title="<div class='small'>Approved per day</div>">
                         {{item.count_approved}}
                     </span>
                </li>
                <li v-if="item.count_not_approved">
                    <span class="badge badge-pill tooltip-active badge-counter-not-approved"
                         data-placement="auto"
                         title="<div class='small'>Requested per day, but not approved yet</div>">
                         {{item.count_not_approved}}
                     </span>
                </li>
            </ul>
        </div>
        <div v-if="items" class="events">
            <div class="row for-events">
                <template>
                <div v-if="conditionForItemsIteration(index)" v-for="(itm, index) in items"
                class="if-events"
                :class="{
                    'col-12': !eventsInRow,
                    'col-sm-6 col-md-4 col-lg-3 col-xl-2': eventsInRow,
                }">
                    <template v-if="itm.type == 'free'">
                        
                        <div class='free-slot'>
                            <b>Free time:</b><br>
                            <b>{{itm.from}} - {{itm.to}}</b>
                            <div class="not-approved-bookings" v-if="itm.not_approved_bookings">
                                <div class="not-approved-bookings-itm" v-for="itmm in itm.not_approved_bookings">
                                    <b>In approving:</b><br>
                                    <b>{{itmm.booking.template_without_user_scope.title}}<br>
                                    {{itmm.from}} - {{itmm.to}}</b>
                                    <!-- <button @click.prevent="$emit('cancel', itm)"
                                        type="button"
                                        class="btn btn-link btn-sm btn-block">Cancel</button > -->
                                    <button @click.prevent="$emit('cancel', itmm.booking)"
                                        type="button"
                                        class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
                                </div>
                            </div>
                            <button @click.prevent="onClickPickFree(itm)"
                                type="button"
                                class="btn btn-link btn-sm btn-block book">Pick</button >
                        </div>
                        
                    </template>
                    <template v-else>
                        <div class='event' :class="{
                            'approved': itm.approved
                        }">
                            <div v-if="itm.time_crossing"
                                class="time-crossing text-warning tooltip-active"
                                data-placement="auto"
                                title="<div class='small'>Event is crossing time one of events above</div>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                            </div>
                            
                            <div class="event-itemm text-warning">
                                <span>Client: </span><b>{{itm.client_without_user_scope.first_name}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Hall: </span><b>{{itm.hall_without_user_scope.title}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Temp: </span><b>{{itm.template_without_user_scope.title}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Time: </span><b>{{itm.from}} - {{itm.to}}</b>
                            </div>
                            <div class="event-itemm">
                                <span>Worker: </span><b>{{itm.worker_without_user_scope.first_name}}</b>
                            </div>
                            
                            <div :class="{'right-placed-actions': rightPlacedActions}">
                                <actions :event="itm"
                                    :day-data="item"
                                    :ref="'actions_' + itm.id"
                                    :rightPlaced="rightPlacedActions"/>
                            </div>
                            
                            <div class="clearfix"></div>
                        </div>
                    </template>
                </div>
                </template>
            </div>
        </div>
        <div v-if="showButtonMore"
            class="for-show-more-events-btn">
                <a href="#"
                    @click.prevent="$emit('clickMore', item)"
                    class="btn btn-sm btn-info show-more-events-btn">
                        More ...
                </a>
                <div class="clearfix"></div>
        </div>
        <div v-if="!items" class="cell-placeholder"></div>
    </div>
</template>

<script>
    // import LoaderAction from "./LoaderAction.vue";
    import Actions from "./event/Actions.vue";
    export default {
        name: 'monthCell',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
                trigger: "hover",
            });
        },
        mounted() {
            $('.tooltip-active').tooltip({
                html: true,
                trigger: "hover",
            });
            // console.log(this.dateRange);
            // console.log(JSON.parse(JSON.stringify(this.item)));
        },
        props: ['item','countersToTop','countersDisTransparency','countersBigger','rightPlacedActions','eventsInRow'],
        data: function(){
            return {
                // range: helper.range.range,
                // app: null,
            };
        },
        computed: {
            items: function () {
                if(this.item === null || typeof this.item.items === 'undefined' ||
                this.item.items === null)
                    return null;
                return this.item.items
            },
            maxEventsPerDay: function () {
                if(this.view == 'month')
                    return this.calendarSettings.month_max_events_per_day_to_show;
                if(this.view == 'week')
                    return this.calendarSettings.week_max_events_per_day_to_show;
                if(this.view == 'day')
                    return this.calendarSettings.day_max_events_per_day_to_show;
                if(this.view == 'list')
                    return this.calendarSettings.list_max_events_per_day_to_show;
                return null;
            },
            showButtonMore: function () {
                return typeof this.item.items !== 'undefined' && Array.isArray(this.item.items) &&
                this.item.items.length > 0 && this.app.lastGetDataType === 'all' &&
                this.item.items.length > this.maxEventsPerDay;
            },
        },
        methods: {
            conditionForItemsIteration: function (index) {
                return typeof this.item.items !== 'undefined' && Array.isArray(this.item.items) &&
                this.item.items.length > 0 &&
                (
                    this.app.lastGetDataType === 'free' ||
                    (this.app.lastGetDataType === 'all' && index < this.maxEventsPerDay )
                );
            },
            actionDropdownMenuHover: function (e) {
                $(e.target).closest('.action-drop').find('.drop-toggle').tooltip('hide');
            },
            onClickPickFree: function (itm) {
                this.app.showPickTimeModal({
                    day: this.item,
                    item: itm,
                });
            },
        },
        components: {
            Actions
        },
    }
</script>

<style lang="scss" scoped>
    .not-approved-bookings-itm{
        background-color: #cf582c;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        margin-bottom: 2px;
        position: relative;
        left: -28px;
        width: calc(100% + 28px);
        /* padding-left: 36px; */
    }
    button.cancel{
        position: absolute;
        top: 0px;
        right: 0px;
        outline: none!important;
        /* background-color: red; */
        border-radius: 0px;
        width: 30px;
        height: 30px;
        font-size: 40px;
        line-height: .5em;
        padding: 0px;
        text-decoration: none;
        color: #fff;
        opacity: .6;
        transition: opacity .3s ease;
        box-shadow: none;
    }
    /* .not-approved-bookings-itm button.cancel */
    .not-approved-bookings-itm:hover button.cancel,
    .booked-slot:hover button.cancel{
        opacity: 1;
    }
    button.cancel span{
        display: block;
        width: 100%;
        height: 100%;
    }
    button.book{
        box-shadow: none;
    }
</style>