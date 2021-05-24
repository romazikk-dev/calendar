// alert(111);

var jqueryValidationFunc = function(){
    
    this.enableJsValidation = true;
    // this.enableJsValidation = false;
    // this.validator = null;
    this.formId = "form#templateForm";
    this.formHasErrors = false;
    
    this.attributes_per_tab = {
        main: ['title','duration','price','description','short_description','notice'],
        specific: ['specific_id'],
    }
    
    this.mainErrorAttrs = [];
    this.specificErrorAttrs = [];
    
    this.init = function(){
        // alert(111);
        this.regSubmitBtn();
        if(this.enableJsValidation)
            this.initValidator();
    }
    
    this.isFormHasErrors = function(){
        return this.formHasErrors;
    }
    
    this.isValidating = function(){
        return this.enableJsValidation;
    }
    
    this.resetErrorAttrs = function(){
        this.mainErrorAttrs = [];
    }
    
    this.triggerFormValidation = function(){
        this.resetErrorAttrs();
        // this.addPhoneRules();
        // this.addStatusRules();
        $(this.formId).valid();
    }
    
    this.triggerFieldValidation = function(selector){
        $(selector).valid();
    }
    
    this.regSubmitBtn = function(){
        let _this = this;
        
        // console.log('regSubmitBtn');
        
        $("#submitBtn").click(function(e){
            e.preventDefault();
            // console.log('submitBtn');
            // _this.addPhoneRules();
            // console.log('not validated');
            if(_this.enableJsValidation){
                _this.addPhoneRules();
                _this.addStatusRules();
                if($(_this.formId).valid()){
                    console.log('validated');
                    $(_this.formId).submit();
                }
                console.log('not validated');
            }else{
                // console.log('submit');
                $(_this.formId).submit();
            }
            // alert(99);
        });
    }
    
    this.handleErrorNotices = function(){
        let _this = this;
        
        if(_this.mainErrorAttrs.length > 0){
            let errorsCount = _this.mainErrorAttrs.length;
            $('#mainErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            // console.log('no mainErrorsCount');
            $('#mainErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.specificErrorAttrs.length > 0){
            let errorsCount = _this.specificErrorAttrs.length;
            $('#specificsErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            // console.log('no mainErrorsCount');
            $('#specificsErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.mainErrorAttrs.length > 0 || _this.specificErrorAttrs.length > 0){
            _this.showErrorAlert();
            _this.formHasErrors = true;
        }else{
            _this.hideErrorAlert();
            _this.formHasErrors = false;
        }
    }
    
    this.showErrorAlert = function(){
        if($(document).find('.alert-form-success').length > 0)
            $(".alert-form-success").remove();
        
        if($(document).find('.alert-form-error').length < 1){
            $(".page-content").prepend(`
                <div class="alert-form-error alert alert-danger alert-dismissible fade show" role="alert">
                    You have an errors
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            `);
        }
    }
    
    this.hideErrorAlert = function(){
        $(document).find('.alert-form-error').remove();
    }
    
    this.handleErrorAttrs = function(type, attr){
        let _this = this;
        
        if(_this.attributes_per_tab.main.includes(attr)){
            if(type == 'add' && !_this.mainErrorAttrs.includes(attr))
                _this.mainErrorAttrs.push(attr);
            if(type == 'delete' && _this.mainErrorAttrs.includes(attr)){
                let index = _this.mainErrorAttrs.indexOf(attr);
                _this.mainErrorAttrs.splice(index, 1);
            }
        }
        
        if(_this.attributes_per_tab.specific.includes(attr)){
            if(type == 'add' && !_this.specificErrorAttrs.includes(attr))
                _this.specificErrorAttrs.push(attr);
            if(type == 'delete' && _this.specificErrorAttrs.includes(attr)){
                let index = _this.specificErrorAttrs.indexOf(attr);
                _this.specificErrorAttrs.splice(index, 1);
            }
        }
    }
    
    this.addSpecificRules = function(){
        let input = $('input[name=specific_id]');
        if(input.length == 0)
            return;
            
        input.rules("remove");
        input.rules("add", {
            required: true,
            messages: {
                required: validationMessages.required.replace(':attribute', 'select'),
                // required: (
                //     (
                //         validationMessages.required_if.replace(':attribute', 'from')
                //     ).replace(':other', 'status')
                // ).replace(':value', 'period'),
                // maxlength: (validationMessages.max.string.replace(':attribute', 'phone')).replace(':max', '255'),
            }
        });
    }
    
    this.addStatusRules = function(){
        let input_from = $('input[name=suspend_from]');
        let input_to = $('input[name=suspend_to]');
        
        let status = $('#statusDropdownMenuBtn').attr('data-status');
        // console.log(status);
        
        // return;
        // console.log(validationMessages);
        if(status != 'period'){
            input_from.rules("remove");
            input_to.rules("remove");
            // $("#statusErrorBadge").addClass('d-none');
            return;
        }
            
        if(input_from.length > 0){
            // console.log(input_from);
            input_from.rules("remove");
            input_from.rules("add", {
                required: true,
                // maxlength: 255,
                messages: {
                    required: (
                        (
                            validationMessages.required_if.replace(':attribute', 'from')
                        ).replace(':other', 'status')
                    ).replace(':value', 'period'),
                    maxlength: (validationMessages.max.string.replace(':attribute', 'phone')).replace(':max', '255'),
                }
            });
        }
        
        if(input_to.length > 0){
            // console.log();
            input_to.rules("remove");
            input_to.rules("add", {
                required: true,
                // maxlength: 255,
                messages: {
                    // required: validationMessages.required_if.replace(':attribute', 'to'),
                    required: (
                        (
                            validationMessages.required_if.replace(':attribute', 'to')
                        ).replace(':other', 'status')
                    ).replace(':value', 'period'),
                    // maxlength: (validationMessages.max.string.replace(':attribute', 'phone')).replace(':max', '255'),
                }
            });
        }
    }
    
    this.addPhoneRules = function(){
        for(let i = 0; i < 10; i++){
            let input_value = $('input[name=phone_value_' + i + ']');
            let input_type = $('input[name=phone_type_' + i + ']');
            let input_custom_type = $('input[name=phone_custom_type_' + i + ']');
            if(input_value.length > 0){
                // clearInterval(interval);
                // console.log(input_value);
                input_value.rules("remove");
                input_value.rules("add", {
                    // required: true,
                    maxlength: 255,
                    messages: {
                        // required: validationMessages.required.replace(':attribute', 'first name'),
                        maxlength: (validationMessages.max.string.replace(':attribute', 'phone')).replace(':max', '255'),
                    }
                });
                if(input_type.length > 0 && input_type.val() == 'custom' && input_custom_type.length > 0){
                    // console.log('input_custom_type 3333333333333');
                    input_custom_type.rules("remove");
                    input_custom_type.rules("add", {
                        // required: true,
                        required: function(element){
                            let inputValueVal = input_value.val();
                            return inputValueVal != '';
                        },
                        maxlength: 255,
                        messages: {
                            required: validationMessages.required.replace(':attribute', 'custom type'),
                            maxlength: (validationMessages.max.string.replace(':attribute', 'custom type')).replace(':max', '255'),
                        }
                    });
                }
            }
        }
    }
    
    this.initValidator = function(){
        // console.log('initValidator');
        
        let _this = this;
        
        let rules = {
            // Main tab rules
            title: {
                required: true,
                maxlength: 255,
            },
            duration: {
                required: true,
                maxlength: 10,
                duration_regexp: true,
            },
            short_description: {
                maxlength: 255,
            },
            price: {
                price_regexp: true,
            },
            description: {
                maxlength: 1000,
            },
            notice: {
                maxlength: 1000,
            },
        }
        
        $.validator.addMethod("duration_regexp", function (value, element, len) {
            return value.match(/\d{2}:\d{2}/i);
        });
        
        $.validator.addMethod("price_regexp", function (value, element, len) {
            if(value == '')
                return true;
            return value.match(/^([0-9]){1,6}(\.[0-9]{2})?$/);
        });
        
        $.validator.addMethod("maxlength", function (value, element, len) {
            return value == "" || value.length <= len;
        });
        
        $(_this.formId).validate({
            ignore: "",
            errorPlacement: function(error, element) {
                let attrName = element.attr("name");
                let errorId = 'error_' + attrName;
                let errorText = $(error).text();
                
                $("#" + errorId).text(errorText);
                
                _this.handleErrorAttrs((errorText == '' ? 'delete' : 'add'), attrName);
                _this.handleErrorNotices();
            },
            rules: rules,
            // Specify validation error messages
            messages: {
                // Main tab rules messages
                title: {
                    required: validationMessages.required.replace(':attribute', 'title'),
                    maxlength: (validationMessages.max.string.replace(':attribute', 'title')).replace(':max', '255'),
                },
                duration: {
                    required: validationMessages.required.replace(':attribute', 'duration'),
                    maxlength: (validationMessages.max.string.replace(':attribute', 'duration')).replace(':max', '10'),
                    duration_regexp: validationMessages.regex.replace(':attribute', 'duration'),
                },
                short_description: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'short description')).replace(':max', '255'),
                },
                price: {
                    price_regexp: validationMessages.regex.replace(':attribute', 'price'),
                },
                description: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'description')).replace(':max', '1000'),
                },
                notice: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'notice')).replace(':max', '1000'),
                },
            },
            submitHandler: function(form) {
                // console.log('submitHandler');
                form.submit();
            },
            success: function(label) {
                // console.log('success');
            },
            invalidHandler: function(form, validator){
                
                _this.resetErrorAttrs();
                
                console.log('invalidHandler');
                // console.log(validator.invalid);
            }
        });
        
        let interval = setInterval(function(){
            if($('input[name=phone_value_0]').length > 0){
                clearInterval(interval);
                _this.addPhoneRules();
            }
        }, 50);
        
        setTimeout(function(){
            _this.addSpecificRules();
        }, 600);
        
    }
    
}

var jqueryValidation = new jqueryValidationFunc();
jqueryValidation.init();