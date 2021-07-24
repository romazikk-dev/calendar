<template>
    <div>
        
        <div class="moving-event-box text-white" :class="{
            'bg-approved': movingEvent.approved,
            'bg-not-approved': !movingEvent.approved,
        }">
                <div class="top-title badge badge-pill badge-warning">Edit event: {{workerFullName}}</div>
                {{templateTitle}}<br>
                {{fullNameOfObj(movingEventClient)}}
                <ul class="itemms text-white" :class="{
                    'bg-approved': movingEvent.approved,
                    'bg-not-approved': !movingEvent.approved,
                }">
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        data-custom-class="tooltip-moving-event-box-info-btn"
                        :data-original-title="'<div class=\'small\'>' + itemmInfoTooltipTitle + '</div>'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                    </li>
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        data-custom-class="tooltip-moving-event-box-edit-btn"
                        title="<div class='small'>Edit</div>">
                            <a href="#" @click.prevent="onClickEdit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                            </a>
                    </li>
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        data-custom-class="tooltip-moving-event-box-close-btn"
                        title="<div class='small'>Close</div>">
                            <a href="#" @click.prevent="onClickClose">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                                </svg>
                            </a>
                    </li>
                </ul>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'movingEventInfoBox',
        updated() {
            
        },
        mounted() {
            
        },
        // props: ['startDate'],
        data: function(){
            return {
                // dateRange: helper.range.range,
            };
        },
        computed: {
            itemmInfoTooltipTitle: function(){
                return `
                    <div class="tooltip-moving-event-info">
                        <div>
                            <h5>Client:</h5>
                            <div class="d-table info-list">
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Name:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>` + this.fullNameOfObj(this.movingEventClient) + `</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Email:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>` + (this.movingEventClient.email ? this.movingEventClient.email : '') + `</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Time:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>` + this.bookingTimeTwoRow + `</b>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr />
                        <div>
                            <h5>Event:</h5>
                            <div class="d-table info-list">
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Hall:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>${this.hallTitle}</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Template:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>${this.templateTitle}</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Worker:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>${this.workerFullName}</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Approved:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>` + (this.movingEvent.approved ? 'Yes' : 'No') + `</b>
                                    </div>
                                </div>
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        Duration:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>${this.durationStrHoursAndMinutes}</b>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                `;
            },
            durationStrHoursAndMinutes: function (){
                if(this.movingEvent === null || typeof this.movingEvent.right_duration === 'undefined' || this.movingEvent.right_duration === null)
                    return null;
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.movingEvent.right_duration);
            },
            bookingTimeOneRow: function(){
                if(this.movingEvent === null || typeof this.movingEvent.time === 'undefined' || this.movingEvent.time === null)
                    return null;
                return moment(this.movingEvent.time).format('YYYY-MM-DD ddd HH:mm');
            },
            bookingTimeTwoRow: function(){
                if(this.movingEvent === null || typeof this.movingEvent.time === 'undefined' || this.movingEvent.time === null)
                    return null;
                return moment(this.movingEvent.time).format('YYYY-MM-DD<br>ddd HH:mm');
            },
            hallTitle: function(){
                if(this.movingEventPicked !== null && typeof this.movingEventPicked.hall !== 'undefined' && this.movingEventPicked.hall !== null)
                    return this.movingEventPicked.hall.title;
                if(this.movingEvent === null || typeof this.movingEvent.hall_without_user_scope === 'undefined')
                    return null;
                return this.movingEvent.hall_without_user_scope.title;
            },
            workerFullName: function(){
                if(this.movingEvent === null)
                    return null;
                if(this.movingEventPicked.worker !== null)
                    return this.fullNameOfObj(this.movingEventPicked.worker);
                return this.fullNameOfObj(this.movingEvent.worker_without_user_scope);
            },
            templateTitle: function(){
                if(this.movingEventPicked !== null && typeof this.movingEventPicked.template !== 'undefined' && this.movingEventPicked.template !== null)
                    return this.movingEventPicked.template.title;
                if(this.movingEvent === null || typeof this.movingEvent.template_without_user_scope === 'undefined')
                    return null;
                return this.movingEvent.template_without_user_scope.title;
            },
        },
        methods: {
            onClickClose: function(){
                $(".tooltip-active").tooltip("hide");
                // this.$emit('close');
                this.resetMovingEvent();
            },
            onClickEdit: function(){
                $(".tooltip-active").tooltip("hide");
                // this.$emit('edit');
                // this.app.$refs.navbar.$refs.modal_book.show();
                this.app.$refs.move_path_picker.show();
            },
        },
        components: {
            // Navigation,
        },
        watch: {
            // dates: function () {
            // 
            // },
        }
    }
</script>

<style lang="scss" scoped>
    
</style>