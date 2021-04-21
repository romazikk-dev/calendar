<template>
    <div>
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    dsdsds                    
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="status alert alert-info" role="alert">
                    This is a info alert—check it out!
                </div>
                <table>
                    <tbody>
                        <tr>
                            <td>From:</td>
                            <td>
                                <input type="text" id="from" name="from" autocomplete="off" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td>To:</td>
                            <td>
                                <input type="text" id="to" name="to" autocomplete="off" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="#" onclick="
                                    event.preventDefault();
                                    $('#from, #to').val('');
                                ">Reset</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
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
            // createdAt: function(){
            //     if(this.data == null || this.data.created_at == null)
            //         return '';
            //     return moment(this.data.created_at).format('DD-MM-YYYY');
            // },
            // updatedAt: function(){
            //     if(this.data == null || this.data.updated_at == null)
            //         return '';
            //     return moment(this.data.updated_at).format('DD-MM-YYYY');
            // },
        },
        methods: {
            // fullName: function(firstName = null, lastName = null){
            //     if(firstName == null && lastName != null)
            //         return lastName;
            //     if(lastName == null && firstName != null)
            //         return firstName;
            //     if(lastName != null && firstName != null)
            //         return firstName + ' ' + lastName;
            //     return '';
            // },
            // resetAllModalsData: function(){
            //     this.infoModalData = null;
            // },
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