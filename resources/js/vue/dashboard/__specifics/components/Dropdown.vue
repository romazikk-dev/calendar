<template>
    <div class="specific-parent-dropdown-picker dropdown">
        
        <div class="pr-2 font-weight-bold">
            Parent specific:
        </div>
        <button :disabled="isFieldsEmpty || editedField"
            class="btn btn-secondary dropdown-toggle"
            :class="{'dropdown-toggle': !isFieldsEmpty}"
            type="button"
            id="specificDropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
                <span v-if="isFieldsEmpty">No specific exists yet</span>
                <span v-else>
                    <span v-html="makeFirstLetterUppercase(pickedField.relation)" v-if="pickedField != null"></span>
                    <span v-else>Main</span>
                </span>
                <!-- {{
                    isFieldsEmpty ?
                    'No specific exists yet':
                    pickedField != null ? makeFirstLetterUppercase(pickedField.relation) : 'Main'
                }} -->
        </button>
        <div class="dropdown-menu specifics-dropdown-menu" aria-labelledby="specificDropdownMenuButton">
            <ul>
                <li class="main-specific">
                    <a @click.prevent="$emit('changed', null)" class="itemm" href="#">Main</a>
                </li>
            </ul>
            <dropdown-list @changed="$emit('changed', $event)" :fields="fields" prefix="-" />
            <!-- <ul>
                <li v-for="(field, index) in fields">
                    <a @click.prevent="$emit('changed', field, index)" class="itemm" href="#">{{makeFirstLetterUppercase(field.title)}}</a>
                </li>
            </ul> -->
        </div>
        
    </div>
</template>

<script>
    // import DropdownItemsList from "./DropdownItemsList.vue";
    export default {
        mounted() {
            // console.log(111111111);
            // console.log(JSON.parse(JSON.stringify(this.editedField)));
            // $(document).find('.tooltip-init').tooltip();
            // console.log(JSON.parse(JSON.stringify(222222)));
        },
        props: ['fields', 'pickedField', 'fieldName', 'editedField'],
        data: function(){
            return {
                // csrfInput: csrfInput,
            };
        },
        computed: {
            isFieldsEmpty: function () {
                return typeof this.fields == 'undefined' || this.fields == null ||
                    !Array.isArray(this.fields) || this.fields.length == 0;
            }
            // isShowEmptyPlaceholder: function () {
            //     return typeof this.showEmptyPlaceholder != 'undefined' && this.showEmptyPlaceholder === 'true';
            // }
        },
        methods: {
            // dropdownChanged: function(event){
            //     console.log(event);
            // },
            makeFirstLetterUppercase: function(str){
                return helper.capitalizeFirstLetter(str);
            },
        },
        components: {
            // dropdownList: DropdownItemsList
        },
        watch: {
            // dropdownChanged: function(event){
            //     console.log(event);
            // },
            editedField: function(val){
                console.log(111111111);
                console.log(JSON.parse(JSON.stringify(val)));
            },
        },
    }
</script>

<style lang="scss" scoped>
    // .dropdown-menu{
    //     max-height: 200px;
    //     overflow-y: auto;
    //     max-width: 200px!important;
    //     padding: 0px;
    //     ul{}
    //     a{
    //         white-space: normal;
    //         border-bottom: 1px solid #e1e1e1;
    //         &:last-child{
    //             border-bottom: none;
    //         }
    //     }
    // }
</style>

<style lang="scss">

</style>