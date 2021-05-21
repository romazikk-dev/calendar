<template>
    <div class="dropdown-items">
        <ul>
            <li v-for="(field, index) in fields">
                <a @click.prevent="$emit('changed', {title: getTitle(field.title), field: field, index: index})"
                    class="itemm"
                    href="#">
                        {{getTitle(field.title)}}
                </a>
                <dropdown-list v-if="hasFields(field)"
                    @changed="$emit('changed', $event)"
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
        props: ['fields','prefix'],
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