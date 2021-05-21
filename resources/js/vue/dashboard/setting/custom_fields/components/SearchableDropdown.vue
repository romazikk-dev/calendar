<template>
    <div class="dropdown">
        
        <span class="text-capitalize d-inline pr-2 font-weight-bold">{{fieldName}}:</span>
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{pickedField ? pickedField.title : 'No ' + fieldName + ' selected'}}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a @click.prevent="$emit('changed', field, index)" v-for="(field, index) in fields" class="dropdown-item" href="#">{{field.title}}</a>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            
        },
        props: ['fields', 'pickedField', 'fieldName'],
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
            // changed: function(){
            //     return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            // },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .dropdown-menu{
        max-height: 200px;
        overflow-y: auto;
        max-width: 200px!important;
        padding: 0px;
        a{
            white-space: normal;
            border-bottom: 1px solid #e1e1e1;
            &:last-child{
                border-bottom: none;
            }
        }
    }
</style>

<style lang="scss">

</style>