<template>
    <div class="modal fade modal-custom-dark-header-footer" :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{capitalizeFirstLetter(getText('text.book_an_event'))}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <loader ref="loader"></loader>             -->
                    <div class="alert alert-warning" role="alert">
                        <template v-if="!showPickTimeBtn">
                            {{getText('text.pick_all_items_and_then_pick_a_time_for_event')}}!
                        </template>
                        <template v-else>
                            {{getText('text.pick_a_time_for_event')}}!
                        </template>
                    </div>
                    <client-picker @change="onChange"
                        :label="capitalizeFirstLetter(getText('text.client'))"
                        ref="client"
                        :small="true"
                        picked-item-placeholder="---"
                        max-dropdown-menu-height="100px" />
                    <edit @change="onChange"
                        ref="edit" />
                </div>
                <div class="modal-footer">
                    <button @click.prevent="pickTime"
                        :disabled="!showPickTimeBtn"
                        type="button"
                        class="btn btn-sm btn-success">
                            {{getText('text.pick_time')}}!
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                        {{capitalizeFirstLetter(getText('text.cancel'))}}
                    </button>
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
    import Edit from "../event/Edit.vue";
    import ClientPicker from "../ClientPicker.vue";
    export default {
        name: 'modalBook',
        mounted() {
            $("#" + this.modalId).on('show.bs.modal', () => {
                // this.setDuration();
                console.log(JSON.parse(JSON.stringify('newEventClient')));
                console.log(JSON.parse(JSON.stringify(this.newEventClient)));
                if(this.isNewEventMainFull && this.newEventClient !== null && typeof this.$refs.edit !== 'undefined'){
                    this.$refs.client.setClient(this.newEventClient);
                    this.$refs.edit.fillFields({
                        hall_id: this.newEventMain.hall.id,
                        worker_id: this.newEventMain.worker.id,
                        template_id: this.newEventMain.template.id,
                    });
                }
            });
            
            $("#" + this.modalId).on('shown.bs.modal', () => {
                // this.$refs['loader'].fadeOut(300);
            });
            
            $("#" + this.modalId).on('hidden.bs.modal', () => {
                this.$refs.edit.reset(false);
                this.$refs.client.reset();
                $('#' + this.modalId).removeAttr('style');
            });
            
            $('#' + this.modalId).draggable({
                handle: ".modal-header, .modal-footer"
            });
        },
        // props: ['bookDate'],
        data: function(){
            return {
                modalId: 'bookModal',
                showPickTimeBtn: false,
            };
        },
        computed: {
            isEditComp: function () {
                return typeof this.$refs.edit !== 'undefined' && this.$refs.edit !== null;
            },
        },
        methods: {
            pickTime: function () {
                if(!this.showPickTimeBtn || !this.isEditComp)
                    return;
                    
                // this.$refs.edit.pickedHall.id
                    
                // let urlSearchParams = new URLSearchParams();
                // urlSearchParams.append("hall", this.$refs.edit.pickedHall.id);
                // urlSearchParams.append("worker", this.$refs.edit.pickedWorker.id);
                // urlSearchParams.append("template", this.$refs.edit.pickedTemplate.id);
                
                this.$store.dispatch('new_event/setMain', {
                    hall: this.$refs.edit.pickedHall,
                    worker: this.$refs.edit.pickedWorker,
                    template: this.$refs.edit.pickedTemplate,
                });
                this.$store.dispatch('new_event/setClient', this.$refs.client.pickedClient);
                this.$store.dispatch('new_event/setShow', true);
                
                this.calendar.getData().then(() => {
                    this.hide();
                });
            },
            onChange: function () {
                // console.log(JSON.parse(JSON.stringify('something')));
                this.setShowPickTimeBtn();
            },
            show: function () {
                $('#' + this.modalId).modal('show');
            },
            hide: function () {
                $('#' + this.modalId).modal('hide');
            },
            setShowPickTimeBtn: function () {
                if(typeof this.$refs.edit === 'undefined' || !this.$refs.edit.isAllItemsPicked ||
                typeof this.$refs.client === 'undefined' || !this.$refs.client.isPicked){
                    this.showPickTimeBtn = false;
                }else{
                    this.showPickTimeBtn = true;
                }
            },
        },
        components: {
            Loader,
            Edit,
            ClientPicker,
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
    
</style>