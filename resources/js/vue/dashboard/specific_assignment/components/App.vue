<template>
    <div>
        
        <input :name="inputName"
            type="hidden"
            v-model="pickedSpecificId" />
        
        <dropdown @specificChange="specificChange($event)" :fields="specifics" />
        
        <div class="text-danger small"
            :id="'error_' + inputName"></div>
        
    </div>
</template>

<script>
    // import RecursiveDropdown from "./RecursiveDropdown.vue";
    export default {
        mounted() {
            console.log(JSON.parse(JSON.stringify(this.specifics)));
        },
        // props: ['postTitle'],
        data: function(){
            return {
                specifics: specifics,
                inputName: 'specific_id',
                pickedSpecificId: null,
                alwaysValidateSpecificInputOnChange: false,
            };
        },
        computed: {
            // showWarningAlert: function () {
            // 
            // },
        },
        methods: {
            isJqueryValidationEnabled: function(){
                return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            specificChange: function(event){
                // console.log(JSON.parse(JSON.stringify(77777)));
                // console.log(JSON.parse(JSON.stringify(event)));
                // return;
                
                if(event === null){
                    this.pickedSpecificId = null;
                    return;
                }
                
                if(typeof event.fields === 'undefined' || event.fields === null || (
                    Array.isArray(event.fields) && event.fields.length == 0
                )){
                    this.pickedSpecificId = event.id;
                }else{
                    this.pickedSpecificId = null;
                }
                
                if(this.alwaysValidateSpecificInputOnChange ||
                (this.isJqueryValidationEnabled() && jqueryValidation.isFormHasErrors()))
                // if(this.isJqueryValidationEnabled() && jqueryValidation.isFormHasErrors())
                    this.$nextTick(() => {
                        this.alwaysValidateSpecificInputOnChange = true;
                        jqueryValidation.triggerFieldValidation('input[name=' + this.inputName + ']');
                    });
                    // setTimeout(function(){
                    //     // jqueryValidation.triggerFormValidation();
                    //     jqueryValidation.triggerFieldValidation('input[name=specific]');
                    // }, 100);
                
                // this.pickedSpecificId = event !== null ? event.id : null;
                // if(event)
                // console.log(JSON.parse(JSON.stringify(event)));
            },
        },
        components: {
            // RecursiveDropdown,
        },
    }
</script>

<style lang="scss" scoped>
    
</style>