<template>
    <div ref="loader" id="loader" :class="{'transparent-loader': transparent}">
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    name: 'loader',
    mounted() {
        // this.getRenderData();
    },
    props: ['text'],
    data: function(){
        return {
            transparent: false,
        }
    },
    methods: {
        showTranparent: function (){
            this.transparent = true;
            setTimeout(() => {
                $(this.$refs['loader']).show();
            }, 10);
        },
        show: function (){
            // alert('show');
            $(this.$refs['loader']).show();
        },
        fadeOut: function (ms){
            // $(this.$refs['loader']).fadeOut(ms);
            setTimeout(() => {
                $(this.$refs['loader']).fadeOut(ms);
                setTimeout(() => {
                    this.transparent = false;
                }, ms);
            }, 20);
        },
    },
};
</script>

<style lang="scss" scoped>
    #loader{
        position: absolute;
        z-index: 999;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        // display: none;
        transition: all 0.3s;
        background-color: #f1f1f1;
        // padding-top: calc()20px;
        // padding-bottom: 20px;
        table{
            width: 100%;
            height: 100%;
        }
        table td{
            vertical-align: middle;
        }
        &.transparent-loader{
            background-color: rgba(241,241,241, .5);
        }
    }
    
    .lds-spinner {
      color: official;
      display: block;
      position: relative;
      width: 80px;
      height: 80px;
      margin: auto;
    }
    .lds-spinner div {
      transform-origin: 40px 40px;
      animation: lds-spinner 1.2s linear infinite;
    }
    .lds-spinner div:after {
      content: " ";
      display: block;
      position: absolute;
      top: 3px;
      left: 37px;
      width: 6px;
      height: 18px;
      border-radius: 20%;
      background: #17a2b8;
    }
    .lds-spinner div:nth-child(1) {
      transform: rotate(0deg);
      animation-delay: -1.1s;
    }
    .lds-spinner div:nth-child(2) {
      transform: rotate(30deg);
      animation-delay: -1s;
    }
    .lds-spinner div:nth-child(3) {
      transform: rotate(60deg);
      animation-delay: -0.9s;
    }
    .lds-spinner div:nth-child(4) {
      transform: rotate(90deg);
      animation-delay: -0.8s;
    }
    .lds-spinner div:nth-child(5) {
      transform: rotate(120deg);
      animation-delay: -0.7s;
    }
    .lds-spinner div:nth-child(6) {
      transform: rotate(150deg);
      animation-delay: -0.6s;
    }
    .lds-spinner div:nth-child(7) {
      transform: rotate(180deg);
      animation-delay: -0.5s;
    }
    .lds-spinner div:nth-child(8) {
      transform: rotate(210deg);
      animation-delay: -0.4s;
    }
    .lds-spinner div:nth-child(9) {
      transform: rotate(240deg);
      animation-delay: -0.3s;
    }
    .lds-spinner div:nth-child(10) {
      transform: rotate(270deg);
      animation-delay: -0.2s;
    }
    .lds-spinner div:nth-child(11) {
      transform: rotate(300deg);
      animation-delay: -0.1s;
    }
    .lds-spinner div:nth-child(12) {
      transform: rotate(330deg);
      animation-delay: 0s;
    }
    @keyframes lds-spinner {
      0% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
</style>