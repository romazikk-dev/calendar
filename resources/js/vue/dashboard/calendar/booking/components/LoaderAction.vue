<template>
    <div ref="loader" id="loader">
        <div class="lds-dual-ring"></div>
    </div>
</template>

<script>

export default {
    name: 'loader_action',
    mounted() {
        // this.getRenderData();
    },
    // props: ['text'],
    data: function(){
        return {
            // transparent: false,
        }
    },
    methods: {
        // showTransparent: function (){
        //     this.transparent = true;
        //     setTimeout(() => {
        //         $(this.$refs['loader']).show();
        //     }, 10);
        // },
        // show: function (){
        //     // alert('show');
        //     $(this.$refs['loader']).show();
        // },
        // fadeOut: function (ms){
        //     // $(this.$refs['loader']).fadeOut(ms);
        //     setTimeout(() => {
        //         $(this.$refs['loader']).fadeOut(ms);
        //         setTimeout(() => {
        //             this.transparent = false;
        //         }, ms);
        //     }, 20);
        // },
    },
};
</script>

<style lang="scss" scoped>
    #loader{
        display: inline;
        // position: absolute;
        // z-index: 999;
        // top: 0px;
        // left: 0px;
        // width: 100%;
        // height: 100%;
        box-sizing: border-box;
        // display: none;
        // transition: all 0.3s;s
        // background-color: #f1f1f1;
        // // padding-top: calc()20px;
        // // padding-bottom: 20px;
        // .d-table{
        //     width: 100%;
        //     height: 100%;
        // }
        // .d-table-cell{
        //     vertical-align: middle;
        // }
        // &.transparent-loader{
        //     background-color: rgba(241,241,241, .5);
        // }
    }
    
    .lds-dual-ring {
      display: inline-block;
      width: 14px;
      height: 14px;
    }
    .lds-dual-ring:after {
      content: " ";
      display: block;
      width: 12px;
      height: 12px;
      margin: 0px;
      border-radius: 50%;
      border: 2px solid #fff;
      border-color: #fff transparent #fff transparent;
      animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
</style>