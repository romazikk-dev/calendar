<template>
    <div>
        
        <form action="" method="post">
            <span v-html="csrfInput"></span>
            <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
            
            <dropdown />
            <tab-pane />
            
        </form>
        
    </div>
</template>

<script>
    import SearchableDropdown from "./SearchableDropdown.vue";
    import TabPane from "./TabPane.vue";
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
            dropdown: SearchableDropdown,
            tabPane: TabPane
        },
    }
</script>

<style lang="scss" scoped>
    
</style>

<style lang="scss">

</style>