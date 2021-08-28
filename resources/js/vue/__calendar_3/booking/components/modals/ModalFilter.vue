<template>
    <div class="modal fade modal-custom-dark-header-footer modal-filters"
        @click.prevent
        :id="modalId">
        <div class="modal-dialog">
    
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                        <b>Filter</b>
                        <div class="btn-group" role="group">
                            <button v-if="isAllFilterBlocksCollapsed != 1"
                                @click="toogleAllFilterBlocks($event, true)"
                                class="btn btn-sm btn-light tooltip-active"
                                data-placement="auto"
                                title="Collapses all filters">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                    </svg>
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                    </svg> -->
                            </button>
                            <button v-if="isAllFilterBlocksCollapsed != -1"
                                @click="toogleAllFilterBlocks($event, false)"
                                class="btn btn-sm btn-light tooltip-active"
                                data-placement="auto"
                                title="Expands all filters">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                    </svg> -->
                            </button>
                            <button v-if="isCheckedAny"
                                @click="onClickRemoveAllFiltersOnlyInModal"
                                class="btn btn-sm btn-light tooltip-active"
                                data-placement="auto"
                                title="Dismisses all filters, only removes filters in modal window, but you will need press apply button in order for filters to take effect">
                                    Dismiss all
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                                    </svg> -->
                            </button>
                        </div>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <edit-independent ref="edit_independent"
                        @change="onEditIndependentChange"
                        @toogle_filter_block="setIsAllFilterBlocksCollapsed"/>
                            
                </div>
                <div class="modal-footer">
                    <button v-if="isFiltersAny && isCheckedAny"
                        @click.prevent="onClickRemoveAllFiltersBtn"
                        class="btn btn-sm btn-warning">
                            Remove all, and close
                    </button>
                    <button v-if="isChangedAny"
                        @click.prevent="onClickApplyBtn"
                        class="btn btn-sm btn-primary">
                            Apply, and close
                    </button>
                    <button @click.prevent="hide"
                        class="btn btn-sm btn-secondary">
                            Close
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    import Loader from "../Loader.vue";
    // import Duration from "../event/Duration.vue";
    import ClientPicker from "../ClientPicker.vue";
    import EditIndependent from "../event/EditIndependent.vue";
    export default {
        name: 'modalFilter',
        updated() {
            $('.tooltip-active').tooltip({
                html: true,
                // trigger: "click",
                trigger: "hover",
            });
        },
        mounted() {
            $("#" + this.modalId).on('show.bs.modal', () => {});
            $("#" + this.modalId).on('shown.bs.modal', () => {});
            $("#" + this.modalId).on('hidden.bs.modal', () => {});
            
            this.setIsAllFilterBlocksCollapsed();
            // console.log(this.$refs);
            // console.log(JSON.parse(JSON.stringify(this.$refs)));
        },
        // props: ['bookDate'],
        data: function(){
            return {
                modalId: 'filterModal',
                isAllFilterBlocksCollapsed: 0,
                
                isCheckedAny: false,
                isChangedAny: false,
                // isChangedAny: false,
            };
        },
        computed: {
            // isCheckedAny: function(){
            //     console.log(JSON.parse(JSON.stringify('isCheckedAny')));
            //     console.log(this.$refs.edit_independent);
            //     // if(this.$refs.edit_independent === 'undefined'){
            //     //     return false;
            //     // }else{
            //     //     return this.$refs.edit_independent.isCheckedAny;
            //     // }
            // },
            // isAllFilterBlocksCollapsed: function (){
            //     console.log(JSON.parse(JSON.stringify('fdfdfd')));
            //     console.log(this.$refs.edit_independent);
            //     if(typeof this.$refs.edit_independent === 'undefined')
            //         return 0;
            //     return this.$refs.edit_independent.isAllFilterBlocksCollapsed();
            //     // console.log(JSON.parse(JSON.stringify('fdfdfd')));
            //     // console.log(JSON.parse(JSON.stringify(this.$refs)));
            //     // return this.$refs.edit_independent.isAllFilterBlocksCollapsed();
            // },
        },
        methods: {
            onEditIndependentChange: function (){
                this.setIsCheckedAny();
                this.setIsCheckedAnyChanged();
            },
            setIsCheckedAnyChanged: function(){
                // console.log(JSON.parse(JSON.stringify('setIsCheckedAnyChanged')));
                this.isChangedAny = this.$refs.edit_independent.isAnyCheckedChanged;
            },
            setIsCheckedAny: function(){
                // console.log(JSON.parse(JSON.stringify('isCheckedAny')));
                // console.log(this.$refs.edit_independent);
                this.isCheckedAny = this.$refs.edit_independent.isCheckedAny;
            },
            onClickRemoveAllFiltersBtn: function (e){
                this.closeTooltipOfEvent(e);
                this.$store.dispatch('filters/removeAllFilters');
                this.hide();
                this.calendar.getData();
                // removeAllFilters
                // alert(11111);
                // this.$refs.edit_independent.removeAllCheckedItems();
            },
            onClickRemoveAllFiltersOnlyInModal: function (e){
                this.closeTooltipOfEvent(e);
                this.$refs.edit_independent.removeAllCheckedItems();
            },
            setIsAllFilterBlocksCollapsed: function (){
                console.log(JSON.parse(JSON.stringify('fdfdfd')));
                console.log(this.$refs.edit_independent.isAllFilterBlocksCollapsed);
                if(typeof this.$refs.edit_independent === 'undefined'){
                    this.isAllFilterBlocksCollapsed = 0;
                }else{
                    this.isAllFilterBlocksCollapsed = this.$refs.edit_independent.isAllFilterBlocksCollapsed;
                }
            },
            toogleAllFilterBlocks: function (e, status) {
                this.closeTooltipOfEvent(e);
                this.$refs.edit_independent.toogleAllFilterBlocks(status);
                this.setIsAllFilterBlocksCollapsed();
            },
            setFilters: function() {
                let filters = {
                    status: this.isCheckedItem('checkedStatus') ?
                        this.getCheckedItem('checkedStatus') : null,
                    hall: this.isCheckedItem('checkedHalls') ?
                        this.getCheckedItem('checkedHalls') : null,
                    worker: this.isCheckedItem('checkedWorkers') ?
                        this.getCheckedItem('checkedWorkers') : null,
                    template: this.isCheckedItem('checkedTemplates') ?
                        this.getCheckedItem('checkedTemplates') : null,
                    client: this.isCheckedItem('checkedClients') ?
                        this.getCheckedItem('checkedClients') : null,
                }
                
                if(typeof this.$refs.edit_independent !== 'undefined' && 
                typeof this.$refs.edit_independent.durationRange !== 'undefined' && 
                this.$refs.edit_independent.durationRange !== null &&
                !this.isDurationRangeFull(this.$refs.edit_independent.durationRange)){
                    filters.duration = this.$refs.edit_independent.durationRange;
                }else{
                    filters.duration = null;
                }
                this.$store.dispatch('filters/setFilters', filters);
            },
            onClickApplyBtn: function(e) {
                this.hide();
                this.setFilters();
                this.calendar.getData();
            },
            getCheckedItem: function (item, asArray = true){
                if(typeof this.$refs.edit_independent === 'undefined')
                    return null;
                    
                if(item == 'checkedStatus')
                    return this.$refs.edit_independent[item];
                    
                if(asArray === true)
                    return Object.values(this.$refs.edit_independent[item]);
                    
                return this.$refs.edit_independent[item];
            },
            getCheckedItemIdsAsArray: function (item){
                if(!this.isCheckedItem(item))
                    return null;
                    
                let checkedItem = this.getCheckedItem(item);
                let checkedItemArr = Object.values(checkedItem);
                let checkedItemIds = [];
                for(let idx in checkedItemArr)
                    checkedItemIds.push(checkedItemArr[idx].id);
                    
                return checkedItemIds;
            },
            isCheckedItem: function (item){
                if(typeof this.$refs.edit_independent === 'undefined')
                    return false;
                    
                if(item == 'checkedStatus' && this.isProp(this.$refs.edit_independent[item])){
                    let checkedItem = this.$refs.edit_independent[item];
                    return Array.isArray(checkedItem) && checkedItem.length > 0;
                }
                
                return typeof this.$refs.edit_independent[item] !== 'undefined' &&
                Object.keys(this.$refs.edit_independent[item]).length > 0;
            },
            show: function (){
                // console.log(JSON.parse(JSON.stringify(e)));
                // this.setFilters();
                this.$refs.edit_independent.setCheckedItems();
                $('#' + this.modalId).modal('show');
            },
            hide: function (){
                $('#' + this.modalId).modal('hide');
            },
        },
        components: {
            Loader,
            ClientPicker,
            EditIndependent,
            // Duration,
        },
        watch: {
            // initValue: (newOne, oldOne) => {},
    	}
    }
</script>

<style lang="scss" scoped>
    .modal-body{
        max-height: 340px;
        overflow-y: auto;
    }
</style>