<template>
    <div>
        
        <div v-if="specifics" id="templateDropdownThroughSpecifics" class="dropdown">
            <span>Template:</span><br>
            <a :class="{disabled: (templates == null)}" class="btn btn-sm btn-info dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ---
            </a>

            <div class="dropdown-menu">
                <a @click.prevent="change(itm)" v-for="itm in specifics" class="dropdown-item" href="#">{{itm.title}}</a>
            </div>
        </div>
        
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
            // console.log(JSON.parse(JSON.stringify(9999999)));
            // console.log(JSON.parse(JSON.stringify(this.specificsArr)));
            // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            // console.log(JSON.parse(JSON.stringify(this.specifics)));
        },
        // props: ['owner','halls','clientInfo','allBookings','cookieFilters'],
        props: ['templates','specifics','pickedHall'],
        data: function(){
            return {
                // firstLevelIdsOfSpecifics: null,
                // specificsLevelMaxDeep: 0,
            };
        },
        computed: {
            // specificsArr: function(){
            //     if(this.specifics !== null)
            //         return Object.values(this.specifics);
            //     return null;
            // },
        },
        methods: {
            change: function(itm){
                
            },
        },
        components: {
            
        },
        watch: {
            specifics: function(val){
                if(val !== null){
                    // this.setFirstLevelIdsOfSpecificsFromTemplates();
                    // console.log(JSON.parse(JSON.stringify(9999999)));
                    console.log(JSON.parse(JSON.stringify(val)));
                    // console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
                    // console.log(JSON.parse(JSON.stringify(this.specificsLevelMaxDeep)));
                    // this.specificsLevelMaxDeep
                }
                // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            },
            // templates: function(val){
            //     if(val !== null){
            //         this.setFirstLevelIdsOfSpecificsFromTemplates();
            //         console.log(JSON.parse(JSON.stringify(9999999)));
            //         console.log(JSON.parse(JSON.stringify(val)));
            //         console.log(JSON.parse(JSON.stringify(this.firstLevelIdsOfSpecifics)));
            //         console.log(JSON.parse(JSON.stringify(this.specificsLevelMaxDeep)));
            //         // this.specificsLevelMaxDeep
            //     }
            //     // console.log(JSON.parse(JSON.stringify(this.pickedHall)));
            // },
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
</style>