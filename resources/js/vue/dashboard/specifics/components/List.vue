<template>
    <div>
        
        <div v-for="(field, index) in fields" :class="{'child-specifics': paddingLeft}">
            <div role="alert" class="alert-item alert alert-primary">
                
                <div class="btnns">
                    <a href="#" class="btnn btn btn-sm btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                        </svg>
                    </a>
                    <a href="#" class="btnn btn btn-sm btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                        </svg>
                    </a>
                    <a href="#" class="btnn btn btn-sm btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-pencil-fill">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- <button type="button" class="close">
                    <span aria-hidden="true">×</span>
                </button> -->
                <b v-html="composeTitle(field.title)"></b><br>
                <template v-if="field.description">
                    {{field.description}}
                </template>
                <template>
                    <span class="small text-muted">No description</span>
                </template>
                <input name="assign_item[1]" type="checkbox" checked="checked" class="assign-item d-none">
            </div>
            
            <!-- <list :key="appKey(field.key)" :parent-key="appKey(field.key)" v-if="hasFields(field)" :fields="field.fields" /> -->
            <list v-if="hasFields(field)" :fields="field.fields" :padding-left="true" :parent-field="field" :parent-title="composeTitle(field.title)" />
        </div>
                
    </div>
</template>

<script>
    export default {
        // name: 'list',
        mounted() {
            console.log('mounted');
            console.log(this.paddingLeft);
        },
        // props: ['fields', 'parentField', 'parentKey'],
        props: ['fields', 'paddingLeft', 'parentField', 'parentTitle'],
        data: function(){
            return {
                modalTitleVal: null,
            };
        },
        computed: {
            iconArrow: function(){
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                `;
            },
            appKey: function(paramKey){
                let key = '';
                if(this.parentKey != null){
                    key += this.parentKey + '_' + paramKey;
                }else{
                    key = paramKey;
                }
                
                return paramKey;
                // if(this.parentField != null){
                //     key
                // }
            },
        },
        methods: {
            composeTitle: function(fieldTitle){
                if(this.parentTitle == null)
                    return fieldTitle;
                return this.parentTitle + this.iconArrow + fieldTitle;
            },
            hasFields: function (field) {
                // console.log(fields);
                let hasFields = (typeof field.fields != 'undefined' && Array.isArray(field.fields) && field.fields.length > 0);
                // console.log(hasFields);
                // console.log(hasFields);
                return hasFields;
            },
        },
        components: {
            // list: List
        },
        watch: {
            // modalDs
        },
    // },
    }
</script>

<style lang="scss" scoped>

</style>