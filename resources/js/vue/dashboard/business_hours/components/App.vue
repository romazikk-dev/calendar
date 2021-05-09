<template>
    <div>
        
        <transition name="wrapper">
            <div v-if="isAllDaysClosed" class="alert alert-warning" role="alert">
                All days of week are weekends!
            </div>
        </transition>
        
        <ul class="actions">
            <li>
                <a @click.prevent="setAllWeekends(true)"
                    href="#"
                    class="btn-action-link"
                    :class="{disabled: isAllDaysClosed}">All weekends</a>
            </li>
            <li>
                <a @click.prevent="setAllWeekends(false)"
                    href="#"
                    class="btn-action-link"
                    :class="{disabled: isAllDaysOpened}">All opened</a>
            </li>
        </ul>
        
        <div class="clearfix"></div>

        <div class="row">
            <div v-for="(itm,index) in weekdays" class="col col-12 col-lg-6 col-xl-4">
                
                <div class="card-weekday card bg-light mb-3" :class="{weekend: itm.is_weekend}">
                    <div class="card-header">
                        <b class="text-uppercase">
                            {{itm.weekday}}
                            <span class="status">({{itm.is_weekend ? 'weekend' : 'opened'}})</span>
                        </b>
                        <label class="switch">
                            <!-- <input type="checkbox"> -->
                            <input class=""
                                :id="`isWeekend_` + itm.weekday"
                                :name="`business_hours[` + itm.weekday + `][is_weekend]`"
                                @change="onWeekendChange"
                                type="checkbox"
                                v-model="itm.is_weekend">
                            <span class="slider round"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Toggle (opened/weekend)"></span>
                        </label>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col col-6">
                                <div>start:</div>
                                <vue-timepicker
                                    :input-class="{disabled: itm.is_weekend, 'form-control': true}"
                                    :name="`business_hours[` + itm.weekday + `][start_hour]`"
                                    v-model="itm.start"
                                    :disabled="itm.is_weekend"
                                    format="HH:mm"
                                    :hide-clear-button="true"></vue-timepicker>
                            </div>
                            <div class="col col-6">
                                <div>end:</div>
                                <vue-timepicker
                                    :input-class="{disabled: itm.is_weekend, 'form-control': true}"
                                    :name="`business_hours[` + itm.weekday + `][end_hour]`"
                                    v-model="itm.end"
                                    :disabled="itm.is_weekend"
                                    format="HH:mm"
                                    :hide-clear-button="true"></vue-timepicker>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    import VueTimepicker from 'vue2-timepicker'
    import 'vue2-timepicker/dist/VueTimepicker.css'
    export default {
        mounted() {
            this.setWeekdays();
        },
        // props: ['postTitle'],
        data: function(){
            return {
                businessHours: businessHours,
                defaultStartTime: '10:00',
                defaultEndTime: '20:00',
                my: 'my',
                weekdays: [
                    {
                        weekday: 'monday',
                        is_weekend: false,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'tuesday',
                        is_weekend: false,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'wednesday',
                        is_weekend: false,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'thursday',
                        is_weekend: false,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'friday',
                        is_weekend: false,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'saturday',
                        is_weekend: true,
                        start: '08:00',
                        end: '19:00',
                    },
                    {
                        weekday: 'sunday',
                        is_weekend: true,
                        start: '08:00',
                        end: '19:00',
                    },
                ]
            };
        },
        computed: {
            isAllDaysOpened: function () {
                // console.log('isAllDaysOpened');
                let isAllDaysOpened = true;
                for(let i = 0; i < this.weekdays.length; i++){
                    if(this.weekdays[i].is_weekend){
                        isAllDaysOpened = false;
                        break;
                    }
                }
                return isAllDaysOpened;
            },
            isAllDaysClosed: function () {
                // console.log('isAllDaysClosed');
                let isAllDaysClosed = true;
                for(let i = 0; i < this.weekdays.length; i++){
                    if(!this.weekdays[i].is_weekend){
                        isAllDaysClosed = false;
                        break;
                    }
                }
                return isAllDaysClosed;
            }
        },
        methods: {
            reCalculateTabValue: function(){
                // return;
                
                let workDays = 0;
                this.weekdays.forEach((item, i) => {
                    if(!item.is_weekend)
                        workDays++;
                });
                
                
                let noticeBadges = $("#hours-tab").find('.notice-badges');
                // console.log(noticeBadge);
                
                noticeBadges.find('.notice-badge').addClass('d-none');
                if(workDays > 0){
                    noticeBadges.find('.notice-badge-success').removeClass('d-none')
                        .attr('data-original-title', 'Currently ' + workDays + ' days opened').text(workDays);
                }else{
                    noticeBadges.find('.notice-badge-warning').removeClass('d-none');
                }
            },
            onWeekendChange: function(){
                this.reCalculateTabValue();
            },
            setAllWeekends: function(asWeekends){
                if((asWeekends && this.isAllDaysClosed) || (!asWeekends && this.isAllDaysOpened))
                    return;
                
                // console.log(JSON.parse(JSON.stringify(this.businessHours)));
                
                this.weekdays.forEach((item, i) => {
                    if(asWeekends){
                        this.businessHours[item.weekday].is_weekend = "on";
                    }else{
                        delete this.businessHours[item.weekday].is_weekend;
                    }
                });
                
                this.setWeekdays();
                this.reCalculateTabValue();
            },
            setWeekdays: function(){
                
                for(let itm in this.weekdays){
                    let weekday = this.weekdays[itm].weekday;
                    
                    if(typeof this.businessHours != 'undefined' &&
                    this.businessHours != null &&
                    this.businessHours.hasOwnProperty(weekday)){
                        if(typeof this.businessHours[weekday].start_hour != 'undefined'){
                            this.weekdays[itm].start = JSON.parse(JSON.stringify(this.businessHours[weekday].start_hour));
                        }else{
                            this.weekdays[itm].start = JSON.parse(JSON.stringify(this.defaultStartTime));
                        }
                        
                        if(typeof this.businessHours[weekday].end_hour != 'undefined'){
                            this.weekdays[itm].end = JSON.parse(JSON.stringify(this.businessHours[weekday].end_hour));
                        }else{
                            this.weekdays[itm].end = JSON.parse(JSON.stringify(this.defaultEndTime));
                        }
                        
                        if(this.businessHours[weekday].hasOwnProperty('is_weekend') && this.businessHours[weekday].is_weekend){
                            this.weekdays[itm].is_weekend = true;
                        }else{
                            this.weekdays[itm].is_weekend = false;
                        }
                    }else{
                        this.weekdays[itm].start = JSON.parse(JSON.stringify(this.defaultStartTime));
                        this.weekdays[itm].end = JSON.parse(JSON.stringify(this.defaultEndTime));
                    }
                }
            },
            getHourValue: function(type, weekday){
                console.log(type, weekday);
            }
        },
        components: {
            VueTimepicker,
        },
    }
