<template>
    <div>
        
        <div v-if="movingEventClient" class="alert alert-primary client-info small" role="alert">
            <div>
                Client: <b>{{fullName(movingEventClient)}}</b>
                <template v-if="movingEventClient.email">
                    <b> - {{movingEventClient.email}}</b>
                </template>
            </div>
            <template v-if="!isPickedItemsChanged">
                <div>Date: <b>{{movingEventDate}}</b></div>
                <div>Time: <b>{{movingEvent.from}}</b></div>
            </template>
            <b v-else class="badge badge-warning text-left">
                <template v-if="isAllItemsPicked">
                    Please choose a time
                </template>
                <template v-else>
                    Please choose all fields and pick a time
                </template>
            </b>
        </div>
        
        <div id="hallDropdown" class="dropdown dropdown-standart">
            <span>Hall: </span><br>
            <a class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown">
                {{!isHallPicked ? '---' : pickedHall.title}}
            </a>

            <div class="dropdown-menu">
                <template v-if="halls.length">
                    <a v-for="itm in halls"
                        v-if="!isHallPicked || (isHallPicked && pickedHall.id !== itm.id)"
                        @click.prevent="change('hall', itm)"
                        class="dropdown-item"
                        href="#">{{itm.title}}</a>
                </template>
                <template v-else>
                    <div class="small pl-1 pr-1">
                        No items to choose ...
                    </div>
                </template>
            </div>
        </div>
        
        <template-picker :templates="templates"
            :specifics="templateSpecifics"
            :specifics-as-id-key="templateSpecificsAsIdKey"
            :picked-template-ids-trace="pickedTemplateIdsTrace"
            v-if="templateSpecifics"
            @change="change('template', $event)" />
            
        <div id="workerDropdown" class="dropdown dropdown-standart">
            <span>Worker:</span><br>
            <a :class="{disabled: !workers}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{fullPickedWorkerName}}
            </a>

            <div class="dropdown-menu">
                <a @click.prevent="change('worker', itm)" v-for="itm in workers" class="dropdown-item" href="#">
                    {{fullName(itm)}}
                </a>
            </div>
        </div>
        
    </div>
</template>

