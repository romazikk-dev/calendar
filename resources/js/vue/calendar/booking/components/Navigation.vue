<template>
    <div class="for-navigation">
        <div class="navigation">
            
            <div class="left-part float-left">
                <button @click.prevent="$emit('previous')" type="button" class="btn btn-sm btn-primary float-left" :disabled="!canGoToPrevious">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </button>
                <button @click.prevent="$emit('next')" type="button" class="btn btn-sm btn-primary float-left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
                <button @click.prevent="$emit('today')" type="button" class="btn btn-sm btn-secondary float-left" :disabled="!canGoToPrevious">
                    today
                </button>
            </div>
            
            <div class="right-part float-right">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button v-for="item in views"
                        :class="{
                            active: item.toLowerCase() == view.toLowerCase(),
                            disabled: item.toLowerCase() == view.toLowerCase()
                        }"
                        :disabled="item.toLowerCase() == view.toLowerCase()"
                        @click.prevent="$emit('change_view', item)"
                        type="button"
                        class="btn btn-sm btn-secondary">{{item}}</button>
                </div>
                <!-- <div class="filter float-right mr-0">
                    <div id="viewDropdown" class="dropdown">
                        <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="viewDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{view}}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="viewDropdownButton">
                            <a v-for="item in views" @click.prevent="$emit('change_view', item)" v-if="item.toLowerCase() != view.toLowerCase()" class="dropdown-item" href="#">{{item}}</a>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <div class="calendar-title">
                {{calendarTitle}}
            </div>
            
        </div>
        <div class="clearfix"></div>
    </div>
</template>

<script>
    // import ModalAuthContent from "./ModalAuthContent.vue";
    // import ModalBookContent from "./ModalBookContent.vue";
    // import ModalCancelBookContent from "./ModalCancelBookContent.vue";
    // import DayRequestedBookedCell from "./DayRequestedBookedCell.vue";
    export default {
        name: 'navigation',
        mounted() {
            console.log(this.view);
            console.log(this.views);
        },
        props: ['views','view','canGoToPrevious','calendarTitle'],
        // props: ['userId','search', 'views','view','startDate','canGoToPrevious'],
        data: function(){
            return {
                // dateRange: helper.range.range,
            };
        },
        computed: {
            // currentDay: function () {
            // 
            // },
        },
        methods: {
            // changeView: function(view){
            //     // console.log(view);
            //     this.$emit('view_changed', view);
            // },
        },
        components: {
            // ModalAuthContent,
            // ModalBookContent,
            // ModalCancelBookContent,
            // DayRequestedBookedCell
        },
        watch: {
            // search: function () {
            //     // console.log(this.search);
            //     // this.getData();
            // }
        }
    }
</script>

<style lang="scss" scoped>
    #viewDropdown{
        .dropdown-toggle{
            text-transform: capitalize;
        }
        .dropdown-item{
            text-transform: capitalize;
        }
    }

    .navigation{
        .left-part{
            button{
                // display: none;
                &:nth-child(1){
                    border-radius: .2rem 0 0 .2rem;
                }
                &:nth-child(2){
                    border-radius: 0 .2rem .2rem 0;
                }
                &:nth-child(3){
                    margin-left: 10px;
                }
            }
        }
    }
</style>