<template>
    <div>
        
        <div class="alert"
            :class="{
                'alert-success': !isCurrentlySuspended && !isCurrentlyFuterSuspension,
                'alert-danger': isCurrentlySuspended,
                'alert-warning': isCurrentlyFuterSuspension
            }"
            role="alert">
                <template v-if="!isCurrentlySuspended && !isCurrentlyFuterSuspension">
                    Hall is <b class="text-uppercase">opened</b>
                </template>
                <template v-if="isCurrentlyTotallySuspended">
                    Hall is <b>completely suspended</b>
                </template>
                <template v-if="isCurrentlyPeriodSuspended">
                    Hall is <b>suspended</b> for period<br>
                    from <b>{{suspensionFrom}}</b> until <b>{{suspensionTo}}</b>
                </template>
                <template v-if="isCurrentlyFuterSuspension">
                    Hall is <b class="text-uppercase text-success">opened</b><br>
                    but will be suspended for period<br>
                    from <b>{{suspensionFrom}}</b> until <b>{{suspensionTo}}</b>
                </template>
        </div>
        
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
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                    {{status.title}}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                        name="from"
                        autocomplete="off"
                        readonly="readonly">
                </div>
                <div class="coll col col-sm-4">
                    <input type="text"
                        id="to"
                        class="date-chooser"
                        v-model="to"
                        name="to"
                        autocomplete="off"
                        readonly="readonly">
                </div>
                <!-- <div class="coll col col-sm-4">
                    <button id="periodSuspendBtn"
                        ref="periodSuspendBtn"
                        data-toggle="dropdown"
                        @click.prevent="!isSuspendRangeSetted && setFromAndToErrors()"
                        type="button"
                        :class="{disabled: periodSuspendBtnDisabled}"
                        :disabled="periodSuspendBtnDisabled"
                        class="btn btn-warning">
                            {{isCurrentlySuspended || isCurrentlyFuterSuspension ? 'Save period' : 'Suspend for a period'}}
                    </button>
                </div> -->
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
            console.log(JSON.parse(JSON.stringify(this.hall)));
            console.log(JSON.parse(JSON.stringify(this.suspension)));
            // console.log(this.hall);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                currentDate: new Date(),
                hall: hall,
                // suspension: typeof suspension != undefined && suspension != null ? suspension : null,
                suspension: suspension,
                status: {
                    type: 'open',
                    title: 'Open'
                },
                statuses: [
                    {
                        type: 'disable',
                        title: 'Open'
                    },
                    {
                        type: 'complete',
                        title: 'Suspend completely'
                    },
                    {
                        type: 'period',
                        title: 'Suspend for period'
                    },
                ],
                
                from: null,
                to: null,
                
                fromErr: null,
                toErr: null,
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
            setTabBadgeStatus: function(){
                if(this.isCurrentlySuspended){
                    let title = this.isCurrentlyTotallySuspended ?
                        'Hall is currently suspended' :
                        'Hall is currently suspended for period<br>from <b>' +
                        this.formatDataDateForDateChooser(this.suspension.from) +
                        '</b><br>until <b>' +
                        this.formatDataDateForDateChooser(this.suspension.to) + '</b>';
                        
                    $("#suspension-tab").append(`
                        <span class="badge badge-pill badge-danger" data-toggle="tooltip" title="${title}">
                            &#x2715;
                        </span>
                    `);
                }else{
                    let title = this.isCurrentlyFuterSuspension ?
                        `Hall is currently opened<br>But will be suspended<br>from <b>` + this.formatDataDateForDateChooser(this.suspension.from) +
                        `</b><br>until <b>` + this.formatDataDateForDateChooser(this.suspension.to) + `</b>` :
                        `Hall is currently opened`;
                    let badgeClass = this.isCurrentlyFuterSuspension ? 'badge-warning' : 'badge-success';
                    let badgeCont = this.isCurrentlyFuterSuspension ? '!' : '&#10003;';
                    $("#suspension-tab").append(`
                        <span class="badge badge-pill ${badgeClass}" data-toggle="tooltip" title="${title}">
                            ${badgeCont}
                        </span>
                    `);
                }
            },
            switchStatus: function(status){
                this.status = status;
                console.log(this.status);
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
                            _this.fromErr = false;
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
                            _this.toErr = false;
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
            // status: function (val) {
            //     console.log(status);
            //     if(status.type == 'suspend_period')
            //         setTimeout(() => {
            //             this.initDatePicker();
            //         }, 1000);
            //         // this.initDatePicker();
            // },
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