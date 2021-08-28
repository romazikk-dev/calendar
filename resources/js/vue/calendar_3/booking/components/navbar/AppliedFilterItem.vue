<template>
    <div class="applied-filter-row">
            
        <div class="applied-filter-title">
            <span class="small">
                {{label}}:
            </span>
        </div>
        <div class="applied-filter-items">
            <template v-if="show == 'duration'">
                <span class="badge badge-success">
                    {{getDurationStrHoursAndMinutes(filter.start)}} - {{getDurationStrHoursAndMinutes(filter.end)}}
                </span>
            </template>
            <template v-if="show == 'badges'">
                <span v-for="(item, index) in filter"
                    class="badge badge-success applied-filter-item">
                        {{getItemTitle(item)}}
                        <span @click="onItemDeleteClick(item)"
                            class="checked-item-close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                                </svg>
                        </span>
                </span>
            </template>
        </div>
        <div class="applied-filter-close">
            <button class="btn btn-sm btn-danger btn-remove-filter" @click="onClickRemoveEntireFilter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                </svg>
            </button>
        </div>
        <div class="clearfix"></div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // $('.tooltip-active').tooltip({
            //     html: true,
            //     // trigger: "click",
            //     trigger: "hover",
            // });
            // console.log(JSON.parse(JSON.stringify('this.$store.state.count')));
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.halls)));
            
            // this.$refs.modal_filter.show();
            
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.$store.getters['filters/all'])));
            console.log(JSON.parse(JSON.stringify(77777777)));
            console.log(JSON.parse(JSON.stringify(this.filter)));
        },
        props: ['filter','type'],
        data: function(){
            return {
                labels: {
                    status: 'Status',
                    hall: 'Hall(s)',
                    template: 'Template(s)',
                    worker: 'Worker(s)',
                    client: 'Client(s)',
                    duration: 'Duration',
                }
                //Using to indicate current picked items
                // pickedItmHall: null,
            };
        },
        computed: {
            show: function(){
                if(['duration'].includes(this.type))
                    return 'duration';
                return 'badges';
            },
            label: function(){
                if(typeof this.type === 'undefined' || this.type === null ||
                typeof this.labels[this.type] === 'undefined')
                    return 'undefined';
                return this.labels[this.type];
            },
            isTypeRight: function(){
                return typeof this.type !== 'undefined' &&
                ['status','client','hall','template','worker'].includes(this.type);
            },
        },
        methods: {
            onClickRemoveEntireFilter: function() {
                if(!this.isTypeRight && this.type != 'duration')
                    return;
                this.$store.dispatch('filters/removeEntireFilter', this.type);
                // alert(111);
                this.calendar.getData();
            },
            onItemDeleteClick: function (item) {
                if(this.type == 'status'){
                    this.$store.dispatch('filters/removeItemFromFilterByValue', {
                        type: this.type,
                        value: item
                    });
                }
                
                if(this.isTypeRight &&
                typeof item !== 'undefined' && item !== null &&
                typeof item.id !== 'undefined' && item.id !== null && !isNaN(item.id))
                    this.$store.dispatch('filters/removeItemFromFilterById', {
                        type: this.type,
                        itemId: item.id
                    });
                    
                this.calendar.getData();
            },
            getDurationStrHoursAndMinutes: function (durationMinutes) {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(durationMinutes);
            },
            getItemTitle: function(item){
                if(this.type == 'status')
                    return this.statusData[item].label;
                if(['hall','template'].includes(this.type))
                    return item.title;
                if(['worker','client'].includes(this.type))
                    return this.fullNameOfObj(item);
            },
        },
        components: {
            // ModalBook,
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