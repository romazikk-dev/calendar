<template>
    <div class="template-assignment">
        
        <div v-if="showWarningAlert" class="alert alert-warning" role="alert">
            Selected <b class="text-uppercase">0</b> templates!
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
                    {{item.title}}
                </b><br>
                <div class="text-lowercase small"
                    v-html="getTitledTrace(item)"></div>
                <span class="small">
                    <template v-if="item.price != null">
                        <span class="badge badge-success">
                            {{item.price}} $
                        </span>
                    </template>
                    <template v-else>
                        <span class="badge badge-warning">
                            no price
                        </span>
                    </template>
                </span>
                <span class="d-block mt-1 small">
                    <span class="">Duration:</span>
                    <b>{{item.duration}}</b>
                </span>
                <input class="assign-item" :name="`assign_templates[` + item.id + `]`" type="checkbox" checked>
                <button @click="dismissSelected(item)" type="button" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">Select templates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <dropdown @filter="filter($event)" :specifics="filterSpecifics" />
                        
                        <div class="for-all-items-list">
                            <table class="all-items-list">
                                <tbody>
                                    <tr v-for="(item,index) in items" v-if="item.show && filteredToShow(item)">
                                        <td>
                                            <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                                <input class="custom-control-input" :id="`temp_` + item.id" type="checkbox" v-model="item.selected">
                                                <label class="custom-control-label" :for="`temp_` + item.id"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <label :for="`temp_` + item.id">
                                                <b>
                                                    {{item.title}}
                                                </b><br>
                                                <div class="text-lowercase small"
                                                    v-html="getTitledTrace(item)"></div>
                                                <span class="small">
                                                    <template v-if="item.price != null">
                                                        <span class="badge badge-success">
                                                            {{item.price}} $
                                                        </span>
                                                    </template>
                                                    <template v-else>
                                                        <span class="badge badge-warning">
                                                            no price
                                                        </span>
                                                    </template>
                                                </span>
                                                <span class="d-block mt-1 small">
                                                    <span class="">Duration:</span>
                                                    <b>{{item.duration}}</b>
                                                </span>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
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
    import Dropdown from "./FilterDropdown.vue";
    export default {
        mounted() {
            // console.log(assignItems);
            // console.log(JSON.parse(JSON.stringify(assignItems)));
            // setTimeout(() => {
    		// 	this.setAssignItems();
    		// }, 300);
            
            // console.log(JSON.parse(JSON.stringify(44444)));
            // console.log(JSON.parse(JSON.stringify(this.items)));
            
            this.setAssignItems();
            this.recalculateBadgeValue(true);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                items: null,
                selectedItems: [],
                assignItems: assignTemplates,
                // assignTemplates: [],
                dataListUrl: templateDataListUrl,
                modalId: 'tempAssignmentModal',
                specificsAsKeyId: typeof specificsAsKeyId === 'object' && specificsAsKeyId !== null ? specificsAsKeyId : null,
                filterSpecifics: null,
                filterBySpecificId: null,
            };
        },
        computed: {
            showWarningAlert: function () {
                // if(this.items == null || this.selectedItems.length == 0)
                //     return false;
                // console.log('showWarningAlert');
                if(this.isJqueryValidationEnabled())
                    return this.selectedItems.length == 0;
                let assignItemsIds = this.assignItems == null ? [] : Object.keys(this.assignItems);
                console.log('showWarningAlert');
                // console.log(JSON.parse(JSON.stringify(this.assignItems)));
                return assignItemsIds.length == 0;
            },
            arrowRightIcon: function () {
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                `;
            },
            selectedItemsKeyAsId: function(){
                if(this.selectedItems === null)
                    return null;
                let selectedItemsKeyAsId = {}
                for(let idx in this.selectedItems){
                    selectedItemsKeyAsId[this.selectedItems[idx].id] = this.selectedItems[idx];
                }
                return selectedItemsKeyAsId;
            }
        },
        methods: {
            isJqueryValidationEnabled: function(){
                return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            },
            filter: function(event){
                this.filterBySpecificId = event;
            },
            filteredToShow: function(template){
                if(this.filterBySpecificId === null)
                    return true;
                
                if(template.specificIdsTraceArr.includes(this.filterBySpecificId))
                    return true;
                // console.los(JSON.parse(JSON.stringify(template)));
                return false;
            },
            getTitledTrace: function(item){
                return item.specific_titled_trace.join(this.arrowRightIcon);
            },
            setFilterSpecifics: function(){
                let _this = this;
                
                // alert(111);
                let filterSpecifics = {};
                for(let idx in this.items){
                    let item = this.items[idx];
                    let specific = item.specific;
                    if(typeof specific.ids_trace !== undefined && specific.ids_trace !== null){
                        let idsTraceArr = (specific.ids_trace.split(',')).map(val => parseInt(val));
                        
                        if(idsTraceArr.length == 0){
                            idsTraceArr = [specific.id];
                        }else{
                            idsTraceArr.push(specific.id);
                        }
                        
                        let idsTracePath = '';
                        if(idsTraceArr.length > 0){
                            for(let idxx in idsTraceArr){
                                if(typeof _this.specificsAsKeyId[idsTraceArr[idxx]] === 'undefined')
                                    continue;
                                
                                // console.log(22222);
                                // console.log(idsTraceArr);
                                    
                                let traceSpecific = _this.specificsAsKeyId[idsTraceArr[idxx]];
                                if(idsTracePath == ''){
                                    idsTracePath += '[' + idsTraceArr[idxx] + ']';
                                }else{
                                    idsTracePath += '.fields[' + idsTraceArr[idxx] + ']';
                                }
                                
                                // console.log(22222);
                                // console.log(idsTracePath);
                                
                                eval(`
                                    if(typeof filterSpecifics${idsTracePath} === 'undefined'){
                                        filterSpecifics${idsTracePath} = {
                                            type: 'specific',
                                            id: ${traceSpecific.id},
                                            title: '${traceSpecific.title}',
                                            count: 1,
                                            fields: {},
                                        }
                                    }else{
                                        filterSpecifics${idsTracePath}.count++;
                                    }
                                `);
                                
                                // console.log('filterSpecifics');
                                // console.log(filterSpecifics);
                            }
                            // console.log('specific.ids_trace');
                            // console.log(idsTraceArr);
                        }
                        
                        // if(idsTracePath == ''){
                        //     idsTracePath += '[' + item.id + ']';
                        // }else{
                        //     idsTracePath += '.fields[' + item.id + ']';
                        // }
                        // // 
                        // eval(`
                        //     if(typeof filterSpecifics${idsTracePath} === 'undefined')
                        //         filterSpecifics${idsTracePath} = {
                        //             type: 'template',
                        //             id: ${item.id},
                        //             title: '${item.title}',
                        //         }
                        // `);
                    }
                    // console.log('specific.ids_trace');
                    // console.log(idsTraceArr);
                }
                
                console.log('filterSpecifics');
                console.log(filterSpecifics);
                this.filterSpecifics = filterSpecifics;
            },
            openModal: function(){
                let _this = this;
                
                // e.preventDefault();
                // if(this.items == null){
                    axios.post(_this.dataListUrl).then((response) => {
                        let items = response.data.data;
                        for(let idx in items){
                            if(_this.selectedItemsKeyAsId !== null && typeof _this.selectedItemsKeyAsId[items[idx].id] !== 'undefined' &&
                            _this.selectedItemsKeyAsId[items[idx].id].selected !== 'undefined'){
                                items[idx].selected = _this.selectedItemsKeyAsId[items[idx].id].selected;
                            }else{
                                items[idx].selected = false;
                            }
                            items[idx].show = true;
                            
                            if(typeof items[idx].specific !== 'undefined' && typeof items[idx].specific.ids_trace !== 'undefined'){
                                if(items[idx].specific.ids_trace !== null){
                                    items[idx].specificIdsTraceArr = (items[idx].specific.ids_trace.split(',')).map(val => parseInt(val));
                                    items[idx].specificIdsTraceArr.push(items[idx].specific.id);
                                }else{
                                    items[idx].specificIdsTraceArr = [items[idx].specific.id];
                                }
                            }else{
                                items[idx].specificIdsTraceArr = [];
                            }
                            // items[idx].show = false;
                            // items[idx].selected = false;
                        }
                        _this.items = items;
                        _this.setFilterSpecifics();
                        
                        console.log(JSON.parse(JSON.stringify(7777777)));
                        console.log(JSON.parse(JSON.stringify(this.items)));
                        console.log(JSON.parse(JSON.stringify(this.selectedItemsKeyAsId)));
                        
                        $("#" + _this.modalId).modal('show');
                    }).catch(function (error) {
                        console.log(error);
                    });
                // }else{
                //     console.log(JSON.parse(JSON.stringify(this.items)));
                //     $("#" + _this.modalId).modal('show');
                // }
            },
            recalculateBadgeValue: function(init = false){
                let _this = this;
                let tabId = "#template-tab";
                
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
                            .attr('data-original-title', _this.selectedItems.length + ' templates').text(_this.selectedItems.length);
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
                    $("#" + this.modalId).modal('hide');
                    
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
            setAssignItems: function(){
                let _this = this;
                
                if(typeof this.assignItems != 'undefined' && this.assignItems != null){
                    console.log('assignItems');
                    // console.log(JSON.parse(JSON.stringify(this.assignItems)));
                    let assignItemsIds = Object.keys(this.assignItems);
                    // console.log(JSON.parse(JSON.stringify(assignItemsIds)));
                    if(this.items == null){
                        axios.post(_this.dataListUrl).then((response) => {
                            let items = response.data.data;
                            for(let idx in items){
                                // console.log(assignItemsIds, items[idx].id);
                                if(assignItemsIds.includes(items[idx].id.toString())){
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
                        // $("#" + this.modalId).modal('show');
                        for(let idx in this.items){
                            if(assignItemsIds.includes(items[idx].id)){
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
            Dropdown
        },
        watch: {
            items: function(val){
                if(val !== null)
                    console.log(JSON.parse(JSON.stringify(44444)));
                    console.log(JSON.parse(JSON.stringify(this.items)));
            },
        }
    }
</script>

<style lang="scss" scoped>
    h5{
        position: relative;
        top: 5px;
    }
    #tempAssignmentModal{
        ul{
            padding: 0px;
            margin: 0px;
            li{
                padding: 0px;
                margin: 0px;
                list-style: none;
            }
        }
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
    .for-all-items-list{
        max-height: 300px;
        overflow-x: auto;
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
    }
</style>