<template>
    <div>
        
        <div class="dropdown" ref="dropdown">
            <input :name="inputName" type="hidden" v-model="inputData">
            <a class="btn btn-secondary dropdown-toggle"
                :class="{
                    'disabled': dropdownDisabled || disabled
                }"
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
                    <search ref="search" @search="search = $event" />
                    <div class="for-items">
                        <a v-for="(item, index) in items"
                            v-if="isSearchApplyable(item.val)"
                            class="dropdown-item"
                            :ref="'item_' + item.key"
                            href="#"
                            @click.prevent="change(item)">
                                {{item.val}}
                        </a>
                    </div>
            </div>
        </div>
        <div v-if="error && !pickedItem" class="small text-danger">{{error}}</div>
        
    </div>
</template>

<script>
    import DropdownSearch from "./DropdownSearch.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.items)));
            // this.checkIfRegionUtc();
            $(this.$refs.dropdown).on('hide.bs.dropdown', () => {
                this.$refs.search.resetClick();
            });
        },
        props: ['items','pickedItemPlaceholder','reseter','disabled','inputName','error','maxDropdownMenuHeight'],
        data: function(){
            return {
                pickedItem: null,
                dropdownDisabled: false,
                search: null,
            };
        },
        computed: {
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
            change: function(item){
                // alert(11);
                
                // console.log(JSON.parse(JSON.stringify(777777)));
                // console.log(JSON.parse(JSON.stringify(item)));
                
                this.pickedItem = JSON.parse(JSON.stringify(item));
                // this.pickedItem = item;
                // this.$refs.search.resetClick();
                this.$emit('change', item);
                if($(this.$refs.dropdown).hasClass('show'))
                    this.$refs.dropdown_toggle.click();
                this.$refs.search.resetClick();
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
        },
        watch: {
            reseter: function(val){
                this.pickedItem = null;
            },
            items: function(val){
                this.checkIfRegionUtc();
            },
        },
    }
</script>

<style lang="scss" scoped>
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
    }
    .dropdown-menu{
        width: 100%;
        .for-items{
            max-height: var(--max-dropdown-menu-height, 250px);
            overflow-x: auto;
        }
    }
</style>