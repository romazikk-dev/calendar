<template>
    <div>
        
        <div>
            <div id="templateDropdownThroughSpecifics" class="dropdown">
                <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{pickedParsedTemplate ? pickedParsedTemplate.title : '---'}}
                </a>

                <div class="dropdown-menu">
                    <a :ref="'itm_specific_' + item.id"
                        :id="'itm_specific_' + item.id"
                        @click.prevent="change(item)"
                        v-for="(item, index) in parsedTemplates"
                        class="dropdown-item"
                        href="#">
                            {{item.title}}
                    </a>
                </div>
            </div>
            
            <dropdown-template-specifics v-if="pickedParsedTemplateFields"
                @change="$emit('change', $event)"
                :templates="templates"
                :picked-template-ids-trace="pickedTemplateIdsTraceWithoutFirstElement"
                :parsed-templates="pickedParsedTemplateFields"
                :specifics="specifics"
                :specifics-as-id-key="specificsAsIdKey"/>
            
        </div>
        
    </div>
</template>

<script>
    // import ClientInfo from "./ClientInfo.vue";
    export default {
        name: 'dropdown_template_specifics',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(7777)));
            // console.log(JSON.parse(JSON.stringify(this.pickedParsedTemplateFields)));
            this.pickItemIfAlreadyPicked();
        },
        props: ['templates','parsedTemplates','specifics','specificsAsIdKey','pickedTemplateIdsTrace'],
        data: function(){
            return {
                pickedParsedTemplate: null,
                pickedTemplateIdsTraceWithoutFirstElement: null,
            };
        },
        computed: {
            IdTrace: function(){
                
            },
            pickedTemplateIdsTraceFirstElement: function(){
                if(Array.isArray(this.pickedTemplateIdsTrace) && this.pickedTemplateIdsTrace.length > 0){
                    let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace));
                    return pickedTemplateIdsTrace.shift();
                }
                return null;
            },
            pickedParsedTemplateFields: function(){
                if(this.pickedParsedTemplate === null || typeof this.pickedParsedTemplate.fields === 'undefined')
                    return null;
                return this.pickedParsedTemplate.fields;
            },
            specificsAsIdKeyArr: function(){
                if(this.specificsAsIdKey !== null)
                    return Object.values(this.specificsAsIdKey);
                return null;
            },
        },
        methods: {
            //Exucutes on specific pick and emmits change if specific is template(do )
            change: function(itm){
                if(this.pickedParsedTemplate !== null && itm.id == this.pickedParsedTemplate.id)
                    return;
                
                this.pickedParsedTemplate = null;
                // if(typeof itm.fields === 'undefined')
                this.$emit('change', null);
                this.$nextTick(() => {
                    this.pickedParsedTemplate = itm;
                    if(itm.type == 'template')
                        this.$emit('change', itm.template);
                });
            },
            //Used if have been passed ids trace of already setted template
            pickItemIfAlreadyPicked: function(){
                let _this = this;
                if(this.pickedTemplateIdsTraceFirstElement !== null){
                    // console.log(JSON.parse(JSON.stringify(4444)));
                    // console.log(JSON.parse(JSON.stringify(_this.pickedTemplateIdsTraceFirstElement)));
                    
                    let i = 0;
                    let interval = setInterval(function () {
                        if(i > 10)
                            clearInterval(interval);
                            
                        i++;
                        if(typeof _this.$refs['itm_specific_' + _this.pickedTemplateIdsTraceFirstElement] !== 'undefined'){
                            
                            _this.pickedParsedTemplate = _this.parsedTemplates[_this.pickedTemplateIdsTraceFirstElement];
                            
                            let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
                            pickedTemplateIdsTrace.shift();
                            _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                            
                            clearInterval(interval);
                        }
                    }, 100);
                }
            },
        },
        components: {
            
        },
        watch: {
            pickedTemplateIdsTrace: function(val){
                if(val === null){
                    // this.pickedParsedTemplate = null;
                    this.pickedTemplateIdsTraceWithoutFirstElement = null
                }else{
                    this.pickItemIfAlreadyPicked();
                }
            },
            parsedTemplates: function(val){
                if(val === null)
                    this.pickedParsedTemplate = null;
            },
        },
    }
</script>

<style lang="scss" scoped>
    .dropdown-toggle{
        width: 100%!important;
        text-align: left;
    }
    .dropdown-toggle::after {
        display: inline-block;
        margin-left: .255em;
        margin-top: 8px;
        vertical-align: .255em;
        content: "";
        border-top: .3em solid;
        border-right: .3em solid transparent;
        border-bottom: 0;
        border-left: .3em solid transparent;
        float: right;
    }
    .dropdown-menu{
        width: 100%!important;
    }
    .dropdown{
        margin-bottom: 10px;
    }
</style>