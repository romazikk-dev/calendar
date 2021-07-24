<template>
    <div>
            
        <div class="applied-filters">
            <div class="titlee">
                <b>Applied filters:</b>
                <!-- <div class="applied-filter-close-all">
                    <button class="btn btn-sm btn-danger btn-remove-filter">
                        Remove all
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                        </svg>
                    </button>
                </div> -->
            </div>
            <div class="applied-filter">
                
                <applied-filter-item v-if="hallFilter"
                    type="hall"
                    :filter="hallFilter" />
                
                <applied-filter-item v-if="templateFilter"
                    type="template"
                    :filter="templateFilter" />
                
                <applied-filter-item v-if="workerFilter"
                    type="worker"
                    :filter="workerFilter" />
                
                <applied-filter-item v-if="clientFilter"
                    type="client"
                    :filter="clientFilter" />
                    
                <applied-filter-item v-if="durationFilter"
                    type="duration"
                    :filter="durationFilter" />
                
            </div>
        </div>
        
    </div>
</template>

<script>
    import AppliedFilterItem from "./AppliedFilterItem.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
            
            // this.$refs.modal_filter.show();
            
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.$store.getters['filters/all'])));
        },
        // props: ['templateSpecifics','templateSpecificsAsIdKey'],
        data: function(){
            return {
                //Using to indicate current picked items
                // pickedItmHall: null,
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
            // onClickBook: function(){
            //     // this.$refs.modal_book.show();
            //     // alert('clickBook');
            // },
            // onClickFilter: function(){
            //     // this.$refs.modal_filter.show();
            // },
        },
        components: {
            AppliedFilterItem,
        },
        watch: {
            // showFilters: function(val){
            //     if(val === false){
            //         // alert(this.isCookieItmsEmpty);
            //         if(!this.isCookieItmsEmpty && !this.isPickedItmsFilled){
            //             this.fillFilters();
            //         }
            //     }
            // },
        },
    }
</script>

<style lang="scss" scoped>
    
</style>