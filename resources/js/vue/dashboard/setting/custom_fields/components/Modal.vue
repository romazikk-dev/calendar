<template>
    <div>
        
        <div class="modal fade modal-custom-dark-header-footer" :id="modalId" tabindex="-1" role="dialog" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">{{modalTitle}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{modalText}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click.prevent="$emit('confirmed')" type="button" class="btn btn-success">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            // console.log(JSON.parse(JSON.stringify(this.fields)));
        },
        props: ['modalTitle', 'modalText'],
        data: function(){
            return {
                // csrfInput: csrfInput,
                modalId: 'customFieldsModal'
            };
        },
        computed: {
            // modalTitle: function(){
            // 
            // },
        },
        methods: {
            open: function(){
                $('#' + this.modalId).modal('show');
                // console.log(JSON.parse(JSON.stringify(this.pickedField)));
            },
            close: function(){
                $('#' + this.modalId).modal('hide');
                // console.log(JSON.parse(JSON.stringify(this.pickedField)));
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    
</style>

<style lang="scss">

</style>