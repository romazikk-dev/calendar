<template>
    <div>
        
        <div v-if="showWarningAlert" class="alert alert-warning" role="alert">
            Selected <b class="text-uppercase">0</b> employees!
        </div>
        
        <div>
            <button @click="openModal()" type="button" class="btn btn-primary btn-sm open-select-item-modal">
                Select
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                </svg>
            </button>
            
            <div v-for="(item,index) in selectedItems" class="alert-item alert alert-primary" role="alert">
                <b>
                    {{item.first_name}} {{item.last_name}}
                </b><br>
                <span class="small">{{item.email}}</span>
                <input class="assign-item" :name="`assign_workers[` + item.id + `]`" type="checkbox" checked>
                <button @click="dismissSelected(item)" type="button" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" id="itemAssignmentModal" tabindex="-1" aria-labelledby="itemAssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemAssignmentModalLabel">Select workers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <table class="all-items-list">
                            <tbody>
                                <tr v-for="(item,index) in items">
                                    <td>
                                        <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                            <input class="custom-control-input" :id="`item_` + item.id" type="checkbox" v-model="item.selected">
                                            <label class="custom-control-label" :for="`item_` + item.id"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <label :for="`item_` + item.id">
                                            <b>
                                                {{item.first_name}} {{item.last_name}}
                                            </b><br>
                                            <span class="small">
                                                {{item.email}}
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="applySelect()" type="button" class="btn btn-primary">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(assignWorkers);
            // console.log(JSON.parse(JSON.stringify(assignWorkers)));
            // setTimeout(() => {
    		// 	this.setAssignWorkers();
    		// }, 300);
            
            this.setAssignWorkers();
            this.recalculateBadgeValue(true);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                items: null,
                selectedItems: [],
                assignWorkers: assignWorkers
            };
        },
        computed: {
            showWarningAlert: function () {
                // if(this.items == null || this.selectedItems.length == 0)
                //     return false;
                // console.log('showWarningAlert');
                if(this.isJqueryValidationEnabled())
                    return this.selectedItems.length == 0;
                let assignWorkersIds = this.assignWorkers == null ? [] : Object.keys(this.assignWorkers);
                console.log('showWarningAlert');
                // console.log(JSON.parse(JSON.stringify(this.assignWorkers)));
                return assignWorkersIds.length == 0;
            },
        },
        methods: {
            isJqueryValidationEnabled: function(){
                return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            openModal: function(){
                // e.preventDefault();
                if(this.items == null){
                    axios.post(dataListUrl).then((response) => {
                        let items = response.data.data;
                        for(let idx in items){
                            items[idx].selected = false;
                        }
                        this.items = items;
                        console.log(JSON.parse(JSON.stringify(this.items)));
                        $("#itemAssignmentModal").modal('show');
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else{
                    console.log(JSON.parse(JSON.stringify(this.items)));
                    $("#itemAssignmentModal").modal('show');
                }
            },
            recalculateBadgeValue: function(init = false){
                let _this = this;
                let tabId = "#worker-tab";
                
                if(init == true && this.showWarningAlert && !this.isJqueryValidationEnabled()){
                    $(tabId).find('.notice-badges').find('.notice-badge-warning').removeClass('d-none');
                    return;
                }
                if(init == true && !this.showWarningAlert && !this.isJqueryValidationEnabled()){
                    let interval = setInterval(function(){
                        if(_this.selectedItems.length > 0){
                            clearInterval(interval);
                            recalculateBadgeValue();
                        }
                    }, 50);
                }
                if(!this.isJqueryValidationEnabled())
                    return;
                
                recalculateBadgeValue();
                
                function recalculateBadgeValue(){
                    let noticeBadges = $(tabId).find('.notice-badges');
                    // console.log(noticeBadge);
                    
                    noticeBadges.find('.notice-badge').addClass('d-none');
                    if(_this.selectedItems.length > 0){
                        noticeBadges.find('.notice-badge-success').removeClass('d-none')
                            .attr('data-original-title', _this.selectedItems.length + ' employees').text(_this.selectedItems.length);
                    }else{
                        noticeBadges.find('.notice-badge-warning').removeClass('d-none');
                    }
                }
            },
            applySelect: function(){
                if(this.items != null){
                    let selectedItems = [];
                    for(let idx in this.items){
                        if(this.items[idx].selected)
                            selectedItems.push(this.items[idx]);
                    }
                    this.selectedItems = JSON.parse(JSON.stringify(selectedItems));
                    $("#itemAssignmentModal").modal('hide');
                    
                    this.recalculateBadgeValue();
                }
            },
            dismissSelected: function(item){
                // e.preventDefault();
                if(this.items != null){
                    for(let idx in this.items){
                        if(this.items[idx].selected && this.items[idx].id == item.id)
                            this.items[idx].selected = false;
                    }
                    this.applySelect();
                }
            },
            setAssignWorkers: function(){
                
                if(typeof this.assignWorkers != 'undefined' && this.assignWorkers != null){
                    console.log('setAssignWorkers');
                    // console.log(JSON.parse(JSON.stringify(this.assignWorkers)));
                    let assignWorkersIds = Object.keys(this.assignWorkers);
                    // console.log(JSON.parse(JSON.stringify(assignWorkersIds)));
                    if(this.items == null){
                        axios.post(dataListUrl).then((response) => {
                            let items = response.data.data;
                            for(let idx in items){
                                // console.log(assignWorkersIds, items[idx].id);
                                if(assignWorkersIds.includes(items[idx].id.toString())){
                                    items[idx].selected = true;
                                }else{
                                    items[idx].selected = false;
                                }
                            }
                            // this.items = JSON.parse(JSON.stringify(items));
                            this.items = items;
                            this.applySelect();
                            // console.log(JSON.parse(JSON.stringify(this.items)));
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        // console.log(JSON.parse(JSON.stringify(this.items)));
                        // $("#itemAssignmentModal").modal('show');
                        for(let idx in this.items){
                            if(assignWorkersIds.includes(items[idx].id)){
                                this.items[idx].selected = true;
                            }else{
                                this.items[idx].selected = false;
                            }
                        }
                        this.applySelect();
                    }
                }
                
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    h5{
        position: relative;
        top: 5px;
    }
    #itemAssignmentModal ul{
        padding: 0px;
        margin: 0px;
    }
    #itemAssignmentModal ul li{
        padding: 0px;
        margin: 0px;
        list-style: none;
    }
    .open-select-item-modal{
        svg{
            position: relative;
            top: -2px;
        }
    }
    .assign-item{
        display: none;
    }
    .alert-item{
        margin-bottom: 0px;
        margin-top: 10px;
        line-height: 1em;
        span{
            &.small{
                line-height: 1em;
            }
        }
        .close{
            position: absolute;
            top: 5px;
            right: 10px;
        }
    }
    table.all-items-list{
        width: 100%;
        tr{
            td{
                vertical-align: top;
                line-height: 1em;
                border-top: 1px solid #dee2e6;
                label{
                    cursor: pointer;
                }
                &:first-child{
                    width: 40px;
                    padding-left: 14px;
                }
                &:last-child{
                    padding-top: 7px;
                    label{
                        display: block;
                        width: 100%;
                    }
                }
            }
            &:nth-child(odd){
                td{
                    background-color: rgba(0,0,0,.05);
                }
            }
            &:last-child{
                td{
                    border-bottom: 1px solid #dee2e6;
                }
            }
        }
    }
    /* .card-body{
        padding: 0px;
    }
    .card-padding{
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    } */
</style>