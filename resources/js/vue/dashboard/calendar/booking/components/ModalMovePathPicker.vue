<template>
    <div>
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer"
            :id="modalId"
            tabindex="-1"
            role="dialog"
            :aria-labelledby="modalId + 'Label'"
            aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" :id="modalId + 'Label'">Event</h5>
                            <button type="button"
                                @click.prevent="close()"
                                class="close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                
                            <div class="card-body">
                                
                                <div v-if="clientInfo" class="alert alert-info client-info" role="alert">
                                    <span class="badge badge-info titt">Client:</span>
                                    <b>{{fullName(clientInfo)}}</b><br>
                                    {{clientInfo.email ? clientInfo.email : ''}}<br>
                                    <div class="small">
                                        <b v-if="!isPickedItemsChanged">{{eventDate}} {{eventWeekday}} {{eventTime}}</b>
                                        <b v-else class="badge badge-warning text-left">Please choose all fields and pick a time</b>
                                    </div>
                                </div>
                                
                                <a v-if="isPickedItemsChanged"
                                    @click.prevent="reset()"
                                    href="#"
                                    class="btn btn-sm btn-warning btn-reset">
                                        Reset to initial values
                                </a>
                                
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
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                @click.prevent="close()"
                                class="btn btn-sm btn-secondary">
                                    Close
                            </button>
                            <button :disabled="!isAllItemsPicked"
                                @click.prevent="pickTime()"
                                type="button"
                                class="btn btn-sm btn-primary">
                                    Choose time
                            </button>
                            <button :disabled="!isAllItemsPicked || !isPickedItemsChanged" type="button" class="btn btn-sm btn-success">Save</button>
                        </div>
                    </div>
                </div>
        </div>
        
    </div>
</template>

