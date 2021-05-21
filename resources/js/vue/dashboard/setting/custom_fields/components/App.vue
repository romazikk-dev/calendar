<template>
    <div>
        
        <form id="customTitlesForm" action="" method="post">
            <span v-html="csrfInput"></span>
            
            <dropdown @changed="dropdownChanged"
                :fields="fields"
                :picked-field="pickedField"
                :field-name="fieldName"/>
                
            <tab-pane :field="pickedField"
                :key="pickedField == null ? 'pickedField' : pickedField.field"
                @changed="valueChanged"
                :field-name="fieldName" />
                
            <modal ref="modal"
                @confirmed="finishSwitchinField"
                modal-title="Confirm"
                modal-text="You will lose not saved data, do proceed switch of field?"/>
            
        </form>
        
    </div>
</template>

<script>
    import SearchableDropdown from "./SearchableDropdown.vue";
    import TabPane from "./TabPane.vue";
    import Modal from "./Modal.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(validationMessages)));
            // console.log(JSON.parse(JSON.stringify(this.fields)));
            this.setPickedFieldFromUrlSearchPart();
        },
        props: ['pickerFieldName'],
        data: function(){
            return {
                csrfInput: csrfInput,
                fields: titles,
                pickedField: null,
                pickedFieldMiddle: null,
                isValueChanged: false,
                formId: 'customTitlesForm',
                formUrl: formUrl,
            };
        },
        computed: {
            fieldName: function(){
                if(typeof this.pickerFieldName != 'undefined' && this.pickerFieldName != null)
                    return this.pickerFieldName;
                return 'field';
            },
        },
        methods: {
            setPickedFieldFromUrlSearchPart: function(){
                let params = (new URL(document.location)).searchParams;
                let field = params.get("field");
                
                if(field != null && typeof this.fields != 'undefined' && this.fields != null && typeof this.fields[field] != 'undefined')
                    this.pickedField = this.fields[field];
            },
            finishSwitchinField: function(){
                this.isValueChanged = false;
                this.pickedField = this.pickedFieldMiddle;
                this.$refs.modal.close();
            },
            valueChanged: function(el){
                this.isValueChanged = el;
                // console.log(el);
                // this.pickedField = el;
                // console.log(JSON.parse(JSON.stringify(this.pickedField)));
                // console.log(JSON.parse(JSON.stringify(index)));
                // return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            dropdownChanged: function(el, index){
                console.log(this.isValueChanged);
                
                if(!this.isValueChanged)
                    this.pickedField = el;
                
                if(this.isValueChanged){
                    this.pickedFieldMiddle = el;
                    this.$refs.modal.open();
                }
                
                // this.$refs.modal.open();
                // if(this.isValueChanged && confirm('Do?')){
                //     this.isValueChanged = false;
                //     this.pickedField = el;
                //     this.$refs.modal.open();
                // }
                
                // console.log(JSON.parse(JSON.stringify(this.pickedField)));
                // console.log(JSON.parse(JSON.stringify(index)));
                // return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
        },
        components: {
            dropdown: SearchableDropdown,
            tabPane: TabPane,
            modal: Modal
        },
    }
</script>

<style lang="scss" scoped>
    
</style>

<style lang="scss">

</style>