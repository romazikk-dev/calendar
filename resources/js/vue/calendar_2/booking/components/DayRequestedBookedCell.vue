<template>
    <div class="calendar-top-items">
        <template v-for="item in items">
            <template v-if="item.type == 'free'">
                <div v-for="not_approved_booking in item.not_approved_bookings"
                    class="calendar-top-item requested-booking-calendar-item">
                        {{capitalizeFirstLetter(getText('text.in_approving'))}}:<br>
                        <b>
                            {{not_approved_booking.template_without_user_scope.title}}<br>
                            {{not_approved_booking.from}} - {{not_approved_booking.to}}
                        </b>
                    <button @click.prevent="$emit('cancel', not_approved_booking)"
                        type="button"
                        class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
                </div>
            </template>
            <div v-if="item.type == 'event'"
                class="calendar-top-item"
                :class="{
                    'booked-calendar-item': item.approved,
                    'requested-booking-calendar-item': !item.approved,
                }">
                    {{
                        item.approved ?
                        capitalizeFirstLetter(getText('text.booked_on')) :
                        capitalizeFirstLetter(getText('text.in_approving'))
                    }}:<br>
                    <b>
                        {{item.template_without_user_scope.title}}<br>
                        {{item.from}} - {{item.to}}
                    </b>
                    <button @click.prevent="$emit('cancel', item)"
                        type="button"
                        class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'dayRequestedBookedCell',
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.getDataForCalendar();
            // console.log(JSON.parse(JSON.stringify(this.items)));
        },
        props: ['date'],
        data: function(){
            return {
                // items: helper.range.range,
            };
        },
        computed: {
            items: function () {
                return this.date == null ? null : this.date.items;
            },
        },
        methods: {
            
        },
        components: {
            
        },
    }
</script>

<style scoped>
    .calendar-top-items{
        padding-top: 3px;
    }
    .calendar-top-item{
        width: 120px;
        margin-bottom: 3px;
        margin-right: 3px;
        border-radius: 3px;
        font-size: 10px;
        padding: 2px 6px;
        color: black;
        text-decoration: none;
        transition: background .2s ease;
        /* line-height: 1.2em; */
        cursor: pointer;
        position: relative;
        text-align: left;
        float: left;
    }
    .requested-booking-calendar-item{
        background-color: #cf582c;
        color: white;
        cursor: default;
    }
    .booked-calendar-item{
        background-color: rgba(114,51,128, 1)!important;
        color: white!important;
        cursor: default;
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
    .requested-booking-calendar-item:hover button.cancel,
    .booked-calendar-item:hover button.cancel{
        opacity: 1;
    }
    button.cancel span{
        display: block;
        width: 100%;
        height: 100%;
    }
</style>