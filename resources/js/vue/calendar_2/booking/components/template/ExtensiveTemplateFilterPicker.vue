<template>
    <div>
            <span>{{templateCustomTitle}}:</span><br>
            <dropdown-template-specifics
                @change="$emit('change', $event)"
                :templates="templates"
                :picked-template-ids-trace="pickedTemplateIdsTrace"
                :parsed-templates="parsedTemplates"
                :specifics="specifics"
                :specifics-as-id-key="specificsAsIdKey"/>
        
    </div>
</template>

<script>
    // import ClientInfo from "./ClientInfo.vue";
    export default {
        name: 'extensive_template_filter_picker',
        mounted() {
            // console.log(JSON.parse(JSON.stringify(7777)));
            // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
        },
        props: ['templates','specifics','specificsAsIdKey','pickedTemplateIdsTrace'],
        data: function(){
            return {
                parsedTemplates: null,
            };
        },
        computed: {
            templateCustomTitle: function(){
                return this.$store.getters['custom_titles/title']('template');
            },
        },
        methods: {
            parseTemplatesAccordingToSpecifics: function(){
                let _this = this;
                
                let specificsAsIdKey = _this.specificsAsIdKey;
                // console.log(JSON.parse(JSON.stringify(this.templates)));
                
                let parsedTemplates = {};
                getRowOfTemps();
                
                this.parsedTemplates = parsedTemplates;
                
                console.log(JSON.parse(JSON.stringify('parsedTemplates')));
                console.log(JSON.parse(JSON.stringify(this.parsedTemplates)));
                
                // console.log(JSON.parse(JSON.stringify(parsedTemplates)));
                
                function getRowOfTemps(){
                    
                    // let parsedTemplatesLevel = [];
                    let idsTracePath = '';
                    for(let i = 0; i < _this.templates.length; i++){
                        let template = _this.templates[i];
                        
                        if(typeof template.specific !== 'undefined' && typeof template.specific.ids_trace !== 'undefined'){
                            if(template.specific.ids_trace !== null){
                                let idsTraceArr = template.specific.ids_trace.split(',');
                                
                                console.log(JSON.parse(JSON.stringify('idsTraceArr')));
                                let currentSpecificsToWorkWith = _this.specifics;
                                
                                idsTracePath = '';
                                for(let k = 0; k < idsTraceArr.length; k++){
                                    let idTrace = idsTraceArr[k];
                                    if(idsTracePath == ''){
                                        idsTracePath += '[' + idTrace + ']';
                                    }else{
                                        idsTracePath += '.fields[' + idTrace + ']';
                                    }
                                    eval(`
                                        if(typeof parsedTemplates${idsTracePath} === 'undefined')
                                            parsedTemplates${idsTracePath} = {
                                                type: 'specific',
                                                id: specificsAsIdKey[${idTrace}].id,
                                                title: specificsAsIdKey[${idTrace}].title,
                                                fields: {}
                                            };
                                    `);
                                }
                                
                                if(idsTracePath === ''){
                                    idsTracePath += '[' + template.specific.id + ']';
                                }else{
                                    idsTracePath += '.fields[' + template.specific.id + ']';
                                }
                                
                                let idsTracePathTemp = idsTracePath + '.fields[' + template.id + ']';
                                
                                eval(`
                                    if(typeof parsedTemplates${idsTracePath} === 'undefined')
                                        parsedTemplates${idsTracePath} = {
                                            type: 'specific',
                                            id: template.specific.id,
                                            title: template.specific.title,
                                            fields: {}
                                        };
                                    
                                    parsedTemplates${idsTracePathTemp} = {
                                        type: 'template',
                                        template: template,
                                        id: template.id,
                                        title: template.title
                                    };
                                `);
                            }else{
                                if(typeof parsedTemplates[template.specific.id] === 'undefined')
                                    parsedTemplates[template.specific.id] = {};
                                    
                                parsedTemplates[template.specific.id][template.id] = template;
                            }
                        }
                    }
                }
            },
        },
        components: {
            
        },
        watch: {
            // pickedTemplateIdsTrace: function(val){
            //     // alert(val);s
            //     // this.pickedTemplateIdsTrace = null;
            //     // this.parsedTemplates = null;
            //     // if(this.templates !== null){
            //     //     this.parsedTemplates = null;
            //     //     this.parseTemplatesAccordingToSpecifics();
            //     // }
            //     // this.parseTemplatesAccordingToSpecifics();
            // },
            templates: function(val){
                // return;
                this.parsedTemplates = null;
                if(val !== null)
                    this.parseTemplatesAccordingToSpecifics();
                    // this.$nextTick(() => {
                    //     this.parseTemplatesAccordingToSpecifics();
                    // });
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>