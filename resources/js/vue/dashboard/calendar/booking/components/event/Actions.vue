<template>
    <div>
        
        <div class="event-actions"
        :style="{
            '--action-color': actionColor,
        }"
        :class="{
            'bigger': bigger,
            'right-placed': rightPlaced,
            'size-middle': size == 'middle',
            'without-hover-bg': withoutHoverBg,
            'disabled-items-with-line-through': disabledItemsWithLineThrough,
            'disabled-drop-menu-items-with-line-through': disabledDropMenuItemsWithLineThrough,
        }">
            <ul>
                <li class="action-move">
                    
                    <a href="#" :data-placement="tooltipPlacement ? tooltipPlacement : 'bottom'"
                        @click.prevent="onClickActionMove($event)"
                        class="tooltip-active"
                        title="<div class='small'>Edit</div>">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                :width="pencilIcoSize"
                                :height="pencilIcoSize"
                                fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                    </a>
                    
                </li>
                <li class="action-date-time">
                    
                    <div class="action-drop dropup">
                        <a class="tooltip-active drop-toggle" href="#"
                            role="button"
                            :id="'actionDateTimeDropdown_' + event.id"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            :data-placement="tooltipPlacement ? tooltipPlacement : 'bottom'"
                            title="<div class='small'>Time</div>">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    :width="clockIcoSize"
                                    :height="clockIcoSize"
                                    fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                        </a>

                        <div @mouseover="actionDropdownMenuHover($event)" @click.stop
                        class="dropdown-menu"
                        :class="{
                            'dropdown-menu-right': dropdownToLeft || dropdownToLeftInDepenseOfWeekday,
                        }" :aria-labelledby="'actionDateTimeDropdown_' + event.id">
                            <div class="itemms">
                                <div>
                                    <a v-if="event.time_crossing"
                                        class="dropdown-item tooltip-active disable"
                                        data-placement="auto"
                                        title="<div class='small'>Not available as event is crossing time one of other events of this day</div>"
                                        @click.stop.prevent
                                        href="#">
                                            Duration
                                            <span v-if="event.time_crossing" class="action-info text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </svg>
                                            </span>
                                    </a>
                                    <a v-else
                                        class="dropdown-item"
                                        @click.prevent="onClickActionDuration($event)"
                                        href="#">Duration</a>
                                </div>
                                <div>
                                    <a class="dropdown-item"
                                        @click.prevent="onClickActionDateTime"
                                        href="#">Date&Time</a>
                                </div>
                                <!-- <div class="action-date-time-current-day" v-if="showDateTimeCurrentDay">
                                    <a class="dropdown-item"
                                        @click.prevent="$emit('clickActionDateTimeCurrentDay')"
                                        href="#">Date&Time<br><span class='small'>(current day)</span></a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    
                </li>
                <li v-if="!event.approved" class="action-approve">
                    
                    <a :ref="'action_approve_loader_' + event.id" class="tooltip-active action-loader d-none">
                        <loader-action />
                    </a>
                    <a :ref="'action_approve_btn_' + event.id" href="#"
                        :data-placement="tooltipPlacement ? tooltipPlacement : 'bottom'"
                        @click.prevent="onClickActionApprove($event)"
                        class="tooltip-active"
                        :class="{
                            'disable': event.time_crossing
                        }"
                        :title="
                            event.time_crossing ?
                            '<div class=\'small\'>Approve<br>Not available, event`s time is crossing one of events of a day,<br>please set right time</div>' :
                            '<div class=\'small\'>Approve</div>'
                        ">
                            <div v-if="event.time_crossing && disabledItemsWithLineThrough" class="line-through"></div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                :width="okIcoSize"
                                :height="okIcoSize"
                                fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                    </a>
                    
                </li>
                <li class="action-remove">
                    
                    <a :ref="'action_remove_loader_' + event.id" class="tooltip-active action-loader d-none">
                        <loader-action />
                    </a>
                    <div class="action-drop dropup" :ref="'action_remove_drop_' + event.id">
                        <a class="tooltip-active drop-toggle" href="#"
                            :ref="'action_remove_drop_toggle_' + event.id"
                            role="button"
                            :id="'actionRemoveDropdown_' + event.id"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            :data-placement="tooltipPlacement ? tooltipPlacement : 'bottom'"
                            title="<div class='small'>Remove</div>">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    :width="removeIcoSize"
                                    :height="removeIcoSize"
                                    fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                        </a>

                        <div @mouseover="actionDropdownMenuHover($event)" @click.stop
                        class="dropdown-menu"
                        :class="{
                            'dropdown-menu-right': dropdownToLeft || dropdownToLeftInDepenseOfWeekday,
                        }" :aria-labelledby="'actionRemoveDropdown_' + event.id">
                            <div class="small">
                                Do you really want to delete this event?
                            </div>
                            <div class="btnns">
                                <a href="#"
                                    @click.prevent="onClickActionRemove($event, event)"
                                    class="btnn text-primary btnn-yes">
                                        Yes
                                </a>
                                <a href="#"
                                    @click.prevent="$refs['action_remove_drop_toggle_' + event.id].click()"
                                    class="btnn text-primary btnn-no">
                                        No
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
    </div>
</template>

