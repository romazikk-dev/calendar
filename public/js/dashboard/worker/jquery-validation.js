// alert(111);

(new function(){
    
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
    
    this.init = function(){
        this.regSubmitBtn();
        if(this.enableJsValidation)
            this.initValidator();
    }
    
    this.regSubmitBtn = function(){
        let _this = this;
        
        $("#submitBtn").click(function(e){
            e.preventDefault();
            if(_this.enableJsValidation){
                if($('form#workerForm').valid()){
                    console.log('validated');
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
            $('#mainErrorBadge').removeClass('d-none').text(_this.mainErrorAttrs.length);
        }else{
            // console.log('no mainErrorsCount');
            $('#mainErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.passwordErrorAttrs.length > 0){
            $('#passwordErrorBadge').removeClass('d-none').text(_this.passwordErrorAttrs.length);
        }else{
            // console.log('no mainErrorsCount');
            $('#passwordErrorBadge').addClass('d-none').text('');
        }
        
        if(_this.addressErrorAttrs.length > 0){
            $('#addressErrorBadge').removeClass('d-none').text(_this.addressErrorAttrs.length);
        }else{
            $('#addressErrorBadge').addClass('d-none').text('');
        }
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
    
    this.initValidator = function(){
        let _this = this;
        
        jQuery.validator.addMethod("in", function(value, element, params){
            return params.includes(value);
        }, '');
        
        $.validator.addMethod("maxlength", function (value, element, len) {
            return value == "" || value.length <= len;
        });
        
        function addPhoneRules(){
            let interval = setInterval(function(){
                for(let i = 0; i < 10; i++){
                    let input = $('input[name=phone_value_' + i + ']');
                    if(input.length){
                        clearInterval(interval);
                    // if(input){
                        console.log(input);
                        input.rules("add", {
                            minlength: 20
                        });
                    }
                }
            }, 50);
        }
        
        $("form#workerForm").validate({
            // Specify validation rules
            // onkeyup: true, 
            // onfocusout: false,
            ignore: "",
            errorPlacement: function(error, element) {
                let attrName = element.attr("name");
                let errorId = 'error_' + attrName;
                let errorText = $(error).text();
                console.log(errorId);
                console.log(errorText);
                $("#" + errorId).text(errorText);
                
                _this.handleErrorAttrs((errorText == '' ? 'delete' : 'add'), attrName);
                _this.handleErrorNotices();
            },
            // onfocusout: false,
            rules: {
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
                password: {
                    required: true,
                    // minlength: 5
                },
                password_confirm: {
                    required: true,
                    // required: function(element){
                    //     let passwordVal = $("#password").val();
                    //     return passwordVal != '';
                    // },
                    equalTo : "#password"
                }
            },
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
                // var invalidKeys = Object.keys(validator.invalid);
                // invalidKeys.forEach((item, i) => {
                //     console.log('item: ' + item);
                //     console.log(validator.invalid[item] == false);
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
        
        addPhoneRules();
        
    }
    
}).init();