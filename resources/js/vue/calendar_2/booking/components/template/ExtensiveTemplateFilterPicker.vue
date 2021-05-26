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
        
        <dropdown-template-specifics :templates="templates"
            :picked-hall="pickedHall"
            :specifics="filteredSpecificsArrIfPickedHallNotNull" />
        
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
            // console.log(JSON.parse(JSON.stringify(7777)));
            // console.log(JSON.parse(JSON.stringify(this.templates)));
            // console.log(JSON.parse(JSON.stringify(this.specifics)));
        },
        // props: ['owner','halls','clientInfo','allBookings','cookieFilters'],
        props: ['templates','specifics','pickedHall'],
        data: function(){
            return {
                firstLevelIdsOfSpecifics: null,
                specificsLevelMaxDeep: 0,
            };
        },
        computed: {
            specificsArr: function(){
                if(this.specifics !== null)
                    return Object.values(this.specifics);
                return null;
            },
            filteredSpecificsArrIfPickedHallNotNull: function(){
                return this.pickedHall === null ? null : this.filteredSpecificsArr;
            },
            filteredSpecificsArr: function(){
                let specificsArr = this.specificsArr;
                
                if(this.firstLevelIdsOfSpecifics === null)
                    return specificsArr;
                    
                let filteredSpecificsArr = [];
                for(let i = 0; i < specificsArr.length; i++){
                    let specific = specificsArr[i];
                    
                    // console.log(JSON.parse(JSON.stringify(222222)));
                    // console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
                    // console.log(JSON.parse(JSON.stringify(specific)));
                    
                    if(this.firstLevelIdsOfSpecifics.includes(specific.id))
                        filteredSpecificsArr.push(specific);
                }
                
                // console.log(JSON.parse(JSON.stringify(222222)));
                // console.log(JSON.parse(JSON.stringify(filteredSpecificsArr)));
                
                return filteredSpecificsArr;
            },
        },
        methods: {
            setFirstLevelIdsOfSpecificsFromTemplates: function(){
                if(Array.isArray(this.templates) && this.templates.length > 0){
                    let firstLevelIdsOfSpecifics = [];
                    for(let i = 0; i < this.templates.length; i++){
                        let template = this.templates[i];
                        // if(typeof template.specific !== 'undefined' && typeof template.specific.ids_trace !== 'undefined' &&
                        // template.specific.ids_trace !== null){
                        //     let idsTraceArr = template.specific.ids_trace.split(',');
                        //     if(idsTraceArr.length > this.specificsLevelMaxDeep)
                        //         this.specificsLevelMaxDeep = idsTraceArr.length;
                        //     let firstIdTrace = parseInt(idsTraceArr[0]);
                        //     if(!firstLevelIdsOfSpecifics.includes(firstIdTrace))
                        //         firstLevelIdsOfSpecifics.push(firstIdTrace);
                        // }
                        
                        if(typeof template.specific !== 'undefined' && typeof template.specific.ids_trace !== 'undefined'){
                            if(template.specific.ids_trace !== null){
                                let idsTraceArr = template.specific.ids_trace.split(',');
                                if(idsTraceArr.length > this.specificsLevelMaxDeep)
                                    this.specificsLevelMaxDeep = idsTraceArr.length;
                                let firstIdTrace = parseInt(idsTraceArr[0]);
                                if(!firstLevelIdsOfSpecifics.includes(firstIdTrace))
                                    firstLevelIdsOfSpecifics.push(firstIdTrace);
                            }else{
                                if(!firstLevelIdsOfSpecifics.includes(template.specific.id))
                                    firstLevelIdsOfSpecifics.push(template.specific.id);
                            }
                        }
                        
                    }
                    this.firstLevelIdsOfSpecifics = firstLevelIdsOfSpecifics;
                }else{
                    this.firstLevelIdsOfSpecifics = null;
                }
                // this.showFilters = false;
                // this.$emit('showCalendar');
            },
        },
        components: {
            
        },
        watch: {
            templates: function(val){
                if(val !== null){
                    this.setFirstLevelIdsOfSpecificsFromTemplates();
                    console.log(JSON.parse(JSON.stringify(9999999)));
                    console.log(JSON.parse(JSON.stringify(val)));
                    console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
                    // console.log(JSON.parse(JSON.stringify(this.sortedSpecificsArr)));
                    // this.specificsLevelMaxDeep
                }
                // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>