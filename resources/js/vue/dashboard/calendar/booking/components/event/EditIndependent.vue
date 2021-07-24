<template>
    <div>
        
        <div class="card bg-light filter-block">
            <div class="card-header">
                Clients: <span class="badge badge-info">{{!isCheckedClients ? 'all' : checkedClientsAsArr.length}}</span>
                <div class="btn-group float-right" role="group">
                    <button v-if="isCheckedClients"
                        @click="checkedClients = {}"
                        class="btn btn-sm btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                            </svg>
                    </button>
                    <button v-if="isCheckedClientsChanged"
                        @click="setCheckedItems('client')"
                        class="btn btn-sm btn-light">Reset</button>
                    <button class="btn btn-sm btn-light" @click="toogleFilterBlock('clients')">
                        <svg v-if="!collapseClients"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <svg v-if="collapseClients"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </button>
                </div>
                <div class="checked-items" v-if="isCheckedClients">
                    <span v-for="(item, index) in parsedClients"
                        v-if="item.checked"
                        class="badge badge-success checked-item">
                            {{fullNameOfObj(item)}}
                            <span @click="onClientCheckedRemove(item)"
                                class="checked-item-close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </span>
                    </span>
                </div>
            </div>
            <div class="card-body" v-if="!collapseClients">
                
                <dropdown-checkboxes :items="parsedClients"
                    v-show="!collapseClients"
                    @change="onClientDropdownChange"
                    :btn-class="{
                        'btn-secondary-custom': true,
                    }"
                    ref="dropdown_clients"
                    :emptyable="true"
                    :small="true"
                    :small-search="true"
                    max-dropdown-menu-height="200px"
                    picked-item-placeholder="Pick a client(s) ..." />
                
            </div>
        </div>
        
        <div class="card bg-light filter-block">
            <div class="card-header">
                Hall: <span class="badge badge-info">{{!isCheckedHalls ? 'all' : checkedHallsAsArr.length}}</span>
                {{isCheckedHallsChanged}}
                <div class="btn-group float-right" role="group">
                    <button v-if="isCheckedHalls"
                        @click="checkedHalls = {}"
                        class="btn btn-sm btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                            </svg>
                    </button>
                    <button v-if="isCheckedHallsChanged"
                        @click="setCheckedItems('hall')"
                        class="btn btn-sm btn-light">Reset</button>
                    <button class="btn btn-sm btn-light" @click="toogleFilterBlock('halls')">
                        <svg v-if="!collapseHalls"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <svg v-if="collapseHalls"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </button>
                </div>
                <div class="checked-items" v-if="isCheckedHalls">
                    <span v-for="(item, index) in parsedHalls"
                        v-if="item.checked"
                        class="badge badge-success checked-item">
                            {{item.title}}
                            <span @click="onHallCheckedRemove(item)"
                                class="checked-item-close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </span>
                    </span>
                </div>
            </div>
            <div class="card-body" v-if="!collapseHalls">
                
                <dropdown-checkboxes :items="parsedHalls"
                    v-show="!collapseHalls"
                    @change="onHallDropdownChange"
                    :btn-class="{
                        'btn-secondary-custom': true,
                    }"
                    ref="dropdown_halls"
                    :emptyable="true"
                    :small="true"
                    :small-search="true"
                    max-dropdown-menu-height="200px"
                    picked-item-placeholder="Pick a hall(s) ..." />
                    
            </div>
        </div>
        
        <div class="card bg-light filter-block">
            <div class="card-header">
                Template: <span class="badge badge-info">{{!isCheckedTemplates ? 'all' : checkedTemplatesAsArr.length}}</span>
                <div class="btn-group float-right" role="group">
                    <button v-if="isCheckedTemplates"
                        @click="checkedTemplates = {}"
                        class="btn btn-sm btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                            </svg>
                    </button>
                    <button v-if="isCheckedTemplatesChanged"
                        @click="setCheckedItems('template')"
                        class="btn btn-sm btn-light">Reset</button>
                    <button class="btn btn-sm btn-light" @click="toogleFilterBlock('templates')">
                        <svg v-if="!collapseTemplates"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <svg v-if="collapseTemplates"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </button>
                </div>
                <div v-show="templateTab == 'with_specifics'">
                    
                </div>
                <div v-show="templateTab == 'just_templates'">
                    <div class="checked-items" v-if="isCheckedTemplates">
                        <span v-for="(item, index) in parsedTemplates"
                            v-if="item.checked"
                            class="badge badge-success checked-item">
                                {{item.title}}
                                <span @click="onTemplateCheckedRemove(item)"
                                    class="checked-item-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body" v-if="!collapseTemplates">
                
                <div class="btn-group mb-2" role="group">
                    <button @click="onClickWithSpecifics"
                        class="btn btn-sm"
                        :class="{
                            'btn-light': templateTab != 'with_specifics',
                            'btn-secondary-custom': templateTab == 'with_specifics',
                        }">With specifics</button>
                    <button @click="onClickJustTemplates"
                        class="btn btn-sm"
                        :class="{
                            'btn-light': templateTab != 'just_templates',
                            'btn-secondary-custom': templateTab == 'just_templates'
                        }">Just templates</button>
                </div>
                <div v-show="templateTab == 'with_specifics'">
                    <template-picker :templates="templates"
                        ref="template_picker"
                        :btn-class="{
                            'btn-info-custom': true,
                        }"
                        :specifics="templateSpecifics"
                        :specifics-as-id-key="templateSpecificsAsIdKey"
                        :picked-template-ids-trace="pickedTemplateIdsTrace" />
                </div>
                <div v-show="templateTab == 'just_templates'">
                        
                    <dropdown-checkboxes :items="parsedTemplates"
                        @change="onTemplateDropdownChange"
                        :btn-class="{
                            'btn-secondary-custom': true,
                        }"
                        ref="dropdown_templates"
                        :emptyable="true"
                        :small="true"
                        :small-search="true"
                        max-dropdown-menu-height="200px"
                        picked-item-placeholder="Pick a template(s) ..." />
                        
                    <!-- <div class="checked-items" v-if="isCheckedTemplates">
                        <span v-for="(item, index) in parsedTemplates"
                            v-if="item.checked"
                            class="badge badge-success checked-item">
                                {{item.title}}
                                <span @click="onTemplateCheckedRemove(index, item)"
                                    class="checked-item-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </span>
                        </span>
                    </div> -->
                    
                </div>
                    
            </div>
        </div>
        
        <div>
            <duration-range ref="duration_range"
                @change="setDurationRange"
                @collapse="toogleFilterBlock('duration')"
                :as-card="true"
                :init-range="initDurationRange"
                default-duration="180" />
        </div>
        
        <div class="card bg-light filter-block">
            <div class="card-header">
                Workers:
                <span class="badge badge-info">{{!isCheckedWorkers ? 'all' : checkedWorkersAsArr.length}}</span>
                <div class="btn-group float-right" role="group">
                    <button v-if="isCheckedWorkers"
                        @click="checkedWorkers = {}"
                        class="btn btn-sm btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-x">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                            </svg>
                    </button>
                    <button v-if="isCheckedWorkersChanged"
                        @click="setCheckedItems('worker')"
                        class="btn btn-sm btn-light">Reset</button>
                    <button class="btn btn-sm btn-light" @click="toogleFilterBlock('workers')">
                        <svg v-if="!collapseWorkers"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        <svg v-if="collapseWorkers"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </button>
                </div>
                <div class="checked-items" v-if="isCheckedWorkers">
                    <span v-for="(item, index) in parsedWorkers"
                        v-if="item.checked"
                        class="badge badge-success checked-item">
                            {{fullNameOfObj(item)}}
                            <span @click="onWorkerCheckedRemove(item)"
                                class="checked-item-close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </span>
                    </span>
                </div>
            </div>
            <div class="card-body" v-if="!collapseWorkers">
                
                <dropdown-checkboxes :items="parsedWorkers"
                    @change="onWorkerDropdownChange"
                    :btn-class="{
                        'btn-secondary-custom': true,
                    }"
                    ref="dropdown_workers"
                    :dropup="true"
                    :emptyable="true"
                    :small="true"
                    :small-search="true"
                    max-dropdown-menu-height="200px"
                    picked-item-placeholder="Pick a worker(s) ..." />
                
            </div>
        </div>
        
    </div>
