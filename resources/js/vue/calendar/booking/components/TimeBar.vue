<template>
    <div class="top-bar-time">        
        <div class="slidecontainer">
            <input ref="slider" @input="change()" @change="change($event)" type="range" :min="sliderMin" :max="sliderMax" v-model="sliderValue" class="slider" id="freeTimeRange">
        </div>
        <div class="bar-time">
            <span class="min">{{start}}</span>
            <span class="max">{{end}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'timeBar',
        mounted() {
            // this.setInitValues();
            // var slider = document.getElementById("freeTimeRange");
            // slider.value = 120;
            
            // this.$refs['slider'].value = 0;
            
            // console.log(this.start);
            // console.log(this.end);
            // console.log(this.durationTime);
            // console.log(this.durationMinutes);
            // console.log(this.dateRange);
            // console.log(this.view);
            // this.getDataForCalendar();
            // console.log(this.free);
            // this.setSliderThumbWidth();
            this.createStyleForSlider();
            // this.setStyleForSlider();
        },
        props: ['start','end','preEnd','startMinutes','endMinutes','preEndMinutes','duration','durationMinutes'],
        data: function(){
            return {
                s: null,
                sliderThumbWidth: 20,
                sliderMin: 0,
                freeMinutes: 0,
                freeMinutesForStart: 0,
                sliderValue: 0,
                sliderDisabled: false,
            };
        },
        computed: {
            sliderMax: function () {
                return (this.endMinutes == null || this.startMinutes == null) ? 0 : this.endMinutes - this.startMinutes;
            },
            sliderWidth: function () {
                return this.$refs['slider'].clientWidth;
            },
        },
        methods: {
            recalculate: function (){
                // console.log('recalculate');
                // this.setSliderThumbWidth();
                
                setTimeout(() => {
                    this.setVars();
                    this.recalculateSlider();
                }, 300);
            },
            recalculateSlider: function (){
                // console.log('recalculateSlider');
                // this.setSliderThumbWidth();
                
                this.setSliderThumbWidth();
                this.setStyleForSlider();
                // console.log(this.start);
                // console.log(this.end);
                // console.log(this.duration);
                // console.log(this.durationMinutes);
                // 
                // console.log(this.startMinutes);
                // console.log(this.endMinutes);
                // console.log(this.preEndMinutes);
            },
            setVars: function (){
                this.sliderValue = 0;
                this.freeMinutes = this.endMinutes - this.startMinutes;
                this.freeMinutesForStart = this.freeMinutes - this.durationMinutes;
            },
            setSliderThumbWidth: function (){
                this.sliderDisabled = false;
                if(this.sliderMax < this.durationMinutes){
                    let sliderWidth = this.$refs['slider'].clientWidth;
                    this.sliderThumbWidth = sliderWidth;
                    this.sliderDisabled = true;
                    return;
                }
                let onePerc = this.sliderMax/100;
                let percentage = Math.round(this.durationMinutes/onePerc);
                let sliderWidth = this.$refs['slider'].clientWidth;
                let widthOnePerc = Math.round(sliderWidth/100) * percentage;
                this.sliderThumbWidth = widthOnePerc;
                
                // console.log(onePerc);
                // console.log(percentage);
                // console.log(this.sliderWidth);
                // console.log(this.durationMinutes);
                // console.log(this.sliderMax);
                // console.log(this.sliderThumbWidth);
            },
            createStyleForSlider: function (){
                this.s = document.createElement("style");
                document.head.appendChild(this.s);
            },
            setStyleForSlider: function (){
                // console.log(33333);
                this.s.textContent = `
                    .slider::-webkit-slider-thumb{
                        width: ` + this.sliderThumbWidth + `px!important;
                    }
                    .slider::-moz-range-thumb{
                        width: ` + this.sliderThumbWidth + `px!important;
                    }
                `;
            },
            change: function (event){
                if(this.sliderDisabled)
                    return;
                // let sliderValue = parseInt(this.$refs['slider'].value);
                // let diff = this.endMinutes - this.startMinutes;
                // console.log(diff);
                // console.log(sliderValue);
                // this.freeMinutes
                // this.
                this.$emit('change', {
                    freeMinutes: this.freeMinutes,
                    freeMinutesForStart: this.freeMinutesForStart,
                    sliderValue: this.sliderValue,
                    sliderWidth: this.sliderWidth,
                    sliderThumbWidth: this.sliderThumbWidth,
                });
            },
            calculatePixelsOffset: function (){
                let slider = this.$refs['slider'];
                let value = slider.value;
                let sliderWidth = parseInt(slider.clientWidth);
                let thumbWidth = $('.slider::-webkit-slider-thumb');
                // $('.slider::-webkit-slider-thumb').hide();
                // 
                // let s = document.createElement("style");
                // document.head.appendChild(s);
                // $('#freeTimeRange').on('input', () => {
                //     console.log(111);
                // });
                // itr.addEventListener("input", () => {
                //   s.textContent = `.slider::-webkit-slider-thumb{background-color: hsl(${itr.value}, 100%, 50%)}`
                // })
                
                this.s.textContent = `
                    .slider::-webkit-slider-thumb{
                        width: ` + this.sliderThumbWidth + `px!important;
                    }
                    .slider::-moz-range-thumb{
                        width: ` + this.sliderThumbWidth + `px!important;
                    }
                `;


                // let sliderThumb = $('#freeTimeRange::-webkit-slider-thumb')
                // console.log(sliderThumb);
                // console.log(sliderWidth);
                // console.log(thumbWidth);
            }
        },
        components: {
            
        },
        watch: {
            sliderDisabled: function (newOne, oldOne) {
                this.$emit((this.sliderDisabled ? 'slider_disabled' : 'slider_enabled'));
        	},
    	}
    }
