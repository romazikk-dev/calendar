<template>
    <div>
        
        <div class="form-group">
            <input v-model="search"
                @blur.prevent.stop
                type="text"
                class="form-control"
                placeholder="Search ...">
            <div @click="resetClick()" v-if="search" class="reset-input">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                </svg> -->
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log(assignWorkers);
            // console.log(JSON.parse(JSON.stringify(assignWorkers)));
        },
        // props: ['postTitle'],
        data: function(){
            return {
                search: null,
            };
        },
        computed: {
            // showWarningAlert: function () {
            //     // console.log(JSON.parse(JSON.stringify(this.assignWorkers)));
            // },
        },
        methods: {
            resetClick: function(e){
                this.search = null;
                this.$emit('reset');
                // console.log(e);
            },
        },
        components: {
            
        },
        watch: {
            search: function(val){
                this.$emit('search', val);
            },
        },
    }
</script>

<style lang="scss" scoped>
    .form-group{
        position: relative;
        padding: 0px 5px;
        input{
            padding-right: 40px;
        }
        .reset-input{
            position: absolute;
            top: 0px;
            right: 5px;
            // background-color: red;
            height: 100%;
            width: 30px;
            text-align: center;
            line-height: 36px;
            cursor: pointer;
        }
    }
</style>