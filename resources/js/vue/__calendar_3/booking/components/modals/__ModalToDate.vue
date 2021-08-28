<template>
    <div class="modal fade modal-custom-dark-header-footer modal-more-events"
        @click.prevent
        :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Jump to date</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <loader ref="loader"></loader>   -->
                    dasdas asd a sd
                </div>
                <div class="modal-footer">
                    
                    <!-- <button v-if="showDurationApplyBtn"
                        @click.prevent="$refs.duration.reset()"
                        class="btn btn-sm btn-warning">
                            Reset
                    </button>
                    <button @click.prevent="onClickOkBtn($event)"
                        v-if="showDurationApplyBtn"
                        :disabled="!showDurationApplyBtn"
                        :class="{
                            'disabled': !showDurationApplyBtn
                        }"
                        class="btn btn-sm btn-primary">
                            Apply
                    </button>
                    <button type="button"
                        @click.prevent="hide(true)"
                        class="btn btn-sm btn-secondary">
                            Close
                    </button> -->
                    
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    import Loader from "../Loader.vue";
    export default {
        name: 'modalToDate',
        updated() {
            // $('.tooltip-active').tooltip({
            //     html: true,
            //     trigger: "hover",
            // });
        },
        mounted() {
            // this.show();
            $("#" + this.modalId).on('show.bs.modal', () => {});
            $("#" + this.modalId).on('shown.bs.modal', () => {});
            $("#" + this.modalId).on('hidden.bs.modal', () => {});
            
            
        },
        // props: ['bookDate'],
        data: function(){
            return {
                modalId: 'goToDateModal',
            };
        },
        computed: {
            // date: function () {},
        },
        methods: {
            show: function () {
                // alert('show');
                $('#' + this.modalId).modal('show');
            },
        },
        components: {
            Loader,
        },
        watch: {
            // initValue: (newOne, oldOne) => {},
    	}
    }
</script>

<style scoped>
    
</style>