</script>

<style lang="scss" scoped>
    .wrapper-enter-active {
        transition: opacity .3s;
        opacity: 1;
    }
    .wrapper-leave-active {
        // transition: opacity .3s;
        opacity: 1;
    }
    .wrapper-enter{
        opacity: 0;
    }
    .wrapper-leave-to{
        opacity: 0;
    }
</style>

<style lang="scss">
    input.disabled{
        /* background-color: #ccc!important; */
        cursor: not-allowed! important;
        color: #c4c4c4;
    }
    
    .vue__time-picker{
        width: 100%!important;
        >{
            input.display-time{
                width: 100%!important;
            }
        }
    }
    
    .actions{
        margin: 0px;
        padding: 0px;
        display: inline-block;
        padding-bottom: 10px!important;
        li{
            list-style: none;
            float: left;
            .drop{
                .dropdown-menu{
                    padding-left: 10px;
                    padding-right: 10px;
                    text-align: center;
                    .btn-actions{
                        // padding-top: 5px;
                        a{
                            display: inline-block;
                            padding: 6px 12px;
                        }
                    }
                }
            }
            a{
                &.btn-drop{
                    display: block;
                    padding: 3px 10px;
                    margin-left: 10px;
                }
                &.btn-action-link{
                    display: block;
                    padding: 3px 10px;
                    margin-left: 10px;
                    &.disabled{
                        text-decoration: none;
                        cursor: default;
                        color: black;
                        display: none;
                    }
                }
            }
        }
    }
    
    .card-weekday{
        position: relative;
        border: none;
        transition: background-color .3s ease;
        // background-color: rgba(198,71,70, 0.1)!important;
        background-color: rgba(89,163,75, 0.2)!important;
        // &:hover{
        //     background-color: #e4e4e4!important;
        // }
        &.weekend{
            background-color: rgba(198,71,70, 0.1)!important;
        }
        .card-header{
            background: none;
            span{
                &.status{
                    color: #666;
                    text-transform: lowercase;
                    font-weight: normal;
                }
            }
        }
        .card-body{
            // border-left-color: transparent;
            // border-left-color: transparent;
            // border-left-color: transparent;
            table{
                width: 100%;
                tr{
                    td{
                        width: 49.9%;
                    }
                }
            }
        }
        .switch {
            position: absolute;
            display: inline-block;
            width: 51px;
            height: 28px;
            top: 9px;
            right: 9px;
            input{
                opacity: 0;
                width: 0;
                height: 0;
                &:checked{
                    +{
                        .slider {
                           // background-color: #2196F3
                           background-color: rgba(198,71,70, 0.1)!important;
                           &:before {
                               -webkit-transform: translateX(0px);
                               -ms-transform: translateX(0px);
                               transform: translateX(0px);
                           }
                        }
                    }
                    
                }
            }
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
                background-color: rgba(89,163,75, 0.5)!important;
                &.round {
                    border-radius: 34px;
                    &:before {
                        border-radius: 50%;
                    }
                }
                &:before {
                    position: absolute;
                    content: "";
                    height: 24px;
                    width: 24px;
                    left: 3px;
                    bottom: 2px;
                    background-color: white;
                    -webkit-transition: .4s;
                    transition: .4s;
                    -webkit-transform: translateX(21px);
                    -ms-transform: translateX(21px);
                    transform: translateX(21px);
                }
            }
        }
    }
</style>