</script>

<style scoped>
    /* .bar{
        width: 100%;
        height: 30px;
        border-radius: 4px;
        background-color: red;
        background-color: rgba(144,238,144, 0.5);
        margin-bottom: 30px;
        position: relative;
        margin-top: 5px;
    } */
    .top-bar-time{
        position: relative;
    }
    .bar-time{
        /* padding-top: 2px; */
        position: absolute;
        font-size: 14px;
        width: 100%;
    }
    .bar-time>span.min, .bar-time>span.max{
        position: relative;
        top: -6px;
    }
    .bar-time>span.min{
        float: left;
        padding-left: 4px;
        /* left: 0px;
        top: -1px;
        padding-left: 4px;
        color: white; */
        /* float: left;
        padding-bottom: 18px; */
    }
    .bar-time>span.max{
        float: right;
        padding-right: 4px;
        /* right: 0px; */
        /* float: right;
        padding-top: 18px; */
    }
    /* .bar-over{
        position: absolute;
        top: 0px;
        left: 0px;
        width: 60%;
        background-color: green;
        height: 18px;
        border-radius: 4px;
        margin-bottom: 30px;
        text-align: center;
        color: white;
        line-height: 18px;
        font-size: 14px;
    }
    .bar-over span.max{
        position: absolute;
        top: 0px;
        color: black;
        padding-right: 4px;
        color: white;
    }
    .bar-over .bar-time{
        padding-top: 1px;
        padding-right: 4px;
    } */
    
    
    
</style>

<style scoped>
    .slidecontainer {
        width: 100%;
        /* border-radius: 3px;
        overflow: hidden; */
    }
    /* The slider itself */
    .slider {
        border-radius: 3px;
        overflow: hidden;
        -webkit-appearance: none;  /* Override default CSS styles */
        appearance: none;
        width: 100%; /* Full-width */
        height: 30px; /* Specified height */
        background-color: rgba(144,238,144, 0.5)!important; /* Grey background */
        outline: none; /* Remove outline */
        opacity: 0.7; /* Set transparency (for mouse-over effects on hover) */
        -webkit-transition: .2s; /* 0.2 seconds transition on hover */
        transition: opacity .2s;
    }
    /* Mouse-over effects */
    .slider:hover {
        opacity: 1; /* Fully shown on mouse-over */
    }
    /* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */
    .slider::-webkit-slider-thumb {
        border-radius: 3px;
        -webkit-appearance: none; /* Override default look */
        appearance: none;
        width: 125px; /* Set a specific slider handle width */
        height: 30px; /* Slider handle height */
        cursor: grab; /* Cursor on hover */
        background: #88b7d5;
    	border: 4px solid #c2e1f5;
    }
    .slider::-moz-range-thumb {
        border-radius: 3px;
        width: 125px; /* Set a specific slider handle width */
        height: 30px; /* Slider handle height */
        cursor: grab; /* Cursor on hover */
        background: #88b7d5;
    	border: 4px solid #c2e1f5;
    }
    
    .slider::-webkit-slider-thumb {
    	position: relative;
    	background: #88b7d5;
    	border: 4px solid #c2e1f5;
    }
    /* .slider::-webkit-slider-thumb:after, .slider::-webkit-slider-thumb:before {
    	bottom: 100%;
    	left: 50%;
    	border: solid transparent;
    	content: "";
    	height: 0;
    	width: 0;
    	position: absolute;
    	pointer-events: none;
    }

    .slider::-webkit-slider-thumb:after {
    	border-color: rgba(136, 183, 213, 0);
    	border-bottom-color: #88b7d5;
    	border-width: 30px;
    	margin-left: -30px;
    }
    .slider::-webkit-slider-thumb:before {
    	border-color: rgba(194, 225, 245, 0);
    	border-bottom-color: #c2e1f5;
    	border-width: 36px;
    	margin-left: -36px;
    } */
</style>