<template>
    <div>
        
        <div id="hallDropdown" class="dropdown dropdown-standart">
            <span>{{capitalizeFirstLetter(getText('text.hall'))}}: </span>
            <br>
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
            :label="capitalizeFirstLetter(getText('text.template'))"
            :specifics="templateSpecifics"
            :specifics-as-id-key="templateSpecificsAsIdKey"
            :picked-template-ids-trace="pickedTemplateIdsTrace"
            v-if="templateSpecifics"
            @change="change('template', $event)" />
            
        <div id="workerDropdown" class="dropdown dropdown-standart">
            <span>{{capitalizeFirstLetter(getText('text.worker'))}}:</span><br>
            <a :class="{disabled: !workers}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{fullPickedWorkerName}}
            </a>

            <div class="dropdown-menu">
                <a @click.prevent="change('worker', itm)" v-for="itm in workers" class="dropdown-item" href="#">
                    {{fullNameOfObj(itm)}}
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
            // this.fillFields();
        },
        // props: ['movingEvent'],
        data: function(){
            return {
                fillingData: null,
                
                pickedHall: null,
                pickedWorker: null,
                pickedTemplate: null,
                
                pickedTemplateIdsTrace: null,
                
                workers: null,
                templates: null,
            };
        },
        computed: {
            isAllItemsPicked: function(){
                return this.pickedHall !== null && this.pickedWorker !== null &&
                this.pickedTemplate !== null;
            },
            isPickedItemsChanged: function(){
                if(!this.isAllItemsPicked || this.movingEvent === null)
                    return true;
                
                return this.pickedHall.id !== this.fillingData.hall_id ||
                this.pickedWorker.id !== this.fillingData.worker_id ||
                this.pickedTemplate.id !== this.fillingData.template_id;
            },
            fullPickedWorkerName: function(){
                let fullName = this.fullNameOfObj(this.pickedWorker);
                return fullName !== null ? fullName : '---';
            },
            isHallPicked: function(){
                return this.pickedHall !== null && typeof this.pickedHall.id !== 'undefined';
            },
        },
        methods: {
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
                
                if(typeof template.specific.ids_trace === 'undefined' || template.specific.ids_trace === null){
                    this.pickedTemplateIdsTrace = [template.specific.id, template.id];
                    return;
                }
                
                let idsTraceString = JSON.parse(JSON.stringify(template.specific.ids_trace));
                let idsTrace = idsTraceString.split(',').map((val) => parseInt(val));
                idsTrace.push(template.specific.id);
                idsTrace.push(template.id);
                
                // console.log(JSON.parse(JSON.stringify('setPickedTemplateIdsTrace')));
                // console.log(JSON.parse(JSON.stringify(idsTrace)));
            
                this.pickedTemplateIdsTrace = idsTrace;
            },
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
                                // console.log(JSON.parse(JSON.stringify(this.templates)));
                                
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
                        // this.resetPickedItems(['worker']);
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
                                // console.log(JSON.parse(JSON.stringify('change_template')));
                                // console.log(JSON.parse(JSON.stringify(template)));
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