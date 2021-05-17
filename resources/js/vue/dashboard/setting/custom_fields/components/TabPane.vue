<template>
    <div>
        <ul class="nav nav-tabs edit-create-nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="en-tab" data-toggle="tab" href="#en" tab-name="en" role="tab" aria-controls="en" aria-selected="true">
                    En
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="de-tab" data-toggle="tab" href="#de" tab-name="de" role="tab" aria-controls="de" aria-selected="true">
                    De
                </a>
            </li>
        </ul>
        
        <div class="edit-create-tab-content tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="en-tab">
                
                English
                
            </div>
            <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                
                German
                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            
        },
        // props: ['showEmptyPlaceholder'],
        data: function(){
            return {
                // csrfInput: csrfInput,
            };
        },
        computed: {
            // isShowEmptyPlaceholder: function () {
            //     return typeof this.showEmptyPlaceholder != 'undefined' && this.showEmptyPlaceholder === 'true';
            // }
        },
        methods: {
            // isJqueryValidationEnabled: function(){
            //     return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            // },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    
</style>

<style lang="scss">

</style>