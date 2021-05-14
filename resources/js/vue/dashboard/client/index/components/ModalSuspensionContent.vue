<template>
    <div>
        
        <div class="modal-content">
            <div class="modal-header">
                <span class="badge-information badge badge-pill badge-info text-uppercase">
                        Client suspension
                </span>
                <h5 class="modal-title" id="modalLabel">
                    {{fullName}}
                    
                    <span class="badge badge-pill badge-danger text-uppercase"
                        v-if="suspended"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle"
                        :data-original-title="getTooltipStatusTitle">suspended</span>
                    <span class="badge badge-pill badge-warning text-uppercase"
                        v-if="!suspended && suspentionInFuture"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle"
                        :data-original-title="getTooltipStatusTitle">active</span>
                    <span class="badge badge-pill badge-success text-uppercase"
                        v-if="!suspended && !suspentionInFuture"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle"
                        :data-original-title="getTooltipStatusTitle">active</span>
                        
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div :class="{
                        'alert-danger': suspended,
                        'alert-success': !suspended && !suspentionInFuture,
                        'alert-warning': !suspended && suspentionInFuture
                    }"
                    v-if="completelySuspended || suspentionInFuture"
                    class="alert"
                    role="alert">
                        <template v-if="completelySuspended"><b>Completely suspended!</b></template>
                        <template v-if="periodSuspended"><b>Suspended!</b><br>From <b>{{dataFrom}}</b> until <b>{{dataTo}}</b></template>
                        <template v-if="!suspended && !suspentionInFuture"><b>Hall is opened</b></template>
                        <template v-if="!suspended && suspentionInFuture">
                            <b>Will be suspended!</b><br>From <b>{{dataFrom}}</b> until <b>{{dataTo}}</b>
                        </template>
                </div>

                <div class="row range-picker">
                    <div class="range-picker-titt">Set suspend period:</div>
                    <div class="col col-sm-6 coll">
                        <span class="titt">From:</span>
                        <input type="text"
                            id="from"
                            class="date-chooser"
                            v-model="from"
                            name="from"
                            autocomplete="off"
                            readonly="readonly">
                        <div v-if="fromErr" class="text-danger small">
                            Required
                        </div>
                    </div>
                    <div class="col col-sm-6 coll">
                        <span class="titt">Until:</span>
                        <input type="text"
                            id="to"
                            class="date-chooser"
                            v-model="to"
                            name="to"
                            autocomplete="off"
                            readonly="readonly">
                        <div v-if="toErr" class="text-danger small">
                            Required
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <!-- <button v-if="suspended" id="disableSuspendBtn" type="button" class="btn btn-success">Open</button> -->
                
                <div class="dropup" v-if="suspended || suspentionInFuture">
                    <button ref="disableSuspendBtn"
                        id="disableSuspendBtn"
                        data-toggle="dropdown"
                        type="button"
                        class="btn btn-success">
                            <template v-if="suspended">
                                Resume
                            </template>
                            <template v-else>
                                Remove period
                            </template>
                    </button>
                    <div @click.stop class="dropdown-menu" aria-labelledby="disableSuspendBtn">
                        <template v-if="suspended">Do you realy want terminate suspension?</template>
                        <template v-else>Do you realy want remove period for future suspension?</template>
                        <br>
                        <a @click.prevent="disableSuspend()" class="btn btn-link" href="#">Yes</a>
                        <a @click.prevent="$refs.disableSuspendBtn.click()" class="btn btn-link" href="#">No</a>
                    </div>
                </div>
                    
                <div class="dropup" v-if="!suspended || periodSuspended">
                    <button ref="completelySuspendBtn"
                        id="completelySuspendBtn"
                        data-toggle="dropdown"
                        type="button"
                        class="btn btn-danger">Suspend completely</button>
                    <div @click.stop class="dropdown-menu" aria-labelledby="completelySuspendBtn">
                        Do you realy want completely suspend this hall?<br>
                        <a @click.prevent="completelySuspend()" class="btn btn-link" href="#">Yes</a>
                        <a @click.prevent="$refs.completelySuspendBtn.click()" class="btn btn-link" href="#">No</a>
                    </div>
                </div>
                
                <div class="dropup">
                    <button id="periodSuspendBtn"
                        ref="periodSuspendBtn"
                        data-toggle="dropdown"
                        @click.prevent="!isSuspendRangeSetted && setFromAndToErrors()"
                        type="button"
                        :class="{disabled: periodSuspendBtnDisabled}"
                        :disabled="periodSuspendBtnDisabled"
                        class="btn btn-warning">
                            {{periodSuspended || suspentionInFuture ? 'Save period' : 'Suspend for a period'}}
                    </button>
                    <div @click.stop class="dropdown-menu dropdown-menu-right" aria-labelledby="periodSuspendBtn">
                        
                        <template v-if="!isSuspendRangeSetted">
                            <div class="text-danger">Please fill required fields!</div>
                        </template>
                        <template v-else>
                            <template v-if="periodSuspended || suspentionInFuture">
                                Do you realy want to save suspension period?<br>
                                <a @click.prevent="periodSuspend()" class="btn btn-link" href="#">Yes</a>
                                <a @click.prevent="$refs.periodSuspendBtn.click()" data-toggle="dropdown" class="btn btn-link" href="#">No</a>
                            </template>
                            <template v-else>
                                Do you realy want to set a suspension period?<br>
                                <a @click.prevent="periodSuspend()" class="btn btn-link" href="#">Yes</a>
                                <a @click.prevent="$refs.periodSuspendBtn.click()" data-toggle="dropdown" class="btn btn-link" href="#">No</a>
                            </template>
                        </template>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'modalSuspensionContent',
        mounted() {
            this.initDatePicker();
            this.setFromAndToVars();
            
            $('[data-toggle="modal-info-dropdown"]').tooltip({ html: true });
            
            console.log(this.data);
        },
        props: ['data'],
        data: function(){
            return {
                showTab: 'main',
                from: null,
                to: null,
                fromErr: null,
                toErr: null,
                // helper: helper,
            };
        },
        computed: {
            getTooltipStatusTitle: function(){
                return helper.getStatusTooltipTitle(typeof this.data.suspension == 'undefined' ? null : this.data.suspension);
            },
            isSuspendRangeSetted: function(){
                return this.from != null && this.to != null;
            },
            dataFrom: function(){
                return this.periodSuspended || this.suspentionInFuture ? this.formatDataDateForDateChooser(this.data.suspension.from) : null;
            },
            dataTo: function(){
                return this.periodSuspended|| this.suspentionInFuture ? this.formatDataDateForDateChooser(this.data.suspension.to) : null;
            },
            suspended: function(){
                return this.isStatus('suspended');
            },
            suspentionInFuture: function(){
                return this.isStatus('future_suspension');
            },
            periodSuspended: function(){
                return this.isStatus('period_suspended');
            },
            completelySuspended: function(){
                return this.suspended && !this.periodSuspended;
            },
            periodSuspendBtnDisabled: function(){
                return ((this.periodSuspended || this.suspentionInFuture) &&
                    this.formatDataDateForDateChooser(this.data.suspension.from) == this.from &&
                    this.formatDataDateForDateChooser(this.data.suspension.to) == this.to) ||
                    (this.data == null);
            },
            suspendUrl: function(){
                // console.log(this.data.id);
                return this.data != null ? toggleSuspension.replace(':id', this.data.id) : null;
            },
            fullName: function(){
                let fullNameArr = [];
                if(!helper.isPropEmpty(this.data.first_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(this.data.first_name));
                if(!helper.isPropEmpty(this.data.last_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(this.data.last_name));
            
                return fullNameArr.join(' ');
            },
        },
        methods: {
            isStatus: function(type){
                return helper.isStatus(type, (typeof this.data.suspension == 'undefined' ? null : this.data.suspension));
            },
            // setSuspendUrl: function(){
            //     this.suspendUrl = toggleSuspension.replace(':id', this.data.id);
            // },
            disableSuspend: function(){
                console.log('disableSuspend');
                this.$refs.disableSuspendBtn.click();
                axios.post(this.suspendUrl, {
                    type: 'disable'
                }).then((response) => {
                    this.$emit('changed');
                }).catch((error) => {
                    
                }).then(() => {
                    
                });
            },
            completelySuspend: function(){
                console.log('completelySuspend');
                this.$refs.completelySuspendBtn.click();
                axios.post(this.suspendUrl, {
                    type: 'complete'
                }).then((response) => {
                    this.$emit('changed');
                }).catch((error) => {
                    
                }).then(() => {
                    
                });
            },
            setFromAndToErrors: function(){
                if(this.from == null)
                    this.fromErr = true;
                if(this.to == null)
                    this.toErr = true;
            },
            periodSuspend: function(){
                if(!this.isSuspendRangeSetted){
                    this.setFromAndToErrors();
                }else{
                    console.log('periodSuspend');
                    this.$refs.periodSuspendBtn.click();
                    axios.post(this.suspendUrl, {
                        type: 'period',
                        from: this.from,
                        to: this.to
                    }).then((response) => {
                        this.$emit('changed');
                    }).catch((error) => {
                        
                    }).then(() => {
                        
                    });
                }
            },
            setFromAndToVars: function(){
                if(this.periodSuspended || this.suspentionInFuture){
                    // console.log('suspentionInFuture');
                    this.from = this.formatDataDateForDateChooser(this.data.suspension.from);
                    this.to = this.formatDataDateForDateChooser(this.data.suspension.to);
                    // console.log(this.from, this.to);
                }else{
                    this.from = null;
                    this.to = null;
                }
            },
            // formatDataDateForDateChooser: function(dataDate){
            //     return moment(dataDate).format('DD-MM-YYYY');
            // },
            initDatePicker: function(){
                let _this = this;
                $(document).ready(function (){
                    var dateFormat = "dd-mm-yy",
                        from = $( "#from" ).datepicker({
                            defaultDate: "+1w",
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
                            defaultDate: "+1w",
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
        watch: {
            data: function () {
                this.setFromAndToVars();
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .modal-content{
        .modal-footer{
            .disabled{
                cursor: not-allowed;
            }
            .dropdown-menu{
                padding: 10px;
                text-align: center;
                min-width: 200px;
                top: -4px!important;
            }
        }
        .modal-body{
            .range-picker{
                padding-bottom: 20px;
                // background-color: red;
                margin-left: 0px;
                margin-right: 0px;
                border: 1px solid #e4e4e4;
                border-radius: 3px;
                position: relative;
                margin-top: 30px;
                background-color: #e4e4e4;
                padding-top: 5px;
                .range-picker-titt{
                    // background-color: #e4e4e4;
                    padding: 0px 6px;
                    height: 24px;
                    line-height: 24px;
                    position: absolute;
                    top: -24px;
                    left: 10px;
                    z-index: 99;
                    border-radius: 3px 3px 0px 0px;
                    // font-weight: bold;
                }
                .coll{
                    .titt{
                        display: block;
                        // text-transform: uppercase;
                        // font-weight: bold;
                        
                        // display: inline-block;
                        // width: 60px;
                        // text-align: right;
                    }
                    .small{
                        position: absolute;
                        margin-top: -2px;
                    }
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
            }
        }
    }
</style>