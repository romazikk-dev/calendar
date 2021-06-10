<template>
    <div>
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" :id="modalId" tabindex="-1" role="dialog" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center">
                            {{text}}
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    // import MonthCell from "./MonthCell.vue";
    export default {
        name: 'modalAlert',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('modalAuthContent')));
        },
        // props: ['userId'],
        data: function(){
            return {
                modalId: 'alertModal',
                text: null,
                // dateRange: helper.range.range,
            };
        },
        computed: {
            // userId: function(){
            //     
            // },
        },
        methods: {
            show: function(text){
                this.text = text;
                $('#' + this.modalId).modal('show');
            },
        },
        components: {
            
        },
    }
</script>

<style scoped>
    
</style>