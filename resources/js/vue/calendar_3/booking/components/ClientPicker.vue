<template>
    <div>
        
        <div v-if="label">{{label}}:</div>
        <dropdown :items="items"
            ref="dropdown"
            :small="small ? true : false"
            @change="$emit('change')"
            :max-dropdown-menu-height="maxDropdownMenuHeight"
            :picked-item-placeholder="pickedItemPlaceholder ? pickedItemPlaceholder : 'Pick a client'" />
        
    </div>
</template>

<script>
    import Dropdown from "./elements/DropdownSearchable/Dropdown.vue";
    export default {
        mounted() {
            // console.log(JSON.parse(JSON.stringify(this.items)));
            // this.checkIfRegionUtc();
            this.getClients();
        },
        props: ['pickedItemPlaceholder','label', 'maxDropdownMenuHeight', 'small'],
        data: function(){
            return {
                clients: null,
            };
        },
        computed: {
            items: function () {
                let items = JSON.parse(JSON.stringify(this.clients));
                for (let idx in items) {
                    items[idx].val = this.fullNameOfObj(items[idx]);
                    items[idx].key = items[idx].id;
                }
                return items;
            },
            isPicked: function () {
                return this.$refs.dropdown.isPicked;
            },
            pickedClient: function(){
                return this.$refs.dropdown.pickedItem;
            },
        },
        methods: {
            getClients: function(){
                this.app.getClients().then((clients) => {
                    console.log(JSON.parse(JSON.stringify(clients)));
                    this.clients = clients;
                    
                    console.log(JSON.parse(JSON.stringify(this.items)));
                });
            },
            reset: function(){
                this.$refs.dropdown.reset();
            },
            setClient: function(client){
                this.$refs.dropdown.setItemByKey(client.id);
                // for(let idx in this.clients){
                //     if(client.id === this.clients[idx].id)
                //         this.$refs.dropdown.setItemByKey(client.id);
                // }
            },
        },
        components: {
            Dropdown,
        },
        watch: {
            // reseter: function(val){
            //     this.pickedItem = null;
            // },
        },
    }
</script>

<style lang="scss" scoped>
    
</style>