<template>
    <div>
        
        <div>
            <div id="templateDropdownThroughSpecifics" class="dropdown">
                <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{pickedParsedTemplate ? pickedParsedTemplate.title : '---'}}
                </a>

                <div class="dropdown-menu">
                    <a @click.prevent="change(item)" v-for="(item, index) in parsedTemplates" class="dropdown-item" href="#">{{item.title}}</a>
                </div>
            </div>
            
            <dropdown-template-specifics v-if="pickedParsedTemplateFields"
                @change="$emit('change', $event)"
                :templates="templates"
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
            console.log(JSON.parse(JSON.stringify(22222)));
            console.log(JSON.parse(JSON.stringify(this.parsedTemplates)));
            // console.log(JSON.parse(JSON.stringify(this.specificsArr)));
            // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            // console.log(JSON.parse(JSON.stringify(this.specifics)));
        },
        // props: ['owner','halls','clientInfo','allBookings','cookieFilters'],
        props: ['templates','parsedTemplates','specifics','specificsAsIdKey','resetPickedProp'],
        data: function(){
            return {
                // firstLevelIdsOfSpecifics: null,
                // specificsLevelMaxDeep: 0,
                pickedParsedTemplate: null,
                resetPicked: 0,
            };
        },
        computed: {
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
                    
                this.pickedParsedTemplate = null;
                this.$emit('change', null);
                this.$nextTick(() => {
                    this.pickedParsedTemplate = itm;
                    if(itm.type == 'template')
                        this.$emit('change', itm.template);
                });
            },
        },
        components: {
            
        },
        watch: {
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