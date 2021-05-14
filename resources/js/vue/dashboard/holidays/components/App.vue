<template>
    <div>
        
        <!-- <div v-if="showWarningAlert" class="alert alert-warning" role="alert">
            Selected <b class="text-uppercase">0</b> employees!
        </div> -->
        
        <div>
            <button @click="openModal()" type="button" class="btn btn-primary btn-sm open-select-item-modal">
                Add
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </button>
            
            <div v-for="(item,index) in holidays" class="alert-item alert alert-primary" role="alert">
                <b>
                    {{item.title}}
                </b><br>
                <template v-if="item.from == item.to">
                    on <b>{{item.from}}</b>
                </template>
                <template v-else>
                    from <b>{{item.from}}</b> until <b>{{item.to}}</b>
                </template>
                <br>
                <span v-if="item.description" class="small">{{item.description}}</span>
                
                <input class="" :name="`holiday_title[` + index + `]`" type="hidden" :value="item.title">
                <input class="" :name="`holiday_description[` + index + `]`" type="hidden" :value="item.description">
                <input class="" :name="`holiday_from[` + index + `]`" type="hidden" :value="item.from">
                <input class="" :name="`holiday_to[` + index + `]`" type="hidden" :value="item.to">
                
                <div class="btnns">
                    <a class="btnn btn btn-sm btn-warning" href="#" @click="removeHoliday(index)">
                        Dismiss
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                        </svg>
                    </a>
                    <a class="btnn btn btn-sm btn-info" href="#" @click="editHoliday(index)">
                        Edit
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                        </svg>
                    </a>
                </div>
                
            </div>
        </div>
        
        <div v-if="showModal" class="modal modal-holiday fade modal-custom-dark-header-footer" id="createHolidayModal" tabindex="-1" aria-labelledby="createHolidayModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                    
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="badge-information badge badge-pill text-uppercase"
                            :class="{'badge-info': holidayEditIndex != null, 'badge-success': holidayEditIndex == null}">
                                {{holidayEditIndex == null ? 'new' : 'edit'}}
                        </span>
                        <h5 class="modal-title" id="createHolidayModalLabel">
                            Holiday
                            <span class="badge badge-pill badge-danger text-uppercase"
                                v-if="formHasErrors">Has errors</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button"
                            :class="{'active': showTab == 'main'}"
                            @click="showTab = 'main'"
                            class="btn btn-secondary">
                                Main
                                <span id="mainHolidayErrorBadge"
                                    class="error-badge badge badge-pill badge-danger d-none"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="0 errors">
                                        0
                                </span>
                        </button>
                        <button type="button"
                            :class="{'active': showTab == 'date_range'}"
                            @click="showTab = 'date_range'"
                            class="btn btn-secondary">
                                Date
                                <span id="dateRangeHolidayErrorBadge"
                                    class="error-badge badge badge-pill badge-danger d-none"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="0 errors">
                                        0
                                </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <form id="holidayRangeForm">
                            <div v-show="showTab == 'main'" class="row">
                                <div class="col col-sm-12">
                                    <div class="form-group">
                                        <label for="holidayTitleInput">Title:</label>
                                        <input v-model="holidayTitle" type="text" name="holiday_title" class="form-control" id="holidayTitleInput" aria-describedby="holidayTitleHelp">
                                        <div id="error_holiday_title" class="text-danger small"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="holidayDescriptionInput">Description:</label>
                                        <textarea v-model="holidayDescription" name="holiday_description" class="form-control" id="holidayDescriptionInput" rows="3"></textarea>
                                        <div id="error_holiday_description" class="text-danger small"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-show="showTab == 'date_range'" class="row range-picker">
                                <div class="range-picker-titt">Set holiday period:</div>
                                <div class="col col-sm-6 coll">
                                    <span class="titt">From:</span>
                                    <input type="text"
                                        id="holiday_from"
                                        class="date-chooser"
                                        v-model="from"
                                        name="holiday_from"
                                        autocomplete="off"
                                        readonly="readonly">
                                    <div id="error_holiday_from" class="text-danger small"></div>
                                </div>
                                <div class="col col-sm-6 coll">
                                    <span class="titt">Until:</span>
                                    <input type="text"
                                        id="holiday_to"
                                        class="date-chooser"
                                        v-model="to"
                                        name="holiday_to"
                                        autocomplete="off"
                                        readonly="readonly">
                                    <div id="error_holiday_to" class="text-danger small"></div>
                                </div>
                            </div>
                            
                            <input type="submit" style="position: absolute; left: -9999px"/>
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="holidayEditIndex != null ? updateHoliday() : addHoliday()" type="button" class="btn btn-success">
                            {{holidayEditIndex != null ? 'Update' : 'Add'}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(assignWorkers);
            console.log(JSON.parse(JSON.stringify(validationMessages)));
            // setTimeout(() => {
    		// 	this.setAssignWorkers();
    		// }, 300);
            
            // this.showModal = true;
            // this.openModal();
            
            // this.openModal();
            
            // this.setAssignWorkers();
            // this.recalculateBadgeValue(true);
            if(typeof holidays != 'undefined')
                this.holidays = holidays;
        },
        // props: ['postTitle'],
        data: function(){
            return {
                holidays: [],
                // holidays: [{
                //     from: 'dasdasd',
                //     to: 'dasda',
                //     title: 'ddddd',
                //     description: 'gggg',
                // }],
                holidayEditIndex: null,
                // selectedItems: [],
                // assignWorkers: assignWorkers,
                modalId: "#createHolidayModal",
                formId: "#holidayRangeForm",
                showTab: "main",
                from: null,
                to: null,
                holidayDescription: null,
                holidayTitle: null,
                showModal: false,
                validator: null,
                errors: {
                    mainErrorAttrs: [],
                    dateErrorAttrs: []
                },
                formHasErrors: false,
            };
        },
        computed: {
            // showWarningAlert: function () {
            //     // if(this.items == null || this.selectedItems.length == 0)
            //     //     return false;
            //     // console.log('showWarningAlert');
            //     if(this.isJqueryValidationEnabled())
            //         return this.selectedItems.length == 0;
            //     let assignWorkersIds = this.assignWorkers == null ? [] : Object.keys(this.assignWorkers);
            //     console.log('showWarningAlert');
            //     // console.log(JSON.parse(JSON.stringify(this.assignWorkers)));
            //     return assignWorkersIds.length == 0;
            // },
        },
        methods: {
            initDatePicker: function(){
                let _this = this;
                
                // console.log('initDatePicker');
                
                $(document).ready(function (){
                    var dateFormat = "dd-mm-yy",
                        from = $( "#holiday_from" ).datepicker({
                            defaultDate: "+1w",
                            changeMonth: true,
                            changeYear: true,
                            numberOfMonths: 1,
                            dateFormat: dateFormat,
                            minDate: new Date()
                        }).on( "change", function() {
                            let date = getDate(this);
                            to.datepicker("option", "minDate", date);
                            // _this.fromErr = false;
                            // _this.from = _this.formatDataDateForDateChooser(date);
                            _this.from = helper.formatStatusDate(date);
                            // $(_this.formId).valid();
                            // _this.from = date;
                            $(this).valid();
                        }),
                        to = $( "#holiday_to" ).datepicker({
                            defaultDate: "+1w",
                            changeMonth: true,
                            changeYear: true,
                            numberOfMonths: 1,
                            dateFormat: dateFormat,
                            minDate: new Date()
                        }).on( "change", function() {
                            let date = getDate(this);
                            from.datepicker( "option", "maxDate", date);
                            // _this.toErr = false;
                            // _this.to = _this.formatDataDateForDateChooser(date);
                            // _this.to = date;
                            _this.to = helper.formatStatusDate(date);
                            // $(_this.formId).valid();
                            
                            $(this).valid();
                        });
                        
                        // console.log(_this.holidayEditIndex);
                        if(_this.holidayEditIndex != null){
                            // console.log('maxDate minDate');
                            try{
                                var fromDate = $.datepicker.parseDate(dateFormat, _this.from);
                            }catch( error ) {
                                var fromDate = null;
                            }
                            try{
                                var toDate = $.datepicker.parseDate(dateFormat, _this.to);
                            }catch( error ) {
                                var toDate = null;
                            }
                            from.datepicker( "option", "maxDate", toDate);
                            to.datepicker("option", "minDate", fromDate);
                        }

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
            handleErrorAttrs: function(type, attr){
                let _this = this;
                
                if(['holiday_title','holiday_description'].includes(attr)){
                    if(type == 'add' && !_this.errors.mainErrorAttrs.includes(attr))
                        _this.errors.mainErrorAttrs.push(attr);
                    if(type == 'delete' && _this.errors.mainErrorAttrs.includes(attr)){
                        let index = _this.errors.mainErrorAttrs.indexOf(attr);
                        _this.errors.mainErrorAttrs.splice(index, 1);
                    }
                }
                
                if(['holiday_from','holiday_to'].includes(attr)){
                    if(type == 'add' && !_this.errors.dateErrorAttrs.includes(attr))
                        _this.errors.dateErrorAttrs.push(attr);
                    if(type == 'delete' && _this.errors.dateErrorAttrs.includes(attr)){
                        let index = _this.errors.dateErrorAttrs.indexOf(attr);
                        _this.errors.dateErrorAttrs.splice(index, 1);
                    }
                }
            },
            handleErrorNotices: function(){
                let _this = this;
                
                if(_this.errors.mainErrorAttrs.length > 0){
                    let errorsCount = _this.errors.mainErrorAttrs.length;
                    $('#mainHolidayErrorBadge').removeClass('d-none')
                        .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
                }else{
                    // console.log('no mainErrorsCount');
                    $('#mainHolidayErrorBadge').addClass('d-none').text('');
                }
                
                if(_this.errors.dateErrorAttrs.length > 0){
                    let errorsCount = _this.errors.dateErrorAttrs.length;
                    $('#dateRangeHolidayErrorBadge').removeClass('d-none')
                        .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
                }else{
                    // console.log('no mainErrorsCount');
                    $('#dateRangeHolidayErrorBadge').addClass('d-none').text('');
                }
                
                if(_this.errors.mainErrorAttrs.length > 0 || _this.errors.dateErrorAttrs.length > 0){
                    // _this.showErrorAlert();
                    _this.formHasErrors = true;
                }else{
                    _this.formHasErrors = false;
                }
            },
            initValidator: function(){
                let _this = this;
                
                $.validator.addMethod("holiday_date", function (value, element, len) {
                    return value.match(/\d{2}-\d{2}-\d{4}/i);
                });
                
                $.validator.addMethod("maxlength", function (value, element, len) {
                    return value == "" || value.length <= len;
                });
                
                _this.validator = $(_this.formId).validate({
                    ignore: "",
                    errorPlacement: function(error, element) {
                        let attrName = element.attr("name");
                        let errorId = 'error_' + attrName;
                        let errorText = $(error).text();
                        
                        $("#" + errorId).text(errorText);
                        if(errorText != ''){
                            $("#" + errorId).text(errorText);
                        }else{
                            
                        }
                        
                        _this.handleErrorAttrs((errorText == '' ? 'delete' : 'add'), attrName);
                        _this.handleErrorNotices();
                    },
                    rules: {
                        holiday_title: {
                            required: true,
                            maxlength: 255,
                        },
                        holiday_description: {
                            maxlength: 1000,
                        },
                        holiday_from: {
                            required: true,
                            maxlength: 20,
                            holiday_date: true,
                        },
                        holiday_to: {
                            required: true,
                            maxlength: 20,
                            holiday_date: true,
                        },
                    },
                    // Specify validation error messages
                    messages: {
                        // Main tab rules messages
                        holiday_title: {
                            required: validationMessages.required.replace(':attribute', 'holiday title'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'title')).replace(':max', '255'),
                        },
                        holiday_description: {
                            maxlength: (validationMessages.max.string.replace(':attribute', 'holiday description')).replace(':max', '1000'),
                        },
                        holiday_from: {
                            required: validationMessages.required.replace(':attribute', 'holiday from'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'holiday from')).replace(':max', '20'),
                            holiday_date: validationMessages.regex.replace(':attribute', 'holiday from'),
                        },
                        holiday_to: {
                            required: validationMessages.required.replace(':attribute', 'holiday to'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'holiday to')).replace(':max', '20'),
                            holiday_date: validationMessages.regex.replace(':attribute', 'holiday to'),
                        },
                    },
                    submitHandler: function(form) {
                        // console.log('submitHandler');
                        // form.submit();
                    },
                    success: function(label) {
                        // console.log('success');
                    },
                    invalidHandler: function(form, validator){
                        // _this.resetErrorAttrs();
                        console.log('invalidHandler');
                        // console.log(validator.invalid);
                    }
                });
                
            },
            openModal: function(){
                let _this = this;
                
                _this.showModal = true;
                
                setTimeout(function(){
                 	$(document).find(_this.modalId).modal('show');
                    _this.initDatePicker();
                    _this.initValidator();
                    $(document).find(_this.modalId).off('hidden.bs.modal');
                    $(document).find(_this.modalId).on('hidden.bs.modal', function () {
                        _this.from = null;
                        _this.to = null;
                        _this.holidayTitle = null;
                        _this.holidayDescription = null;
                        _this.formHasErrors = false;
                        
                        _this.holidayEditIndex = null;
                        _this.showTab = "main";
                        
                        _this.errors.mainErrorAttrs = [];
                        _this.errors.dateErrorAttrs = [];
                        
                        _this.showModal = false;
                    });
                }, 100);
            },
            removeHoliday: function(index){
                if(typeof this.holidays[index] == 'undefined' || this.holidays[index] == null)
                    return;
                    
                this.holidays.splice(index, 1);
            },
            editHoliday: function(index){
                let _this = this;
                
                if(typeof _this.holidays[index] == 'undefined' || _this.holidays[index] == null)
                    return;
                
                _this.from = _this.holidays[index].from;
                _this.to = _this.holidays[index].to;
                _this.holidayTitle = _this.holidays[index].title;
                _this.holidayDescription = _this.holidays[index].description;
                _this.holidayEditIndex = index;
                _this.openModal();
            },
            updateHoliday: function(){
                let _this = this;
                
                console.log('updateHoliday');
                
                if($(this.formId).valid() && this.from != null && this.to != null &&
                !isNaN(_this.holidayEditIndex) && typeof _this.holidays[_this.holidayEditIndex] != 'undefined'){
                    _this.holidays[_this.holidayEditIndex].from = this.from;
                    _this.holidays[_this.holidayEditIndex].to = this.to;
                    _this.holidays[_this.holidayEditIndex].title = this.holidayTitle;
                    _this.holidays[_this.holidayEditIndex].description = this.holidayDescription;
                    
                    $(document).find(this.modalId).modal('hide');
                }
            },
            addHoliday: function(){
                let _this = this;
                
                if($(this.formId).valid() && this.from != null && this.to != null){
                    _this.holidays.push({
                        from: this.from,
                        to: this.to,
                        title: this.holidayTitle,
                        description: this.holidayDescription,
                    });
                    
                    $(document).find(this.modalId).modal('hide');
                }
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .alert-item{
        margin-bottom: 0px!important;
        margin-top: 10px!important;
        .btnns{
            position: absolute;
            top: 0px;
            right: 0px;
            padding-top: 7px;
            padding-right: 5px;
            .btnn{
                float: right;
                margin-left: 8px;
            }
        }
    }
</style>

<style lang="scss">
.modal-holiday{
    .modal-content{
        // .modal-footer{
        //     .disabled{
        //         cursor: not-allowed;
        //     }
        //     .dropdown-menu{
        //         padding: 10px;
        //         text-align: center;
        //         min-width: 200px;
        //         top: -4px!important;
        //     }
        // }
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
                            &:focus, &.error{
                                background-color: #fff!important;
                            }
                            &.error, &.error:focus{
                                box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                                -webkit-box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                                -moz-box-shadow: 0px 0px 5px 0px rgba(198,71,70,0.3);
                                border: 2px solid rgba(198,71,70,0.6)!important;
                            }
                            &:focus{
                                box-shadow: 0px 0px 5px 0px rgba(70,198,85,0.3);
                                -webkit-box-shadow: 0px 0px 5px 0px rgba(70,198,85,0.3);
                                -moz-box-shadow: 0px 0px 5px 0px rgba(70,198,85,0.3);
                                border: 2px solid rgba(70,198,85,0.6)!important;
                            }
                        }
                    }
                }
            }
        }
    }
}

    // h5{
    //     position: relative;
    //     top: 5px;
    // }
    // #itemAssignmentModal ul{
    //     padding: 0px;
    //     margin: 0px;
    // }
    // #itemAssignmentModal ul li{
    //     padding: 0px;
    //     margin: 0px;
    //     list-style: none;
    // }
    // .open-select-item-modal{
    //     svg{
    //         position: relative;
    //         top: -2px;
    //     }
    // }
    // .assign-item{
    //     display: none;
    // }
    // .alert-item{
    //     margin-bottom: 0px;
    //     margin-top: 10px;
    //     line-height: 1em;
    //     span{
    //         &.small{
    //             line-height: 1em;
    //         }
    //     }
    //     .close{
    //         position: absolute;
    //         top: 5px;
    //         right: 10px;
    //     }
    // }
    // table.all-items-list{
    //     width: 100%;
    //     tr{
    //         td{
    //             vertical-align: top;
    //             line-height: 1em;
    //             border-top: 1px solid #dee2e6;
    //             label{
    //                 cursor: pointer;
    //             }
    //             &:first-child{
    //                 width: 40px;
    //                 padding-left: 14px;
    //             }
    //             &:last-child{
    //                 padding-top: 7px;
    //                 label{
    //                     display: block;
    //                     width: 100%;
    //                 }
    //             }
    //         }
    //         &:nth-child(odd){
    //             td{
    //                 background-color: rgba(0,0,0,.05);
    //             }
    //         }
    //         &:last-child{
    //             td{
    //                 border-bottom: 1px solid #dee2e6;
    //             }
    //         }
    //     }
    // }
    /* .card-body{
        padding: 0px;
    }
    .card-padding{
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    } */
</style>