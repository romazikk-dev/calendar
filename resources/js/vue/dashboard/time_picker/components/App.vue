<template>
    <div>
        <vue-timepicker
            :id="id"
            :hour-range="hourRange"
            v-model="inputValue"
            :input-class="{'form-control': true}"
            :input-style="{'width': '100%'}"
            :name="name"
            format="HH:mm"
            hide-disabled-items
            :hide-clear-button="true"></vue-timepicker>
    </div>
</template>

<script>
    import VueTimepicker from 'vue2-timepicker'
    import 'vue2-timepicker/dist/VueTimepicker.css'
    export default {
        mounted() {
            // console.log('Component mounted.');
            
            // console.log(this.value);
            console.log(this.max);
            this.setHourRange();
        },
        props: ['name','id','value','min','max'],
        data: function(){
            return {
                // businessHours: businessHours,
                inputValue: this.value,
                hourRange: [[0, 23]],
            };
        },
        methods: {
            setHourRange: function(){
                let firstHour = 0;
                let lastHour = 23;
                if(isRightHour(this.getProp('min')))
                    firstHour = parseInt(this.min);
                if(isRightHour(this.getProp('max')))
                    lastHour = parseInt(this.max);
                
                this.hourRange = [[firstHour, lastHour]];
                
                function isRightHour(hour){
                    return typeof hour != 'undefined' && hour != null && !isNaN(hour);
                }
            },
            getProp: function(prop){
                return typeof this[prop] != 'undefined' ? this[prop] : null;
            },
            // setWeekdays: function(){
            // 
            // },
        },
        components: {
            VueTimepicker,
        },
    }
</script>

<style>
    .vue__time-picker, .vue__time-picker input{
        width: 100%!important;
    }
</style>