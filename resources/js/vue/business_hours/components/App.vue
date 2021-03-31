<template>
    <div id="businessHours" class="card bg-light">
        <div class="card-header">
            <h5>Business hours:</h5>
        </div>
        <div class="card-body">
            <table>
                <template v-for="(itm,index) in weekdays">
                    <tr class="start">
                        <td>
                            {{itm.weekday}}<br>
                            {{itm.start}}<br>
                            {{itm.end}}<br>
                            {{itm.is_weekend}}
                        </td>
                        <td>
                            <small>start:</small>
                            <vue-timepicker
                                :input-class="{disabled: itm.is_weekend, 'form-control': true}"
                                :name="`business_hours[` + itm.weekday + `][start_hour]`"
                                v-model="itm.start"
                                :disabled="itm.is_weekend"
                                format="HH:mm"
                                :hide-clear-button="true"></vue-timepicker>
                        </td>
                        <td>
                            <small>end:</small>
                            <vue-timepicker
                                :input-class="{disabled: itm.is_weekend, 'form-control': true}"
                                :name="`business_hours[` + itm.weekday + `][end_hour]`"
                                v-model="itm.end"
                                :disabled="itm.is_weekend"
                                format="HH:mm"
                                :hide-clear-button="true"></vue-timepicker>
                        </td>
                    </tr>
                    <tr class="end">
                        <td></td>
                        <td colspan="2">
                            <input class=""
                                :id="`isWeekend_` + itm.weekday"
                                :name="`business_hours[` + itm.weekday + `][is_weekend]`"
                                type="checkbox"
                                v-model="itm.is_weekend">
                            <label :for="`isWeekend_` + itm.weekday">Weekend</label>
                        </td>
                    </tr>
                </template>
            </table>
        </div>
    </div>
</template>

<script>
    import VueTimepicker from 'vue2-timepicker'
    import 'vue2-timepicker/dist/VueTimepicker.css'
    export default {
        mounted() {
            // console.log('Component mounted.');
            
            // $(function () {
            //     setTimeout(function(){
            //         $('.bs-timepicker').timepicker();
            //     }, 300);
            //     // alert(111);
            // });
            
            // this.greet();
            // console.log(111);
            // console.log(this.my);
            // setTimeout(() => {
                this.setWeekdays();
            // }, 300);
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
        methods: {
            setWeekdays: function(){
                // console.log(this.businessHours);
                // return;
                
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
                        // if()
                    }
                
                    // if(typeof this.businessHours != 'undefined' &&
                    // this.businessHours != null &&
                    // this.businessHours.hasOwnProperty(weekday) &&
                    // typeof this.businessHours[weekday].start_hour != 'undefined'){
                    //     this.weekdays[itm].start = JSON.parse(JSON.stringify(this.businessHours[weekday].start_hour));
                    // }else{
                    //     this.weekdays[itm].start = JSON.parse(JSON.stringify(this.defaultStartTime));
                    // }
                    // 
                    // if(typeof this.businessHours != 'undefined' &&
                    // this.businessHours != null &&
                    // this.businessHours.hasOwnProperty(weekday) &&
                    // typeof this.businessHours[weekday].end_hour != 'undefined'){
                    //     this.weekdays[itm].end = JSON.parse(JSON.stringify(this.businessHours[weekday].end_hour));
                    // }else{
                    //     this.weekdays[itm].end = JSON.parse(JSON.stringify(this.defaultEndTime));
                    // }
                    // this.weekdays[itm].end = JSON.parse(JSON.stringify(this.defaultEndTime));
                    // console.log(this.weekdays[itm]);
                }
                // return JSON.parse(JSON.stringify(this.weekdays));
                // console.log(this.weekdays);
                // console.log(JSON.parse(JSON.stringify(this.weekdays)));
                // return 1;
                // JSON.stringify(type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime)
                // console.log(1);
                // console.log(type, weekday);
                // console.log(this.defaultStartTime, this.defaultEndTime);
                // let defaultTime = JSON.parse(JSON.stringify(type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime));
                
                // let defaultTime = type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime;
                // return defaultTime;
                // return JSON.parse(JSON.stringify(businessHours && businessHours[weekday] && businessHours[weekday][type] ? businessHours[weekday][type] : defaultTime));
            },
            getHourValue: function(type, weekday){
                // return 1;
                // JSON.stringify(type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime)
                // console.log(1);
                console.log(type, weekday);
                // console.log(this.defaultStartTime, this.defaultEndTime);
                // let defaultTime = JSON.parse(JSON.stringify(type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime));
                
                // let defaultTime = type == 'start_hour' ? this.defaultStartTime : this.defaultEndTime;
                // return defaultTime;
                // return JSON.parse(JSON.stringify(businessHours && businessHours[weekday] && businessHours[weekday][type] ? businessHours[weekday][type] : defaultTime));
            }
        },
        components: {
            VueTimepicker,
        },
    }
</script>

<style>
    input.disabled{
        /* background-color: #ccc!important; */
        cursor: not-allowed! important;
        color: #c4c4c4;
    }
</style>