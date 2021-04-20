<template>
    <div v-if="show">
        
        <div v-if="clientInfo" class="user-block">
            <span>Hello, </span>
            <button type="button"
                @click="enter"
                class="btn-filter btn btn-sm btn-primary">
                    {{fullName}}
            </button>
        </div>
        
        <div v-else class="guest-block">
            Hello, <b>{{fullName}}</b>
            <button type="button"
                @click="enter"
                class="btn-filter btn btn-sm btn-primary">
                    Enter
            </button>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="enterModal">
            <div class="modal-dialog">
                <!-- ModalClientInfoContent -->
                <modal-client-info-content
                    v-if="clientInfo"
                    :client-info="clientInfo"
                    :all-bookings="allBookings"></modal-client-info-content>
                <!-- ModalAuthContent -->
                <modal-auth-content v-else :user-id="userId"></modal-auth-content>
            </div>
        </div>
        
    </div>
</template>

<script>
    import ModalAuthContent from "./ModalAuthContent.vue";
    import ModalClientInfoContent from "./ModalClientInfoContent.vue";
    export default {
        name: 'clientInfo',
        mounted() {
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.getDataForCalendar();
            setTimeout(() => {
                this.show = true;
            }, 100);
        },
        props: ['clientInfo','userId','allBookings'],
        data: function(){
            return {
                show: false,
                // dates: null,
                // view: helper.range.view,
                // curreny_view_idx: helper.range.currenyViewIdx,
                // currentDate: {
                //     year: moment(helper.range.currentDate).format('YYYY'),
                //     month: moment(helper.range.currentDate).format('MM'),
                //     day: moment(helper.range.currentDate).format('DD'),
                // },
                // views: ['Month','Week','Day'],
                // owner: owner,
                // halls: halls,
                // workers: workers,
                // templates: templates
                // halls: 
            };
        },
        computed: {
            fullName: function () {
                if(this.clientInfo == null)
                    return 'guest';
                return this.clientInfo.first_name + ' ' + this.clientInfo.last_name;
            },
        },
        methods: {
            enter: function(){
                // console.log(view);
                $('#enterModal').modal('show');
                // // console.log(view);
                // this.choosedItmView = view;
                // this.putFiltersInCookie();
                // this.composeLink();
                // this.emitChange();
            },
            info: function(){
                console.log(111);
                // $('#enterModal').modal('show');
                // // console.log(view);
                // this.choosedItmView = view;
                // this.putFiltersInCookie();
                // this.composeLink();
                // this.emitChange();
            },
        },
        components: {
            ModalAuthContent,
            ModalClientInfoContent
        },
    }
</script>

<style lang="scss" scoped>
    .user-block{
        span{
            position: relative;
            top: 2px;
            font-size: .875rem;
        }
    }
</style>