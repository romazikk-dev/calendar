<template>
    <div class="specific-search">
        
        <div class="form-group search-field">
            <input v-model="search" type="email" class="form-control form-control-sm" placeholder="Search ...">
            <a v-if="search" @click.prevent="clearSearchInput($event)" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary text-uppercase search-btn">Search</button>
            
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
            };
        },
        computed: {
            // iconArrow: function(){
            // 
            // },
        },
        methods: {
            clearSearchInput: function(event){
                this.search = null;
            },
        },
        components: {
            // list: List
        },
        watch: {
            // modalDs
        },
    // },
    }
</script>

<style lang="scss" scoped>

</style>