<template>
    <div>
        
        <div class="dropdown specific-filter-dropdown">
            <button class="btn btn-block btn-info btn-sm dropdown-toggle text-left mb-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span v-html="pickedSpecificTitle"></span>
                <span v-if="pickedSpecificCount" class="badge badge-pill badge-success">{{pickedSpecificCount}}</span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" @click.prevent="filter(null)">All</a>
                <dropdown-list @filter="filter($event)" :prefix="prefix" :specifics="specifics" />
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: "filter_dropdown",
        mounted() {
            // console.log(JSON.parse(JSON.stringify(assignItems)));
        },
        props: ['specifics'],
        data: function(){
            return {
                pickedSpecific: null,
                prefix: '',
            };
        },
        computed: {
            pickedSpecificTitle: function () {
                if(this.pickedSpecific === null)
                    return 'All';
                return typeof this.pickedSpecific.titlePath !== 'undefined' ?
                    this.pickedSpecific.titlePath : this.pickedSpecific.title;
                // console.log(JSON.parse(JSON.stringify(this.assignItems)));
            },
            pickedSpecificCount: function () {
                if(this.pickedSpecific === null)
                    return null;
                return this.pickedSpecific.count;
            },
        },
        methods: {
            // isJqueryValidationEnabled: function(){
            //     return (typeof jqueryValidation != 'undefined' && jqueryValidation.isValidating());
            // },
            filter: function(event){
                this.pickedSpecific = event;
                this.$emit('filter', event === null ? null : event.id);
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
    .specific-filter-dropdown{
        .dropdown-item{
            white-space: normal;
            // background-color: white;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
        }
        .dropdown-menu{
            // white-space: normal!important;
            // background-color: black!important;
        }
        .dropdown-toggle{
            white-space: normal!important;
            &::after {
                display: inline-block;
                // margin-left: .255em;
                vertical-align: .255em;
                content: "";
                border-top: .3em solid;
                border-right: .3em solid transparent;
                border-bottom: 0;
                border-left: .3em solid transparent;
                float: right;
                margin-left: 3px;
                margin-top: 8px;
            }
        }
        // .dropdown-toggle::after {
        //     display: inline-block;
        //     // margin-left: .255em;
        //     vertical-align: .255em;
        //     content: "";
        //     border-top: .3em solid;
        //     border-right: .3em solid transparent;
        //     border-bottom: 0;
        //     border-left: .3em solid transparent;
        //     float: right;
        //     margin-left: 3px;
        //     margin-top: 8px;
        // }
        .dropdown-menu{
            width: 100%;
            max-height: 200px;
            overflow-x: auto;
        }
    }
</style>