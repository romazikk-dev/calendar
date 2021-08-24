<template>
    
    <tr class="event free"
    :class="{
        'too-short': item.too_short,
    }">
        <td>
            {{item.from}} - {{item.to}}
        </td>
        <td>
            <div class="status"></div>
        </td>
        <td>
            <a class="free-time-btn" href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Free time</a>
        </td>
        <td>
            <div class="float-right">
                <a class="pick-btn" href="#" @click.prevent="onClickPickFree(getDayItem(index), item)">Pick</a>
            </div>
            <span v-if="item.too_short" class="small float-right">
                Too short!
            </span>
        </td>
    </tr>
                            
</template>

<script>
    export default {
        name: 'listCalendarFreeRow',
        mounted() {
            
        },
        updated: function () {},
        props: ['item'],
        data: function(){
            return {
                // data: null,
            };
        },
        computed: {
            datesPerWeekday: function () {},
        },
        methods: {
            getDayItem: function(index){
                if(this.data === null || typeof this.data[index] === 'undefined')
                    return null;
                return this.data[index];
            },
            onClickPickFree: function (dayData, event) {
                if(event.too_short)
                    return;
                this.app.showPickTimeModal({
                    day: dayData,
                    item: event,
                });
            },
        },
        components: {
            
        },
        watch: {
            
        }
    }
</script>

<style lang="scss" scoped>
    
</style>

<style>
    
</style>