<template>
    <div>
        <div v-if="item.count_total" class="counters">
            <ul>
                <li>
                    <span class="badge badge-pill tooltip-active badge-counter-total"
                         data-placement="auto"
                         title="<div class='small'>Total per day</div>">
                         {{item.count_total}}
                     </span>
                </li>
                <li v-if="item.count_approved">
                    <span class="badge badge-pill tooltip-active badge-counter-approved"
                         data-placement="auto"
                         title="<div class='small'>Approved per day</div>">
                         {{item.count_approved}}
                     </span>
                </li>
                <li v-if="item.count_not_approved">
                    <span class="badge badge-pill tooltip-active badge-counter-not-approved"
                         data-placement="auto"
                         title="<div class='small'>Requested per day, but not approved yet</div>">
                         {{item.count_not_approved}}
                     </span>
                </li>
            </ul>
        </div>
        <div v-if="items" class="events">
            <ul>
                <li v-if="item.items" v-for="itm in items">
                    <template v-if="itm.type == 'free'">
                        
                        <div class='free-slot'>
                            <b>Free time:</b><br>
                            <b>{{itm.from}} - {{itm.to}}</b>
                            <div class="not-approved-bookings" v-if="itm.not_approved_bookings">
                                <div class="not-approved-bookings-itm" v-for="itmm in itm.not_approved_bookings">
                                    <b>In approving:</b><br>
                                    <b>{{itmm.booking.template_without_user_scope.title}}<br>
                                    {{itmm.from}} - {{itmm.to}}</b>
                                    <!-- <button @click.prevent="$emit('cancel', itm)"
                                        type="button"
                                        class="btn btn-link btn-sm btn-block">Cancel</button > -->
                                    <button @click.prevent="$emit('cancel', itmm.booking)"
                                        type="button"
                                        class="btn btn-link btn-sm btn-block cancel"><span>×</span></button>
                                </div>
                            </div>
                            <!-- <button @click.prevent="$emit('open-modal', itm)"
                                type="button"
                                class="btn btn-link btn-sm btn-block book">Book</button > -->
                        </div>
                        
                        <!-- <div class='event' :class="{
                            'approved': itm.approved
                        }">
                            <div class="top-part">
                                <b>{{itm.approved ? 'Booked' : 'Request'}}</b>
                                <div class="actions">
                                    <ul>
                                        <li>
                                            <a href="#" @click.prevent="$emit('move', itm)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrows-move" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10zM.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" @click.prevent="$emit('remove', itm)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <b>{{itm.template_without_user_scope.title}}<br>
                            {{itm.time_from}} - {{itm.time_to}}</b>
                        </div> -->
                        
                    </template>
                    <template v-else>
                        <div class='event' :class="{
                            'approved': itm.approved
                        }">
                            <div class="top-part">
                                <b>{{itm.approved ? 'Booked' : 'Request'}}</b>
                                <div class="actions">
                                    <ul>
                                        <li>
                                            <a href="#" @click.prevent="$emit('move', itm)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrows-move" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10zM.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" @click.prevent="$emit('remove', itm)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <b>{{itm.template_without_user_scope.title}}<br>
                            {{itm.time_from}} - {{itm.time_to}}</b>
                            <!-- <button @click.prevent="$emit('cancel', itm.booking)"
                                type="button"
                                class="btn btn-link btn-sm btn-block cancel"><span>×</span></button> -->
                        </div>
                    </template>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'monthCell',
        mounted() {
            // console.log(this.dateRange);
            // console.log(JSON.parse(JSON.stringify(this.item)));
        },
        props: ['item'],
        data: function(){
            return {
                // range: helper.range.range,
            };
        },
        computed: {
            items: function () {
                if(this.item === null || typeof this.item.items === 'undefined' ||
                this.item.items === null)
                    return null;
                return this.item.items
            },
        },
        methods: {
            
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
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