<template>
    <div>
        
        <div class="modal-content">
            <div class="modal-header">
                <span class="badge-information badge badge-pill badge-info text-uppercase">
                        Client info
                </span>
                <h5 class="modal-title" id="modalLabel">
                    {{fullName}}
                    
                    <span class="badge badge-pill badge-danger text-uppercase"
                        v-if="suspended"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle">
                            suspended
                    </span>
                    <span class="badge badge-pill badge-warning text-uppercase"
                        v-if="!suspended && suspentionInFuture"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle">
                            active
                    </span>
                    <span class="badge badge-pill badge-success text-uppercase"
                        v-if="!suspended && !suspentionInFuture"
                        data-toggle="modal-info-dropdown"
                        data-placement="auto"
                        :title="getTooltipStatusTitle">
                            active
                    </span>
                    
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button"
                    :class="{'active': showTab == 'main'}"
                    @click="showTab = 'main'"
                    class="btn btn-secondary">Main</button>
                <button type="button"
                    :class="{'active': showTab == 'phones'}"
                    @click="showTab = 'phones'"
                    class="btn btn-secondary">
                        Phones
                        <span class="text-warning"
                            v-if="!data.phones.length"
                            data-toggle="modal-info-dropdown"
                            data-placement="top"
                            title="No phones">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                </svg>
                        </span>
                        <span class="badge badge-pill badge-info" v-else
                            data-toggle="modal-info-dropdown"
                            data-placement="top"
                            :title="data.phones.length + ' phones'">
                                {{data.phones.length}}
                        </span>
                </button>
            </div>
            <div class="modal-body">
                
                <template v-if="showTab == 'main'">
                    <table class="info-table">
                        <tr v-if="data.first_name">
                            <td>First Name:</td>
                            <td><b>{{ data.first_name.charAt(0).toUpperCase() + data.first_name.slice(1) }}</b></td>
                        </tr>
                        <tr v-if="data.last_name">
                            <td>Last Name:</td>
                            <td><b>{{ data.last_name.charAt(0).toUpperCase() + data.last_name.slice(1) }}</b></td>
                        </tr>
                        <tr v-if="data.email">
                            <td>Email:</td>
                            <td><b>{{ data.email }}</b></td>
                        </tr>
                        <tr v-if="data.gender">
                            <td>Gender:</td>
                            <td><b>{{ data.gender }}</b></td>
                        </tr>
                        <tr v-if="data.birthdate">
                            <td>Birthdate:</td>
                            <td><b>{{ data.birthdate }}</b></td>
                        </tr>
                        <tr v-if="data.country">
                            <td>Country:</td>
                            <td><b>{{ data.country }}</b></td>
                        </tr>
                        <tr v-if="data.town">
                            <td>Town:</td>
                            <td><b>{{ data.town }}</b></td>
                        </tr>
                        <tr v-if="data.street">
                            <td>Street:</td>
                            <td><b>{{ data.street }}</b></td>
                        </tr>
                        <tr v-if="data.created_at">
                            <td>Created At:</td>
                            <td><b>{{ createdAt }}</b></td>
                        </tr>
                        <tr v-if="data.updated_at">
                            <td>Updated At:</td>
                            <td><b>{{ updatedAt }}</b></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td></td>
                        </tr>
                    </table>
                </template>
                
                <template v-if="showTab == 'phones'">
                    <template v-if="data.phones && data.phones.length > 0">
                        <table class="info-table">
                            <tr v-for="phone in data.phones">
                                <td>{{ phone.type }}:</td>
                                <td>{{ phone.phone }}</td>
                            </tr>
                        </table>
                    </template>
                    <template v-else>
                        No phones.
                    </template>
                </template>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'modalInfoContent',
        mounted() {
            console.log('modalInfoContent');
            // console.log(JSON.parse(this.data));
            // console.log(this.data);
            // this.initDataTable();
            // this.regClickMoreInfoBtn();
            // console.log($('[data-toggle="modal-info-dropdown"]'));
            $('[data-toggle="modal-info-dropdown"]').tooltip({ html: true });
        },
        props: ['data'],
        data: function(){
            return {
                showTab: 'main',
                // businessHours: 
                weekdays: ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'],
            };
        },
        computed: {
            getTooltipStatusTitle: function(){
                return helper.getStatusTooltipTitle(typeof this.data.suspension == 'undefined' ? null : this.data.suspension);
            },
            suspended: function(){
                return this.isStatus('suspended');
            },
            suspentionInFuture: function(){
                return this.isStatus('future_suspension');
            },
            periodSuspended: function(){
                return this.isStatus('period_suspended');
            },
            createdAt: function(){
                if(this.data == null || this.data.created_at == null)
                    return '';
                return moment(this.data.created_at).format('DD-MM-YYYY');
            },
            updatedAt: function(){
                if(this.data == null || this.data.updated_at == null)
                    return '';
                return moment(this.data.updated_at).format('DD-MM-YYYY');
            },
            fullName: function(){
                let fullNameArr = [];
                if(!helper.isPropEmpty(this.data.first_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(this.data.first_name));
                if(!helper.isPropEmpty(this.data.last_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(this.data.last_name));
            
                return fullNameArr.join(' ');
            },
        },
        methods: {
            isStatus: function(type){
                return helper.isStatus(type, (typeof this.data.suspension == 'undefined' ? null : this.data.suspension));
            },
            fullAddress: function(hall){
                let arrAddress = [];
                if(!helper.isPropEmpty(hall.country))
                    arrAddress.push(hall.country);
                if(!helper.isPropEmpty(hall.town))
                    arrAddress.push(hall.town);
                if(!helper.isPropEmpty(hall.street))
                    arrAddress.push(hall.street);
                
                return arrAddress.join(', ');
            },
            resetAllModalsData: function(){
                this.infoModalData = null;
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .modal-content{
        .modal-body{
            max-height: 300px;
            overflow-x: auto;
            .info-table{
                width: 100%;
                tr{
                    td{
                        vertical-align: top;
                        padding: 3px;
                        padding: 6px;
                        &:first-child{
                            width: 120px;
                            text-transform: uppercase;
                        }
                    }
                    &:nth-of-type(odd) {
                        td{
                            background-color: rgba(0,0,0,.05);
                        }
                    }
                }
            }
        }
    }
</style>