<script>
    import ExtensiveTemplateFilterPicker from "./template/ExtensiveTemplateFilterPicker.vue";
    export default {
        name: 'ModalMovePathPicker',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.templateSpecificsAsIdKey)));
        },
        // props: ['movingEvent'],
        data: function(){
            return {
                modalId: 'pathPickerModal',
                pickedHall: null,
                pickedWorker: null,
                pickedTemplate: null,
                
                eventDateObj: null,
                
                pickedTemplateIdsTrace: null,
                
                workers: null,
                templates: null,
                
                // clientInfo: null,
            };
        },
        computed: {
            movingEvent: function(){
                return this.$store.getters['moving_event/event'];
            },
            clientInfo: function(){
                return this.$store.getters['moving_event/client'];
            },
            eventDate: function(){
                if(this.eventDateObj === null)
                    return null;
                    
                return moment(this.eventDateObj).format('YYYY-MM-DD');
            },
            eventTime: function(){
                if(this.eventDateObj === null)
                    return null;
                    
                return moment(this.eventDateObj).format('HH:mm');
            },
            eventWeekday: function(){
                if(this.eventDateObj === null)
                    return null;
                    
                return moment(this.eventDateObj).format('ddd');
            },
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
            halls: function(){
                return this.$store.getters['halls/all'];
            },
            templateSpecifics: function(){
                return this.$store.getters['specifics/templateSpecifics'];
            },
            templateSpecificsAsIdKey: function(){
                return this.$store.getters['specifics/templateSpecificsAsIdKey'];
            },
            isMovingEvent: function(){
                return this.movingEvent !== null && typeof this.movingEvent.template_without_user_scope !== 'undefined';
            },
            movingEventTemplate: function(){
                return this.isMovingEvent ? this.movingEvent.template_without_user_scope : null;
            },
            isShown: function(){
                return $('#' + this.modalId).hasClass('in');
            },
        },
        methods: {
            close: function(){
                this.$store.commit('moving_event/reset');
                this.hide();
            },
            pickTime: function(){
                if(this.isAllItemsPicked){
                    this.$store.commit('move_event/setItems', {
                        hall: this.pickedHall,
                        worker: this.pickedWorker,
                        template: this.pickedTemplate,
                    });
                    this.hide();
                    this.$emit('pick_time');
                }
            },
            reset: function(){
                this.resetPickedItems();
                this.fillFields();
            },
            setPickedTemplateIdsTrace: function(){
                if(this.movingEventTemplate === null)
                    return;
                
                let template = JSON.parse(JSON.stringify(this.movingEventTemplate));
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
                // console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
            },
            fullName: function (obj){
                if(obj === null ||
                typeof obj.first_name === 'undefined' ||
                typeof obj.last_name === 'undefined')
                    return null;
                    
                let fullName = obj.first_name;
                
                if(obj.last_name !== null && typeof obj.last_name === 'string' &&
                obj.last_name.length > 0)
                    fullName += ' ' + obj.last_name;
                    
                return fullName;
            },
            show: function (){
                $('#' + this.modalId).modal('show');
            },
            hide: function (){
                $('#' + this.modalId).modal('hide');
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
            },
            change: function(type, itm, callbackResolver = null){
                itm = JSON.parse(JSON.stringify(itm));
                let url, urlParams;
                // if(itm === null)
                //     return;
                
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
                            });
                            
                        break;
                    case 'template':
                        // alert(8888);
                        console.log(JSON.parse(JSON.stringify(itm)));
                        // return;
                    
                        this.resetPickedItems(['template','worker']);
                        // lo(222);
                        // console.log('5555555');
                        // console.log(this.pickedItmTemplate);
                        // this.pickedTemplate = null;
                        if(itm === null){
                            // console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
                            return;
                        }
                        
                        url = new URL(routes.calendar.booking.worker.get);
                        urlParams = new URLSearchParams();
                        urlParams.append('hall_id', this.pickedHall.id);
                        urlParams.append('template_id', itm.id);
                        url.search = urlParams;
                        
                        // alert(111);
                        axios.get(url.toString())
                            .then((response) => {
                                let workers = [];
                                response.data.workers.forEach((item, i) => {
                                    workers.push(item);
                                });
                                this.workers = workers;
                                this.pickedTemplate = itm;
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
                            });
                            
                        break;
                    case 'worker':
                    
                        this.pickedWorker = (itm ? itm : null);
                        
                        break;
                    // case 'view':
                    // 
                    //     this.pickedView = (itm ?? null);
                    // 
                    //     break;
                    default:
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
                if(this.isMovingEvent){
                    this.eventDateObj = moment(this.movingEvent.time).toDate();
                    
                    this.resetPickedItems();
                    let hall = this.getItemById(this.halls, this.movingEvent.hall_id);
                    // console.log(JSON.parse(JSON.stringify(hall)));
                    
                    if(hall === null)
                        return;
                    
                    new Promise((resolve, reject) => {
                        this.change('hall', hall, () => resolve());
                    }).then((result) => {
                        let template = this.getItemById(this.templates, this.movingEvent.template_id);
                        return new Promise((resolve, reject) => {
                            this.change('template', template, () => resolve());
                            this.setPickedTemplateIdsTrace();
                        });
                    }).then((result) => {
                        let worker = this.getItemById(this.workers, this.movingEvent.worker_id);
                        this.change('worker', worker);
                    });
                }
            },
        },
        components: {
            TemplatePicker: ExtensiveTemplateFilterPicker,
        },
        watch: {
            movingEvent: function(val){
                // console.log(JSON.parse(JSON.stringify('movingEvent changed')));
                // console.log(JSON.parse(JSON.stringify(val)));
                if(val !== null){
                    this.fillFields();
                    // console.log(JSON.parse(JSON.stringify('movingEvent changed')));
                    // console.log(JSON.parse(JSON.stringify(val)));
                }
            },
            pickedHall: function(val){
                // console.log(JSON.parse(JSON.stringify('pickedHall changed')));
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
    .card-body{
        padding: 0px;
        .btn-reset{
            width: 100%;
        }
        .client-info{
            position: relative;
            .titt{
                position: absolute;
                top: -10px;
                left: 14px;
            }
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
    }
</style>