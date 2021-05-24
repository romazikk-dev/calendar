<template>
    <div>
        
        <div v-for="(field, index) in fields" :class="{'child-specifics': (search !== null && search !== '' ? false : paddingLeft)}">
                {{idsTrace}}
                <div role="alert"
                    class="alert-item alert alert-primary"
                    :class="{
                        'd-none': !showDependingOnSearch(field),
                    }">
                    
                    <div class="btnnns">
                        <div class="btnnn action-drop dropdown show float-right">
                            <a :ref="dropdownRemoveButtonId + '_' + field.id"
                                class="btn btn-sm btn-warning"
                                href="#"
                                role="button"
                                :id="dropdownRemoveButtonId + '_' + field.id"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                    </svg>
                            </a>

                            <div @click.stop class="dropdown-menu dropdown-menu-right" :aria-labelledby="dropdownRemoveButtonId + '_' + field.id">
                                Do you realy want to remove this specific and all under it?
                                <div class="btnns">
                                    <a href="#"
                                        @click.prevent="
                                            $emit('removeBtnClick', getAddEventData(field));
                                            $refs[dropdownRemoveButtonId + '_' + field.id][0].click();
                                        "
                                        class="btnn text-primary">
                                            Yes
                                    </a>
                                    <a href="#"
                                        @click.prevent="$refs[dropdownRemoveButtonId + '_' + field.id][0].click()"
                                        class="btnn text-primary"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                            No
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <a @click.prevent="" href="#" class="btnn btn btn-sm btn-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                            </svg>
                        </a> -->
                        <a @click.prevent="$emit('addBtnClick', getAddEventData(field))"
                            v-if="drewNextLevel"
                            href="#"
                            class="btnnn btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                                </svg>
                        </a>
                        <a @click.prevent="$emit('editBtnClick', getEditEventData(field))"
                            href="#"
                            class="btnnn btn btn-sm btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-pencil-fill">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- <button type="button" class="close">
                        <span aria-hidden="true">×</span>
                    </button> -->
                    <!-- <span class="badge"
                        :class="{
                            'badge-primary': (hasFields(field) && drewNextLevel),
                            'badge-success': !(hasFields(field) && drewNextLevel),
                        }">
                            {{ (hasFields(field) && drewNextLevel) ? 'Category' : 'Item' }}
                    </span><br> -->
                    <b>{{field.id}} {{field.title}}</b><br>
                    <b class="small muted" v-html="composeTitle(field.title)"></b><br>
                    <!-- <template v-if="field.description">
                        {{field.description}}
                    </template>
                    <template v-else>
                        <span class="small text-muted">No description</span>
                    </template> -->
                    <!-- <input name="assign_item[1]" type="checkbox" checked="checked" class="assign-item d-none"> -->
                </div>
                
                <!-- <list :key="appKey(field.key)" :parent-key="appKey(field.key)" v-if="hasFields(field)" :fields="field.fields" /> -->
                <list v-if="hasFields(field) && drewNextLevel"
                    :key="field.id"
                    :deep="deep + 1"
                    @addBtnClick="$emit('addBtnClick', $event)"
                    @editBtnClick="$emit('editBtnClick', $event)"
                    @removeBtnClick="$emit('removeBtnClick', $event)"
                    :ids-trace="getIdsTrace(field)"
                    :search="search"
                    :fields="field.fields"
                    :padding-left="true"
                    :parent-field="field"
                    :parent-add-event-data="getAddEventData(field)"
                    :parent-title="composeTitle(field.title)" />
            
            
        </div>
                
    </div>
</template>

<script>
    export default {
        // name: 'list',
        mounted() {
            console.log('mounted');
            // console.log(this.relation);
            // console.log(this.idsTrace);
            // console.log(this.paddingLeft);
            if(this.idsTrace != null)
                console.log(JSON.parse(JSON.stringify(this.idsTrace)));
            // console.log(this.deep);
        },
        // props: ['fields', 'parentField', 'parentKey'],
        props: ['deep', 'fields', 'paddingLeft', 'parentField', 'parentTitle', 'parentAddEventData', 'search', 'idsTrace'],
        data: function(){
            return {
                modalTitleVal: null,
                dropdownRemoveButtonId: 'dropdownRemoveButton',
                maxDeep: maxDeep,
                // maxDeep: 3,
            };
        },
        computed: {
            drewNextLevel: function(){
                return (this.deep + 1) < this.maxDeep;
            },
            areFieldsEmpty: function(){
                return !Array.isArray(this.fields) || this.fields.length === 0;
            },
            iconArrow: function(){
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                `;
            },
            // appKey: function(paramKey){
            //     let key = '';
            //     if(this.parentKey != null){
            //         key += this.parentKey + '_' + paramKey;
            //     }else{
            //         key = paramKey;
            //     }
            // 
            //     return paramKey;
            //     // if(this.parentField != null){
            //     //     key
            //     // }
            // },
        },
        methods: {
            getIdsTrace: function(field){
                // return this.idsTrace.push(1);
                // console.log(JSON.parse(JSON.stringify(this.idsTrace)));
                // let idsTrace = JSON.parse(JSON.stringify(this.idsTrace));
                // console.log(JSON.parse(JSON.stringify(field)));
                // if()
                if(Array.isArray(this.idsTrace)){
                    // console.log(3333);
                    // console.log(JSON.parse(JSON.stringify(this.idsTrace)));
                    let idsTrace = JSON.parse(JSON.stringify(this.idsTrace));
                    // idsTrace.push(field.id);
                    // console.log(44444);
                    // console.log(JSON.parse(JSON.stringify(idsTrace)));
                    // return idsTrace.push(field.id);
                    idsTrace.push(field.id);
                    return idsTrace;
                }else{
                    // console.log(5555);
                    // console.log(JSON.parse(JSON.stringify([field.id])));
                    return [field.id];
                    // return [2];
                }
            },
            // getInputName: function(field, item, parentPrefix = null){
            //     if(parentPrefix === null){
            //         return 'field[' + field.key + '][' + item + ']';
            //     }
            // },
            showDependingOnSearch: function(field){
                if(this.search === null || this.search === '')
                    return true;
                if((typeof field.title == 'string' && field.title.includes(this.search)) ||
                (typeof field.description == 'string' && field.description.includes(this.search))){
                    return true;
                }else{
                    return false;
                }
            },
            getAddEventData: function(field){
                return {
                    title: field.title,
                    field: field,
                    relation: this.composeTitle(field.title),
                    idsTrace: this.idsTrace,
                }
            },
            getEditEventData: function(field){
                // let parentData = this.parentField !== null ? this.getAddEventData(field) : null;
                let data = this.getAddEventData(field);
                data.parentAddEventData = this.parentAddEventData;
                return data;
                // console.log(JSON.parse(JSON.stringify(data)));
                // console.log();
                // return {
                //     title: field.title,
                //     field: field,
                //     relation: this.composeTitle(field.title)
                // }
            },
            // getRelation: function(fieldTitle){
            //     if(this.relation == null)
            //         return fieldTitle;
            //     return this.relation + `
            //         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
            //             <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            //         </svg>
            //     ` + fieldTitle;
            // },
            // composeTitle(field.title)
            // composeKeyIndexesForInput: function(fieldTitle){
            // 
            // },
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