<template>
    <div>
        
        <div class="contt"
            @click.prevent="onClick">
                <div class="checker"
                    :class="{
                        'switched-on': checker === true,
                        /* 'bg-primary': switcher === true,
                        'text-white': switcher === true, */
                    }">
                        <svg v-if="checker"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            fill="currentColor"
                            class="bi bi-check"
                            viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                </div>
                {{label}}
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'checkbox',
        mounted() {
            this.setChecker();
        },
        /*
        *   e = {event, nextEvent}
        */
        props: ['label','checked'],
        data: function(){
            return {
                // duration: 100,
                checker: false,
            };
        },
        computed: {
            // isDurationChanged: function () {
            //     return this.isE && this.duration != this.e.event.right_duration;
            // },
        },
        methods: {
            setChecker: function (){
                if(typeof this.checked !== 'undefined' && this.checked !== null){
                    this.checker = this.checked;
                }else{
                    this.checker = false;
                }
            },
            onClick: function (){
                this.checker = !this.checker;
                this.$emit('change', this.checker);
            },
        },
        components: {
            // TimeBarFill,
        },
        watch: {
            checked: function(val) {
                // console.log(JSON.parse(JSON.stringify(val)));
                this.setChecker();
        	},
    	}
    }
</script>

<style lang="scss" scoped>
    .contt{
        --checker-size: 24px;
        --checker-background-color: rgba(0,0,0, .1);
        --checker-on-background-color: rgba(0,123,255, .8);
        cursor: pointer;
        .checker{
            width: var(--checker-size, 20px);
            height: var(--checker-size, 20px);
            line-height: calc(var(--checker-size, 20px) - 10%);
            text-align: center;
            border-radius: 3px;
            // cursor: pointer;
            float: left;
            margin-right: 10px;
            transition: background .3s ease;
            background-color: var(--checker-background-color);
            &.switched-on{
                background-color: var(--checker-on-background-color);
                color: white;
            }
        }
        &:hover{
            // background-color: #e1e1e1;
            --checker-background-color: rgba(0,0,0, .2);
            --checker-on-background-color: rgba(0,123,255, 1);
        }
    }
</style>