<template>
    <div class="dropdown">
        
        <span class="d-inline pr-2 font-weight-bold">
            Parent specific:
        </span>
        <button :disabled="isFieldsEmpty"
            class="btn btn-secondary dropdown-toggle"
            :class="{'dropdown-toggle': !isFieldsEmpty}"
            type="button"
            id="specificDropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
                {{
                    isFieldsEmpty ?
                    'No specific exists yet':
                    pickedField != null ? makeFirstLetterUppercase(pickedField.title) : 'Main'
                }}
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
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            // $(document).find('.tooltip-init').tooltip();
            // console.log(JSON.parse(JSON.stringify(222222)));
        },
        props: ['fields', 'pickedField', 'fieldName'],
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