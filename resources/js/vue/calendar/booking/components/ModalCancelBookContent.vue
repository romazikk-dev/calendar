<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{modalTitle}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <loader ref="loader"></loader>
            <table>
                <tbody>
                    <tr>
                        <td>Title:</td>
                        <td><b>{{bTitle}}</b></td>
                    </tr>
                    <tr>
                        <td>Hall:</td>
                        <td><b>{{filters.hall.title}}</b></td>
                    </tr>
                    <tr>
                        <td>Worker:</td>
                        <td><b>{{filterWorkerFullName}}</b></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td><b>{{bDate}}</b></td>
                    </tr>
                    <tr>
                        <td>Time:</td>
                        <td><b>{{bTime}}</b></td>
                    </tr>
                    <tr>
                        <td>Duration:</td>
                        <td><b>{{bDuration}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <template v-if="!successfullyCanceled">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click.prevent="cancel"
                    type="button"
                    class="btn btn-success"
                    :disabled="cancelButtonDisabled">Yes</button>
            </template>
            <template v-if="successfullyCanceled">
                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
            </template>
        </div>
    </div>
</template>

<script>
    // import VueTimepicker from 'vue2-timepicker'
    // import 'vue2-timepicker/dist/VueTimepicker.css'
    // import TimeBar from "./TimeBar.vue";
    import Loader from "./Loader.vue";
    export default {
        name: 'modalCancelBookContent',
        mounted() {
            // console.log(this.filters);
            // this.$refs['loader'].show();
            // 
            // $("#cancelBookModal").on('hidden.bs.modal', () => {
            //     this.bookDate = null;
            //     // console.log(this.bookDate);
            // });
            
            // $("#cancelBookModal").on('show.bs.modal', () => {
            //     if(this.bookingItem == null)
            //         this.$refs['loader'].show();
            //     // console.log(this.bookDate);
            // });
            // 
            // $("#cancelBookModal").on('hidden.bs.modal', () => {
            //     this.bookingItem = null;
            // });
        },
        props: ['booking'],
        data: function(){
            return {
                // bookingItem: booking,
                cancelButtonDisabled: false,
                successfullyCanceled: false,
                question: null,
                modalTitle: null,
                bTitle: null,
                bTimeMoment: null,
                bDuration: null,
                filters: filters,
                // template: filters.template,
                // choosedH: null,
                // choosedM: null,
                // initValue: {
                //     HH: '',
                //     mm: '',
                // },
                // startPeriodDatetime: null,
                // endPeriodDatetime: null,
                // preEndPeriodDatetime: null,
                // timeBarChangeTimeout: null,
                // s: null,
                // arrowPosition: 10,
                // hintText: 'Move slider to choose time for booking.'
            };
        },
        computed: {
            filterWorkerFullName: function () {
                return filters.worker.first_name + ` ` + filters.worker.last_name;
            },
            bDate: function () {
                if(this.bTimeMoment == null)
                    return '';
                return this.bTimeMoment.format('YYYY-MM-DD');
            },
            bTime: function () {
                if(this.bTimeMoment == null)
                    return '';
                return this.bTimeMoment.format('HH:mm');
            },
        },
        methods: {
            onCancel: function (response){
                this.$emit('canceled', response);
                // this.$refs['loader'].showTranparent();
            },
            cancel: function (){
                // this.bookButtonDisabled = true;
                // this.$refs['loader'].showTranparent();
                let url = routes.calendar.booking.book.cancel;
                
                url = url.replace(':hall_id', filters.hall.id);
                url = url.replace(':template_id', filters.template.id);
                url = url.replace(':worker_id', filters.worker.id);
                url = url.replace(':booking_id', this.booking.id);
                
                // console.log(url);
                // return;
                
                axios.delete(url)
                .then((response) => {
                    // handle success
                    // this.dates = response.data.data;
                    console.log('success');
                    this.onCancel(response.data);
                    // console.log(JSON.parse(JSON.stringify(response)));
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(() => {
                    // always executed
                    console.log('always');
                    // this.$refs['loader'].fadeOut(300);
                    // setTimeout(() => {
                    //     this.successfullyBooked = true;
                    //     this.bookButtonDisabled = false;
                    // }, 300);
                    
                });
                // console.log(url);
            },
        },
        components: {
            // VueTimepicker,
            // TimeBar,
            Loader
        },
        watch: {
            booking: function(newOne, oldOne){
                if(this.booking == null)
                    return;
                    
                this.booking = this.booking
                    
                this.$refs['loader'].fadeOut(300);
                // setTimeout(() => {
                //     this.$refs['loader'].fadeOut(300);
                // });
                if(this.booking.approved == 1){
                    this.modalTitle = `Cancel booked event?`;
                    this.question = `Do you realy want cancel this booked event?`;
                }else{
                    this.modalTitle = `Cancel request for event?`;
                    this.question = `Do you realy want cancel this request to book an event?`;
                }
                this.bTimeMoment = moment(this.booking.time);
                this.bTitle = this.booking.template_without_user_scope.title;
                
                let durationSeconds = this.booking.template_without_user_scope.duration/60;
                let durationHourPart = parseInt(durationSeconds/60);
                if(durationHourPart < 10)
                    durationHourPart = '0' + durationHourPart;
                let durationMinutePart = durationSeconds%60;
                if(durationMinutePart < 10)
                    durationMinutePart = '0' + durationMinutePart;
                this.bDuration = durationHourPart + ':' + durationMinutePart;
                console.log(JSON.parse(JSON.stringify(this.booking)));
        		// console.log("Title changed from " + newOne + " to " + oldOne)
        	},
            // initValue: (newOne, oldOne) => {
            //     console.log(helper.parse(newOne));
        	// 	// console.log("Title changed from " + newOne + " to " + oldOne)
        	// },
    	}
    }
</script>

<style scoped>
    .modal-body{
        position: relative;
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
    .modal-body table{
        width: 100%;
    }
    .modal-body table td{
        /* width: 49.9%; */
    }
    .modal-body table tr:nth-child(odd) td{
        background-color: #f1f1f1;
    }
    .modal-body table td:first-child{
        text-align: right;
        width: 100px;
    }
    .modal-body table td:last-child{
        padding-left: 15px;
    }
</style>