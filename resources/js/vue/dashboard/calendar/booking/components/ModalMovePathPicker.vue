<template>
    <div>
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer"
            :id="modalId"
            tabindex="-1"
            role="dialog"
            :aria-labelledby="modalId + 'Label'"
            aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" :id="modalId + 'Label'">
                                {{capitalizeFirstLetter(getText('text.edit_event'))}}
                            </h5>
                            <button type="button"
                                @click.prevent="close()"
                                class="close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div ref="modal_body" class="modal-body">
                            <loader ref="loader" />
                            
                            <div v-if="movingEventClient" class="alert alert-primary client-info small" role="alert">
                                <!-- <div>
                                    Client: <b>{{fullNameOfObj(movingEventClient)}}</b>
                                    <template v-if="movingEventClient.email">
                                        <b> - {{movingEventClient.email}}</b>
                                    </template>
                                </div> -->
                                <div>
                                    Client: <b>{{clientName}}</b>
                                    <!-- <template v-if="movingEventClient.email">
                                        <b> - {{movingEventClient.email}}</b>
                                    </template> -->
                                </div>
                                <template v-if="!isPickedItemsChanged">
                                    <div>Date: <b>{{movingEventDate}}</b></div>
                                    <div>Time: <b>{{movingEvent.from}}</b></div>
                                </template>
                                <b v-else class="badge badge-warning text-left">
                                    <template v-if="isAllItemsPicked">
                                        Please choose a time
                                    </template>
                                    <template v-else>
                                        Please choose all fields and pick a time
                                    </template>
                                </b>
                            </div>
                            
                            <edit @change="onChange" ref="edit" />
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button"
                                @click.prevent="close()"
                                class="btn btn-sm btn-secondary">
                                    Close
                            </button> -->
                            <button :disabled="!isAllItemsPicked"
                                @click.prevent="pickTime()"
                                type="button"
                                class="btn btn-sm btn-primary">
                                    {{capitalizeFirstLetter(getText('text.pick_time'))}}
                            </button>
                            <a v-if="isPickedItemsChanged"
                                @click.prevent="reset()"
                                href="#"
                                class="btn btn-sm btn-warning btn-reset">
                                    {{capitalizeFirstLetter(getText('text.reset'))}}
                            </a>
                        </div>
                    </div>
                </div>
        </div>
        
    </div>
</template>