<script>
    import ExtensiveTemplateFilterPicker from "../template/ExtensiveTemplateFilterPicker.vue";
    export default {
        name: 'ModalMovePathPicker',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
            this.fillFields();
        },
        // props: ['movingEvent'],
        data: function(){
            return {
                pickedHall: null,
                pickedWorker: null,
                pickedTemplate: null,
                
                pickedTemplateIdsTrace: null,
                
                workers: null,
                templates: null,
            };
        },
        computed: {
            // Data of picked items (`pickedHall`, `pickedWorker`, `pickedTemplate`)
            isAllItemsPicked: function(){
                return this.pickedHall !== null && this.pickedWorker !== null &&
                this.pickedTemplate !== null;
            },
            isPickedItemsChanged: function(){
                if(!this.isAllItemsPicked || this.movingEvent === null)
                    return true;
                    
                return this.pickedHall.id !== this.movingEvent.hall_id ||
                this.pickedWorker.id !== this.movingEvent.worker_id ||
                this.pickedTemplate.id !== this.movingEvent.template_id;
            },
            fullPickedWorkerName: function(){
                let fullName = this.fullName(this.pickedWorker);
                return fullName !== null ? fullName : '---';
            },
            isHallPicked: function(){
                return this.pickedHall !== null && typeof this.pickedHall.id !== 'undefined';
            },
            // Status of this modal (Shown/Hidden)
            isShown: function(){
                return $('#' + this.modalId).hasClass('in');
            },
        },
        methods: {
            // close: function(){
            //     this.$store.commit('moving_event/reset');
            //     // this.hide();
            // },
            // Show free slots to pick time for moving_event
            // pickTime: function(){
            setMovingEventPickedItems: function(){
                if(this.isAllItemsPicked){
                    this.$store.dispatch('moving_event/setPicked', {
                        hall: this.pickedHall,
                        worker: this.pickedWorker,
                        template: this.pickedTemplate,
                    });
                    // this.hide();
                    // this.$emit('pick_time');
                }
            },
            reset: function(){
                this.$store.dispatch('moving_event/resetPicked');
                this.resetPickedItems();
                this.fillFields();
            },
            setPickedTemplateIdsTrace: function(){
                // if(this.movingEventTemplate === null)
                if(this.pickedTemplate === null)
                    return;
            
                let template = JSON.parse(JSON.stringify(this.pickedTemplate));
                
                if(typeof template.specific === 'undefined' || template.specific === null)
                    return;
            
                if(typeof template.specific.ids_trace === 'undefined' || template.specific.ids_trace === null)
                    return [template.specific.id];
            
                let idsTraceString = JSON.parse(JSON.stringify(template.specific.ids_trace));
                let idsTrace = idsTraceString.split(',').map((val) => parseInt(val));
                idsTrace.push(template.specific.id);
                idsTrace.push(template.id);
            
                this.pickedTemplateIdsTrace = idsTrace;
                // console.log(JSON.parse(JSON.stringify('setPickedTemplateIdsTrace')));
            },
            fullName: function (obj){
                return calendarHelper.person.fullName(obj);
            },
            // show: function (){
            //     // $('#' + this.modalId).modal('show');
            //     this.fillFields();
            // },
            // hide: function (){
            //     $('#' + this.modalId).modal('hide');
            // },
            resetPickedItems: function(items = null){
                if(items !== null && !Array.isArray(items))
                    return;
                
                if(items === null || items.includes("hall"))
                    this.pickedHall = null;
                
                if(items === null || items.includes("template")){
                    this.pickedTemplate = null;
                    this.pickedTemplateIdsTrace = null;
                }
                
                if(items === null || items.includes("worker"))
                    this.pickedWorker = null;
                    
                if(items === null || items.includes("hall") ||
                items.includes("template") || items.includes("worker"))
                    this.$emit('change');
            },
            change: function(type, itm, callbackResolver = null){
                let _this = this;
                
                itm = JSON.parse(JSON.stringify(itm));
                let url, urlParams;
                
                switch(type) {
                    case 'hall':
                        if(this.pickedHall !== null && typeof this.pickedHall.id !== 'undefined' &&
                        parseInt(this.pickedHall.id) ===  parseInt(itm.id))
                            return;
                        
                        this.resetPickedItems();
                        
                        url = new URL(routes.calendar.booking.template.get);
                        urlParams = new URLSearchParams();
                        urlParams.append('hall_id', itm.id);
                        url.search = urlParams;
                        
                        axios.get(url.toString())
                            .then((response) => {
                                this.pickedHall = itm;
                                if(typeof response.data.templates === 'undefined' ||
                                response.data.templates === null)
                                    return;
                                let templates = [];
                                response.data.templates.forEach((item, i) => {
                                    templates.push(item);
                                });
                                this.templates = templates;
                                // this.pickedHall = itm;
                                console.log(JSON.parse(JSON.stringify(this.templates)));
                                
                                if(callbackResolver !== null)
                                    callbackResolver();
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                            })
                            .then(function () {
                                // always executed
                                _this.$emit('change');
                            });
                            
                        break;
                    case 'template':
                        this.resetPickedItems(['template','worker']);
                        if(itm === null)
                            return;
                        
                        url = new URL(routes.calendar.booking.worker.get);
                        urlParams = new URLSearchParams();
                        urlParams.append('hall_id', this.pickedHall.id);
                        urlParams.append('template_id', itm.id);
                        url.search = urlParams;
                        
                        axios.get(url.toString())
                            .then((response) => {
                                let workers = [];
                                response.data.workers.forEach((item, i) => {
                                    workers.push(item);
                                });
                                this.workers = workers;
                                this.pickedTemplate = itm;
                                
                                if(callbackResolver !== null)
                                    callbackResolver();
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                            })
                            .then(function () {
                                // always executed
                                _this.$emit('change');
                            });
                            
                        break;
                    case 'worker':
                        this.pickedWorker = (itm ? itm : null);
                        _this.$emit('change');
                        break;
                }
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
            fillFields: function(){
                // return;
                let _this = this;
                
                if(this.isMovingEvent){
                    let hall, template, worker;
                    
                    this.resetPickedItems();
                    hall = getItem('hall');
                    
                    console.log(JSON.parse(JSON.stringify('hall')));
                    console.log(JSON.parse(JSON.stringify(hall)));
                    
                    if(hall === null)
                        return;
                    
                    new Promise((resolve, reject) => {
                        this.change('hall', hall, () => resolve());
                    }).then((result) => {
                        template = getItem('template');
                        
                        console.log(JSON.parse(JSON.stringify('template')));
                        console.log(JSON.parse(JSON.stringify(template)));
                        
                        return new Promise((resolve, reject) => {
                            this.change('template', template, () => {
                                this.setPickedTemplateIdsTrace();
                                resolve();
                            });
                            
                            console.log(JSON.parse(JSON.stringify('pickedTemplateIdsTrace')));
                            // console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
                        });
                    }).then((result) => {
                        worker = getItem('worker');
                        
                        console.log(JSON.parse(JSON.stringify('worker')));
                        console.log(JSON.parse(JSON.stringify(worker)));
                        
                        this.change('worker', worker);
                    });
                }
                
                // Returns item one of (`worker`,`hall`,`template`)
                // depending on filled data in moving_event $store module
                function getItem(type){
                    if(!['hall','template','worker'].includes(type))
                        return null;
                        
                    let item, itemId;
                    
                    let aliases = {
                        hall: {
                            thisItems: 'halls',
                            movingEventId: 'hall_id'
                        },
                        template: {
                            thisItems: 'templates',
                            movingEventId: 'template_id'
                        },
                        worker: {
                            thisItems: 'workers',
                            movingEventId: 'worker_id'
                        },
                    }
                    
                    if(_this.movingEventIsPickedFull)
                        item = _this.getItemById(_this[aliases[type].thisItems], _this.movingEventPicked.[type].id);
                    if(typeof item === 'undefined' || item === null)
                        item = _this.getItemById(_this[aliases[type].thisItems], _this.movingEvent[aliases[type].movingEventId]);
                        
                    return item
                }
            },
        },
        components: {
            TemplatePicker: ExtensiveTemplateFilterPicker,
        },
        watch: {
            pickedHall: function(val){
                if(val === null){
                    this.templates = null;
                    this.workers = null;
                }
            },
            pickedTemplate: function(val){
                if(val === null){
                    this.workers = null;
                }
            },
        }
    }
</script>

<style lang="scss" scoped>
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