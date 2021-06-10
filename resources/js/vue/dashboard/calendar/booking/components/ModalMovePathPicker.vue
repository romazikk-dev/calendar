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
                            <h5 class="modal-title" :id="modalId + 'Label'">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                
                            <div class="card-body">
                                
                                <div id="hallDropdown" class="dropdown">
                                    <span>Hall: </span><br>
                                    <a class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown">
                                        {{pickedHall == null ? '---' : pickedHall.title}}
                                    </a>
                
                                    <div class="dropdown-menu">
                                        <!-- <a @click.prevent="change('hall', itm)"
                                            v-for="itm in halls"
                                            v-if="pickedItmHall == null || (pickedItmHall != null && itm.id != pickedItmHall.id)"
                                            class="dropdown-item"
                                            href="#">{{itm.title}}</a> -->
                                        <template v-if="halls.length">
                                            <a v-for="itm in halls"
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
                                
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
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
            
            // console.log('ModalMovePathPicker');
            
            // $('.popover-dismiss').popover({
            //     trigger: 'focus'
            // });
            // console.log(11111);
            // console.log(component.$options.name);
            
            // $('#enterModal').on('hidden.bs.modal', () => {
            //     this.showTab = 'info';
            // })
        },
        props: ['clientInfo'],
        data: function(){
            return {
                modalId: 'pathPickerModal',
                pickedHall: null,
                pickedWorker: null,
                pickedTemplate: null,
                
                pickedTemplateIdsTrace: null,
                
                workers: null,
                templates: null,
            };
        },
        computed: {
            halls: function(){
                return this.$store.getters['halls/all'];
            },
            templateSpecifics: function(){
                return this.$store.getters['specifics/templateSpecifics'];
            },
            templateSpecificsAsIdKey: function(){
                return this.$store.getters['specifics/templateSpecificsAsIdKey'];
            },
            // customTitle: function(){
            //     return (name) => {
            //         return this.$store.getters['custom_titles/title'](name);
            //     }
            // },
        },
        methods: {
            show: function (){
                $('#' + this.modalId).modal('show');
            },
            resetPickedItems: function(items = null){
                if(items !== null && !Array.isArray(items))
                    return;
                    
                if(items === null || items.includes("hall"))
                    this.pickedHall = null;
                    
                if(items === null || items.includes("template"))
                    this.pickedTemplate = null;
                    
                if(items === null || items.includes("worker"))
                    this.pickedWorker = null;
            },
            change: function(type, itm){
                itm = JSON.parse(JSON.stringify(itm));
                // console.log(itm);
                switch(type) {
                    case 'hall':
                        this.resetPickedItems();
                        
                        let url = new URL(routes.calendar.booking.template.get);
                        let params = new URLSearchParams();
                        params.append('hall_id', itm.id);
                        url.search = params;
                        
                        axios.post(url.toString())
                            .then((response) => {
                                if(typeof response.data.templates === 'undefined' ||
                                response.data.templates === null)
                                    return;
                                let templates = [];
                                response.data.templates.forEach((item, i) => {
                                    templates.push(item);
                                });
                                this.templates = templates;
                                this.pickedItmHall = itm;
                                console.log(JSON.parse(JSON.stringify(this.templates)));
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
                    
                        this.resetPickedItems(['template','worker']);
                        // lo(222);
                        // console.log('5555555');
                        // console.log(this.pickedItmTemplate);
                        if(itm === null)
                            return;
                        // alert(111);
                        axios.get(routes.calendar.booking.worker.index + '?template=' + itm.id)
                            .then((response) => {
                                let workers = [];
                                response.data.workers.forEach((item, i) => {
                                    workers.push(item);
                                });
                                this.workers = workers;
                                this.pickedItmTemplate = itm;
                                // console.log(JSON.parse(JSON.stringify(this.templates)));
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
                    
                        this.pickedItmWorker = (itm ?? null);
                        
                        break;
                    case 'view':
                    
                        this.pickedItmView = (itm ?? null);
                        
                        break;
                    default:
                }
            },
        },
        components: {
            TemplatePicker: ExtensiveTemplateFilterPicker,
        },
    }
</script>

<style lang="scss" scoped>
    .card-body{
        padding: 0px;
        #hallDropdown{
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