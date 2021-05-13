<template>
    <div>
        
        <!-- <div class="alert"
            :class="{
                'alert-success': !isCurrentlySuspended && !isCurrentlyFuterSuspension,
                'alert-danger': isCurrentlySuspended,
                'alert-warning': isCurrentlyFuterSuspension
            }"
            role="alert">
                <template v-if="!isCurrentlySuspended && !isCurrentlyFuterSuspension">
                    <b class="text-uppercase">Active</b>
                </template>
                <template v-if="isCurrentlyTotallySuspended">
                    <b>Completely suspended</b>
                </template>
                <template v-if="isCurrentlyPeriodSuspended">
                    <b>Suspended</b> for period<br>
                    from <b>{{suspensionFrom}}</b> until <b>{{suspensionTo}}</b>
                </template>
                <template v-if="isCurrentlyFuterSuspension">
                    <b class="text-uppercase text-success">Active</b><br>
                    but will be suspended for period<br>
                    from <b>{{suspensionFrom}}</b> until <b>{{suspensionTo}}</b>
                </template>
        </div> -->
        
        <input type="hidden"
            id="status"
            v-model="status.type"
            class="date-chooser"
            name="status"
            autocomplete="off"
            readonly="readonly">

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle"
                :class="{
                    'btn-success': status.type == 'disable',
                    'btn-danger': status.type == 'complete',
                    'btn-warning': status.type == 'period',
                }"
                type="button"
                id="statusDropdownMenuBtn"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                :data-status="status.type">
                    {{status.title}}
            </button>
            <div class="dropdown-menu" aria-labelledby="statusDropdownMenuBtn">
                <a v-for="st in statuses"
                    @click.prevent="switchStatus(st)"
                    class="dropdown-item"
                    href="#">{{st.title}}</a>
            </div>
        </div>
        
        <div v-show="status.type == 'period'" class="range-picker">
            <div class="row">
                
                <div class="coll col col-sm-4">
                    <span>From:</span>
                </div>
                <div class="coll col col-sm-4">
                    <span>To:</span>
                </div>
                <div class="coll col col-sm-4"></div>
                
            </div>
            <div class="row">
                
                <div class="coll col col-sm-4">
                    <input type="text"
                        id="from"
                        class="date-chooser"
                        v-model="from"
                        name="suspend_from"
                        autocomplete="off"
                        readonly="readonly"><br>
                    <span id="error_suspend_from" class="small text-danger">{{fromErr ? fromErr : ""}}</span>
                </div>
                <div class="coll col col-sm-4">
                    <input type="text"
                        id="to"
                        class="date-chooser"
                        v-model="to"
                        name="suspend_to"
                        autocomplete="off"
                        readonly="readonly"><br>
                    <span id="error_suspend_to" class="small text-danger">{{toErr ? toErr : ""}}</span>
                </div>
                
            </div>
        </div>
                
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                
                <!-- <modal-info-content :data="infoModalData" v-if="showContent == 'info'"></modal-info-content>
                <modal-suspension-content :data="suspendModalData"
                    @changed="updateData"
                    v-if="showContent == 'suspension'"></modal-suspension-content> -->
                
            </div>
        </div>
        
    </div>
</template>

