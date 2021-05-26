<template>
    <div class="specific-search">
        
        <div class="form-group search-field">
            <input @keydown.enter.prevent
                @keyup.enter.prevent="$refs.buttonFilter.click()"
                v-model="search"
                type="text"
                class="form-control form-control-sm"
                placeholder="Filter ...">
            <a v-if="search" @click.prevent="clearSearchInput()" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>
        </div>
        <button
            ref="buttonFilter"
            @click.prevent="searchClick()"
            type="button"
            class="btn btn-sm btn-outline-primary text-uppercase search-btn">
                Filter
        </button>
            
    </div>
</template>

<script>
    export default {
        // name: 'list',
        mounted() {
            
        },
        // props: ['fields', 'paddingLeft', 'parentField', 'parentTitle'],
        data: function(){
            return {
                search: null,
                searchEvent: null,
            };
        },
        computed: {
            // iconArrow: function(){
            // 
            // },
        },
        methods: {
            searchClick: function(){
                // if(this.search != null && this.search !== this.searchEvent){
                if(this.search !== this.searchEvent){
                    this.searchEvent = this.search;
                    this.$emit('search', this.search);
                }
            },
            clearSearchInput: function(){
                this.search = null;
                this.searchClick();
            },
        },
        components: {
            // list: List
        },
        watch: {
            search: function(valNew, valOld){
                // console.log(valNew);
                // console.log(valOld);
                if((valOld !== null || valOld !== '') && (valNew === null || valNew === '')){
                    // console.log(valNew);
                    // console.log(valOld);
                    this.searchEvent = valOld;
                    this.clearSearchInput();
                }
                // console.log(valNew);
                // console.log(valOld);
                // this.search = null;
                // this.searchClick();
            },
        },
    // },
    }
</script>

<style lang="scss" scoped>

</style>