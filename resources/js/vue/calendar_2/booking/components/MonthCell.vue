<template>
    <div>
        <ul>
            <li v-for="itm in items">
                <div v-if="itm.type == 'free'" class='free-slot'>
                    <b>Free time:</b><br>
                    <b>{{itm.from}} - {{itm.to}}</b>
                    <div class="not-approved-bookings" v-if="itm.not_approved_bookings">
                        <div class="not-approved-bookings-itm" v-for="itmm in itm.not_approved_bookings">
                            <b>In approving:</b><br>
                            <b>{{itmm.template_without_user_scope.title}}<br>
                            {{itmm.from}} - {{itmm.to}}</b>
                            <!-- <button @click.prevent="$emit('cancel', itm)"
                                type="button"
                                class="btn btn-link btn-sm btn-block">Cancel</button > -->
                            <button @click.prevent="$emit('cancel', itmm.booking)"
                                type="button"
                                class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
                        </div>
                    </div>
                    <button @click.prevent="$emit('open-modal', itm)"
                        type="button"
                        class="btn btn-link btn-sm btn-block book">Book</button >
                </div>
                <div v-if="itm.type == 'event'" class='booked-slot'>
                    <b>Booked on:</b><br>
                    <b>{{itm.template_without_user_scope.title}}<br>
                    {{itm.from}} - {{itm.to}}</b>
                    <button @click.prevent="$emit('cancel', itm)"
                        type="button"
                        class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'monthCell',
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.getDataForCalendar();
            console.log(JSON.parse(JSON.stringify(this.items)));
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

<style scoped>
    ul{
        padding: 0px;
        margin: 0px;
        width: 100%;
        padding-left: 30px;
        padding-right: 4px;
        padding-top: 4px;
    }
    ul li{
        list-style: none;
        /* background-color: red; */
    }
    ul li .free-slot{
        background-color: red;
        display: block;
        width: 100%;
        background-color: rgba(144,238,144, 0.5);
        margin-bottom: 3px;
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: black;
        text-decoration: none;
        transition: background .2s ease;
        margin-bottom: 6px;
    }
    .booked-slot{
        background-color: red;
        display: block;
        width: 100%;
        background-color: rgba(114,51,128, 1);
        /* margin-bottom: 3px; */
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: white;
        text-decoration: none;
        transition: background .2s ease;
        position: relative;
        left: -27px;
        width: calc(100% + 27px);
        margin-bottom: 6px;
    }
    ul li:first-child .booked-slot{
        left: 0px;
        width: 100%;
    }
    /* ul li a:hover{
        background-color: rgba(144,238,144, 1);
    } */
    .not-approved-bookings{
        /* background-color: brown;
        color: white; */
        /* padding: 2px 6px; */
        /* margin-bottom: 2px; */
        /* border-radius: 3px; */
    }
    .not-approved-bookings-itm{
        background-color: #cf582c;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        margin-bottom: 2px;
        position: relative;
        left: -28px;
        width: calc(100% + 28px);
        /* padding-left: 36px; */
    }
    button.cancel{
        position: absolute;
        top: 0px;
        right: 0px;
        outline: none!important;
        /* background-color: red; */
        border-radius: 0px;
        width: 30px;
        height: 30px;
        font-size: 40px;
        line-height: .5em;
        padding: 0px;
        text-decoration: none;
        color: #fff;
        opacity: .6;
        transition: opacity .3s ease;
        box-shadow: none;
    }
    /* .not-approved-bookings-itm button.cancel */
    .not-approved-bookings-itm:hover button.cancel,
    .booked-slot:hover button.cancel{
        opacity: 1;
    }
    button.cancel span{
        display: block;
        width: 100%;
        height: 100%;
    }
    button.book{
        box-shadow: none;
    }
</style>