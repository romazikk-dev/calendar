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
                :key="'dropdown_template_specifics_' + Math.floor(Math.random() * 10000)"
                @change="$emit('change', $event)"
                :is-changed-on-click="changedOnClick"
                :templates="templates"
                :picked-template-ids-trace="pickedTemplateIdsTraceWithoutFirstElement"
                :reset-picked-prop="resetPicked"
                :parsed-templates="pickedParsedTemplateFields"
                :specifics="specifics"
                :specifics-as-id-key="specificsAsIdKey"/>
            
        </div>
        
        <!-- <dropdown-template-specifics v-if="pickedSpecifics"
            :templates="templates"
            :picked-hall="pickedHall"
            :specifics="specifics" /> -->
        
        <!-- <div v-for="(specific, index) in specifics">
            {{specific.title}}
        </div> -->
        
    </div>
</template>

<script>
    // import ClientInfo from "./ClientInfo.vue";
    export default {
        name: 'dropdown_template_specifics',
        mounted() {
            console.log(JSON.parse(JSON.stringify(999999996666666)));
            console.log(JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace)));
            // console.log(JSON.parse(JSON.stringify(this.specificsArr)));
            // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            // console.log(JSON.parse(JSON.stringify(this.specifics)));
            
            this.pickItemIfAlreadyPicked();
        },
        // props: ['owner','halls','clientInfo','allBookings','cookieFilters'],
        props: ['templates','parsedTemplates','specifics','specificsAsIdKey','resetPickedProp','pickedTemplateIdsTrace','isChangedOnClick'],
        data: function(){
            return {
                // firstLevelIdsOfSpecifics: null,
                // specificsLevelMaxDeep: 0,
                pickedParsedTemplate: null,
                resetPicked: 0,
                pickedTemplateIdsTraceWithoutFirstElement: null,
                // rendered: false,
                changedOnClick: false,
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
            // pickedTemplateIdsTraceWithoutFirstElement: function(){
            //     if(this.pickedTemplateIdsTrace === null)
            //         return null;
            // 
            //     let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace));
            //     pickedTemplateIdsTrace.shift();
            // 
            //     return pickedTemplateIdsTrace;
            // 
            //     // _this.$nextTick(function(){
            //     //     console.log(JSON.parse(JSON.stringify(22222222222)));
            //     //     console.log(JSON.parse(JSON.stringify(pickedTemplateIdsTrace)));
            //     //     // let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
            //     //     pickedTemplateIdsTrace.shift();
            //     //     _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
            //     // });
            // },
            pickedParsedTemplateFields: function(){
                // if()
                // if(this.pickedParsedTemplate === null
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
            change: function(itm){
                if(this.pickedParsedTemplate !== null && itm.id == this.pickedParsedTemplate.id)
                    return;
                
                this.changedOnClick = true;
                this.pickedParsedTemplate = null;
                this.$emit('change', null);
                this.$nextTick(() => {
                    this.pickedParsedTemplate = itm;
                    if(itm.type == 'template')
                        this.$emit('change', itm.template);
                });
            },
            pickItemIfAlreadyPicked: function(){
                if(this.isChangedOnClick === true)
                    return;
                    
                let _this = this;
                if(this.pickedTemplateIdsTraceFirstElement !== null){
                    let i = 0;
                    let interval = setInterval(function () {
                        if(i > 10)
                            clearInterval(interval);
                            
                        i++;
                        if(typeof _this.$refs['itm_specific_' + _this.pickedTemplateIdsTraceFirstElement] !== 'undefined'){
                            
                            _this.pickedParsedTemplate = _this.parsedTemplates[_this.pickedTemplateIdsTraceFirstElement];
                            
                            // _this.$refs['itm_specific_' + _this.pickedTemplateIdsTraceFirstElement][0].click();
                            
                            let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
                            // if(pickedTemplateIdsTrace.length > 1){
                            pickedTemplateIdsTrace.shift();
                                // _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                            // }
                            
                            // if(pickedTemplateIdsTrace.length > 1){
                            _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                                // _this.pickedTemplateIdsTraceWithoutFirstElement = null;
                            // }else{
                            //     _this.pickedTemplateIdsTraceWithoutFirstElement = null;
                            // }
                            // _this.rendered = true;
                            
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
                // return;
                // console.log(JSON.parse(JSON.stringify('pickedTemplateIdsTrace')));
                // console.log(JSON.parse(JSON.stringify(val)));
                
                // if(this.rendered === false)
                    this.pickItemIfAlreadyPicked();
                
                return;
                
                let _this = this;
                if(this.pickedTemplateIdsTraceFirstElement !== null){
                    let i = 0;
                    let interval = setInterval(function () {
                        if(i > 10)
                            clearInterval(interval);
                            
                        i++;
                        if(typeof _this.$refs['itm_specific_' + _this.pickedTemplateIdsTraceFirstElement] !== 'undefined'){
                            
                            // let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
                            // pickedTemplateIdsTrace.shift();
                            
                            // console.log(JSON.parse(JSON.stringify(22222222222)));
                            // console.log(JSON.parse(JSON.stringify(pickedTemplateIdsTrace)));
                            
                            // _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                            
                            _this.$refs['itm_specific_' + _this.pickedTemplateIdsTraceFirstElement][0].click();
                            let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
                            
                            setTimeout(function(){
                                pickedTemplateIdsTrace.shift();
                                _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                            }, 1000);
                            // _this.$nextTick(function(){
                            //     console.log(JSON.parse(JSON.stringify(22222222222)));
                            //     pickedTemplateIdsTrace.shift();
                            //     console.log(JSON.parse(JSON.stringify(pickedTemplateIdsTrace)));
                            //     // let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(_this.pickedTemplateIdsTrace));
                            //     // pickedTemplateIdsTrace.shift();
                            //     // _this.pickedTemplateIdsTraceWithoutFirstElement = pickedTemplateIdsTrace;
                            // });
                            
                            // if(this.pickedTemplateIdsTrace === null)
                            //     return null;
                            // 
                            // let pickedTemplateIdsTrace = JSON.parse(JSON.stringify(this.pickedTemplateIdsTrace));
                            // pickedTemplateIdsTrace.shift();
                            // 
                            // return pickedTemplateIdsTrace;
                            
                            clearInterval(interval);
                        }
                    }, 100);
                }
            },
            parsedTemplates: function(val){
                if(val === null)
                    this.pickedParsedTemplate = null;
                // console.log(JSON.parse(JSON.stringify(888888)));
                // console.log(JSON.parse(JSON.stringify(val)));
                // let parsedTemplates = Object.values(val);
                // console.log(JSON.parse(JSON.stringify(parsedTemplates)));
                // console.log(JSON.parse(JSON.stringify(val)));
                // console.log(JSON.parse(JSON.stringify(this.specificsAsIdKeyArr)));
                // specificsAsIdKeyArr
            },
            specifics: function(val){
                if(val !== null){
                    // this.setFirstLevelIdsOfSpecificsFromTemplates();
                    // console.log(JSON.parse(JSON.stringify(9999999)));
                    // console.log(JSON.parse(JSON.stringify(val)));
                    // console.log(JSON.parse(JSON.stringify(this.templates)));
                    // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
                    // console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
                    // console.log(JSON.parse(JSON.stringify(this.specificsLevelMaxDeep)));
                    // this.specificsLevelMaxDeep
                }
                // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            },
        },
    }
</script>

<style lang="scss" scoped>
    .dropdown-toggle{
        // width: 100%!important;
        // max-width: 300px;
        // width: 500px;
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