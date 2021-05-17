<template>
    <div class="dropdown">
        
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Select title
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
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
                csrfInput: csrfInput,
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