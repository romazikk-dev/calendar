<template>
    <div>
        <vue-timepicker
            :id="id"
            v-model="inputValue"
            :input-class="{'form-control': true}"
            :input-style="{'width': '100%'}"
            :name="name"
            format="HH:mm"
            :hide-clear-button="true"></vue-timepicker>
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
                // this.setWeekdays();
            // }, 300);
            console.log(this.value);
        },
        props: ['name','id','value'],
        data: function(){
            return {
                // businessHours: businessHours,
                inputValue: this.value
            };
        },
        methods: {
            setWeekdays: function(){
                
            },
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