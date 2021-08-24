<template>
    <tr class="event"
    :class="{
        'approved': item.approved,
        'time-crossing': item.time_crossing,
    }">
        <td>
            {{item.from}} - {{item.to}}
            <div v-if="item.time_crossing"
                class="warning-sign text-warning tooltip-active float-right"
                data-placement="auto"
                title="<div class='small'>Event is crossing time one of other event</div>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
            </div>
        </td>
        <td>
            <div class="status tooltip-active"
                data-placement="auto"
                :title="getStatusTooltipTitle(item)"></div>
        </td>
        <td :colspan="hideActions ? 2 : false">
            <!-- <div v-if="item.time_crossing"
                class="warning-sign text-warning tooltip-active float-left"
                data-placement="auto"
                title="<div class='small'>Event is crossing time one of other event</div>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
            </div> -->
            <div class="infos">
                <div class="info-item">
                    <span class="vall vall-client">{{fullNameOfObj(item.client_without_user_scope)}}</span>
                    <span class="small labell">(client)</span>
                </div>
                <div class="info-item">
                    <span class="vall vall-hall">{{item.hall_without_user_scope.title}}</span>
                    <span class="small labell">(hall)</span>
                </div>
                <div class="info-item">
                    <span class="vall vall-template">{{item.template_without_user_scope.title}}</span>
                    <span class="small labell">(template)</span>
                </div>
                <div class="info-item">
                    <span class="vall vall-worker">{{fullNameOfObj(item.worker_without_user_scope)}}</span>
                    <span class="small labell">(worker)</span>
                </div>
                <div class="info-item">
                    <span class="vall vall-worker">{{getDurationStrHoursAndMinutes(item.right_duration)}}</span>
                    <span class="small labell">(duration)</span>
                </div>
            </div>
        </td>
        <td v-if="!hideActions">
            <div class="float-right">
                <actions :event="item"
                    :day-data="dayItem"
                    :ref="'actions_' + item.id"
                    action-color="#549fb7"
                    size="middle"
                    :without-hover-bg="true"
                    :dropdown-to-left="true"
                    tooltip-placement="top"
                    :disabled-items-with-line-through="true"
                    :disabled-drop-menu-items-with-line-through="true"/>
            </div>
        </td>
    </tr>
</template>

<script>
    import Actions from "../event/Actions.vue";
    export default {
        name: 'listCalendarEventRow',
        mounted() {
            
        },
        updated: function () {},
        props: ['item','hideActions', 'dayItem'],
        data: function(){
            return {
                // data: null,
            };
        },
        computed: {
            // datesPerWeekday: function () {},
        },
        methods: {
            getStatusTooltipTitle: function (item) {
                if(item.approved)
                    return "<div class='small'>Approved</div>";
                return "<div class='small'>Not approved</div>";
            },
            getDurationStrHoursAndMinutes: function (duration) {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(duration);
            },
        },
        components: {
            Actions
        },
        watch: {
            
        }
    }
</script>

<style lang="scss" scoped>
    tbody{
        width: 100%;
    }
    .calendar-item{
        /* width: 96%;
        position: absolute;
        left: 2%;
        background-color: rgba(144,238,144, 0.5);
        border-radius: 4px;
        z-index: 9;
        cursor: pointer;
        line-height: 1em;
        padding: 2px; */
        
        position: absolute;
        /* display: block; */
        width: 96%;
        left: 2%;
        /* background-color: rgba(144,238,144, 0.5); */
        /* background-color: #d4f4c9; */
        margin-bottom: 3px;
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: black;
        text-decoration: none;
        transition: background .2s ease;
        z-index: 10;
        /* line-height: 1.2em; */
        cursor: pointer;
        font-weight: bold;
    }

    .free-calendar-item{
        /* background-color: #d4f4c9!important; */
        background-color: rgba(144,238,144, 0.5);
    }
    .free-calendar-item:hover{
        background-color: rgba(144,238,144, 0.8);
    }

    /* .booked-calendar-item:hover{
        background-color: rgba(114,51,128, 1)!important;
    } */
    .faded-time{
        font-weight: bold;
        color: #ccc;
    }
</style>

<style>
    
</style>