</template>

<script>
    import ExtensiveTemplateFilterPicker from "../template/ExtensiveTemplateFilterPicker.vue";
    import Dropdown from "../elements/DropdownSearchable/Dropdown.vue";
    import DropdownCheckboxes from "../elements/DropdownSearchableCheckboxes/Dropdown.vue";
    import DurationRange from "./DurationRange.vue";
    import Checkbox from "../elements/Checkbox.vue";
    export default {
        name: 'editIndependetComponent',
        updated() {
            // this.initTabs();
        },
        mounted() {
            this.setClients();
            this.setTemplates();
            this.setWorkers();
            
            this.setCollapseDuration();
            this.setDurationRange();
            this.setCheckedItems();
        },
        // props: ['movingEvent'],
        data: function(){
            return {
                templateTab: 'with_specifics',
                
                fillingData: null,
                
                pickedTemplateIdsTrace: null,
                
                clients: null,
                workers: null,
                templates: null,
                
                checkedHalls: {},
                checkedWorkers: {},
                checkedTemplates: {},
                checkedClients: {},
                
                collapseHalls: false,
                collapseTemplates: false,
                collapseWorkers: false,
                collapseClients: false,
                collapseDuration: false,
                
                durationRange: null,
                
                // isCheckedDurationChanged: false,
            };
        },
        computed: {
            initDurationRange: function() {
                // return this.durationFilter !== null ? this.durationFilter : null;
                return this.durationFilter;
            },
            isAllFilterBlocksCollapsed: function(status) {
                if(this.collapseHalls === true && this.collapseTemplates === true &&
                this.collapseWorkers === true && this.collapseClients === true && this.collapseDuration === true)
                    return 1;
                if(this.collapseHalls === false && this.collapseTemplates === false &&
                this.collapseWorkers === false && this.collapseClients === false && this.collapseDuration === false)
                    return -1;
                return 0;
            },
            // computedCheckedHalls: function(){
            //     return this.checkedHalls;
            // },
            // isCheckedHallsChanged: function(){
            //     let arr = Object.values(this.checkedHalls);
            //     return arr.length > 0 ? true : false;
            // },
            isAnyCheckedChanged: function(){
                return this.isCheckedClientsChanged || this.isCheckedHallsChanged ||
                this.isCheckedWorkersChanged || this.isCheckedTemplatesChanged || this.isCheckedDurationChanged;
            },
            isCheckedClientsChanged: function(){
                return this.isCheckedItemChanged(this.clientFilter, this.checkedClientsAsArr);
            },
            isCheckedHallsChanged: function(){
                return this.isCheckedItemChanged(this.hallFilter, this.checkedHallsAsArr);
            },
            isCheckedWorkersChanged: function(){
                return this.isCheckedItemChanged(this.workerFilter, this.checkedWorkersAsArr);
            },
            isCheckedTemplatesChanged: function(){
                return this.isCheckedItemChanged(this.templateFilter, this.checkedTemplatesAsArr);
            },
            isCheckedDurationChanged: function(){
                let durationRangeStr = JSON.stringify(this.durationRange);
                let durationFilterStr = this.durationFilter === null ?
                    JSON.stringify(this.durationRangeMinMax) : JSON.stringify(this.durationFilter);
                console.log(JSON.parse(JSON.stringify('isCheckedDurationChanged')));
                console.log(JSON.parse(JSON.stringify(durationRangeStr)));
                console.log(JSON.parse(JSON.stringify(durationFilterStr)));
                return durationRangeStr != durationFilterStr;
            },
            checkedHallsAsArr: function(){
                return Object.values(this.checkedHalls);
            },
            checkedWorkersAsArr: function(){
                return Object.values(this.checkedWorkers);
            },
            checkedTemplatesAsArr: function(){
                return Object.values(this.checkedTemplates);
            },
            checkedClientsAsArr: function(){
                return Object.values(this.checkedClients);
            },
            isCheckedAll: function(){
                return this.isCheckedHalls && this.isCheckedWorkers &&
                this.isCheckedTemplates && this.isCheckedClients && this.isCheckedDuration;
            },
            isCheckedAny: function(){
                return this.isCheckedHalls || this.isCheckedWorkers ||
                this.isCheckedTemplates || this.isCheckedClients || this.isCheckedDuration;
            },
            isCheckedHalls: function(){
                return this.checkedHallsAsArr.length > 0;
            },
            isCheckedWorkers: function(){
                return this.checkedWorkersAsArr.length > 0;
            },
            isCheckedTemplates: function(){
                return this.checkedTemplatesAsArr.length > 0;
            },
            isCheckedClients: function(){
                return this.checkedClientsAsArr.length > 0;
            },
            isCheckedDuration: function(){
                return (
                    this.durationRange !== null &&
                    (
                        (
                            typeof this.durationRange.start !== 'undefined' &&
                            this.durationRange.start !== null &&
                            !isNaN(this.durationRange.start) &&
                            this.durationRange.start != this.durationRangeMinMax.start
                        ) ||
                        (
                            typeof this.durationRange.end !== 'undefined' &&
                            this.durationRange.end !== null &&
                            !isNaN(this.durationRange.end) &&
                            this.durationRange.end != this.durationRangeMinMax.end
                        ) 
                    )
                );
            },
            parsedWorkers: function(){
                let parsed = {}
                if(this.workers !== null && this.workers.length > 0){
                    parsed = JSON.parse(JSON.stringify(this.workers));
                    for(let idx in parsed){
                        parsed[idx].key = parsed[idx].id;
                        parsed[idx].val = this.fullNameOfObj(parsed[idx]);
                        if(typeof this.checkedWorkers[parsed[idx].id] !== 'undefined' &&
                        typeof this.checkedWorkers[parsed[idx].id].checked !== 'undefined' &&
                        this.checkedWorkers[parsed[idx].id].checked === true){
                            parsed[idx].checked = true;
                        }else{
                            // this.halls[idx].checked = false;
                            delete parsed[idx].checked;
                        }
                    }
                }
                return parsed;
            },
            parsedHalls: function(){
                let parsed = {}
                if(this.halls !== null && this.halls.length > 0){
                    parsed = JSON.parse(JSON.stringify(this.halls));
                    for(let idx in parsed){
                        parsed[idx].key = parsed[idx].id;
                        parsed[idx].val = parsed[idx].title;
                        if(typeof this.checkedHalls[parsed[idx].id] !== 'undefined' &&
                        typeof this.checkedHalls[parsed[idx].id].checked !== 'undefined' &&
                        this.checkedHalls[parsed[idx].id].checked === true){
                            parsed[idx].checked = true;
                        }else{
                            // this.halls[idx].checked = false;
                            delete parsed[idx].checked;
                        }
                    }
                }
                return parsed;
            },
            parsedTemplates: function(){
                let parsed = {}
                if(this.templates !== null && this.templates.length > 0){
                    parsed = JSON.parse(JSON.stringify(this.templates));
                    for(let idx in parsed){
                        parsed[idx].key = parsed[idx].id;
                        parsed[idx].val = parsed[idx].title;
                        if(typeof this.checkedTemplates[parsed[idx].id] !== 'undefined' &&
                        typeof this.checkedTemplates[parsed[idx].id].checked !== 'undefined' &&
                        this.checkedTemplates[parsed[idx].id].checked === true){
                            parsed[idx].checked = true;
                        }else{
                            // this.halls[idx].checked = false;
                            delete parsed[idx].checked;
                        }
                    }
                }
                return parsed;
            },
            parsedClients: function(){
                let parsed = {}
                if(this.clients !== null && this.clients.length > 0){
                    parsed = JSON.parse(JSON.stringify(this.clients));
                    for(let idx in parsed){
                        parsed[idx].key = parsed[idx].id;
                        parsed[idx].val = this.fullNameOfObj(parsed[idx]);
                        if(typeof this.checkedClients[parsed[idx].id] !== 'undefined' &&
                        typeof this.checkedClients[parsed[idx].id].checked !== 'undefined' &&
                        this.checkedClients[parsed[idx].id].checked === true){
                            parsed[idx].checked = true;
                        }else{
                            // this.halls[idx].checked = false;
                            delete parsed[idx].checked;
                        }
                    }
                }
                return parsed;
            },
        },
        methods: {
            isCheckedItemChanged: function(filter, checked){
                if(filter === null){
                    filter = [];
                }else{
                    filter = Object.values(JSON.parse(JSON.stringify(filter)));
                }
                checked = JSON.parse(JSON.stringify(checked));
                
                if(filter.length != checked.length)
                    return true;
                
                if(checked.length == 0)
                    return false;
                
                let isFilterChanged = false;
                for(let idx in filter){
                    let filterItm = filter[idx];
                    let existsInItem = false;
                    for(let idxx in checked){
                        let checkedItm = checked[idxx];
                        if(checkedItm.id == filterItm.id){
                            existsInItem = true;
                            break;
                        }
                    }
                    if(existsInItem == false){
                        isFilterChanged = true;
                        break;
                    }
                }
                
                return isFilterChanged;
            },
            removeAllCheckedItems: function() {
                this.checkedHalls = {}
                this.checkedWorkers = {}
                this.checkedTemplates = {}
                this.checkedClients = {}
                this.$refs.duration_range.onClickFullRange();
            },
            setCheckedItems: function(checkedItemType = null) {
                // alert(2233);
                if(checkedItemType === null || checkedItemType === 'client'){
                    this.checkedClients = {}
                    if(this.clientFilter !== null)
                        for(let idx in this.clientFilter){
                            let item = JSON.parse(JSON.stringify(this.clientFilter[idx]));
                            item.checked = true;
                            this.onClientDropdownChange(item);
                        }
                }
                if(checkedItemType === null || checkedItemType === 'hall'){
                    this.checkedHalls = {}
                    if(this.hallFilter !== null)
                        for(let idx in this.hallFilter){
                            let item = JSON.parse(JSON.stringify(this.hallFilter[idx]));
                            item.checked = true;
                            this.onHallDropdownChange(item);
                        }
                }
                if(checkedItemType === null || checkedItemType === 'template'){
                    this.checkedTemplates = {}
                    if(this.templateFilter !== null)
                        for(let idx in this.templateFilter){
                            let item = JSON.parse(JSON.stringify(this.templateFilter[idx]));
                            item.checked = true;
                            this.onTemplateDropdownChange(item);
                        }
                }
                if(checkedItemType === null || checkedItemType === 'worker'){
                    this.checkedWorkers = {}
                    if(this.workerFilter !== null)
                        for(let idx in this.workerFilter){
                            let item = JSON.parse(JSON.stringify(this.workerFilter[idx]));
                            item.checked = true;
                            this.onWorkerDropdownChange(item);
                        }
                }
                if(checkedItemType === null || checkedItemType === 'duration'){
                    // alert(1111);
                    if(this.durationFilter !== null){
                        this.$refs.duration_range.setInitRange(this.durationFilter);
                        if(typeof this.$refs.duration_range.$refs.time_bar_duration !== 'undefined')
                            this.$refs.duration_range.$refs.time_bar_duration.setInputInitValue(this.durationFilter);
                    }else{
                        this.$refs.duration_range.setInitRange(this.durationRangeMinMax);
                        if(typeof this.$refs.duration_range.$refs.time_bar_duration !== 'undefined')
                            this.$refs.duration_range.$refs.time_bar_duration.setInputInitValue(this.durationRangeMinMax);
                    }
                }
            },
            setDurationRange: function() {
                if(typeof this.$refs.duration_range !== 'undefined')
                    this.durationRange = this.$refs.duration_range.range;
            },
            setCollapseDuration: function(type) {
                if(typeof this.$refs.duration_range !== 'undefined' &&
                this.$refs.duration_range.collapsed === true){
                    this.collapseDuration = true;
                }else{
                    this.collapseDuration = false;
                }
            },
            toogleFilterBlock: function(type) {
                if(type == 'halls')
                    this.collapseHalls = !this.collapseHalls;
                if(type == 'clients')
                    this.collapseClients = !this.collapseClients;
                if(type == 'templates')
                    this.collapseTemplates = !this.collapseTemplates;
                if(type == 'workers')
                    this.collapseWorkers = !this.collapseWorkers;
                if(type == 'duration')
                    this.setCollapseDuration();
                    
                this.$emit('toogle_filter_block');
            },
            toogleAllFilterBlocks: function(status) {
                status = status === true ? true : false;
                this.collapseHalls = status;
                this.collapseTemplates = status;
                this.collapseWorkers = status;
                this.collapseClients = status;
                if(typeof this.$refs.duration_range !== 'undefined' &&
                typeof this.$refs.duration_range.collapsed !== 'undefined'){
                    this.$refs.duration_range.collapsed = status;
                }
            },
            onTemplateCheckedRemove: function(item){
                if(typeof this.checkedTemplates[item.id] !== 'undefined'){
                    delete this.checkedTemplates[item.id];
                    this.checkedTemplates = JSON.parse(JSON.stringify(this.checkedTemplates));
                }
            },
            onTemplateDropdownChange: function(item){
                if(typeof item.checked !== 'undefined' && item.checked === true){
                    this.checkedTemplates[item.id] = item;
                }else{
                    delete this.checkedTemplates[item.id];
                }
                this.checkedTemplates = JSON.parse(JSON.stringify(this.checkedTemplates));
            },
            onHallCheckedRemove: function(item){
                if(typeof this.checkedHalls[item.id] !== 'undefined'){
                    // this.onHallDropdownChange({
                    //     index: index,
                    //     item: item,
                    // });
                    delete this.checkedHalls[item.id];
                    this.checkedHalls = JSON.parse(JSON.stringify(this.checkedHalls));
                }
                // console.log(JSON.parse(JSON.stringify(index)));
                // console.log(JSON.parse(JSON.stringify(item)));
            },
            onHallDropdownChange: function(item){
                // return;
                // JSON.parse(JSON.stringify(66666666));
                // JSON.parse(JSON.stringify(item));
                // console.log(JSON.parse(JSON.stringify(66666666)));
                // console.log(JSON.parse(JSON.stringify(item)));
                // return;
                if(typeof item.checked !== 'undefined' && item.checked === true){
                    this.checkedHalls[item.id] = item;
                }else{
                    delete this.checkedHalls[item.id];
                }
                this.checkedHalls = JSON.parse(JSON.stringify(this.checkedHalls));
                console.log(JSON.parse(JSON.stringify(this.checkedHalls)));
            },
            onClientCheckedRemove: function(item){
                if(typeof this.checkedClients[item.id] !== 'undefined'){
                    delete this.checkedClients[item.id];
                    this.checkedClients = JSON.parse(JSON.stringify(this.checkedClients));
                }
            },
            onClientDropdownChange: function(item){
                if(typeof item.checked !== 'undefined' && item.checked === true){
                    this.checkedClients[item.id] = item;
                }else{
                    delete this.checkedClients[item.id];
                }
                this.checkedClients = JSON.parse(JSON.stringify(this.checkedClients));
            },
            onWorkerCheckedRemove: function(item){
                if(typeof this.checkedWorkers[item.id] !== 'undefined'){
                    // console.log(JSON.parse(JSON.stringify('onWorkerCheckedRemove')));
                    // console.log(JSON.parse(JSON.stringify(this.checkedWorkers[index])));
                    delete this.checkedWorkers[item.id];
                    this.checkedWorkers = JSON.parse(JSON.stringify(this.checkedWorkers));
                    // console.log(JSON.parse(JSON.stringify(this.checkedWorkers)));
                }
            },
            onWorkerDropdownChange: function(item){
                if(typeof item.checked !== 'undefined' && item.checked === true){
                    this.checkedWorkers[item.id] = item;
                }else{
                    delete this.checkedWorkers[item.id];
                }
                this.checkedWorkers = JSON.parse(JSON.stringify(this.checkedWorkers));
            },
            reassignTemplates: function(){
                let templates = JSON.parse(JSON.stringify(this.templates));
                this.templates = null;
                setTimeout(() => {
                 	this.templates = templates;
                }, 1);
            },
            onClickWithSpecifics: function(){
                if(this.templateTab == 'with_specifics')
                    return;
                this.templateTab = 'with_specifics';
            },
            onClickJustTemplates: function(){
                if(this.templateTab == 'just_templates')
                    return;
                this.reassignTemplates();
                this.templateTab = 'just_templates';
            },
            setClients: function(params = null){
                this.app.getClients(params).then((data) => {
                    this.clients = data;
                });
            },
            setTemplates: function(params = null){
                this.app.getTemplates(params).then((data) => {
                    this.templates = data;
                });
            },
            setWorkers: function(params = null){
                this.app.getWorkers(params).then((data) => {
                    this.workers = data;
                });
            },
            reset: function(fillFields = true){
                this.$store.dispatch('moving_event/resetPicked');
                this.resetPickedItems();
                if(fillFields === true)
                    this.fillFields();
            },
            setPickedTemplateIdsTrace: function(template){
                // if(this.movingEventTemplate === null)
                if(typeof template === 'undefined' || template === null)
                    return;
                
                // console.log(JSON.parse(JSON.stringify(template)));
                // let template = JSON.parse(JSON.stringify(this.pickedTemplate));
                
                if(typeof template.specific === 'undefined' || template.specific === null)
                    return;
            
                if(typeof template.specific.ids_trace === 'undefined' || template.specific.ids_trace === null)
                    return [template.specific.id];
            
                let idsTraceString = JSON.parse(JSON.stringify(template.specific.ids_trace));
                let idsTrace = idsTraceString.split(',').map((val) => parseInt(val));
                idsTrace.push(template.specific.id);
                idsTrace.push(template.id);
            
                this.pickedTemplateIdsTrace = idsTrace;
                // console.log(JSON.parse(JSON.stringify('pickedTemplateIdsTrace')));
                // console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
            },
            getItemById: function(items, id){
                let item = null;
                for(let i = 0; i < items.length; i++){
                    if(typeof items[i].id !== 'undefined' &&
                    items[i].id == id){
                        item = items[i];
                        break;
                    }
                }
                return item;
            },
            fillFields: function(data = null){
                if(data !== null)
                    this.fillingData = data;
                    
                let hall, template, worker;
                let _this = this;
                
                let {hall_id, template_id, worker_id} = this.fillingData;
                
                this.resetPickedItems();
                
                return new Promise((resolve, reject) => {
                    if(typeof hall_id === 'undefined' || hall_id === null ||
                    typeof template_id === 'undefined' || template_id === null ||
                    typeof worker_id === 'undefined' || worker_id === null)
                        reject('Not all parameters for filling!');
                        
                    hall = _this.getItemById(this.halls, hall_id);
                    
                    if(hall === null)
                        reject('Hall(`hall_id`) parameter is wrong');
                    
                    resolve(hall);
                }).then((hall) => {
                    return new Promise((resolve, reject) => {
                        this.change('hall', hall, () => resolve());
                    }).then((result) => {
                        template = _this.getItemById(this.templates, template_id);
                        if(template === null)
                            reject('Template(`template_id`) parameter is wrong');
                        
                        return new Promise((resolve, reject) => {
                            this.change('template', template, () => {
                                this.setPickedTemplateIdsTrace(template);
                                resolve();
                            });
                        });
                    }).then((result) => {
                        worker = _this.getItemById(this.workers, worker_id);
                        if(worker === null)
                            reject('Worker(`worker_id`) parameter is wrong');
                        this.change('worker', worker);
                    });
                });
            },
        },
        components: {
            TemplatePicker: ExtensiveTemplateFilterPicker,
            Dropdown,
            DurationRange,
            DropdownCheckboxes,
            Checkbox,
        },
        watch: {
            durationRange: function(val){
                this.$emit('change');
            },
            clientFilter: function(val){
                this.setCheckedItems('client');
                // console.log(JSON.parse(JSON.stringify('watch')));
            },
            hallFilter: function(val){
                this.setCheckedItems('hall');
                // console.log(JSON.parse(JSON.stringify('watch')));
            },
            templateFilter: function(val){
                this.setCheckedItems('template');
                // console.log(JSON.parse(JSON.stringify('watch')));
            },
            workerFilter: function(val){
                this.setCheckedItems('worker');
                // console.log(JSON.parse(JSON.stringify('watch')));
            },
            checkedHalls: function(val){
                this.$emit('change');
            },
            checkedWorkers: function(val){
                this.$emit('change');
            },
            checkedTemplates: function(val){
                this.$emit('change');
            },
            checkedClients: function(val){
                this.$emit('change');
            },
            isCheckedDuration: function(val){
                this.$emit('change');
            },
        }
    }
