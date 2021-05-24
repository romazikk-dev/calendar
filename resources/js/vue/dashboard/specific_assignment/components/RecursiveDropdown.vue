<template>
    <div>
        
        <div v-if="fieldsNotEmpty" class="specific-dropdown dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{
                    pickedItem ?
                    pickedItem.title :
                    'Choose specific ...'
                }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a v-for="(field, index) in fields"
                    @click.prevent="itemClick(field, index)"
                    class="dropdown-item" href="#">
                        {{index}} {{field.title}}
                </a>
                <!-- <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a> -->
            </div>
        </div>
        
        <dropdown @specificChange="$emit('specificChange', $event)"
            v-if="fieldsToDrawInnerDropdown"
            :fields="fieldsToDrawInnerDropdown.fields" />
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.specifics)));
            // this.
        },
        props: ['fields'],
        data: function(){
            return {
                pickedItem: null,
                fieldsToDrawInnerDropdown: null,
            };
        },
        computed: {
            fieldsNotEmpty: function () {
                return typeof this.fields !== 'undefined' && this.fields !== null &&
                (
                    !Array.isArray(this.fields) ||
                    Array.isArray(this.fields) && this.fields.length > 0
                );
            },
        },
        methods: {
            fieldHasFields: function (field) {
                console.log(JSON.parse(JSON.stringify(333333333)));
                console.log(JSON.parse(JSON.stringify(field)));
                return (
                    typeof field != 'undefined' && field != null &&
                    typeof field.fields != 'undefined' && field.fields != null
                    &&
                    (
                        !Array.isArray(field.fields) ||
                        (Array.isArray(field.fields) && field.fields.length > 0)
                    )
                );
            },
            itemClick: function(field, index){
                this.pickedItem = field;
                this.fieldsToDrawInnerDropdown = this.fieldHasFields(field) ? field : null;
                // this.$emit('specificChange', this.fieldsToDrawInnerDropdown);
                console.log(JSON.parse(JSON.stringify(this.fieldsToDrawInnerDropdown)));
                console.log('itemClick');
                this.$emit('specificChange', this.pickedItem);
            },
        },
        components: {
            
        },
        watch: {
            fields: function (field) {
                console.log('watch fields');
                this.fieldsToDrawInnerDropdown = null;
                this.pickedItem = null;
            },
        },
    }
</script>

<style lang="scss" scoped>
    
</style>