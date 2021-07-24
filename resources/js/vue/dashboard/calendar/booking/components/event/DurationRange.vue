<template>
    <div>
        
        <template v-if="asCard === true">
            <div class="card bg-light filter-block">
                <div class="card-header">
                    Duration: <span class="badge badge-info">{{durationStartStrHoursAndMinutes}} - {{durationEndStrHoursAndMinutes}}</span>
                    <div class="btn-group float-right" role="group">
                        <button v-if="!isFullRange"
                            @click="onClickFullRange"
                            class="btn btn-sm btn-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                                </svg>
                        </button>
                        <button v-if="isChanged"
                            @click="onClickReset"
                            class="btn btn-sm btn-light">Reset</button>
                        <button class="btn btn-sm btn-light" @click="toogleColapsed">
                            <svg v-if="!collapsed"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                            </svg>
                            <svg v-if="collapsed"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body" v-if="!collapsed">
                    <!-- <b>{{durationStartStrHoursAndMinutes}} - {{durationEndStrHoursAndMinutes}}</b> -->
                    <time-bar-fill-range ref="time_bar_duration"
                        @change="onChange($event)"
                        :offset="offset"
                        :range="range"
                        main-color="rgba(40,167,69, .6)" />
                </div>
            </div>
        </template>
        <template v-else>
            <div>Duration range: <b>{{durationStartStrHoursAndMinutes}} - {{durationEndStrHoursAndMinutes}}</b></div>
            <div class="for-time-bar-fill pb-3">
                <time-bar-fill-range ref="time_bar_duration"
                    @change="onChange($event)"
                    :offset="offset"
                    :range="range"
                    main-color="rgba(40,167,69, .3)" />
            </div>
        </template>
        
    </div>
</template>

<script>
    import TimeBarFillRange from "../modals/TimeBarFillRange.vue";
    export default {
        name: 'durationRange',
        beforeMount() {
            this.setInitRange();
        },
        /*
        *   e = {event, nextEvent}
        */
        props: ['initRange','asCard','checkedTemplates'],
        data: function(){
            return {
                range: {
                    start: 10,
                    end: 180,
                },
                offset: 10,
                collapsed: false,
            };
        },
        computed: {
            durationStartStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.range.start);
            },
            durationEndStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.range.end);
            },
            isFullRange: function(){
                return this.range.start === 10 && this.range.end === 180;
            },
            isChanged: function(){
                console.log(JSON.parse(JSON.stringify('isChanged')));
                console.log(JSON.parse(JSON.stringify(this.initRange)));
                
                let durationRangeStr = JSON.stringify(this.range);
                let durationFilterStr = this.durationFilter === null ?
                    JSON.stringify(this.durationRangeMinMax) : JSON.stringify(this.durationFilter);
                return durationRangeStr != durationFilterStr;
            },
        },
        methods: {
            onClickFullRange: function(){
                // alert(this.durationRangeMinMax.start);
                // this.setInitRange(null);
                this.$refs.time_bar_duration.setInputInitValue(this.durationRangeMinMax);
            },
            onClickReset: function(){
                this.$refs.time_bar_duration.setInputInitValue(this.initRange);
            },
            toogleColapsed: function(){
                this.collapsed = !this.collapsed;
            },
            isCheckedTemplates: function(){
                let arr = Object.values(this.checkedTemplates);
                return arr.length > 0 ? true : false;
            },
            onChange: function (e){
                this.range = this.getRightRangeFromRange(e);
                
                // console.log(JSON.parse(JSON.stringify('onChange')));
                // console.log(JSON.parse(JSON.stringify(this.initRange)));
                
                this.$emit('change', this.range);
            },
            setInitRange: function (){
                this.range = this.getRightRangeFromRange(this.initRange);
                // console.log(JSON.parse(JSON.stringify('setInitRange')));
                // console.log(JSON.parse(JSON.stringify(this.range)));
            },
            getRightRangeFromRange: function (range){
                if(typeof range === 'undefined' || range == null ||
                typeof range.start === 'undefined' || typeof range.end === 'undefined' ||
                isNaN(range.start) || isNaN(range.end))
                    return {
                        start: this.durationRangeMinMax.start,
                        end: this.durationRangeMinMax.end,
                    };
                
                let start, end;
                
                if(range.start > this.durationRangeMinMax.end){
                    start = this.durationRangeMinMax.end;
                }else if(range.start < this.durationRangeMinMax.start){
                    start = this.durationRangeMinMax.start;
                }else{
                    start = parseInt(range.start);
                }
                
                if(range.end > this.durationRangeMinMax.end){
                    end = this.durationRangeMinMax.end;
                }else if(range.end < this.durationRangeMinMax.start){
                    end = this.durationRangeMinMax.start;
                }else{
                    end = parseInt(range.end);
                }
                
                if(start > (end - this.offset)){
                    start = end - this.offset;
                }
                
                return {
                    start: start,
                    end: end,
                };
            },
        },
        components: {
            TimeBarFillRange,
        },
        watch: {
            collapsed: function(val) {
                this.$emit('collapse', val);
        	},
            // range: function(val) {
            //     this.$emit('change', val);
        	// },
    	}
    }
</script>

<style lang="scss" scoped>
    .card{
        .card-header{
            padding: 6px;
        }
        .card-body{
            padding: 10px;
            padding-bottom: 26px;
        }
    }
    .modal-content .btn-group .btn {
        &:first-child{
            border-radius: 3px 0px 0px 3px!important;
        }
        &:last-child{
            border-radius: 0px 3px 3px 0px!important;
        }
        &:only-child{
            border-radius: 3px 3px 3px 3px!important;
        }
    }
</style>