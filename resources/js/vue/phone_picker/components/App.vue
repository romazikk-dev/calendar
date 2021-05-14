<template>
    <div>
        <!-- <div class="">
            <button click="" type="button" class="btn btn-sm btn-primary float-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </button>
        </div> -->
        <div class="phones">
            <div class="row">
                <div class="coll col-12 col-lg-6" v-for="(phone,index) in phones">
                        
                    <div class="contt">
                        <div class="col-left">
                            
                            <div class="d-table">
                                <div class="d-table-row">
                                    <div class="d-table-cell">
                                        
                                        <div class="form-group">
                                            <label :for="'phoneValue_' + index">Phone:</label>
                                            <input :name="indexPrefixes.value+index" type="text"
                                                v-model="phone.phone.value"
                                                class="form-control"
                                                :id="'phoneValue_' + index">
                                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                            <span :id="'error_phone_value_' + index"
                                                class="form-text text-danger small">{{phone.phone.error}}</span>
                                        </div>
                                        
                                    </div>
                                    <div class="d-table-cell">
                                        
                                        <input :name="indexPrefixes.id+index" 
                                            type="hidden"
                                            v-model="phone.id.value"
                                            class="form-control">
                                            
                                        <input :name="indexPrefixes.type+index" 
                                            type="hidden"
                                            v-model="phone.type.value"
                                            class="form-control">
                                        
                                        <div class="form-group">
                                            <label for="dropdownMenuButton">Type:</label>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{phone.type.value}}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a @click.prevent="phone.type.value = onChangeType(index, phoneType)"
                                                        v-for="phoneType in phoneTypes"
                                                        class="dropdown-item"
                                                        href="#">{{phoneType}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div v-if="phone.type.value == 'custom'" class="form-group">
                                            <input :name="indexPrefixes.custom_type+index"
                                                type="text"
                                                v-model="phone.custom_type.value"
                                                class="form-control">
                                            <span :id="'error_' + indexPrefixes.custom_type + index"
                                                class="form-text text-danger small">
                                                    {{phone.custom_type.error}}
                                            </span>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div><!--  /.col-left -->
                        <div class="col-right">
                            
                            <button class="btn btn-success btn-add"
                                :class="{
                                    'on-one-item': phones.length == 1
                                }"
                                @click="addItem()"
                                v-if="showAddItem"
                                type="button">
                                    +
                            </button>
                            <button class="btn btn-danger btn-remove"
                                @click="removeItem(index)"
                                v-if="showRemoveItem"
                                type="button">
                                    -
                            </button>
                            
                        </div><!--  /.col-right -->
                    </div><!--  /.contt -->
                    
                </div><!--  /.coll -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.setTabData(this.phonesCount);
            if(phones != null)
                this.phones = phones;
        },
        // props: ['postTitle'],
        data: function(){
            return {
                phones: [
                    {
                        // id: null,
                        // phone: null,
                        // type: phoneTypes.mobile,
                        // custom_type: null
                        
                        id: {
                            value: null,
                            error: null,
                        },
                        phone: {
                            value: null,
                            error: null,
                        },
                        type: {
                            value: phoneTypes.mobile,
                            error: null,
                        },
                        custom_type: {
                            value: null,
                            error: null,
                        },
                        // type: phoneTypes.mobile,
                        // custom_type: null
                    }
                ],
                phoneTypes: phoneTypes,
                maxPhoneItems: 10,
                indexPrefixes: indexPrefixes,
            };
        },
        computed: {
            showRemoveItem: function () {
                return this.phones.length > 1;
            },
            showAddItem: function () {
                return this.phones.length < this.maxPhoneItems;
            },
            phonesPhoneTypeProperty() {
                return this.phones.map(phone => phone.type.value);
            },
            phonesPhoneCustomTypeProperty() {
                return this.phones.map(phone => phone.custom_type.value);
            },
            phonesCount() {
                let phonesCount = 0;
                this.phones.forEach(function(item, index){
                    // console.log(item.phone.value);
                    if(typeof item.phone.value != 'undefined' && item.phone.value != '' && item.phone.value != null)
                        phonesCount++;
                });
                return phonesCount;
            }
        },
        methods: {
            onChangeType: function(index, phoneType){
                let _this = this;
                
                if(phoneType != 'custom' && !$(document).find('#error_phone_custom_type_' + index).val()){
                     _this.triggerFormValidation();
                }
                
                return phoneType;
            },
            addItem: function(){
                console.log('addItem');
                if(this.phones.length >= this.maxPhoneItems)
                    return;
                this.phones.push({
                    id: {
                        value: null,
                        error: null,
                    },
                    phone: {
                        value: null,
                        error: null,
                    },
                    type: {
                        value: phoneTypes.mobile,
                        error: null,
                    },
                    custom_type: {
                        value: null,
                        error: null,
                    },
                });
            },
            triggerFormValidation: function(){
                setTimeout(function(){
                    console.log('triggerFormValidation');
                 	// $('form#workerForm').valid();
                    jqueryValidation.triggerFormValidation();
                }, 50);
            },
            removeItem: function(index){
                console.log('removeItem');
                this.phones.splice(index, 1);
                this.triggerFormValidation();
            },
            setTabData: function(val){
                let noticeBadges = $("#phones-tab").find('.notice-badges');
                
                if(val > 0){
                    noticeBadges.find('.notice-badge-success')
                        .attr('data-original-title', val + ' phones')
                        .removeClass('d-none').text(val);
                }else{
                    // noticeBadges.find('.notice-badge-warning').removeClass('d-none');
                    noticeBadges.find('.notice-badge').addClass('d-none');
                }
            },
        },
        components: {
            
        },
        watch: {
            // phonesPhoneCustomTypeProperty: function (val) {
            //     console.log(val);
            // },
            phonesCount: function (val) {
                // console.log(val);
                this.setTabData(val);
            },
        }
    }
