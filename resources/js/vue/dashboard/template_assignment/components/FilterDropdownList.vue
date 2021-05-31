<template>
    <div>
        
        <div v-for="(specific,index) in specifics">
            <a class="dropdown-item" href="#" @click.prevent="filter(specific)">
                <span v-html="getTitle(specific)"></span>
                <span v-if="specific.count" class="badge badge-pill badge-success">{{specific.count}}</span>
            </a>
            <dropdown-list v-if="!isSpecificFieldsEmpty(specific)"
                @filter="$emit('filter', $event)"
                :prefix="getPrefixArr(specific)"
                :specifics="specific.fields" />
        </div>
        
    </div>
</template>

<script>
    export default {
        name: "filter_dropdown_list",
        mounted() {
            // console.log(JSON.parse(JSON.stringify(assignItems)));
        },
        props: ['specifics','prefix'],
        data: function(){
            return {
                showDropdownItemBg: true,
                // prefixSymbol: '-',
                // titledPrefix: [],
            };
        },
        computed: {
            joinedPrefix: function (){
                // console.log(JSON.parse(JSON.stringify(this.prefix)));
                // if(this.prefix === null)
                //     return '';
                // return this.prefix.join(this.prefixSymbolDivider);
                if(Array.isArray(this.prefix))
                    return this.prefix.join(this.prefixSymbolDivider);
                // return this.prefix;
                // return JSON.parse(JSON.stringify(this.prefix));
            },
            prefixSymbolDivider: function (){
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                `;
            },
            // prefix: function (){
            //     let prefix = '';
            //     for(let i = 0; i < this.prefixLength; i++)
            //         prefix += this.prefixSymbol;
            //     return prefix;
            //     // console.log(JSON.parse(JSON.stringify(this.assignItems)));
            // },
        },
        methods: {
            filter: function(specific){
                specific.titlePath = this.getTitle(specific);
                this.$emit('filter', specific);
            },
            getTitle: function(specific){
                let prefix = JSON.parse(JSON.stringify(this.prefix));
                if(Array.isArray(prefix)){
                    prefix.push(specific.title);
                    return prefix.join(this.prefixSymbolDivider);
                }else{
                    return specific.title;
                }
            },
            getPrefixArr: function(specific){
                let prefixArr = [];
                if(Array.isArray(this.prefix)){
                    let prefix = JSON.parse(JSON.stringify(this.prefix));
                    prefix.push(specific.title);
                    prefixArr = prefix;
                }else{
                    prefixArr = [specific.title];
                }
                
                return prefixArr;
            },
            isSpecificFieldsEmpty: function(specific){
                if(typeof specific.fields === 'undefined')
                    return true;
                    
                let fieldsAsArr = Object.values(specific.fields);
                if(fieldsAsArr.length == 0)
                    return true;
                
                return false;
            },
        },
        components: {
            
        },
        watch: {
            // items: function(val){
            // 
            // },
        }
    }
</script>

<style lang="scss" scoped>
    .dropdown-item{
        white-space: normal;
        border-bottom: 1px solid #f1f1f1;
        
        // background-color: white;
    }
</style>