<template>
    <div v-else class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button @click="showTab = 'info'"
                        :class="{'active': showTab == 'info'}"
                        type="button"
                        class="btn btn-info">Info</button>
                    <button @click="showTab = 'bookings'"
                        :class="{'active': showTab == 'bookings'}"
                        type="button"
                        class="btn btn-info">Bookings</button>
                </div>
                <button @click="showTab = 'logout'"
                    :class="{'active': showTab == 'logout'}"
                    type="button"
                    class="btn btn-secondary float-right">Logout</button>
            </h5>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            
            <template v-if="showTab == 'info'">
                <table class="info-table">
                    <tr>
                        <td>Name: </td>
                        <td>{{fullName}}</td>
                    </tr>
                    <tr>
                        <td>Email: </td>
                        <td>{{clientInfo.email}}</td>
                    </tr>
                    <tr>
                        <td>Phone: </td>
                        <td>{{clientInfo.phone}}</td>
                    </tr>
                </table>
            </template>
            <template v-if="showTab == 'bookings'">
                bookings
            </template>
            <template v-if="showTab == 'logout'">
                Do you really want to logout?
                <div>
                    <button @click="logout" type="button" class="btn btn-link">Yes</button>
                    <button @click="showTab = 'info'"
                        type="button"
                        class="btn btn-link">No</button>
                </div>
            </template>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'modalClientInfoContent',
        mounted() {
            
            // console.log(11111);
            // console.log(component.$options.name);
            
            $('#enterModal').on('hidden.bs.modal', () => {
                this.showTab = 'info';
            })
        },
        // props: ['range','view','curreny_view_idx','currentDate'],
        props: ['clientInfo'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                showTab: 'info',
                // signupErrors: null,
                // signinErrors: null,
                // clientInfoToShow: null,
            };
        },
        computed: {
            fullName: function () {
                if(this.clientInfo == null)
                    return 'guest';
                return this.clientInfo.first_name + ' ' + this.clientInfo.last_name;
            },
        },
        methods: {
            logout: function(){
                let componentApp = this.getParentComponentByName(this, 'app');
                componentApp.logout();
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .modal-content{
        background-color: #6c757d;
        .modal-body{
            position: relative;
            background-color: #fff;
            table{
                width: 100%;
                &.info-table{
                    tr:nth-child(odd){
                        td{
                            background-color: #f1f1f1;
                        }
                    }
                    td{
                        vertical-align: top;
                        padding: 6px 0px;
                        &:first-child{
                            width: 70px;
                            text-align: right;
                            padding-right: 10px;
                        }
                    }
                }
            }
        }
        .modal-header, .modal-footer{
            background-color: #6c757d;
            color: white;
        }
        .modal-header{
            position: relative;
            .modal-title{
                font-size: 14px;
                font-weight: normal;
                color: #f4f4f4;
                width: 100%;
                b{
                    color: white;
                }
            }
            .close{
                font-size: 60px;
                color: #fff;
                opacity: .7;
                transition: opacity .3s ease;
                line-height: .8em;
                padding: 0px;
                margin: 0px;
                position: absolute;
                top: 0px;
                right: 0px;
                height: 60px;
                width: 60px;
            }
            .close span{
                position: absolute;
                top: 0px;
                right: 0px;
                height: 60px;
                width: 60px;
            }
            .close:hover{
                color: #fff;
                opacity: 1!important;
            }
        }
    }
</style>