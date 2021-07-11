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
                <button type="button" class="btn btn-sm btn-info">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
  <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
</svg> -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                    </svg>
                </button>
            </div>

            <!-- <div class="btn-group ml-2 float-left">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    All
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Book</a>
                </div>
            </div> -->
            
            <div class="clearfix"></div>
        </div>
        
        <modal-book ref="modal_book" />
        
    </div>
</template>

<script>
    import ModalBook from "./modals/ModalBook.vue";
    export default {
        name: 'navbar',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
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
            onClickBook: function(){
                this.$refs.modal_book.show();
                // alert('clickBook');
            },
            // changeView: function(view){
            //     // console.log(view);
            //     // alert(1111);
            //     // this.choosedItmView = view;
            //     this.cookieItmView = view;
            //     this.putFiltersInCookie();
            //     // this.composeSearch();
            //     this.emitChange();
            // },
        },
        components: {
            ModalBook,
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
    .navbarr{
        padding: 6px 6px;
        position: relative;
        .disabled-cover{
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            background: rgba(255,255,255, .8);
            text-align: center;
            line-height: 30px;
        }
    }
</style>