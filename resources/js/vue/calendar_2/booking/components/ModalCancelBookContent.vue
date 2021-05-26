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
            
        },
        props: ['booking'],
        data: function(){
            return {
                cancelButtonDisabled: false,
                successfullyCanceled: false,
                question: null,
                modalTitle: null,
                bTitle: null,
                bTimeMoment: null,
                bDuration: null,
                filters: filters,
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
            // onCancel: function (responseData){
            //     this.$emit('canceled', responseData);
            // },
            cancel: function (){
                let componentApp = this.getParentComponentByName(this, 'app');
                componentApp.cancelBooking(this.booking, (response) => {
                    console.log('success');
                    // this.onCancel(response.data);
                });
            },
        },
        components: {
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
                // console.log(JSON.parse(JSON.stringify(this.booking)));
        		// console.log("Title changed from " + newOne + " to " + oldOne)
        	},
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