<script>
    import LoaderAction from "../LoaderAction.vue";
    export default {
        name: 'Actions',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.dayItem)));
            // console.log(this.bigger);
            // alert(1111);
            $('.tooltip-active').tooltip({
                html: true,
                trigger: "hover",
            });
        },
        props: [
            'event','dayData','bigger','rightPlaced','showDateTimeCurrentDay','actionColor',
            'size','withoutHoverBg','dropdownToLeft','emitEventsOnBtnsClick','tooltipPlacement',
            'disabledItemsWithLineThrough', 'disabledDropMenuItemsWithLineThrough',
        ],
        data: function(){
            return {
                // range: helper.range.range,
                // app: null,
            };
        },
        computed: {
            dropdownToLeftInDepenseOfWeekday: function () {
                return this.isProp(this.dayData) && [6,7].includes(this.dayData.weekday);
            },
            isEvents: function () {
                return this.isProp(this.events) &&
                Array.isArray(this.events) && this.events.length > 0;
            },
            events: function () {
                if(!this.isProp(this.dayData) || !this.isProp(this.dayData.items))
                    return null;
                return this.dayData.items;
            },
            pencilIcoSize: function () {
                if(this.isProp(this.size) && this.size == 'middle')
                    return 14;
                if(this.isProp(this.size) && this.size == 'large')
                    return 16;
                return 12;
            },
            clockIcoSize: function () {
                if(this.isProp(this.size) && this.size == 'middle')
                    return 14;
                if(this.isProp(this.size) && this.size == 'large')
                    return 16;
                return 12;
            },
            okIcoSize: function () {
                if(this.isProp(this.size) && this.size == 'middle')
                    return 22;
                if(this.isProp(this.size) && this.size == 'large')
                    return 26;
                return 18;
            },
            removeIcoSize: function () {
                if(this.isProp(this.size) && this.size == 'middle')
                    return 21;
                if(this.isProp(this.size) && this.size == 'large')
                    return 26;
                return 16;
            },
        },
        methods: {
            getNextEvent: function () {
                if(!this.isProp(this.event) || !this.isEvents)
                    return null;
                
                let nextEvent = null;
                let isNext = false;
                for(let i = 0; i < this.events.length; i++){
                    if(isNext && this.event.worker_id == this.events[i].worker_id && this.events[i].approved == 1){
                        nextEvent = this.events[i];
                        break;
                    }
                    if(this.event.id == this.events[i].id){
                        isNext = true;
                    }
                }
                
                return nextEvent;
            },
            onClickActionMove: function (e) {
                $(e.target).closest('.action-move').find('.tooltip-active').tooltip("hide");
                
                if(this.isProp(this.emitEventsOnBtnsClick)){
                    this.$emit('clickActionMove');
                    return;
                }
                
                // this.app.setMovingEvent(this.event).then((data) => {
                    this.app.$refs.move_path_picker.show(this.event);
                // });
            },
            onClickActionDuration: function (e) {
                if(typeof this.event.time_crossing !== 'undefined' && this.event.time_crossing === true)
                    return;
                    
                $(e.target).closest('.action-drop').find('.tooltip-active').click();
                
                if(this.isProp(this.emitEventsOnBtnsClick)){
                    this.$emit('clickActionDuration');
                    return;
                }
                
                // this.app.setMovingEvent(this.event).then((data) => {
                    this.app.showModalDuration({
                        day: this.dayData,
                        event: this.event,
                        nextEvent: this.getNextEvent(),
                    });
                // });
            },
            onClickActionDateTime: function () {
                if(this.isProp(this.emitEventsOnBtnsClick)){
                    this.$emit('clickActionDateTime');
                    return;
                }
                
                this.app.setMovingEvent(this.event).then((data) => {
                    this.calendar.getData({
                        type: 'free',
                        exclude_ids: [this.movingEvent.id]
                    });
                });
            },
            onClickActionApprove: function (e) {
                if(typeof this.event.time_crossing !== 'undefined' && this.event.time_crossing === true)
                    return;
                
                this.closeTooltipOfEvent(e);
                
                if(this.isProp(this.emitEventsOnBtnsClick)){
                    this.$emit('clickActionApprove');
                    return;
                }
                
                this.toggleActionLoader('action_approve_loader_' + this.event.id, 'action_approve_btn_' + this.event.id);
                this.app.approveEvent(this.event.id).then((data) => {
                    if(typeof data.status == 'undefined' || data.status != 'success')
                        if(typeof data.msg !== 'undefined' && data.msg !== null)
                            alert(data.msg);
                    
                    this.toggleActionLoader('action_approve_btn_' + this.event.id, 'action_approve_loader_' + this.event.id);
                    this.calendar.getData();
                });
            },
            onClickActionRemove: function (e) {
                this.$refs['action_remove_drop_toggle_' + this.event.id].click();
                
                new Promise((resolve, reject) => {
                    this.toggleActionLoader('action_remove_loader_' + this.event.id, 'action_remove_drop_' + this.event.id);
                    this.app.removeEvent(this.event.id).then((data) => {
                        resolve(data);
                    });
                }).then((data) => {
                    if(typeof data.status === 'undefined' || data.status != 'success')
                        if(typeof data.msg !== 'undefined' && data.msg !== null)
                            alert(data.msg);
                            
                    this.toggleActionLoader('action_remove_drop_' + this.event.id, 'action_remove_loader_' + this.event.id);
                    this.calendar.getData();
                });
            },
            toggleActionLoader: function (refShow, refHide) {
                $(this.$refs[refShow]).removeClass('d-none');
                $(this.$refs[refHide]).addClass('d-none');
            },
            actionDropdownMenuHover: function (e) {
                $(e.target).closest('.action-drop').find('.drop-toggle').tooltip('hide');
            },
        },
        components: {
            LoaderAction
        },
    }
</script>

<style lang="scss" scoped>
    
</style>