</script>

<style lang="scss" scoped>
    @media screen and (min-width: 992px) {
        .phones{
            .coll{
                &:nth-child(odd){
                    padding-right: 8px;
                }
                &:nth-child(even){
                    padding-left: 8px;
                }
                &:nth-last-child(-n+2){
                    .contt{
                        margin-bottom: 0px;
                    }
                }
            }
        }
    }
</style>

<style lang="scss" scoped>
    .phones{
        .coll{
            &:last-child{
                .contt{
                    margin-bottom: 0px!important;
                }
            }
            .contt{
                position: relative;
                padding-right: 117px;
                background-color: #e9ecef;
                padding-left: 15px;
                border-radius: 5px;
                margin-bottom: 15px;
                // &:last-child(){
                //     margin-bottom: 0px;
                // }
                .col-left{
                    // background-color: yellow;
                    .dropdown-toggle{
                        width: 100%;
                        text-align: left;
                        &::after {
                            display: inline-block;
                            margin-left: .255em;
                            vertical-align: .255em;
                            content: "";
                            border-top: .3em solid;
                            border-right: .3em solid transparent;
                            border-bottom: 0;
                            border-left: .3em solid transparent;
                            float: right;
                            margin-top: 10px;
                        }
                    }
                    input, button{
                        width: 100%;
                    }
                    label{
                        margin-bottom: 2px!important;
                    }
                    .d-table{
                        width: 100%;
                        .d-table-row{
                            .d-table-cell{
                                padding-right: 15px;
                                width: 49.9%;
                                &:nth-child(2){
                                    padding-right: 0px;
                                }
                                // &.half-size{
                                //     width: 49.9%;
                                //     &:nth-child(2){
                                //         padding-right: 0px;
                                //     }
                                // }
                                // &.third-size{
                                //     width: 33.3%;
                                //     &:nth-child(3){
                                //         padding-right: 0px;
                                //     }
                                // }
                                // &:nth-child(2){
                                //     padding-right: 0px;
                                // }
                            }
                        }
                    }
                }
                .col-right{
                    width: 102px;
                    position: absolute;
                    top: 0px;
                    right: 15px;
                    // background-color: red;
                    height: 100%;
                    padding-top: 25px;
                    button{
                        float: left;
                        // margin-left: 29px;
                        // margin-bottom: 1rem;
                        width: 36px;
                        // margin-right: 15px;
                        margin-left: 15px;
                        // &:first-child{
                        //     margin-left: 15px;
                        // }
                    }
                }
            }
        }
    }
</style>