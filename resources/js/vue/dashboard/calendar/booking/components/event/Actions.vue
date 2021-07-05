<template>
    <div>
        
        <div class="event-actions"
        :class="{
            'bigger': bigger,
            'right-placed': rightPlaced,
        }">
            <ul>
                <li class="action-move">
                    
                    <a href="#" data-placement="bottom"
                        @click.prevent="clickActionMove($event)"
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
                            :id="'actionDateTimeDropdown_' + itm.id"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-placement="bottom"
                            title="<div class='small'>Time</div>">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    :width="clockIcoSize"
                                    :height="clockIcoSize"
                                    fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                        </a>

                        <div @mouseover="actionDropdownMenuHover($event)" @click.stop class="dropdown-menu" :aria-labelledby="'actionDateTimeDropdown_' + itm.id">
                            <div class="itemms">
                                <div>
                                    <a v-if="itm.time_crossing"
                                        class="dropdown-item tooltip-active disable"
                                        data-placement="auto"
                                        title="<div class='small'>Not available as event is crossing time one of other events of this day</div>"
                                        @click.stop.prevent
                                        href="#">
                                            Duration
                                            <span v-if="itm.time_crossing" class="action-info text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </svg>
                                            </span>
                                    </a>
                                    <a v-else
                                        class="dropdown-item"
                                        @click.prevent="clickActionDuration($event, itm)"
                                        href="#">Duration</a>
                                </div>
                                <div>
                                    <a class="dropdown-item"
                                        @click.prevent="$emit('clickActionDateTime')"
                                        href="#">Date&Time</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </li>
                <li v-if="!itm.approved" class="action-approve">
                    
                    <a :ref="'action_approve_loader_' + itm.id" class="tooltip-active action-loader d-none">
                        <loader-action />
                    </a>
                    <a :ref="'action_approve_btn_' + itm.id" href="#"
                        data-placement="bottom"
                        @click.prevent="clickActionApprove($event, itm)"
                        class="tooltip-active"
                        :class="{
                            'disable': itm.time_crossing
                        }"
                        :title="
                            itm.time_crossing ?
                            '<div class=\'small\'>Approve<br>Not available, event`s time is crossing one of events of a day,<br>please set right time</div>' :
                            '<div class=\'small\'>Approve</div>'
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                :width="okIcoSize"
                                :height="okIcoSize"
                                fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                    </a>
                    
                </li>
                <li class="action-remove">
                    
                    <a :ref="'action_remove_loader_' + itm.id" class="tooltip-active action-loader d-none">
                        <loader-action />
                    </a>
                    <div class="action-drop dropup" :ref="'action_remove_drop_' + itm.id">
                        <a class="tooltip-active drop-toggle" href="#"
                            :ref="'action_remove_drop_toggle_' + itm.id"
                            role="button"
                            :id="'actionRemoveDropdown_' + itm.id"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-placement="bottom"
                            title="<div class='small'>Remove</div>">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    :width="removeIcoSize"
                                    :height="removeIcoSize"
                                    fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                        </a>

                        <div @mouseover="actionDropdownMenuHover($event)" @click.stop class="dropdown-menu" :aria-labelledby="'actionRemoveDropdown_' + itm.id">
                            <div class="small">
                                Do you really want to delete this event?
                            </div>
                            <div class="btnns">
                                <a href="#"
                                    @click.prevent="onActionRemove($event, itm)"
                                    class="btnn text-primary btnn-yes">
                                        Yes
                                </a>
                                <a href="#"
                                    @click.prevent="$refs['action_remove_drop_toggle_' + itm.id].click()"
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
            // console.log(JSON.parse(JSON.stringify(this.rightPlaced)));
            // console.log(this.bigger);
            // alert(1111);
        },
        props: ['itm','bigger','rightPlaced'],
        data: function(){
            return {
                // range: helper.range.range,
                // app: null,
            };
        },
        computed: {
            pencilIcoSize: function () {
                return this.bigger === true ? 16 : 12;
            },
            clockIcoSize: function () {
                return this.bigger === true ? 16 : 12;
            },
            okIcoSize: function () {
                return this.bigger === true ? 26 : 18;
            },
            removeIcoSize: function () {
                return this.bigger === true ? 26 : 16;
            },
        },
        methods: {
            clickActionMove: function (e) {
                // console.log(e.target);
                $(e.target).closest('.action-move').find('.tooltip-active').tooltip("hide");
                this.$emit('clickActionMove');
            },
            clickActionDuration: function (e, event) {
                if(typeof event.time_crossing !== 'undefined' && event.time_crossing === true)
                    return;
                    
                $(e.target).closest('.action-drop').find('.tooltip-active').click();
                this.$emit('clickActionDuration');
            },
            clickActionApprove: function (e, event) {
                if(typeof event.time_crossing !== 'undefined' && event.time_crossing === true)
                    return;
                
                // this.toggleActionLoader('action_approve_loader_' + event.id, 'action_approve_btn_' + event.id);
                this.$emit('clickActionApprove');
            },
            onActionRemove: function (e, event) {
                // let _this = this;
                this.$refs['action_remove_drop_toggle_' + event.id].click(); 
                // this.toggleActionLoader(true, 'action_remove_drop_' + event.id, 'action_remove_loader_' + event.id);
                
                // this.$emit.()
                return;
                // new Promise((resolve, reject) => {
                //     this.toggleActionLoader('action_remove_loader_' + event.id, 'action_remove_drop_' + event.id);
                //     this.app.removeEvent(event.id, (data) => {
                //         resolve(data);
                //     });
                // }).then((data) => {
                //     if(typeof data.status !== 'undefined' && data.status == 'success'){
                //         // alert('Successfuly removed!!!');
                //     }else{
                //         if(typeof data.msg !== 'undefined' && data.msg !== null)
                //             alert(data.msg);
                //     }
                //     this.toggleActionLoader('action_remove_drop_' + event.id, 'action_remove_loader_' + event.id);
                //     this.$emit('removed');
                // });
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