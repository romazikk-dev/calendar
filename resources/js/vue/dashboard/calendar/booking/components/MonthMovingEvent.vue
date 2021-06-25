<template>
    <div>
        
        <div class="alert alert-info client-info" role="alert">
            <div class="titt">
                <!-- <span class="badge badge-info">Moving event:</span> -->
                <b>Moving event</b>
            </div>
            <div class="btn-group actts" role="group" aria-label="Basic example">
               <button @click="$emit('edit')" type="button" class="btn btn-sm btn-primary">Edit</button>
               <button @click="$emit('close')" type="button" class="btn btn-sm btn-primary">Close</button>
            </div>
            <div class="clearfix"></div>
            <hr />
            <div class="row">
                <div class="coll col col-sm-6">
                    
                    <div class="d-table info-list">
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Client:
                            </div>
                            <div class="d-table-cell">
                                <b>{{fullName(client)}}</b>
                            </div>
                        </div>
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Email:
                            </div>
                            <div class="d-table-cell">
                                <b>{{client.email ? client.email : ''}}</b>
                            </div>
                        </div>
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Booked on:
                            </div>
                            <div class="d-table-cell">
                                <b>{{bookingTime}}</b>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="coll col col-sm-6">
                    
                    <div class="d-table info-list">
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Hall:
                            </div>
                            <div class="d-table-cell">
                                <b>{{hallTitle}}</b>
                            </div>
                        </div>
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Template:
                            </div>
                            <div class="d-table-cell">
                                <b>{{templateTitle}}</b>
                            </div>
                        </div>
                        <div class="d-table-row">
                            <div class="d-table-cell">
                                Worker:
                            </div>
                            <div class="d-table-cell">
                                <b>{{workerFullName}}</b>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
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
                componentApp: this.getParentComponentByName(this, 'app'),
            };
        },
        computed: {
            bookingTime: function(){
                if(this.event === null || typeof this.event.time === 'undefined' || this.event.time === null)
                    return null;
                
                return moment(this.event.time).format('YYYY-MM-DD ddd HH:mm');
                // let bookingTimeMoment = moment(this.event.time);
                // return this.event.hall_without_user_scope.title;
            },
            hallTitle: function(){
                if(this.picked !== null && typeof this.picked.hall !== 'undefined' && this.picked.hall !== null)
                    return this.picked.hall.title;
                if(this.event === null || typeof this.event.hall_without_user_scope === 'undefined')
                    return null;
                return this.event.hall_without_user_scope.title;
            },
            workerFullName: function(){
                if(this.picked !== null && typeof this.picked.worker !== 'undefined' && this.picked.worker !== null)
                    return this.fullName(this.picked.worker);
                if(this.event === null || typeof this.event.worker_without_user_scope === 'undefined')
                    return null;
                return this.fullName(this.event.worker_without_user_scope);
            },
            templateTitle: function(){
                if(this.picked !== null && typeof this.picked.template !== 'undefined' && this.picked.template !== null)
                    return this.picked.template.title;
                if(this.event === null || typeof this.event.template_without_user_scope === 'undefined')
                    return null;
                return this.event.template_without_user_scope.title;
            },
            clientName: function(){
                if(this.event === null)
                    return null;
                return this.fullName(this.event);
            },
            clientEmail: function(){
                return this.client === null ||
                typeof client.email === 'undefined' || client.email === null ?
                    '---' : client.email;
            },
            picked: function(){
                return this.$store.getters['moving_event/picked'];
            },
            event: function(){
                return this.$store.getters['moving_event/event'];
            },
            client: function(){
                return this.$store.getters['moving_event/client'];
            },
        },
        methods: {
            fullName: function(obj){
                return calendarHelper.person.fullName(obj);
            },
            // close: function(){
            //     this.$store.commit('moving_event/reset');
            // },
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
    .alert{
        position: relative;
        padding: 6px;
        .titt{
            float: left;
            position: relative;
            top: 4px;
        }
        .actts{
            float: right;
        }
        hr{
            margin-top: 8px;
            margin-bottom: 8px;
        }
        .info-list{
            // background-color: red;
            margin: auto;
            .d-table-cell{
                &:first-child{
                    // padding-right: 10px;
                    text-align: right;
                }
                &:last-child{
                    padding-left: 10px;
                }
            }
        }
        .coll{
            
        }
    }
</style>