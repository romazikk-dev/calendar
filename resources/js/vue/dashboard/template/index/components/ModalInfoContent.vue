<template>
    <div>
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    {{ title }}
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
                    class="btn btn-secondary">
                        Employees
                        <span class="text-warning"
                            v-if="!data.workers.length"
                            data-toggle="modal-info-dropdown"
                            data-placement="top"
                            title="No employees">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                </svg>
                        </span>
                        <span class="badge badge-pill badge-info" v-else
                            data-toggle="modal-info-dropdown"
                            data-placement="top"
                            :title="data.workers.length + ' employees'">
                                {{data.workers.length}}
                        </span>
                        <!-- <span class="badge badge-info badge-pill">
                            {{data.workers && data.workers.length > 0 ? data.workers.length : 0}}
                        </span> -->
                </button>
            </div>
            <div class="modal-body">
                
                <template v-if="showTab == 'main'">
                    <table class="info-table">
                        <tr v-if="data.title">
                            <td>Title:</td>
                            <td><b>{{ data.title }}</b></td>
                        </tr>
                        <tr v-if="data.notice">
                            <td>Notice:</td>
                            <td><b>{{ data.notice }}</b></td>
                        </tr>
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
                    </table>
                </template>
                
                <template v-if="showTab == 'employees'">
                    <template v-if="data.workers && data.workers.length > 0">
                        <table class="info-table">
                            <tr v-for="worker in data.workers">
                                <td>{{ fullName(worker) }}:</td>
                                <td>
                                    <b>{{ worker.email }}</b>
                                </td>
                            </tr>
                        </table>
                    </template>
                    <template v-else>
                        No employees
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
            // this.initDataTable();
            // this.regClickMoreInfoBtn();
            $('[data-toggle="modal-info-dropdown"]').tooltip({ html: true });
        },
        props: ['data'],
        data: function(){
            return {
                showTab: 'main',
                // workers: null,
                weekdays: ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'],
            };
        },
        computed: {
            title: function(){
                return helper.capitalizeFirstLetter(this.data.title);
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
        },
        methods: {
            resetAllModalsData: function(){
                this.infoModalData = null;
            },
            fullName: function(worker){
                let fullNameArr = [];
                if(!helper.isPropEmpty(worker.first_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(worker.first_name));
                if(!helper.isPropEmpty(worker.last_name))
                    fullNameArr.push(helper.capitalizeFirstLetter(worker.last_name));
            
                return fullNameArr.join(' ');
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