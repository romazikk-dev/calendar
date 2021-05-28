<template>
    <div>
        
        <!-- <div id="templateDropdown" class="dropdown">
            <span>Template:</span>
            <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{pickedItmTemplate == null ? '---' : (pickedItmTemplate.title)}}
            </a>

            <div class="dropdown-menu">
                <a @click.prevent="change('template', itm)" v-for="itm in templates" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div> -->
        
        <!-- <template v-if="parsedTemplates"> -->
            <span>Template:</span><br>
            <dropdown-template-specifics
                @change="$emit('change', $event)"
                :templates="templates"
                :parsed-templates="parsedTemplates"
                :specifics="specifics"
                :specifics-as-id-key="specificsAsIdKey"/>
        <!-- </template> -->
        
        <!-- <div v-if="templates">
            dddddd
        </div> -->
        
    </div>
</template>

<script>
    // import ClientInfo from "./ClientInfo.vue";
    export default {
        name: 'extensive_template_filter_picker',
        mounted() {
            console.log(JSON.parse(JSON.stringify(7777)));
            // console.log(JSON.parse(JSON.stringify(this.templates)));
            console.log(JSON.parse(JSON.stringify('specifics')));
            console.log(JSON.parse(JSON.stringify(this.specifics)));
            console.log(JSON.parse(JSON.stringify('templates')));
            console.log(JSON.parse(JSON.stringify(this.templates)));
        },
        // props: ['owner','halls','clientInfo','allBookings','cookieFilters'],
        props: ['templates','specifics','specificsAsIdKey','pickedHall'],
        data: function(){
            return {
                firstLevelIdsOfSpecifics: null,
                specificsLevelMaxDeep: 0,
                parsedTemplates: null,
            };
        },
        computed: {
            // specificsArr: function(){
            //     if(this.specifics !== null)
            //         return Object.values(this.specifics);
            //     return null;
            // },
            // filteredSpecificsArrIfPickedHallNotNull: function(){
            //     return this.pickedHall === null ? null : this.filteredSpecificsArr;
            // },
            // filteredSpecificsArr: function(){
            //     let specificsArr = this.specificsArr;
            // 
            //     if(this.firstLevelIdsOfSpecifics === null)
            //         return specificsArr;
            // 
            //     let filteredSpecificsArr = [];
            //     for(let i = 0; i < specificsArr.length; i++){
            //         let specific = specificsArr[i];
            // 
            //         // console.log(JSON.parse(JSON.stringify(222222)));
            //         // console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
            //         // console.log(JSON.parse(JSON.stringify(specific)));
            // 
            //         if(this.firstLevelIdsOfSpecifics.includes(specific.id))
            //             filteredSpecificsArr.push(specific);
            //     }
            // 
            //     // console.log(JSON.parse(JSON.stringify(222222)));
            //     // console.log(JSON.parse(JSON.stringify(filteredSpecificsArr)));
            // 
            //     return filteredSpecificsArr;
            // },
        },
        methods: {
            // setFirstLevelIdsOfSpecificsFromTemplates: function(){
            //     if(Array.isArray(this.templates) && this.templates.length > 0){
            //         let firstLevelIdsOfSpecifics = [];
            //         for(let i = 0; i < this.templates.length; i++){
            //             let template = this.templates[i];
            //             // if(typeof template.specific !== 'undefined' && typeof template.specific.ids_trace !== 'undefined' &&
            //             // template.specific.ids_trace !== null){
            //             //     let idsTraceArr = template.specific.ids_trace.split(',');
            //             //     if(idsTraceArr.length > this.specificsLevelMaxDeep)
            //             //         this.specificsLevelMaxDeep = idsTraceArr.length;
            //             //     let firstIdTrace = parseInt(idsTraceArr[0]);
            //             //     if(!firstLevelIdsOfSpecifics.includes(firstIdTrace))
            //             //         firstLevelIdsOfSpecifics.push(firstIdTrace);
            //             // }
            // 
            //             if(typeof template.specific !== 'undefined' && typeof template.specific.ids_trace !== 'undefined'){
            //                 if(template.specific.ids_trace !== null){
            //                     let idsTraceArr = template.specific.ids_trace.split(',');
            //                     if(idsTraceArr.length > this.specificsLevelMaxDeep)
            //                         this.specificsLevelMaxDeep = idsTraceArr.length;
            //                     let firstIdTrace = parseInt(idsTraceArr[0]);
            //                     if(!firstLevelIdsOfSpecifics.includes(firstIdTrace))
            //                         firstLevelIdsOfSpecifics.push(firstIdTrace);
            //                 }else{
            //                     if(!firstLevelIdsOfSpecifics.includes(template.specific.id))
            //                         firstLevelIdsOfSpecifics.push(template.specific.id);
            //                 }
            //             }
            // 
            //         }
            //         this.firstLevelIdsOfSpecifics = firstLevelIdsOfSpecifics;
            //     }else{
            //         this.firstLevelIdsOfSpecifics = null;
            //     }
            //     // this.showFilters = false;
            //     // this.$emit('showCalendar');
            // },
            parseTemplatesAccordingToSpecifics: function(){
                let _this = this;
                
                let specificsAsIdKey = _this.specificsAsIdKey;
                // console.log(JSON.parse(JSON.stringify(7777)));
                // console.log(JSON.parse(JSON.stringify('specifics')));
                // console.log(JSON.parse(JSON.stringify(this.specifics)));
                // console.log(JSON.parse(JSON.stringify('templates')));
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
                                    
                                    // if(typeof parsedTemplates${idsTracePathTemp} === 'undefined')
                                    //     parsedTemplates${idsTracePathTemp} = {};
                                    
                                    parsedTemplates${idsTracePathTemp} = {
                                        type: 'template',
                                        template: template,
                                        id: template.id,
                                        title: template.title
                                    };
                                    
                                    // parsedTemplates${idsTracePathTemp}[template.id] = {
                                    //     type: 'template',
                                    //     id: template.id,
                                    //     title: template.title
                                    // };
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
            // parsedTemplates: function(val){
            //     // console.log(JSON.parse(JSON.stringify(9999999)));
            //     // console.log(JSON.parse(JSON.stringify(val)));
            // },
            templates: function(val){
                // return;
                this.parsedTemplates = null;
                if(val !== null)
                    this.$nextTick(() => {
                        this.parseTemplatesAccordingToSpecifics();
                    });
                    // this.parseTemplatesAccordingToSpecifics();
                    // this.setFirstLevelIdsOfSpecificsFromTemplates();
                    // console.log(JSON.parse(JSON.stringify(9999999)));
                    // console.log(JSON.parse(JSON.stringify(val)));
                    // console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
                    
                    // console.log(JSON.parse(JSON.stringify(this.sortedSpecificsArr)));
                    // this.specificsLevelMaxDeep
                    
                    // console.log(JSON.parse(JSON.stringify(7777)));
                    // console.log(JSON.parse(JSON.stringify('specifics')));
                    // console.log(JSON.parse(JSON.stringify(this.specifics)));
                    // console.log(JSON.parse(JSON.stringify('templates')));
                    // console.log(JSON.parse(JSON.stringify(this.templates)));
                // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>