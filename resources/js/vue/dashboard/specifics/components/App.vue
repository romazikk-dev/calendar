<template>
    <div class="template-specifics">
        
        <div>
            <div class="specifics-handles">
                <button @click="openModal()" type="button" class="btn btn-primary btn-sm open-select-item-modal float-left">
                    Add
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                
                <search />
                
                <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
                
                <div class="clearfix"></div>
            </div>
            
            <list :fields="fields" />
        </div>
        
        <!-- Modal -->
        <div v-if="renderModal" class="modal fade modal-custom-dark-header-footer" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">New specific</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="input-group mb-3 pt-1 pb-1"
                            :class="{'tooltip-init': fields.length == 0}"
                            data-placement="top"
                            title="Tooltip on top">
                                <dropdown :fields="fields"
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
                                @keyup.prevent="titleChanged($event)"
                                aria-describedby="error_modal_title_val">
                            <div id="error_modal_title_val"
                                class="form-text small text-danger"></div>
                        </div>
                        
                        <div class="form-group">
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
                        </div>
                        
                        <div class="form-group">
                            <label for="modalTextareaDescription">Description</label>
                            <textarea  name="modal_description_val" v-model="modalDescriptionVal" class="form-control" id="modalTextareaDescription" rows="3"></textarea>
                            <div id="error_modal_description_val"
                                class="form-text small text-danger"></div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click.prevent="addSpecific" type="button" class="btn btn-primary">Add</button>
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
            // console.log(assignHalls);
            // console.log(JSON.parse(JSON.stringify(assignHalls)));
            // setTimeout(() => {
    		// 	this.setAssignHalls();
    		// }, 300);
            
            // this.setAssignHalls();
            // this.recalculateBadgeValue(true);
            this.openModal();
            
            // console.log(JSON.parse(JSON.stringify(3333)));
            // console.log($(document).find('.tooltip-init').length);
            // $(document).find('.tooltip-init').tooltip();
        },
        props: ['formId'],
        data: function(){
            return {
                // items: null,
                // selectedItems: [],
                // list: list,
                validator: null,
                
                modalTitleVal: null,
                modalKeyVal: null,
                modalDescriptionVal: null,
                
                applySlug: true,
                
                pickedField: null,
                renderModal: false,
                modalId: 'createSpecificModal',
                // fields: [],
                fields: [
                    {
                        title: 'one',
                        key: 'one',
                        desc: 'dasda',
                        parent: null,
                        fields: [
                            {
                                title: 'one',
                                key: 'one',
                                desc: 'dasda',
                                // parent: 'one',
                                fields: [],
                            },
                            {
                                title: 'two',
                                key: 'two',
                                desc: 'dasda',
                                parent: 'two',
                                fields: [
                                    {
                                        title: 'third',
                                        key: 'one',
                                        desc: 'dasda',
                                        parent: 'two',
                                        fields: [],
                                    },
                                ]
                            },
                        ]
                    },
                    {
                        title: 'two',
                        key: 'two',
                        desc: 'dasda',
                        parent: null,
                        fields: [],
                    },
                    {
                        title: 'three',
                        key: 'three',
                        desc: 'dasda',
                        parent: null,
                        fields: [],
                    },
                ],
            };
        },
        computed: {
            // showWarningAlert: function () {
            // 
            // },
        },
        methods: {
            titleChanged: function(event){
                if(this.applySlug === false)
                    return;
                let val = $(event.target).val();
                let slug = helper.createSlug(val);
                this.modalKeyVal = slug;
                
                this.$nextTick(() => {
                    if(val)
                        $("#modalInputKey").valid();
                });
                // $("#modalInputKey").valid();
                // console.log(slug);
            },
            keyChanged: function(event){
                if(this.applySlug === true)
                    this.applySlug = false;
            },
            addSpecific: function(event){
                let _this = this;
                
                // $("form#" + this.formId).valid()
                // if($("form#" + this.formId).valid()){
                // let titleValid = $("#modalInputTitle").valid();
                // console.log(titleValid);
                // let keyValid = $("#modalInputKey").valid();
                // let descriptionValid = $("#modalTextareaDescription").valid();
                // let descriptionValid = true;
                // if(titleValid && keyValid && descriptionValid){
                if($("form#" + this.formId).valid()){
                    let field = {
                        title: _this.modalTitleVal,
                        key: _this.modalKeyVal,
                        description: _this.modalDescriptionVal,
                        fields: []
                    }
                    if(_this.pickedField !== null){
                        _this.pickedField.field.fields.push(field);
                    }else{
                        _this.fields.push(field);
                    }
                    // _this.fields.push({
                    //     title: _this.modalTitleVal,
                    //     key: _this.modalKeyVal,
                    //     description: _this.modalDescriptionVal,
                    //     // slug: 
                    // });
                    console.log(JSON.parse(JSON.stringify(_this.fields)));
                }
                
                // console.log(JSON.parse(JSON.stringify(valid)));
                // console.log(JSON.parse(JSON.stringify(this.modalDescriptionVal)));
                // console.log(event);
            },
            dropdownChanged: function(event){
                // return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
                console.log(JSON.parse(JSON.stringify(event)));
                // this.pickedField = JSON.parse(JSON.stringify(event));
                this.pickedField = event;
                // console.log(JSON.parse(JSON.stringify(event)));
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
                            console.log('hidden.bs.modal');
                            // $("#" + _this.modalId).off('hidden.bs.modal');
                            _this.destroyValidator();
                            // _this.validator.resetForm();
                            // _this.validator.destroy();
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
            },
            initValidator: function(){
                let _this = this;
                
                jQuery.validator.addMethod("key", function(value, element, params){
                    // console.log(value);
                    return value.match(/^[\w]+$/);
                    // return false;
                }, '');
                
                jQuery.validator.addMethod("unique_key", function(value, element, params){
                    for(let i = 0; i < _this.fields.length; i++){
                        if(typeof _this.fields[i].key != 'undefined' && _this.fields[i].key === value){
                            console.log(22222);
                            return false;
                        }
                    }
                    return true;
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
                        
                        if(errorText)
                            console.log(attrName + ': ' + errorText);
                        // console.log(attrName + ': ' + errorText);
                        $("#" + errorId).text(errorText);
                    },
                    rules: {
                        modal_title_val: {
                            required: true,
                            maxlength: 255,
                        },
                        modal_key_val: {
                            required: true,
                            maxlength: 255,
                            key: true,
                            unique_key: true,
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
                        },
                        modal_key_val: {
                            required: validationMessages.required.replace(':attribute', 'key'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'key')).replace(':max', '255'),
                            key: validationMessages.regex.replace(':attribute', 'key'),
                            unique_key: validationMessages.unique.replace(':attribute', 'key'),
                        },
                        modal_description_val: {
                            maxlength: (validationMessages.max.string.replace(':attribute', 'description')).replace(':max', '255'),
                        },
                    },
                    submitHandler: function(form) {
                        console.log('submitHandler');
                        // form.submit();
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
            // modalDs
        },
    // },
    }
</script>

<style lang="scss" scoped>

</style>