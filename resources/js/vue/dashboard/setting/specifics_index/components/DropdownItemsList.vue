<template>
    <div class="dropdown-items">
        <ul>
            <li v-for="(field, index) in fields">
                <a @click.prevent="$emit('changed', {title: getTitle(field.title), field: field, index: index, relation: getRelation(field.title), idsTrace: idsTrace})"
                    class="itemm disabled"
                    href="#">
                        {{getTitle(field.title)}}
                </a>
                <dropdown-list v-if="hasFields(field)"
                    :ids-trace="getIdsTrace(field)"
                    @changed="$emit('changed', $event)"
                    :relation="getRelation(field.title)"
                    :fields="field.fields"
                    :prefix="pref"/>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            // $(document).find('.tooltip-init').tooltip();
            // console.log(JSON.parse(JSON.stringify(222222)));
            // console.log(this.prefix);
        },
        props: ['fields','prefix','relation','idsTrace'],
        data: function(){
            return {
                // csrfInput: csrfInput,
            };
        },
        computed: {
            pref: function(){
                if(this.prefix == null)
                    return '-';
                return this.prefix + '-';
            },
            // isFieldsEmpty: function () {
            //     return typeof this.fields == 'undefined' || this.fields == null ||
            //         !Array.isArray(this.fields) || this.fields.length == 0;
            // }
        },
        methods: {
            getIdsTrace: function(field){
                if(Array.isArray(this.idsTrace)){
                    // console.log(JSON.parse(JSON.stringify(this.idsTrace)));
                    let idsTrace = JSON.parse(JSON.stringify(this.idsTrace));
                    idsTrace.push(field.id);
                    return idsTrace;
                }else{
                    return [field.id];
                }
            },
            getRelation: function(fieldTitle){
                if(this.relation == null)
                    return fieldTitle;
                return this.relation + `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                ` + fieldTitle;
            },
            getTitle: function(title){
                return this.prefix + ' ' + this.makeFirstLetterUppercase(title);
            },
            makeFirstLetterUppercase: function(str){
                return helper.capitalizeFirstLetter(str);
            },
            hasFields: function (field) {
                let hasFields = (typeof field.fields != 'undefined' && Array.isArray(field.fields) && field.fields.length > 0);
                return hasFields;
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