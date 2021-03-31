<template>
    <div>
        <div class="handles">
        </div>
        <div class="handles">
            <button type="button" class="btn btn-sm btn-primary float-left">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
                Previous
            </button>
            <button type="button" class="btn btn-sm btn-primary float-right">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
            
        </div>
        <table>
            <thead>
                <tr>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in 6">
                    <td v-for="k in 7">{{dates[(((i*7) + k))-8].day}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    // require("../../../../ts/calendar_helper/enums/View").Helper;
    // import { View } from "../../../../ts/calendar_helper/enums/View";
    // console.log(View);
    export default {
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
        },
        props: ['dates','range','view'],
        data: function(){
            return {
                // dateRange: helper.range.range,
                // dates: helper.range.dates
            };
        },
        methods: {
            // openModal: function(){
            // 
            // },
        },
        components: {
            // EnumView
        },
    }
</script>

<style scoped>
    table{
        width: 100%;
    }
    table td, table th{
        border: 1px solid #ccc;
    }
</style>