<script>
    import ExtensiveTemplateFilterPicker from "./template/ExtensiveTemplateFilterPicker.vue";
    import Loader from "./Loader.vue";
    import Edit from "./event/Edit.vue";
    export default {
        name: 'ModalMovePathPicker',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
            this.$nextTick(() => {
                this.setIsAllItemsPicked();
            });
            
            $('#' + this.modalId).draggable({
                handle: ".modal-header, .modal-footer"
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                $('#' + this.modalId).removeAttr('style');
            });
        },
        // props: [],
        data: function(){
            return {
                modalId: 'pathPickerModal',
                
                isPickedItemsChanged: false,
                isAllItemsPicked: false,
                
                e: null,
            };
        },
        computed: {
            clientName: function(){
                // console.log(this.e);
                // console.log(JSON.parse(JSON.stringify(this.e)));
                return this.isProp(this.e) ? this.fullNameOfObj(this.e.client_without_user_scope) : '';
            },
            isShown: function(){
                return $('#' + this.modalId).hasClass('in');
            },
            // fillingData: function(){
            //     if(this.isProp(this.e)){
            //         return {
            //             hall_id: this.e.hall_without_user_scope.id,
            //             template_id: this.e.template_without_user_scope.id,
            //             worker_id: this.e.worker_without_user_scope.id,
            //         }
            //     }else if(this.movingEventIsPickedFull){
            //         return {
            //             hall_id: this.movingEventPicked.hall.id,
            //             template_id: this.movingEventPicked.template.id,
            //             worker_id: this.movingEventPicked.worker.id,
            //         }
            //     }else{
            //         return {
            //             hall_id: this.movingEvent.hall_id,
            //             template_id: this.movingEvent.template_id,
            //             worker_id: this.movingEvent.worker_id,
            //         }
            //     }
            // },
        },
        methods: {
            onChange: function(){
                this.setIsPickedItemsChanged();
                this.setIsAllItemsPicked();
            },
            setIsPickedItemsChanged: function(){
                if(typeof this.$refs.edit === 'undefined'){
                    this.isPickedItemsChanged = false;
                }else{
                    this.isPickedItemsChanged = this.$refs.edit.isPickedItemsChanged;
                }
            },
            setIsAllItemsPicked: function(){
                // console.log(JSON.parse(JSON.stringify('setIsAllItemsPicked')));
                if(typeof this.$refs.edit === 'undefined'){
                    this.isAllItemsPicked = false;
                }else{
                    this.isAllItemsPicked = this.$refs.edit.isAllItemsPicked;
                }
                // console.log(JSON.parse(JSON.stringify(this.isAllItemsPicked)));
            },
            close: function(){
                // this.$store.commit('moving_event/reset');
                this.hide();
            },
            // Show free slots to pick time for moving_event
            pickTime: function(){
                if(this.isAllItemsPicked){
                    this.app.setMovingEvent(this.e).then((data) => {
                        // console.log(this.$refs.edit.pickedHall);
                        // console.log(JSON.parse(JSON.stringify(this.$refs.edit.pickedHall)));
                        // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
                        this.$store.dispatch('moving_event/setPicked', {
                            hall: this.$refs.edit.pickedHall,
                            worker: this.$refs.edit.pickedWorker,
                            template: this.$refs.edit.pickedTemplate,
                        });
                        
                        console.log(JSON.parse(JSON.stringify(this.movingEventPicked)));
                        
                        this.calendar.getData({
                            type: 'free',
                            exclude_ids: [this.movingEvent.id],
                            // test: true
                        }).then(() => {
                            this.hide();
                        });
                    });
                    // this.$store.dispatch('moving_event/setPicked', {
                    //     hall: this.$refs.edit.pickedHall,
                    //     worker: this.$refs.edit.pickedWorker,
                    //     template: this.$refs.edit.pickedTemplate,
                    // });
                    // 
                    // this.calendar.getData({
                    //     type: 'free',
                    //     exclude_ids: [this.movingEvent.id],
                    //     // test: true
                    // }).then(() => {
                    //     this.hide();
                    // });
                }
            },
            reset: function(){
                this.showLoader();
                // this.$store.dispatch('moving_event/resetPicked');
                this.fillFields().then(() => {
                    this.hideLoader();
                });
            },
            // setPickedTemplateIdsTrace: function(){
            //     // if(this.movingEventTemplate === null)
            //     if(this.pickedTemplate === null)
            //         return;
            // 
            //     let template = JSON.parse(JSON.stringify(this.pickedTemplate));
            // 
            //     if(typeof template.specific === 'undefined' || template.specific === null)
            //         return;
            // 
            //     if(typeof template.specific.ids_trace === 'undefined' || template.specific.ids_trace === null)
            //         return [template.specific.id];
            // 
            //     let idsTraceString = JSON.parse(JSON.stringify(template.specific.ids_trace));
            //     let idsTrace = idsTraceString.split(',').map((val) => parseInt(val));
            //     idsTrace.push(template.specific.id);
            //     idsTrace.push(template.id);
            // 
            //     this.pickedTemplateIdsTrace = idsTrace;
            //     // console.log(JSON.parse(JSON.stringify('setPickedTemplateIdsTrace')));
            // },
            show: function (e){
                if(!this.isProp(e))
                    return;
                
                this.e = e;
                // if(this.isProp(e)){
                //     this.e = e;
                // }else{
                //     this.e = null;
                //     if(!this.isMovingEvent || typeof this.$refs.edit === 'undefined')
                //         return;
                // }
                
                this.showLoader();
                $('#' + this.modalId).modal('show');
                // this.fillFields(true).then(() => {
                //     this.hideLoader(200);
                // });
                this.fillFields().then(() => {
                    this.hideLoader(200);
                });
            },
            // fillFields: function (useFillingData = false){
            //     return new Promise((resolve, reject) => {
            //         if(this.isProp(this.e))
            //             resolve();
            //         if(!this.isMovingEvent)
            //             reject('Moving event not setted');
            //         if(typeof this.$refs.edit === 'undefined')
            //             reject('`edit` component is undefined');
            //         resolve();
            //     }).then(() => {
            //         if(useFillingData)
            //             return this.$refs.edit.fillFields(this.fillingData);
            //         return this.$refs.edit.fillFields();
            //     });
            // },
            fillFields: function (){
                return this.$refs.edit.fillFields({
                    hall_id: this.e.hall_without_user_scope.id,
                    template_id: this.e.template_without_user_scope.id,
                    worker_id: this.e.worker_without_user_scope.id,
                });
                // return new Promise((resolve, reject) => {
                //     if(this.isProp(this.e))
                //         resolve();
                //     if(!this.isMovingEvent)
                //         reject('Moving event not setted');
                //     if(typeof this.$refs.edit === 'undefined')
                //         reject('`edit` component is undefined');
                //     resolve();
                // }).then(() => {
                //     if(useFillingData)
                //         return this.$refs.edit.fillFields(this.fillingData);
                //     return this.$refs.edit.fillFields();
                // });
            },
            hide: function (){
                $('#' + this.modalId).modal('hide');
            },
            showLoader: function(){
                $(this.$refs.modal_body).css({'max-weight':'100px'});
                // $(this.$refs.modal_body).find('.card-body').css({'position': 'absolute', 'display': 'none'});
                this.$refs.loader.show();
            },
            hideLoader: function(milliseconds = 0){
                let _this = this;
                
                if(milliseconds > 0){
                    setTimeout(function(){
                     	hideLoader();
                    }, milliseconds);
                }else{
                    hideLoader();
                }
                
                function hideLoader(){
                    $(_this.$refs.modal_body).css({'max-weight':'auto'});
                    // $(_this.$refs.modal_body).find('.card-body').css({'position':'relative', 'display': 'block'});
                    _this.$refs.loader.fadeOut(300);
                }
            },
        },
        components: {
            TemplatePicker: ExtensiveTemplateFilterPicker,
            Loader,
            Edit
        },
        watch: {
            // pickedHall: function(val){
            //     if(val === null){
            //         this.templates = null;
            //         this.workers = null;
            //     }
            // },
            // pickedTemplate: function(val){
            //     if(val === null){
            //         this.workers = null;
            //     }
            // },
        }
    }
</script>

<style lang="scss" scoped>
    
</style>