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
                <div class="for-info-table">
                    
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
                    
                </div>
            </template>
            
            <template v-if="showTab == 'bookings'">
                <div class="for-bookings-list">
                    
                    <div class="for-booking-item" v-for="itm in allBookings" :class="{'approved-book': itm.approved, 'requested-book': !itm.approved}">
                        
                        <div class="booking-item">
                            <!-- <button type="button"
                                class="close">
                                    <span>×</span>
                            </button> -->
                            
                            <div class="drop-cancel btn-group dropleft float-right">
                                <button type="button"
                                    class="close"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    :id="'bookings_dropdown_toogle_' + itm.id">
                                        <span>×</span>
                                </button>
                                <!-- <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Dropleft
                                </button> -->
                                <div @click.stop class="dropdown-menu">
                                    {{
                                        itm.approved ?
                                        'Do you really want delete this booking?' :
                                        'Do you really want delete this request on booking?'
                                    }}
                                    <div>
                                        <a class="btn btn-sm btn-link" @click.prevent="cancel(itm)" href="#">Yes</a>
                                        <a class="btn btn-sm btn-link" @click.prevent="closeDropdown('bookings_dropdown_toogle_' + itm.id)" href="#">No</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <a tabindex="0" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?">Dismissible popover</a> -->
                            
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="small">{{itm.approved ? 'Booked on:' : 'In approving:'}}</div>
                                            <div class="item-date">{{getStartDate(itm.time)}}</div>
                                            <div class="item-time"><b>{{getStartTime(itm.time)}}</b></div>
                                        </td>
                                        <td>
                                            <div class="book-item-property">
                                                <b>{{itm.template_without_user_scope.title}}</b>
                                                <span>({{customTitle('template')}})</span>
                                            </div>
                                            <div class="book-item-property">
                                                {{itm.worker_without_user_scope.first_name + ' ' + itm.worker_without_user_scope.last_name}}
                                                <span>({{customTitle('worker')}})</span>
                                            </div>
                                            <div class="book-item-property">
                                                {{itm.hall_without_user_scope.title}}
                                                <span>({{customTitle('hall')}})</span>
                                            </div>
                                            <div class="book-item-property">
                                                {{parseSecondsToHourMinuteString(itm.template_without_user_scope.duration)}}
                                                <span>(Duration)</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    
                </div>
            </template>
            <template v-if="showTab == 'logout'">
                <div class="pt-3 pb-3">
                    Do you really want to logout?
                    <div>
                        <button @click="logout" type="button" class="btn btn-link">Yes</button>
                    </div>
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
            
            // $('.popover-dismiss').popover({
            //     trigger: 'focus'
            // });
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
                componentApp: null,
                // signupErrors: null,
                // signinErrors: null,
                // clientInfoToShow: null,
            };
        },
        computed: {
            customTitle: function(){
                return (name) => {
                    return this.$store.getters['custom_titles/title'](name);
                }
            },
            allBookings: function () {
                return this.$store.getters['client/bookings'];
            },
            fullName: function () {
                if(this.clientInfo == null)
                    return 'guest';
                return this.clientInfo.first_name + ' ' + this.clientInfo.last_name;
            },
        },
        methods: {
            cancel: function (booking){
                let componentApp = this.getParentComponentByName(this, 'app');
                componentApp.cancelBooking(booking, (response) => {
                    console.log('success');
                    // this.onCancel(response.data);
                });
            },
            // deleteRequest: function (dropdownToogleId){
            //     $(document).find("#" + dropdownToogleId).click();
            // },
            closeDropdown: function (dropdownToogleId){
                $(document).find("#" + dropdownToogleId).click();
            },
            getStartDate: function (dateTime){
                return moment(dateTime).format('YYYY-MM-DD');
            },
            getStartTime: function (dateTime){
                return moment(dateTime).format('HH:mm');
            },
            cancel: function (booking){
                // console.log(booking);
                // return;
                if(this.componentApp == null)
                    this.componentApp = this.getParentComponentByName(this, 'app');
                this.componentApp.cancelBooking(booking, (response) => {
                    console.log('success');
                    // this.onCancel(response.data);
                });
            },
            logout: function(){
                if(this.componentApp == null)
                    this.componentApp = this.getParentComponentByName(this, 'app');
                this.componentApp.logout();
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .modal-content{
        background-color: #6c757d;
        .modal-header{
            .modal-title{
                display: block;
                width: 100%;
            }
            padding-right: 16px!important;
        }
        .modal-body{
            position: relative;
            background-color: #fff;
            padding-right: 0px;
            padding-top: 0px;
            padding-bottom: 0px;
            line-height: 1.2em;
            .for-info-table{
                padding-right: 16px!important;
                padding-top: 16px;
                padding-bottom: 16px;
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
                    // &.bookings-list{
                    //     td{
                    //         vertical-align: top;
                    //         &:first-child{
                    //             width: 140px;
                    //             // text-align: right;
                    //             // padding-right: 10px;
                    //         }
                    //     }
                    // }
                }
            }
            .for-bookings-list{
                max-height: 300px;
                overflow-x: auto;
                padding-right: 16px;
                padding-top: 16px;
                padding-bottom: 16px;
                .for-booking-item{
                    padding-bottom: 15px;
                    &:last-child{
                        padding-bottom: 0px;
                    }
                    button.close{
                        position: absolute;
                        z-index: 99;
                        top: 0px;
                        right: 0px;
                        outline: none!important;
                        // background-color: #6c757d;
                        border-radius: 0px;
                        width: 35px;
                        height: 35px;
                        font-size: 50px;
                        line-height: .5em;
                        padding: 0px;
                        text-decoration: none;
                        // color: #fff;
                        color: #6c757d;
                        opacity: 1;
                        transition: opacity .3s ease;
                        box-shadow: none;
                        font-weight: normal;
                        cursor: pointer;
                        span{
                            display: block;
                            width: 100%;
                            height: 100%;
                        }
                    }
                    
                    position: relative;
                    &:hover .booking-item{
                        -webkit-box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                        -moz-box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                        box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                    }
                    .booking-item{
                        background-color: #f1f1f1;
                        // padding: 6px;
                        position: relative;
                        border-radius: 4px;
                        overflow: hidden;
                        transition: box-shadow .3s;
                        -webkit-box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
                        -moz-box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
                        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
                        // &:hover{
                        //     -webkit-box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                        //     -moz-box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                        //     box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
                        // }
                        table{
                            width: 100%;
                            margin: 0px;
                            td{
                                padding: 6px;
                                vertical-align: top;
                                position: relative;
                                &:first-child{
                                    width: 110px;
                                    background-color: #e1e1e1;
                                    color: white;
                                    // text-align: right;
                                    // padding-right: 10px;
                                    b{
                                        
                                    }
                                    .item-date{
                                        font-weight: bold;
                                        color: #fae0d6;
                                        font-size: 12px;
                                        padding-top: 4px;
                                        // border-bottom: 1px solid white;
                                    }
                                    .item-time{
                                        position: relative;
                                        top: -2px;
                                    }
                                }
                                &:last-child{
                                    padding-left: 20px;
                                    .book-item-property{
                                        span{
                                            font-size: 14px;
                                            color: #999;
                                        }
                                    }
                                }
                            }
                        }
                        .drop-cancel{
                            .dropdown-menu{
                                // margin-top: -10px;
                                // z-index: 99999;
                                margin-right: 10px;
                                padding-left: 10px;
                                padding-right: 10px;
                                width: 260px;
                            }
                            
                            .dropdown-menu:after, .dropdown-menu:before {
                            	left: 100%;
                            	top: 15px;
                            	border: solid transparent;
                            	content: "";
                            	height: 0;
                            	width: 0;
                            	position: absolute;
                            	pointer-events: none;
                            }

                            .dropdown-menu:after {
                            	border-color: rgba(255, 255, 255, 0);
                            	border-left-color: #fff;
                            	border-width: 10px;
                            	margin-top: -10px;
                            }
                            .dropdown-menu:before {
                            	border-color: rgba(0, 0, 0, 0);
                            	border-left-color: rgba(0,0,0,.15);
                            	border-width: 11px;
                            	margin-top: -11px;
                            }
                        }
                        // &.requested-book{
                        //     td{
                        //         &:first-child{
                        //             background-color: #cf582c;
                        //         }
                        //     }
                        // }
                    }// \.booking-item
                    
                    &.requested-book{
                        td{
                            &:first-child{
                                background-color: #cf582c!important;
                            }
                        }
                    }
                    &.approved-book{
                        td{
                            &:first-child{
                                background-color: rgba(114,51,128, 1)!important;
                            }
                        }
                    }
                }
            }
        }
        .modal-header, .modal-footer{
            background-color: #6c757d;
            color: white;
        }
    }
</style>