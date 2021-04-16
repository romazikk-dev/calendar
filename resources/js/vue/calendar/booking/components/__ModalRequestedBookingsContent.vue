<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Requested bookings: {{date}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="item" v-for="itm in bookings">
                In approving:<br>
                {{itm.booking.template_without_user_scope.title}}<br>
                {{itm.from + ' - ' + itm.to}}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</template>

<script>
    // import VueTimepicker from 'vue2-timepicker'
    // import 'vue2-timepicker/dist/VueTimepicker.css'
    // import TimeBar from "./TimeBar.vue";
    // import Loader from "./Loader.vue";
    export default {
        mounted() {
            
            
            
            // $("#bookModal").on('shown.bs.modal', () => {
            //     if(this.successfullyBooked)
            //         this.$refs['loader'].fadeOut(300);
            // 
            //     this.setInitValue();
            //     this.$refs['time_bar'].recalculate();
            // 
            //     // this.$refs['loader'].show();
            //     // console.log(JSON.parse(JSON.stringify(this.template)));
            //     // console.log(this.bookDate);
            //     // console.log(JSON.parse(JSON.stringify(this.bookDate)));
            //     // console.log(JSON.parse(JSON.stringify(this.bookTimePeriod)));
            //     // this.startPeriodDate = new Date(
            //     //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
            //     // );
            //     // let startPeriodDate = moment(
            //     //     this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day + ' ' + this.bookTimePeriod.from + ':00'
            //     // );
            //     // let timezoneOffset = Math.abs(this.startPeriodDate.getTimezoneOffset());
            //     // this.startPeriodDate = startPeriodDate.add(timezoneOffset, 'minutes').toDate();
            //     // console.log(JSON.parse(JSON.stringify(this.bookTimePeriod)));
            //     this.bookOn = this.bookTimePeriod.from;
            //     // this.modalOpened = true
            //     this.$refs['loader'].fadeOut(300);
            //     setTimeout(() => {
            //         this.bookButtonDisabled = false;
            //     }, 300);
            //     // console.log(this.$refs['time_bar'].sliderDisabled);
            //     // console.log(this.startPeriodDate.getTimezoneOffset());
            // });
            // 
            // $("#bookModal").on('hidden.bs.modal', () => {
            //     // this.modalOpened = false
            //     this.successfullyBooked = false;
            //     this.bookButtonDisabled = true;
            //     this.$refs['loader'].show();
            //     this.arrowPosition = 10;
            //     this.setStyleForArrow();
            // });
            
            
            
        },
        props: ['userId','bookDate','bookTimePeriod','bookings'],
        data: function(){
            return {
                // modalOpened: false,
                bookButtonDisabled: true,
                successfullyBooked: false,
                bookOn: null,
                template: filters.template,
                choosedH: null,
                choosedM: null,
                initValue: {
                    HH: '',
                    mm: '',
                },
                startPeriodDatetime: null,
                endPeriodDatetime: null,
                preEndPeriodDatetime: null,
                timeBarChangeTimeout: null,
                s: null,
                arrowPosition: 10,
                hintText: 'Move slider to choose time for booking.'
            };
        },
        computed: {
            date: function () {
                if(this.bookDate == null)
                    return '';
                return this.bookDate.year + '-' + this.bookDate.month + '-' + this.bookDate.day;
            },
            // bookOn: function () {
            //     if(this.choosedH != null && this.choosedM != null){
            //         return this.choosedH + ':' + this.choosedM;
            //     }else{
            //         return 0;
            //     }
            // },
        },
        methods: {
            onBooked: function (response){
                this.$emit('booked');
                // this.$refs['loader'].showTranparent();
            },
        },
        components: {
            // VueTimepicker,
            // TimeBar,
            // Loader
        },
        watch: {
            // bookTimePeriod: (newOne, oldOne) => {
            //     // console.log(helper.parse(newOne));
        	// 	// console.log("Title changed from " + newOne + " to " + oldOne)
        	// },
    	}
    }
</script>

<style scoped>
    .item{
        background-color: #cf582c;
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        margin-bottom: 10px;
    }
    .item:last-child{
        margin-bottom: 0px;
    }
    .modal-body{
        position: relative;
        max-height: 200px;
        overflow-x: auto;
    }
    .modal-header, .modal-footer{
        background-color: #6c757d;
        color: white;
    }
    .modal-header{
        position: relative;
    }
    .close{
        font-size: 60px;
        color: #fff;
        opacity: .7;
        transition: opacity .3s ease;
        line-height: .8em;
        padding: 0px;
        margin: 0px;
        position: absolute;
        top: 0px;
        right: 0px;
        height: 60px;
        width: 60px;
    }
    .close span{
        position: absolute;
        top: 0px;
        right: 0px;
        height: 60px;
        width: 60px;
    }
    .close:hover{
        color: #fff;
        opacity: 1!important;
    }
    .modal-title{
        font-weight: normal;
        color: #f4f4f4;
    }
    .modal-title b{
        color: white;
    }
    .small{
        line-height: 1.2em!important;
    }
</style>