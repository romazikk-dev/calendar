<template>
    <div>
        
        <div class="new-event-box text-white bg-approved">
                <div class="top-title badge badge-pill badge-primary">New event: {{workerFullName}}</div>
                {{templateTitle}}<br>
                {{fullNameOfObj(newEventClient)}}
                <ul class="itemms text-white bg-approved">
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        :data-original-title="'<div class=\'small\'>' + itemmInfoTooltipTitle + '</div>'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                    </li>
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        title="<div class='small'>Edit</div>">
                            <a href="#" @click.prevent="onClickEdit($event)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                            </a>
                    </li>
                    <li class="itemm tooltip-active"
                        data-placement="left"
                        title="<div class='small'>Close</div>">
                            <a href="#" @click.prevent="onClickClose($event)">
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
    // import MonthCellCounters from "./MonthCellCounters.vue";
    export default {
        name: 'monthMovingEvent',
        updated() {
            // $('.tooltip-active').tooltip({
            //     html: true,
            // });
        },
        mounted() {
            // if(this.componentApp === null)
            //     this.componentApp = this.getParentComponentByName(this, 'app');
            // 
            // // this.setDates(moment(new Date()).startOf('month').toDate());
            // this.setDates(moment(this.startDate).startOf('month').toDate());
            // 
            // this.getData();
            // 
            // // this.getData();
            // $("#bookModal").on('hidden.bs.modal', () => {
            //     this.bookDate = null;
            //     // console.log(this.bookDate);
            // });
        },
        // props: ['startDate'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                // componentApp: this.getParentComponentByName(this, 'app'),
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
                                        <b>` + this.fullName(this.newEventClient) + `</b>
                                    </div>
                                </div>` +
                                (typeof this.newEventClient.email !== 'undefined' && this.newEventClient.email !== null ?
                                `<div class="d-table-row">
                                    <div class="d-table-cell">
                                        Email:
                                    </div>
                                    <div class="d-table-cell">
                                        <b>` + this.newEventClient.email + `</b>
                                    </div>
                                </div>` : ``) +
                            `</div>
                
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
                                </div>` +
                            `</div>
                
                        </div>
                    </div>
                `;
            },
            hallTitle: function(){
                if(this.isNewEventMainFull)
                    return this.newEventMain.hall.title;
                return null;
            },
            templateTitle: function(){
                if(this.isNewEventMainFull)
                    return this.newEventMain.template.title;
                return null;
            },
            workerFullName: function(){
                if(this.isNewEventMainFull)
                    return this.fullNameOfObj(this.newEventMain.worker);
                return null;
            },
        },
        methods: {
            onClickClose: function(e){
                $(".tooltip-active").tooltip("hide");
                // this.$emit('close');
                this.$store.dispatch('new_event/reset');
                this.calendar.getData();
            },
            onClickEdit: function(e){
                $(".tooltip-active").tooltip("hide");
                // this.$emit('edit');
                // this.$store.dispatch('new_event/reset');
                this.navbar.$refs.modal_book.show();
                // console.log(JSON.parse(JSON.stringify(this.calendar.$refs)));
                // console.log(this.navbar.$refs);
            },
            fullName: function(obj){
                return calendarHelper.person.fullName(obj);
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