<template>
    <div class="counters">
        <ul>
            <li>
                <span class="badge badge-pill badge-primary">2</span>
            </li>
            <li>
                <span class="badge badge-pill badge-info">1</span>
            </li>
            <li>
                <span class="badge badge-pill badge-warning">1</span>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'monthCellCount',
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.getDataForCalendar();
            // console.log(JSON.parse(JSON.stringify(this.items)));
        },
        props: ['items'],
        data: function(){
            return {
                // range: helper.range.range,
            };
        },
        methods: {
            
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    .counters{
        // background-color: red;
        width: 26px;
        float: left;
        font-size: 12px;
        padding-top: 26px;
        padding-left: 4px;
        line-height: 14px;
        opacity: .4;
        transition: opacity .3s ease;
        &:hover{
            opacity: 1;
        }
        ul{
            padding: 0px;
            margin: 0px;
            li{
                list-style: none;
                padding: 0px;
                margin: 0px;
                span{
                    cursor: pointer;
                }
            }
        }
    }
</style>