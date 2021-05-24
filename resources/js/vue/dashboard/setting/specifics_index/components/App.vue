<template>
    <div class="template-specifics">
        
        <div>
            <div class="specifics-handles">
                
                <search v-if="!areFieldsEmpty" @search="search" />
                <div class="float-left small" v-if="areFieldsEmpty">
                    No specifics to show
                </div>
                
                <button @click="openModal()" type="button" class="btn btn-primary btn-sm open-select-item-modal float-right">
                    Add
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                
                <!-- <button type="submit" class="btn btn-sm btn-success float-right">Save</button> -->
                
                <div class="clearfix"></div>
                <div v-if="searchCountFoundResults !== null">Found {{searchCountFoundResults}} results</div>
            </div>
            
            <list @addBtnClick="listItemAddBtnClick"
                :deep="0"
                :search="toSearch"
                @editBtnClick="listItemEditBtnClick"
                @removeBtnClick="listItemRemoveBtnClick"
                :fields="fields" />
        </div>
        
        <!-- Modal -->
        <div v-if="renderModal" class="specific-modal modal fade modal-custom-dark-header-footer" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">New specific</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <form :id="formId">
                            
                            <div class="input-group mb-3 pt-1 pb-1"
                                :class="{'tooltip-init': fields.length == 0}"
                                data-placement="top"
                                title="Tooltip on top">
                                    <dropdown :fields="fields"
                                        :edited-field="editedField"
                                        :picked-field="pickedField"
                                        @changed="dropdownChanged"/>
                            </div>
                            
                            <div class="form-group">
                                <label for="modalInputTitle">Title</label>
                                <input v-model="modalTitleVal"
                                    name="modal_title_val"
                                    type="text"
                                    class="form-control"
                                    id="modalInputTitle"
                                    aria-describedby="error_modal_title_val">
                                <div id="error_modal_title_val"
                                    class="form-text small text-danger"></div>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label for="modalInputKey">Key (required when creating)</label>
                                <input v-model="modalKeyVal"
                                    name="modal_key_val"
                                    type="text"
                                    class="form-control"
                                    id="modalInputKey"
                                    @keyup.prevent="keyChanged($event)"
                                    aria-describedby="error_modal_key_val">
                                <div id="error_modal_key_val"
                                    class="form-text small text-danger"></div>
                            </div> -->
                            
                            <div class="form-group">
                                <label for="modalTextareaDescription">Description</label>
                                <textarea  name="modal_description_val" v-model="modalDescriptionVal" class="form-control" id="modalTextareaDescription" rows="3"></textarea>
                                <div id="error_modal_description_val"
                                    class="form-text small text-danger"></div>
                            </div>
                            
                        </form>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click.prevent="triggerSubmit"
                            type="button"
                            class="btn btn-primary"
                            :class="{'btn-primary': !editedField, 'btn-info': editedField}">
                                {{!editedField ? 'Add' : 'Save'}}
                        </button>
                        <!-- <button type="submit" class="btn btn-primary">Add</button> -->
                        <!-- <button @click.prevent="addSpecific" type="button" class="btn btn-primary">Add</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Dropdown from "./Dropdown.vue";
    import Search from "./Search.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(specifics)));
        },
        // props: ['formId'],
        data: function(){
            return {
                formId: 'addEditForm',
                validator: null,
                
                modalTitleVal: null,
                modalIdVal: null,
                modalDescriptionVal: null,
                
                editedField: false,
                
                pickedField: null,
                renderModal: false,
                modalId: 'createSpecificModal',
                fields: specifics,
                
                dropdownRelation: null,
                
                toSearch: null,
                searchCountFoundResults: null,
            };
        },
        computed: {
            areFieldsEmpty: function(){
                return !Array.isArray(this.fields) || this.fields.length === 0;
            },
        },
        methods: {
            triggerSubmit: function(){
                $(document).find('#' + this.formId).submit();
            },
            search: function(event){
                this.toSearch = event;
                // console.log(JSON.parse(JSON.stringify(event)));
            },
            listItemRemoveBtnClick: function(event){
                let _this = this;
                
                console.log('listItemRemoveBtnClick');
                
                axios.post(showUrl, {
                    id: event.field.id,
                    action: 'remove',
                })
                .then(function (response) {
                    console.log(JSON.parse(JSON.stringify(response.data)));
                    _this.setFields(response.data);
                    // $("#" + _this.modalId).modal('hide');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listItemEditBtnClick: function(event){
                // console.log(JSON.parse(JSON.stringify(event)));
                // return;
                if(typeof event.parentAddEventData !== 'undefined' && event.parentAddEventData !== null){
                    this.pickedField = event.parentAddEventData;
                }else{
                    this.pickedField = null;
                }
                this.modalTitleVal = JSON.parse(JSON.stringify(event.field.title));
                // this.modalIdVal = JSON.parse(JSON.stringify(event.field.id));
                this.modalDescriptionVal = JSON.parse(JSON.stringify(event.field.description));
                this.editedField = event.field;
                this.openModal();
                // console.log(JSON.parse(JSON.stringify(event)));
            },
            listItemAddBtnClick: function(event){
                // return;
                // console.ls
                this.pickedField = event;
                this.openModal();
            },
            setFieldsIfNotEmpty: function(fields){
                if(Array.isArray(fields) && fields.length > 0)
                    this.fields = JSON.parse(JSON.stringify(fields));
            },
            setFields: function(fields){
                console.log('setFields');
                if(Array.isArray(fields) && fields.length > 0){
                    this.fields = JSON.parse(JSON.stringify(fields));
                }else{
                    this.fields = [];
                }
            },
            onModalFormSubmit: function(event){
                let _this = this;
                
                if(_this.editedField !== false){
                    // _this.editedField.title = _this.modalTitleVal;
                    // _this.editedField.description = _this.modalDescriptionVal;
                    // $("#" + _this.modalId).modal('hide');
                    
                    let data = {
                        id: _this.editedField.id,
                        title: _this.modalTitleVal,
                        description: _this.modalDescriptionVal,
                        parent_id: null,
                    }
                    
                    if(_this.pickedField !== null)
                        data.parent_id = _this.pickedField.field.id;
                        
                    axios.post(showUrl, data)
                    .then(function (response) {
                        // _this.setFields(response.data);
                        _this.setFields(response.data);
                        $("#" + _this.modalId).modal('hide');
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                }else{
                    let data = {
                        title: _this.modalTitleVal,
                        description: _this.modalDescriptionVal,
                        ids_trace: null,
                        parent_id: null,
                    }
                    
                    if(_this.pickedField !== null){
                        data.parent_id = _this.pickedField.field.id;
                        if(Array.isArray(_this.pickedField.idsTrace) && _this.pickedField.idsTrace.length > 0){
                            let idsTrace = JSON.parse(JSON.stringify(_this.pickedField.idsTrace));
                            if(typeof data.parent_id !== 'undefined' && Number.isInteger(data.parent_id))
                                idsTrace.push(data.parent_id);
                            data.ids_trace = idsTrace.join(',');
                        }else{
                            if(typeof data.parent_id !== 'undefined' && Number.isInteger(data.parent_id)){
                                data.ids_trace = data.parent_id;
                            }
                        }
                    }
                    
                    axios.post(showUrl, data)
                    .then(function (response) {
                        _this.setFields(response.data);
                        $("#" + _this.modalId).modal('hide');
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            dropdownChanged: function(event){
                // console.log(JSON.parse(JSON.stringify(event)));
                this.dropdownRelation = event !== null ? event.relation : null;
                this.pickedField = event;
            },
            openModal: function(){
                let _this = this;
                
                this.renderModal = true;
                let interval = setInterval(() => {
                    if($(document).find("#" + this.modalId).length > 0){
                        $(document).find("#" + this.modalId).modal('show');
                        $(document).find('.tooltip-init').tooltip();
                        this.initValidator();
                        // $("#" + this.modalId).off('hidden.bs.modal');
                        $("#" + this.modalId).on('hidden.bs.modal', function(){
                            // console.log('hidden.bs.modal');
                            
                            _this.destroyValidator();
                            // $("#" + _this.modalId).off('hidden.bs.modal');
                            
                        });
                        clearInterval(interval);
                    }
                }, 10);
            },
            destroyValidator: function(){
                $("#" + this.modalId).off('hidden.bs.modal');
                this.validator.resetForm();
                this.validator.destroy();
                // console.log($(document).find('#error_modal_title_val'));
                $(document).find('#error_modal_title_val').text('');
                $(document).find('#error_modal_key_val').text('');
                // $(document).find("#" + this.modalId).trigger("reset");
                this.modalTitleVal = null;
                this.modalKeyVal = null;
                this.modalDescriptionVal = null;
                this.pickedField = null;
                this.editedField = false;
                this.renderModal = false;
            },
            initValidator: function(){
                let _this = this;
                
                jQuery.validator.addMethod("key", function(value, element, params){
                    return value.match(/^[\w]+$/);
                }, '');
                
                jQuery.validator.addMethod("maxlength", function (value, element, len) {
                    return value == "" || value.length <= len;
                });
                
                _this.validator = $("form#" + _this.formId).validate({
                    // Specify validation rules
                    // onkeyup: true, 
                    onfocusout: false,
                    ignore: "",
                    errorPlacement: function(error, element) {
                        // console.log(error);
                        let attrName = element.attr("name");
                        let errorId = 'error_' + attrName;
                        let errorText = $(error).text();
                        
                        // console.log('errorPlacement');
                        if(errorText){
                            console.log(attrName + ': ' + errorText);
                        }
                        
                        $("#" + errorId).text(errorText);
                    },
                    rules: {
                        modal_title_val: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: showUrl,
                                type: "post",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                dataType: 'json',
                                data: {
                                    action: 'validate_title_uniqueness',
                                    parent_id: function() {
                                        if(_this.pickedField !== null && typeof _this.pickedField.field !== 'undefined' &&
                                        typeof _this.pickedField.field.id !== 'undefined')
                                            return _this.pickedField.field.id;
                                        return null;
                                    },
                                    id: function() {
                                        if(_this.editedField !== null && typeof _this.editedField.id !== 'undefined')
                                            return _this.editedField.id;
                                        return null;
                                    },
                                }
                            },
                        },
                        modal_description_val: {
                            maxlength: 255,
                        },
                    },
                    // Specify validation error messages
                    messages: {
                        modal_title_val: {
                            required: validationMessages.required.replace(':attribute', 'title'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'title')).replace(':max', '255'),
                            remote: validationMessages.unique.replace(':attribute', 'title'),
                        },
                        modal_description_val: {
                            maxlength: (validationMessages.max.string.replace(':attribute', 'description')).replace(':max', '255'),
                        },
                    },
                    submitHandler: function(form) {
                        console.log('submitHandler');
                        // form.submit();
                        _this.onModalFormSubmit();
                    },
                    success: function(label) {
                        // console.log('success');
                    },
                    // error: function(label) {
                    //     console.log('error');
                    // }
                    invalidHandler: function(form, validator){
                        // _this.resetErrorAttrs();
                
                        console.log('invalidHandler');
                    }
                });
            }
        },
        components: {
            Dropdown,
            Search,
        },
        watch: {
            toSearch: function(val){
                if(val === null || val === ''){
                    this.searchCountFoundResults = null;
                }else{
                    this.$nextTick(() => {
                        this.searchCountFoundResults = $(document).find('.template-specifics').find('.alert-item:visible').length;
                        // this.searchCountFoundResults
                        console.log(this.searchCountFoundResults);
                    });
                }
            },
            pickedField: function(newVal, oldVal){
                // console.log(newVal);
                console.log(JSON.parse(JSON.stringify(newVal)));
                // console.log(oldVal);
                // if(oldVal !== null)
                
                // console.log(this.validator.errorList);
                // console.log(this.validator.invalid.);
                // console.log(JSON.parse(JSON.stringify(this.validator)));
                if(this.validator != null && this.validator.errorList.length !== 0)
                    $("#" + this.formId).valid();
            }
        },
    // },
    }
</script>

<style lang="scss" scoped>

</style>