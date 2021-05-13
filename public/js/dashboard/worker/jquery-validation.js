// alert(111);

var jqueryValidationFunc = function(){
    
    this.enableJsValidation = true;
    // this.enableJsValidation = false;
    // this.validator = null;
    
    this.attributes_per_tab = {
        main: ['email','first_name','last_name','gender','birthdate'],
        address: ['country','town','street'],
        password: ['password','password_confirm'],
    }
    
    this.mainErrorAttrs = [];
    this.addressErrorAttrs = [];
    this.passwordErrorAttrs = [];
    this.phoneErrorAttrs = [];
    this.statusErrorAttrs = [];
    
    this.init = function(){
        this.regSubmitBtn();
        if(this.enableJsValidation)
            this.initValidator();
    }
    
    this.isValidating = function(){
        return this.enableJsValidation;
    }
    
    this.resetErrorAttrs = function(){
        this.mainErrorAttrs = [];
        this.addressErrorAttrs = [];
        this.passwordErrorAttrs = [];
        this.phoneErrorAttrs = [];
        this.statusErrorAttrs = [];
    }
    
    this.triggerFormValidation = function(){
        this.resetErrorAttrs();
        this.addPhoneRules();
        this.addStatusRules();
        $('form#workerForm').valid();
    }
    
    this.regSubmitBtn = function(){
        let _this = this;
        
        $("#submitBtn").click(function(e){
            e.preventDefault();
            // _this.addPhoneRules();
            if(_this.enableJsValidation){
                _this.addPhoneRules();
                _this.addStatusRules();
                if($('form#workerForm').valid()){
                    // console.log('validated');
                    $('form#workerForm').submit();
                }
            }else{
                $('form#workerForm').submit();
            }
            // alert(99);
        });
         // onclick="$('form#workerForm').valid();"
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
        
        if(_this.passwordErrorAttrs.length > 0){
            let errorsCount = _this.passwordErrorAttrs.length;
            $('#passwordErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            // console.log('no mainErrorsCount');
            $('#passwordErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.addressErrorAttrs.length > 0){
            let errorsCount = _this.addressErrorAttrs.length;
            $('#addressErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            $('#addressErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.phoneErrorAttrs.length > 0){
            let errorsCount = _this.phoneErrorAttrs.length;
            $('#phoneErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            $('#phoneErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.statusErrorAttrs.length > 0){
            let errorsCount = _this.statusErrorAttrs.length;
            $('#statusErrorBadge').removeClass('d-none')
                .attr('data-original-title', errorsCount + ' errors').text(errorsCount);
        }else{
            $('#statusErrorBadge').addClass('d-none').text('');
        }
    }
    
    this.handleErrorAttrs = function(type, attr){
        let _this = this;
        
        if(attr.startsWith("suspend")){
            if(type == 'add' && !_this.statusErrorAttrs.includes(attr))
                _this.statusErrorAttrs.push(attr);
            if(type == 'delete' && _this.statusErrorAttrs.includes(attr)){
                let index = _this.statusErrorAttrs.indexOf(attr);
                _this.statusErrorAttrs.splice(index, 1);
            }
        }
        
        if(attr.startsWith("phone")){
            // console.log(attr);
            if(type == 'add' && !_this.phoneErrorAttrs.includes(attr))
                _this.phoneErrorAttrs.push(attr);
            if(type == 'delete' && _this.phoneErrorAttrs.includes(attr)){
                let index = _this.phoneErrorAttrs.indexOf(attr);
                _this.phoneErrorAttrs.splice(index, 1);
            }
            // console.log(_this.phoneErrorAttrs);
        }
        
        if(_this.attributes_per_tab.main.includes(attr)){
            if(type == 'add' && !_this.mainErrorAttrs.includes(attr))
                _this.mainErrorAttrs.push(attr);
            if(type == 'delete' && _this.mainErrorAttrs.includes(attr)){
                let index = _this.mainErrorAttrs.indexOf(attr);
                _this.mainErrorAttrs.splice(index, 1);
            }
        }
        if(_this.attributes_per_tab.address.includes(attr)){
            if(!_this.addressErrorAttrs.includes(attr))
                _this.addressErrorAttrs.push(attr);
            if(type == 'delete' && _this.addressErrorAttrs.includes(attr)){
                let index = _this.addressErrorAttrs.indexOf(attr);
                _this.addressErrorAttrs.splice(index, 1);
            }
        }
        if(_this.attributes_per_tab.password.includes(attr)){
            if(!_this.passwordErrorAttrs.includes(attr))
                _this.passwordErrorAttrs.push(attr);
            if(type == 'delete' && _this.passwordErrorAttrs.includes(attr)){
                let index = _this.passwordErrorAttrs.indexOf(attr);
                _this.passwordErrorAttrs.splice(index, 1);
            }
        }
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
        let _this = this;
        
        let rules = {
            // Main tab rules
            email: {
                required: true,
                email: true,
                remote: {
                    url: checkEmailUrl,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json'
                }
            },
            first_name: {
                required: true,
                maxlength: 255,
            },
            last_name: {
                maxlength: 255,
            },
            gender: {
                required: true,
                in: ['male','female'],
                // minlength: 5
            },
            // Address tab rules
            country: {
                maxlength: 255,
            },
            town: {
                maxlength: 255,
            },
            street: {
                maxlength: 255,
            },
            // Password tab rules
            // password: {
            //     required: true,
            //     // minlength: 5
            // },
            // password_confirm: {
            //     required: true,
            //     // required: function(element){
            //     //     let passwordVal = $("#password").val();
            //     //     return passwordVal != '';
            //     // },
            //     equalTo : "#password"
            // }
        }
        
        if(typeof worker === 'undefined' || worker === null){
            rules.password = {
                required: true,
            }
            rules.password_confirm = {
                required: true,
                // required: function(element){
                //     let passwordVal = $("#password").val();
                //     return passwordVal != '';
                // },
                equalTo : "#password"
            }
            // rules.email.remote.data = {
            //     current
            // }
        }else{
            // rules.password = {
            //     required: true,
            // }
            rules.password_confirm = {
                // required: true,
                required: function(element){
                    let passwordVal = $("#password").val();
                    return passwordVal != '';
                },
                equalTo : "#password"
            }
        }
            // console.log(worker);
        // if
        
        jQuery.validator.addMethod("in", function(value, element, params){
            return params.includes(value);
        }, '');
        
        $.validator.addMethod("maxlength", function (value, element, len) {
            return value == "" || value.length <= len;
        });
        
        $("form#workerForm").validate({
            // Specify validation rules
            // onkeyup: true, 
            // onfocusout: false,
            ignore: "",
            errorPlacement: function(error, element) {
                let attrName = element.attr("name");
                let errorId = 'error_' + attrName;
                let errorText = $(error).text();
                // console.log(errorId);
                // console.log(errorText);
                $("#" + errorId).text(errorText);
                
                _this.handleErrorAttrs((errorText == '' ? 'delete' : 'add'), attrName);
                _this.handleErrorNotices();
            },
            // onfocusout: false,
            rules: rules,
            // Specify validation error messages
            messages: {
                // Main tab rules messages
                email: {
                    required: validationMessages.required.replace(':attribute', 'email'),
                    email: validationMessages.email.replace(':attribute', 'email'),
                    remote: validationMessages.unique.replace(':attribute', 'email'),
                },
                first_name: {
                    required: validationMessages.required.replace(':attribute', 'first name'),
                    maxlength: (validationMessages.max.string.replace(':attribute', 'first name')).replace(':max', '255'),
                },
                last_name: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'last name')).replace(':max', '255'),
                },
                gender: {
                    required: validationMessages.required.replace(':attribute', 'gender'),
                    in: validationMessages.in.replace(':attribute', 'gender'),
                },
                // Address tab rules messages
                country: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'country')).replace(':max', '255'),
                },
                town: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'town')).replace(':max', '255'),
                },
                street: {
                    maxlength: (validationMessages.max.string.replace(':attribute', 'street')).replace(':max', '255'),
                },
                // Password tab rules messages
                password: {
                    required: validationMessages.required.replace(':attribute', 'password'),
                },
                password_confirm: {
                    required: validationMessages.required.replace(':attribute', 'password confirm'),
                    equalTo: (validationMessages.same.replace(':attribute', 'password confirm')).replace(':other', 'password'),
                },
            },
            submitHandler: function(form) {
                // console.log('submitHandler');
                form.submit();
            },
            success: function(label) {
                // console.log('success');
            },
            // error: function(label) {
            //     console.log('error');
            // }
            invalidHandler: function(form, validator){
                
                _this.resetErrorAttrs();
                
                // var invalidKeys = Object.keys(validator.invalid);
                // invalidKeys.forEach((item, i) => {
                //     // console.log('item: ' + item);
                //     // console.log(validator.invalid[item] == false);
                //     if (validator.invalid[item] == false){
                //         console.log('false');
                //         _this.handleErrorAttrs('delete', item);
                //         _this.handleErrorNotices();
                //     }
                // });
                // 
                // console.log(invalidKeys);
                // Object.entries(validator.invalid).forEach(invalid => {
                //     console.log('33333333: ' + invalid[0]);
                //     if (typeof invalid[1] == false){
                //         _this.handleErrorAttrs('delete', invalid[0]);
                //         _this.handleErrorNotices();
                //     }
                //     // key: student[0]
                //     // value: student[1]
                //     // console.log(`Student: ${student[0]} is ${student[1].age} years old`);
                // });
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
        
    }
    
}

var jqueryValidation = new jqueryValidationFunc();
jqueryValidation.init();
// ).init();