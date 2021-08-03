<template>
    <div>
        
        <div class="alert alert-info navbarr">
            <!-- <div class="disabled-cover text-muted small">Not available, as you currently are editing an event</div> -->
            <div class="btn-group" role="group" aria-label="Basic example">
                <!-- <button type="button" class="btn btn-sm btn-info">All</button> -->
                <button type="button"
                    @click="onClickBook"
                    class="btn btn-sm btn-approved">Book</button>
            </div>
            
            <div class="btn-group float-right" role="group" aria-label="Basic example">
                <button type="button"
                    @click="onClickFilter"
                    class="btn btn-sm btn-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                        </svg>
                </button>
                <button type="button"
                    v-if="!isFiltersEmpty"
                    @click="showAppliedFilters = !showAppliedFilters"
                    class="btn btn-sm btn-secondary">
                        {{countAppliedFilters}} filter(s)
                        <svg xmlns="http://www.w3.org/2000/svg"
                        v-if="showAppliedFilters"
                        width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg"
                        v-if="!showAppliedFilters"
                        width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                </button>
                <button type="button"
                    v-if="!isFiltersEmpty"
                    @click="onClickRemoveAllFilters"
                    class="btn btn-sm btn-danger">
                        <svg data-v-7f3cfdd9="" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                            <path data-v-7f3cfdd9="" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                        </svg>
                </button>
            </div>
            
            <div class="clearfix"></div>
            
            <applied-filters v-if="!isFiltersEmpty && showAppliedFilters" />
            
        </div>
        
        <!-- <modal-book ref="modal_book" /> -->
        <modal-filter ref="modal_filter" />
        
    </div>
</template>

<script>
    // import ModalBook from "../modals/ModalBook.vue";
    import ModalFilter from "../modals/ModalFilter.vue";
    import AppliedFilters from "./AppliedFilters.vue";
    export default {
        name: 'navbar',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
            
            // this.$refs.modal_filter.show();
            
            console.log(JSON.parse(JSON.stringify(44444)));
            console.log(JSON.parse(JSON.stringify(this.$store.getters['filters/all'])));
        },
        // props: ['templateSpecifics','templateSpecificsAsIdKey'],
        data: function(){
            return {
                showAppliedFilters: true,
            };
        },
        computed: {
            // customTitle: function(){
            //     return (name) => {
            //         return this.$store.getters['custom_titles/title'](name);
            //     }
            // },
        },
        methods: {
            onClickBook: function(){
                this.app.$refs.modal_book.show();
                // alert('clickBook');
            },
            onClickFilter: function(){
                this.$refs.modal_filter.show();
            },
            onClickRemoveAllFilters: function(){
                this.$store.dispatch('filters/removeAllFilters');
                this.calendar.getData();
            },
        },
        components: {
            // ModalBook,
            ModalFilter,
            AppliedFilters
        },
        watch: {
            isFiltersEmpty: function(newVal, oldVal){
                if(newVal === false && oldVal === true){
                    this.showAppliedFilters = true;
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
    
</style>