<script>
    // import ModalInfoContent from "./ModalInfoContent.vue";
    // import ModalSuspensionContent from "./ModalSuspensionContent.vue";
    export default {
        name: 'app',
        mounted() {
            this.setCurrentStatus();
            this.setTabBadgeStatus();
            this.setFromAndToVars();
            this.initDatePicker();
            // this.initDataTable();
            // this.regClickMoreInfoBtn();
            // console.log(JSON.parse(JSON.stringify(this.hall)));
            // console.log(JSON.parse(JSON.stringify(this.suspension)));
            if(this.oldSuspension != null)
                this.setCurrentStatusFromOldData();
            // console.log(typeof suspension != undefined && suspension != null ? suspension : null);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                currentDate: new Date(),
                suspension: typeof suspensionDB == 'undefined' || suspensionDB == null ? null : suspensionDB,
                oldSuspension: typeof oldSuspension == 'undefined' || oldSuspension == null ? null : oldSuspension,
                // suspension: suspension,
                status: {
                    type: 'disable',
                    title: 'Active'
                },
                statuses: [
                    {
                        type: 'disable',
                        title: 'Active'
                    },
                    {
                        type: 'complete',
                        title: 'Completely suspended'
                    },
                    {
                        type: 'period',
                        title: 'Suspended for period'
                    },
                ],
                
                from: null,
                to: null,
                
                fromErr: typeof fromErr == 'undefined' || fromErr == null ? null : fromErr,
                toErr: typeof toErr == 'undefined' || toErr == null ? null : toErr,
            };
        },
        computed: {
            // periodSuspendBtnDisabled: function(){
            //     // console.log(this.status.type == 'suspend_period' &&
            //     //     this.formatDataDateForDateChooser(this.suspension.from) == this.from &&
            //     //     this.formatDataDateForDateChooser(this.suspension.to) == this.to);
            //     return (this.status.type == 'suspend_period' &&
            //         (this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension) &&
            //         this.formatDataDateForDateChooser(this.suspension.from) == this.from &&
            //         this.formatDataDateForDateChooser(this.suspension.to) == this.to);
            // },
            isCurrentlyTotallySuspended: function(){
                if(this.suspension == null)
                    return false;
                
                if(this.suspension.from == null && this.suspension.to == null)
                    return true;
                return false;
            },
            isCurrentlyPeriodSuspended: function(){
                if(this.suspension == null)
                    return false;
                
                if(this.suspension.from != null && this.suspension.to != null && this.isCurrentDateInRange(this.suspension.from, this.suspension.to))
                    return true;
                return false;
            },
            isCurrentlyFuterSuspension: function(){
                if(this.suspension == null)
                    return false;
                
                if(this.suspension.from != null && this.suspension.to != null && !this.isCurrentDateInRange(this.suspension.from, this.suspension.to))
                    return true;
                return false;
            },
            isCurrentlySuspended: function(){
                if(this.suspension == null)
                    return false;
                
                if(this.suspension.from == null && this.suspension.to == null)
                    return true;
                    
                if(this.suspension.from != null && this.suspension.to != null && !this.isCurrentDateInRange(this.suspension.from, this.suspension.to))
                    return false;
                return true;
            },
            suspensionFrom: function(){
                // return this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension ? moment(this.suspension.from).format('YYYY-MM-DD') : null;
                return this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension ?
                    this.formatDataDateForDateChooser(this.suspension.from) : null;
            },
            suspensionTo: function(){
                // return this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension ? moment(this.suspension.to).format('YYYY-MM-DD') : null;
                return this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension ?
                    this.formatDataDateForDateChooser(this.suspension.to) : null;
            }
        },
        methods: {
            isJqueryValidationEnabled: function(){
                return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            validateTo: function(){
                if(this.isJqueryValidationEnabled())
                    $('#to').valid();
            },
            validateFrom: function(){
                this.isJqueryValidationEnabled()
                    $('#from').valid();
            },
            setFromAndToVars: function(){
                if(this.isCurrentlyPeriodSuspended || this.isCurrentlyFuterSuspension){
                    this.from = this.formatDataDateForDateChooser(this.suspension.from);
                    this.to = this.formatDataDateForDateChooser(this.suspension.to);
                }
            },
            // setFromAndToErrors: function(){
            //     if(this.from == null)
            //         this.fromErr = true;
            //     if(this.to == null)
            //         this.toErr = true;
            // },
            // isSuspendRangeSetted: function(){
            //     return this.from != null && this.to != null;
            // },
            setCurrentStatus: function(status){
                if(!this.isCurrentlySuspended && !this.isCurrentlyFuterSuspension){
                    this.status = this.statuses[0];
                }else if(this.isCurrentlyTotallySuspended){
                    this.status = this.statuses[1];
                }else{
                    this.status = this.statuses[2];
                }
            },
            setCurrentStatusFromOldData: function(){
                // console.log(JSON.parse(JSON.stringify(this.suspension)));
                if(this.oldSuspension.status == 'disable'){
                    console.log(JSON.parse(JSON.stringify(this.statuses[0])));
                    this.status = JSON.parse(JSON.stringify(this.statuses[0]));
                }
                if(this.oldSuspension.status == 'complete'){
                    console.log(JSON.parse(JSON.stringify(this.statuses[1])));
                    this.status = JSON.parse(JSON.stringify(this.statuses[1]));
                }
                if(this.oldSuspension.status == 'period'){
                // this.oldSuspension.suspend_from != null && this.oldSuspension.suspend_to != null){
                    console.log(JSON.parse(JSON.stringify(this.statuses[2])));
                    this.status = JSON.parse(JSON.stringify(this.statuses[2]));
                    
                    if(this.oldSuspension.suspend_from != null){
                        this.from = this.oldSuspension.suspend_from;
                    }
                    if(this.oldSuspension.suspend_to != null){
                        this.to = this.oldSuspension.suspend_to;
                    }
                    // if(this.oldSuspension.suspend_from != null){
                    //     this.from = this.oldSuspension.suspend_from;
                    // }else{
                    //     this.fromErr = 'Required';
                    // }
                    // 
                    // if(this.oldSuspension.suspend_to != null){
                    //     this.to = this.oldSuspension.suspend_to;
                    // }else{
                    //     this.toErr = 'Required';
                    // }
                }
            },
            setTabBadgeStatus: function(){
                if(this.isCurrentlySuspended){
                    let title = this.isCurrentlyTotallySuspended ?
                        'Completely suspended' :
                        'Suspended for period<br>from <b>' +
                        this.formatDataDateForDateChooser(this.suspension.from) +
                        '</b><br>until <b>' +
                        this.formatDataDateForDateChooser(this.suspension.to) + '</b>';
                        
                    $("#suspension-tab").prepend(`
                        <span class="text-danger" data-toggle="tooltip" title="${title}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-stop-fill" viewBox="0 0 16 16">
                                <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                            </svg>
                        </span>
                    `);
                }else{
                    let title = this.isCurrentlyFuterSuspension ?
                        `Active<br>Will be suspended<br>from <b>` + this.formatDataDateForDateChooser(this.suspension.from) +
                        `</b><br>until <b>` + this.formatDataDateForDateChooser(this.suspension.to) + `</b>` :
                        `Active`;
                    let textClass = this.isCurrentlyFuterSuspension ? 'text-warning' : 'text-success';
                    let badgeCont = this.isCurrentlyFuterSuspension ?
                        `
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        ` :
                        `
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                            </svg>
                        `;
                    $("#suspension-tab").prepend(`
                        <span class="${textClass}" data-toggle="tooltip" title="${title}">
                            ${badgeCont}
                        </span>
                    `);
                }
            },
            triggerFormValidation: function(){
                if(this.isJqueryValidationEnabled()){
                    setTimeout(function(){
                        console.log('triggerFormValidation');
                     	// $('form#workerForm').valid();
                        jqueryValidation.triggerFormValidation();
                    }, 50);
                }
            },
            addStatusValidationRules: function(){
                if(this.isJqueryValidationEnabled()){
                    setTimeout(function(){
                        console.log('addStatusValidationRules');
                        jqueryValidation.addStatusRules();
                    }, 50);
                }
            },
            switchStatus: function(status){
                this.status = status;
                if(this.status.type == 'period'){
                    // this.triggerFormValidation();
                    this.addStatusValidationRules();
                }else{
                    if(this.isJqueryValidationEnabled()){
                        $("#statusErrorBadge").addClass('d-none');
                        $('#error_suspend_from').text('');
                        $('#error_suspend_to').text('');
                    }
                    // this.from = null;
                    // this.to = null;
                }
                // console.log(JSON.parse(JSON.stringify(this.status)));
                // console.log(this.status);
            },
            isCurrentDateInRange: function(from, to){
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(from);
                let toMoment = moment(to);
                return (currentDateMoment.diff(fromMoment) >= 0 && currentDateMoment.diff(toMoment) <= 0);
            },
            initDatePicker: function(){
                let _this = this;
                $(document).ready(function (){
                    var dateFormat = "dd-mm-yy",
                        from = $( "#from" ).datepicker({
                            // defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 1,
                            dateFormat: dateFormat,
                            minDate: new Date()
                        }).on( "change", function() {
                            let date = getDate(this);
                            to.datepicker("option", "minDate", date);
                            // _this.fromErr = false;
                            _this.from = _this.formatDataDateForDateChooser(date);
                        }),
                        to = $( "#to" ).datepicker({
                            // defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 1,
                            dateFormat: dateFormat,
                            minDate: new Date()
                        }).on( "change", function() {
                            let date = getDate(this);
                            from.datepicker( "option", "maxDate", date);
                            // _this.toErr = false;
                            _this.to = _this.formatDataDateForDateChooser(date);
                        });

                        function getDate( element ) {
                            var date;
                            try {
                                date = $.datepicker.parseDate( dateFormat, element.value );
                            } catch( error ) {
                                date = null;
                            }

                            return date;
                        }
                });
            },
        },
        components: {
            // ModalInfoContent,
            // ModalSuspensionContent
        },
        watch: {
            to: function(val){
                // this.triggerFormValidation();
                this.validateTo();
                // $('#to').valid();
            },
            from: function(val){
                // this.triggerFormValidation();
                this.validateFrom();
                // $('#from').valid();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .range-picker{
        padding-top: 10px;
        max-width: 900px;
        input{
            &.date-chooser{
                // width: 160px;
                width: 100%;
                text-align: center;
                font-weight: bold;
                color: #666;
                border-radius: 3px;
                // background-color: #e5e5e5;
                background-color: #f6f6f6!important;
                border: 2px solid #e4e4e4;
                // color: white;
                cursor: pointer;
                font-size: 20px;
                outline: none;
                &:focus{
                    background-color: #fff!important;
                    border: 2px solid rgba(198,71,70,0.6)!important;
                    -webkit-box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                    -moz-box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                    box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                }
            }
        }
    }
</style>