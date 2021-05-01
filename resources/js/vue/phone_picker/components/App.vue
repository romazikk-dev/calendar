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
                                            <label for="phoneInput">Phone:</label>
                                            <input :name="'phone_'+index" type="text"
                                                v-model="phone.phone"
                                                class="form-control"
                                                :id="'phoneInput_' + index"
                                                aria-describedby="emailHelp">
                                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                            <span id="emailHelp"
                                                v-if="isPhoneError('phone', index)"
                                                class="form-text text-danger small">
                                                    {{phoneErrors[index]['phone']}}
                                            </span>
                                        </div>
                                        
                                    </div>
                                    <div class="d-table-cell">
                                        
                                        <input :name="'phone_id_'+index" 
                                            type="hidden"
                                            v-model="phone.id"
                                            class="form-control"
                                            id="phoneInput"
                                            aria-describedby="emailHelp">
                                            
                                        <input :name="'phone_type_'+index" 
                                            type="hidden"
                                            v-model="phone.type"
                                            class="form-control"
                                            id="phoneInput"
                                            aria-describedby="emailHelp">
                                        
                                        <div class="form-group">
                                            <label for="typeSelect">Type:</label>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{phone.type}}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a @click.prevent="phone.type = phoneType"
                                                        v-for="phoneType in phoneTypes"
                                                        class="dropdown-item"
                                                        href="#">{{phoneType}}</a>
                                                    <a @click.prevent="phone.type = 'custom'" class="dropdown-item" href="#">Custom</a>
                                                </div>
                                            </div>
                                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                        </div>
                                        
                                        <div v-if="phone.type == 'custom'" class="form-group">
                                            <input :name="'custom_phone_type_'+index"
                                                type="text"
                                                v-model="phone.custom_type"
                                                class="form-control"
                                                id="phoneInput"
                                                aria-describedby="emailHelp">
                                            <span id="emailHelp"
                                                v-if="isPhoneError('custom_type', index)"
                                                class="form-text text-danger small">
                                                    {{phoneErrors[index]['custom_type']}}
                                            </span>
                                        </div>
                                        
                                    </div>
                                    <!-- <div class="d-table-cell third-size"
                                         v-if="phone.type == 'custom'">
                                        
                                        <div class="form-group">
                                            <label for="phoneInput">Custom type:</label>
                                            <input :name="'custom_phone_type_'+index"
                                                type="text"
                                                v-model="phone.custom_type"
                                                class="form-control"
                                                id="phoneInput"
                                                aria-describedby="emailHelp">
                                            <span id="emailHelp"
                                                v-if="isPhoneError('custom_type', index)"
                                                class="form-text text-danger small">
                                                    {{phoneErrors[index]['custom_type']}}
                                            </span>
                                        </div>
                                        
                                    </div> -->
                                </div>
                            </div>
                            
                        </div>
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
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(this.phoneTypes);
            console.log(JSON.parse(JSON.stringify(phoneErrors)));
            this.setOldPhones();
            // oldPhones
        },
        // props: ['postTitle'],
        data: function(){
            return {
                phones: [
                    {
                        id: null,
                        phone: null,
                        type: phoneTypes.mobile,
                        custom_type: null
                    }
                ],
                phoneTypes: phoneTypes,
                maxPhoneItems: 10,
                phoneErrors: phoneErrors,
            };
        },
        computed: {
            showRemoveItem: function () {
                return this.phones.length > 1;
            },
            showAddItem: function () {
                return this.phones.length < this.maxPhoneItems;
            }
        },
        methods: {
            isPhoneError: function(errorType, index){
                if(this.phoneErrors == null ||
                typeof this.phoneErrors[index] == 'undefined')
                    return false;
                if(errorType == 'phone' && typeof this.phoneErrors[index]['phone'] != 'undefined')
                    return true;
                if(errorType == 'custom_type' && typeof this.phoneErrors[index]['custom_type'] != 'undefined'){
                    if(typeof this.phones[index]['phone'] != 'undefined' && this.phones[index]['phone'] != null){
                        return true;
                    }else{
                        delete this.phoneErrors[index]['custom_type'];
                    }
                }
                return false;
            },
            setOldPhones: function(){
                if(oldPhones != null)
                    this.phones = oldPhones;
            },
            addItem: function(){
                console.log('addItem');
                if(this.phones.length >= this.maxPhoneItems)
                    return;
                this.phones.push({
                    id: null,
                    phone: null,
                    type: phoneTypes.mobile,
                    custom_type: null
                });
            },
            removeItem: function(index){
                console.log('removeItem');
                this.phones.splice(index, 1);
            },
        },
        components: {
            
        },
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