</script>

<style lang="scss" scoped>
    .checked-items{
        padding-top: 5px;
        padding-bottom: 0px;
        position: relative;
        // top: 3px;
        // margin-top: 5px;
        // padding-bottom: 5px;
        // height: auto;
        .checked-item{
            // padding: 0px;
            // margin-top: 3px;
            float: left;
            margin: 0px;
            margin-right: 4px;
            margin-top: 4px;
            position: relative;
            padding-right: 34px;
            overflow-x: hidden;
            .checked-item-close{
                position: absolute;
                top: 0px;
                right: 0px;
                background-color: rgba(255,255,255, .3);
                height: 100%;
                width: 30px;
                cursor: pointer;
                transition: background .3s ease;
                &:hover{
                    background-color: rgba(255,255,255, .2);
                }
            }
        }
    }
    .card{
        .card-header{
            padding: 6px;
        }
        .card-body{
            padding: 10px;
            // padding-bottom: 0px;
        }
    }
    .btn-reset{
        width: 100%;
    }
    .dropdown-standart{
        .dropdown-toggle{
            width: 100%;
            text-align: left;
            position: relative;
            &::after {
                display: inline-block;
                margin-left: .255em;
                vertical-align: .255em;
                content: "";
                border-top: .3em solid;
                border-right: .3em solid transparent;
                border-bottom: 0;
                border-left: .3em solid transparent;
                position: absolute;
                top: 12px;
                right: 10px;
            }
        }
        .dropdown-menu{
            width: 100%;
        }
    }
</style>