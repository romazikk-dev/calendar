<template>
    <div>
        
        <div class="contt">
            <div class="row">
                <div class="col col-sm-12 col-md-6">
                    
                    <div class="d-table">
                        <div class="d-table-row">
                            <div class="left-coll d-table-cell">
                                Region:
                            </div>
                            <div class="right-coll d-table-cell">
                                
                                <dropdown @change="regionChange($event)"
                                    ref="dropdown_regions"
                                    :items="regions"
                                    :error="regionError"
                                    input-name="timezone_region" />
                                    
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="col col-sm-12 col-md-6">
                    
                    <!-- fdf -->
                    <div class="d-table">
                        <div class="d-table-row">
                            <div class="left-coll d-table-cell">
                                Timezone:
                            </div>
                            <div class="right-coll d-table-cell">
                                
                                <dropdown :disabled="!timezonesByRegion"
                                    @change="timezoneChange($event)"
                                    ref="dropdown_timezone"
                                    :reseter="reseter"
                                    :items="timezonesByRegion"
                                    :error="timezoneKeyError"
                                    input-name="timezone_key" />
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import Dropdown from "./Dropdown.vue";
    export default {
        mounted() {
            console.log(JSON.parse(JSON.stringify(this.timezones)));
            this.setPickedItemsFromValues();
        },
        // props: ['postTitle'],
        data: function(){
            return {
                timezones: timezones,
                pickedRegion: null,
                pickedTimezone: null,
                disableReseter: false,
                reseter: 0,
                values: timezone_values,
                errors: typeof errors !== 'undefined' ? errors : null,
            };
        },
        computed: {
            regionError: function(){
                if(this.errors === null || typeof this.errors.timezone_region === 'undefined' ||
                typeof this.errors.timezone_region[0] === 'undefined')
                    return null;
                return this.errors.timezone_region[0];
            },
            timezoneKeyError: function(){
                if(this.errors === null || typeof this.errors.timezone_key === 'undefined' ||
                typeof this.errors.timezone_key[0] === 'undefined')
                    return null;
                return this.errors.timezone_key[0];
            },
            regions: function () {
                let regions = [];
                let regionsNames = Object.keys(timezones)
                for(let idx in regionsNames){
                    let regionName = regionsNames[idx].trim();
                    let region = {
                        key: regionName,
                        val: regionName == 'utc' ? regionName.toUpperCase() : this.capitalizeFirstLetter(regionName),
                    }
                    regions.push(region);
                }
                // console.log(1111111);
                // console.log(regionsNames);
                return regions;
            },
            timezonesByRegion: function () {
                if(this.pickedRegion === null || typeof this.timezones[this.pickedRegion] === 'undefined')
                    return null;
                
                let timezonesByRegionParsed = [];
                let timezonesByRegion = this.timezones[this.pickedRegion];
                for(let idx in timezonesByRegion){
                    let timezoneByRegion = timezonesByRegion[idx];
                    timezonesByRegionParsed.push({
                        key: timezoneByRegion.key,
                        val: timezoneByRegion.timezone,
                    });
                }
                return timezonesByRegionParsed;
            },
        },
        methods: {
            setPickedItemsFromValues: function(){
                if(typeof this.values.timezone_region !== 'undefined'){
                    this.disableReseter = true;
                    
                    // this.pickedRegion = this.values.timezone_region;
                    for(let idx in this.regions){
                        let key = this.regions[idx].key;
                        
                        if(key === this.values.timezone_region){
                            this.$refs.dropdown_regions.change(this.regions[idx]);
                            break;
                        }
                    }
                    
                    if(typeof this.values.timezone_key !== 'undefined'){
                        // this.pickedRegion = this.values.timezone_region;
                        for(let idx in this.timezonesByRegion){
                            let key = this.timezonesByRegion[idx].key;
                            
                            if(key === this.values.timezone_key){
                                // alert(1);
                                console.log(JSON.parse(JSON.stringify(this.timezonesByRegion[idx])));
                                this.$refs.dropdown_timezone.change(this.timezonesByRegion[idx]);
                                break;
                            }
                        }
                    }
                    
                    this.disableReseter = false;
                }
            },
            regionChange: function(event){
                if(event === null || typeof event.key === 'undefined' ||
                typeof this.timezones[event.key] === 'undefined'){
                    this.pickedRegion = null;
                }else{
                    this.pickedRegion = event.key;
                }
                if(!this.disableReseter)
                    this.reseter++;
            },
            timezoneChange: function(event){
                
            },
            capitalizeFirstLetter: function(val){
                return helper.capitalizeFirstLetter(val);
            },
        },
        components: {
            Dropdown,
        },
        watch: {
            pickedRegion: function(val){
                console.log(JSON.parse(JSON.stringify(val)));
            },
        },
    }
</script>

<style lang="scss" scoped>
    .contt{
        .d-table{
            width: 100%;
        }
        .left-coll{
            width: 100px;
            text-transform: uppercase;
        }
        // .right-coll{
        //     padding-left: 150px;
        // }
        .dropdown-toggle{
            width: 100%;
            text-align: left;
            position: relative;
            &::after{
                display: inline-block;
                margin-left: .255em;
                vertical-align: .255em;
                content: "";
                border-top: .3em solid;
                border-right: .3em solid transparent;
                border-bottom: 0;
                border-left: .3em solid transparent;
                position: absolute;
                top: 16px;
                right: 14px;
            }
        }
        .dropdown-menu{
            width: 100%;
            max-height: 250px;
            overflow-x: auto;
        }
    }
</style>