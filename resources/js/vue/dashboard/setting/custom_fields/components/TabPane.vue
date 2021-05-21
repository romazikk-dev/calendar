<template>
    <div>
        
        <div class="pt-3">
            <div v-if="fieldNotEmpty">
                <input type="hidden"
                    name="field_name"
                    v-model="field.field"/>
                <ul class="nav nav-tabs edit-create-nav-tabs" id="myTab" role="tablist">
                    <li v-for="(value, index) in values" class="nav-item" role="presentation">
                        <a class="nav-link text-capitalize"
                            :id="value.abr + '-tab'"
                            data-toggle="tab"
                            :href="'#' + value.abr"
                            :tab-name="value.abr"
                            role="tab"
                            :aria-controls="value.abr"
                            aria-selected="true">
                                {{value.abr}}
                        </a>
                    </li>
                    <li class="action-btn">
                        <button type="submit" class="btn btn-sm btn-success float-right">
                            Save
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                            </svg>
                        </button>
                        <!-- <button id="submitBtn" class="btn btn-success btn-sm float-right">
                            Apply
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                            </svg>
                        </button> -->
                    </li>
                </ul>
                
                <div class="edit-create-tab-content tab-content" id="myTabContent">
                    <div v-for="(value, index) in values"
                        class="tab-pane fade"
                        :id="value.abr"
                        role="tabpanel"
                        :aria-labelledby="value.abr + '-tab'">
                        
                        <div class="form-group">
                            <label :for="value.abr + '_' + field.field">
                                <span class="text-capitalize">
                                    {{field.title}}
                                </span>
                                <span class="small">
                                    ({{value.title}})
                                </span>
                            </label>
                            <input type="text"
                                 class="form-control"
                                :id="value.abr + '_' + field.field"
                                :name="'field[' + field.field + '][' + value.abr + ']'"
                                v-model="value.value"
                                @keyup="changed"/>
                            <!-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"> -->
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>
                            <!-- <span class="text-capitalize">{{value.title}}</span> {{fieldName}}:<br>
                            <input type="text"
                                :id="value.abr + '_' + field.field"
                                :name="'field[' + field.field + '][' + value.abr + ']'"
                                v-model="value.value"/> -->
                    </div>
                </div>
            </div>
            <div v-else>
                Please select title
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.pickedField)));
            console.log(JSON.parse(JSON.stringify(222222)));
            // tabSwitcher.init();
            this.regTabSwitch();
            this.switchToCorrectTab();
            // if(this.field != null)
            //     this.pushUrlState();
            
            this.setOldValues();
        },
        props: ['field', 'fieldName'],
        data: function(){
            return {
                oldValues: null,
                // requestTab: requestTab,
            };
        },
        computed: {
            fieldNotEmpty: function () {
                // return 'eee';
                if(typeof this.field == 'undefined' || this.field == null || this.field.values == 'undefined')
                    return false;
                
                var values = Object.keys(this.field.values);
                return values.length > 0;
            },
            values: function () {
                if(!this.fieldNotEmpty)
                    return null;
                // console.log(JSON.parse(JSON.stringify(Object.values(this.field.values))));
                return JSON.parse(JSON.stringify(Object.values(this.field.values)));
                // JSON.parse(JSON.stringify(222222))
                // return Object.values(this.field.values);
            }
        },
        methods: {
            switchToCorrectTab: function(){
                console.log('switchToCorrectTab');
                console.log('requestTab');
                console.log(requestTab);
                if(requestTab != null){
                    setTimeout(() => {
                        // console.log("#" + requestTab + "-tab");
                        // console.log($("#" + requestTab + "-tab").length);
                        $("#" + requestTab + "-tab").tab('show');
                        requestTab = null;
                    }, 50);
                    // this.$nextTick(() => {
                    //     console.log("#" + requestTab + "-tab");
                    //     console.log($("#" + requestTab + "-tab").length);
                    //     $("#" + requestTab + "-tab").tab('show');
                    //     requestTab = null;
                    // });
                    // console.log("#" + requestTab + "-tab");
                    // console.log($("#" + requestTab + "-tab").length;
                    // $("#" + requestTab + "-tab").tab('show');
                    // requestTab = null;
                }else{
                    setTimeout(() => {
                        // console.log("#" + requestTab + "-tab");
                        // console.log($("#" + requestTab + "-tab").length);
                        $("#myTab .nav-item").eq(0).find('a.nav-link').tab('show');
                        // requestTab = null;
                    }, 50);
                }
            },
            regTabSwitch: function(){
                let _this = this;
                
                $('.nav-tabs a.nav-link').on('shown.bs.tab', function(){
                    _this.pushUrlState();
                    // let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
                    // let field = _this.field.field;
                    // let searchPart = '?tab=' + tab +'&field=' + field;
                    // let newFormUrl = _this.formUrl + searchPart;
                    // $("#" + _this.formId).attr('action', newFormUrl);
                    // 
                    // let currentUrl = location.protocol + '//' + location.host + location.pathname + searchPart;
                    // window.history.pushState({}, null, currentUrl);
                    // 
                    // // console.log(JSON.parse(JSON.stringify(_this.field.field)));
                    // // console.log(this.field);
                    // console.log('The new tab is now fully shown.');
                });
            },
            pushUrlState: function(){
                let formId = this.$parent.formId;
                let formUrl = this.$parent.formUrl;
                let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
                let field = this.field.field;
                // console.log(JSON.parse(JSON.stringify(111)));
                // console.log(JSON.parse(JSON.stringify(this.field.field)));
                // console.log(JSON.parse(JSON.stringify(field)));
                let searchPart = '?tab=' + tab +'&field=' + field;
                // console.log(JSON.parse(JSON.stringify(searchPart)));
                let newFormUrl = formUrl + searchPart;
                // console.log(JSON.parse(JSON.stringify(newFormUrl)));
                // console.log(JSON.parse(JSON.stringify(formId)));
                $("#" + formId).attr('action', newFormUrl);
                // $("#customTitlesForm").attr('action', 'ddd');
                
                let currentUrl = location.protocol + '//' + location.host + location.pathname + searchPart;
                window.history.pushState({}, null, currentUrl);
                
                // console.log(JSON.parse(JSON.stringify(_this.field.field)));
                // console.log(this.field);
                console.log('The new tab is now fully shown.');
            },
            changed: function(){
                // return;
                // console.log(JSON.parse(JSON.stringify(Object.values(this.oldValues))));
                // console.log(JSON.parse(JSON.stringify(Object.values(this.values))));
                // console.log(JSON.stringify(Object.values(this.oldValues)));
                // console.log(JSON.stringify(Object.values(this.values)));
                let oldValues = Object.values(this.oldValues);
                let values = Object.values(this.values);
                for(let i = 0; i < values.length; i++){
                    if(values[i].value == '')
                        values[i].value = null;
                }
                let equal = (JSON.stringify(oldValues) == JSON.stringify(values));
                // let JSON.parse(JSON.stringify(Object.values(this.field.values)));
                // console.log(equal);
                this.$emit('changed', !equal);
                // return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            setOldValues: function(){
                // console.log(JSON.parse(JSON.stringify(333)));
                // console.log(JSON.parse(JSON.stringify(this.field)));
                if(typeof this.field != 'undefined' && this.field != null && typeof this.field.values != 'undefined')
                    this.oldValues = JSON.parse(JSON.stringify(Object.values(this.field.values)));
            }
        },
        components: {
            
        },
        watch: {
            field: function (val) {
                this.setOldValues();
                // this.oldValues = JSON.parse(JSON.stringify(Object.values(this.field.values)));
                // console.log(JSON.parse(JSON.stringify(val)));
            },
        }
    }
</script>

<style lang="scss" scoped>
    
</style>

<style lang="scss">

</style>