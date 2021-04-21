<template>
    <div>
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    {{ data.title }}
                    
                    <!-- <span :class="{'badge-success': data.is_closed == 0, 'badge-danger': data.is_closed == 1}"
                        class="badge badge-pill text-uppercase">closed</span> -->
                        
                        <span class="badge badge-pill badge-success text-uppercase" v-if="!data.is_closed == 1">opened</span>
                        <span class="badge badge-pill badge-danger text-uppercase" v-else>closed</span>
                    
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
                    :class="{'active': showTab == 'employees'}"
                    @click="showTab = 'employees'"
                    class="btn btn-secondary">Employees
                        <span class="badge badge-info badge-pill">
                            {{data.workers && data.workers.length > 0 ? data.workers.length : 0}}
                        </span>
                </button>
                <button type="button"
                    :class="{'active': showTab == 'business_hours'}"
                    @click="showTab = 'business_hours'"
                    class="btn btn-secondary">Business hours</button>
            </div>
            <div class="modal-body">
                
                <template v-if="showTab == 'main'">
                    <table class="info-table">
                        <tr v-if="data.short_description">
                            <td>Short description:</td>
                            <td><b>{{ data.short_description }}</b></td>
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
                
                <template v-if="showTab == 'employees'">
                    <template v-if="data.workers && data.workers.length > 0">
                        <table class="info-table">
                            <tr v-for="worker in data.workers">
                                <td>{{ fullName(worker.first_name, worker.last_name) }}:</td>
                                <td>
                                    <b>{{ worker.email }}</b>
                                </td>
                            </tr>
                        </table>
                    </template>
                </template>
                
                <template v-if="showTab == 'business_hours'">
                    <template v-if="data.business_hours">
                        <table class="info-table">
                            <template v-for="business_hour in JSON.parse(data.business_hours)">
                                <tr v-if="!business_hour.is_weekend">
                                    <td>{{ business_hour.weekday }}:</td>
                                    <td class="text-uppercase text-success"><b>{{ business_hour.start + ' - ' + business_hour.end }}</b></td>
                                </tr>
                                <tr v-else>
                                    <td>{{ business_hour.weekday }}:</td>
                                    <td class="text-uppercase text-danger"><b>closed</b></td>
                                </tr>
                            </template>
                        </table>
                    </template>
                </template>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // this.initDataTable();
            // this.regClickMoreInfoBtn();
        },
        props: ['data'],
        data: function(){
            return {
                showTab: 'main',
                // workers: null,
            };
        },
        computed: {
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
        },
        methods: {
            fullName: function(firstName = null, lastName = null){
                if(firstName == null && lastName != null)
                    return lastName;
                if(lastName == null && firstName != null)
                    return firstName;
                if(lastName != null && firstName != null)
                    return firstName + ' ' + lastName;
                return '';
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
        overflow: hidden;
        .modal-header{
            // position: relative;
            // padding-bottom: 30px;
            // .badge{
            //     position: absolute;
            //     left: 10px;
            //     bottom: 5px;
            // }
        }
        .btn-group{
            // position: relative;
            // top: -2px;
            margin-top: -1px;
            border-radius: 0px;
            .btn{
                border-radius: 0px!important;
            }
        }
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
                        // .alert{
                        //     padding: 4px 10px;
                        //     &.bh-alert{
                        //         margin-bottom: 4px;
                        //         text-align: right;
                        //         .bh-weekday{
                        //             display: inline-block;
                        //             width: 100px;
                        //             text-align: left;
                        //             padding-right: 15px;
                        //             text-transform: uppercase;
                        //             float: left;
                        //         }
                        //     }
                        // }
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