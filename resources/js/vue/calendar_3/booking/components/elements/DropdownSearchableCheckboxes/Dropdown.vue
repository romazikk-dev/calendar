<template>
    <div>
        <div v-if="label">{{label}}:</div>
        <div :class="{
            'dropdown': !dropup,
            'dropup': dropup,
        }" ref="dropdown">
            <input :name="inputName" type="hidden" v-model="inputData">
            <a class="btn dropdown-toggle"
                :class="dropdownToggleClass"
                href="#"
                role="button"
                ref="dropdown_toggle"
                id="dropdownMenuLink"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                    {{
                        pickedItem == null ?
                        pickedItemPlaceholderComputed:
                        pickedItem.val
                    }}
            </a>

            <div @click.prevent.stop
                class="dropdown-menu"
                :style="{
                    '--max-dropdown-menu-height': maxDropdownMenuHeight,
                }"
                aria-labelledby="dropdownMenuLink">
                    <search ref="search"
                        :small="smallSearch === true ? true : false"
                        @search="search = $event" />
                    <div v-if="search" class="small search-info">
                        Found {{countItems}} of {{items.length}}
                    </div>
                    <div class="for-items">
                        <a v-if="emptyable && !search && pickedItem !== null"
                            class="dropdown-item"
                            ref="item_empty"
                            href="#"
                            @click.prevent="change(null)">
                                {{pickedItemPlaceholderComputed}}
                        </a>
                        <a v-for="(item, index) in items"
                            v-if="isSearchApplyable(item.val)"
                            class="dropdown-item"
                            :ref="'item_' + item.key"
                            href="#">
                                <checkbox @change="onChange(item, $event)"
                                    :key="'checkbox_' + item.id"
                                    :checked="item.checked"
                                    :label="item.val" />
                        </a>
                    </div>
            </div>
        </div>
        <div v-if="error && !pickedItem" class="small text-danger">{{error}}</div>
        
    </div>
</template>

<script>
    import DropdownSearch from "./DropdownSearch.vue";
    import Checkbox from "../Checkbox.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.items)));
            // this.checkIfRegionUtc();
            $(this.$refs.dropdown).on('hide.bs.dropdown', () => {
                if(this.cabBeReseted)
                    this.$refs.search.resetClick();
            });
        },
        props: [
            'items','pickedItemPlaceholder','reseter','disabled','inputName','error',
            'maxDropdownMenuHeight','small','label','smallSearch','btnClass','dropup',
            'emptyable'
        ],
        data: function(){
            return {
                pickedItem: null,
                dropdownDisabled: false,
                search: null,
                cabBeReseted: true,
            };
        },
        computed: {
            // checkableItems: function(){
            //     let checkableItems = JSON.parse(JSON.stringify(this.items));
            //     for(let idx in checkableItems){
            //         checkableItems[idx].checked = false;
            //     }
            //     return checkableItems;
            // },
            countItems: function () {
                let count = 0;
                for(let idx in this.items){
                    if(this.isSearchApplyable(this.items[idx].val))
                        count++;
                }
                return count;
            },
            dropdownToggleClass: function () {
                let classes = {
                    'disabled': this.dropdownDisabled || this.disabled,
                    'btn-sm': this.small === true,
                }
                
                if(typeof this.btnClass !== 'undefined' && this.btnClass !== null){
                    classes = Object.assign(classes, this.btnClass);
                }else{
                    classes['btn-secondary'] = true;
                }
                
                return classes;
            },
            isPicked: function () {
                return this.pickedItem !== null;
            },
            inputData: function () {
                return this.pickedItem !== null && typeof this.pickedItem.key !== 'undefined' ? this.pickedItem.key : null;
            },
            pickedItemPlaceholderComputed: function () {
                if(this.disabled)
                    return '---';
                    
                if(this.pickedItemPlaceholder === null || typeof this.pickedItemPlaceholder === 'undefined')
                    return 'Choose item';
                
                return this.capitalizeFirstLetter(this.pickedItemPlaceholder);
            },
        },
        methods: {
            onChange: function(item, checker){
                item.checked = checker === true ? true : false;
                this.$emit('change', item);
                // console.log(JSON.parse(JSON.stringify(item)));
                // console.log(JSON.parse(JSON.stringify(checker)));
            },
            setItemByKey: function(key){
                for(let idx in this.items){
                    if(this.items[idx].key == key){
                        this.change(this.items[idx]);
                        break;
                    }
                }
            },
            isSearchApplyable: function(val){
                if(this.search == null || this.search == '')
                    return true;
                return val.toLowerCase().includes(this.search.toLowerCase());
            },
            capitalizeFirstLetter: function(val){
                return helper.capitalizeFirstLetter(val);
            },
            checkIfRegionUtc: function(){
                if(this.items !== null && Array.isArray(this.items) &&  this.items.length == 1 &&
                typeof this.items[0].key !== 'undefined' && this.items[0].key === 'utc'){
                    // console.log(434343434);
                    this.pickedItem = this.items[0];
                    // this.dropdownDisabled = true;
                }
            },
            reset: function(){
                this.pickedItem = null;
                this.$refs.search.resetClick();
            }
        },
        components: {
            search: DropdownSearch,
            Checkbox,
        },
        watch: {
            reseter: function(val){
                this.pickedItem = null;
            },
            items: function(val){
                this.checkIfRegionUtc();
            },
            countItems: function(val){
                if(typeof this.dropup === 'undefined' || this.dropup === null)
                    return;
                this.cabBeReseted = false;
                this.$refs.dropdown_toggle.click();
                this.$refs.dropdown_toggle.click();
                this.$refs.search.setFocusOnInput();
                this.cabBeReseted = true;
            },
        },
    }
</script>

<style lang="scss" scoped>
    .dropdown-item{
        // padding: 0px;
        // margin: 0px;
        &:active{
            background-color: #e9ecef;
            color: black;
        }
    }
    .search-info{
        padding: 0px 6px;
        margin-top: -14px;
        color: rgba(0,0,0, .6);
    }
    .dropdown-toggle{
        width: 100%;
        text-align: left;
        position: relative;
        &::after{
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
            position: absolute;
            top: 16px;
            right: 14px;
        }
        &.btn-sm{
            &::after {
                top: 12px;
                right: 10px;
            }
        }
    }
    .dropdown-menu{
        width: 100%;
        .for-items{
            max-height: var(--max-dropdown-menu-height, 250px);
            overflow-x: auto;
        }
    }
</style>