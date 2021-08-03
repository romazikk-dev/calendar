<!--
    Link to slider source
    https://codepen.io/vsync/pen/mdEJMLv
-->

<template>
    <div>
        <div class="range-slider grad"
            ref="range_slider"
            :style="{
                '--min': minDuration,
                '--max': maxDuration,
                '--step': 1,
                '--value': input,
                '--text-value': input,
                '--prefix': '$',
                '--output-transparency': outputTransparency ? outputTransparency : 1,
            }">
            <input type="range"
                ref="input_range"
                :min="minDuration"
                :max="maxDuration"
                step="1"
                v-model="input">
            <output v-if="showOutput">{{inputStrHoursAndMinutes}}</output>
            <div class='range-slider__progress'>
                <div class='stopper' :style="{
                    'width': stopperWidth,
                }"></div>
            </div>
            <span class="min-tick float-left">{{minDurationStrHoursAndMinutes}}</span>
            <span class="max-tick float-right">{{maxDurationStrHoursAndMinutes}}</span>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'timeBar',
        mounted() {
            // this.setInputInitValue();
            // console.log('duration');
            // console.log(JSON.parse(JSON.stringify(this.duration)));
            // console.log(this.durationInMinutes);
            // console.log(this.durationInSeconds);
            
            // console.log(this.minDuration);
            // console.log(this.maxDuration);
            this.setInputInitValue();
            
            // console.log('duration iii');
            // console.log(this.duration);
            // console.log(this.input);
            // console.log(this.stopAt);
        },
        // updated() {
        //     this.input = this.duration;
        // },
        // props: ['start','end','preEnd','startMinutes','endMinutes','preEndMinutes','duration','durationMinutes'],
        props: ['durationInMinutes','durationInSeconds','showOutput','stopper','outputTransparency','realDuration'],
        data: function(){
            return {
                input: 20,
                // stopper: 120,
            };
        },
        computed: {
            stopperWidth: function () {
                if(typeof this.stopper === 'undefined' || this.stopper === null)
                    return 0;
                let perc = 100 - (
                    (this.stopper - this.minDuration) / (this.maxDuration - this.minDuration) * 100
                );
                perc = Math.abs(Math.floor(perc));
                return perc > 100 ? '0%' : perc + "%";
            },
            inputStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.input);
            },
            minDurationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.minDuration);
            },
            maxDurationStrHoursAndMinutes: function () {
                return calendarHelper.time.composeHourMinuteTimeFromMinutes(this.maxDuration);
            },
            minDuration: function () {
                let minDuration = this.$store.getters['template/minDuration'];
                return minDuration === null ? 10 : minDuration;
            },
            maxDuration: function () {
                let maxDuration = this.$store.getters['template/maxDuration'];
                return maxDuration === null ? 180 : maxDuration;
            },
            // Always should return val in minutes
            duration: function () {
                if(typeof this.durationInMinutes !== 'undefined' && this.durationInMinutes !== null)
                    return this.durationInMinutes;
                
                if(typeof this.durationInSeconds !== 'undefined' && this.durationInSeconds !== null)
                    return Math.floor(this.durationInSeconds/60);
                
                return null;
            },
        },
        methods: {
            reset: function (){
                this.setInputInitValue();
                // alert(this.duration);
                // alert(this.durationInMinutes);
            },
            setInputInitValue: function (){
                if(this.duration === null){
                    this.input = this.minDuration;
                }else{
                    if(this.duration < this.minDuration){
                        this.input = this.minDuration;
                    }else if(this.duration > this.maxDuration){
                        this.input = this.maxDuration;
                    }else{
                        this.input = this.duration;
                    }
                }
            },
        },
        components: {
            
        },
        watch: {
            // stopper: function (val){
            //     if(val < this.)
            //     // console.log(JSON.parse(JSON.stringify('Stopper changed')));
            //     // console.log(JSON.parse(JSON.stringify(val)));
            //     // this.setInputInitValue();
            // },
            // durationInMinutes: function () {
            //     this.setInputInitValue();
            // },
            // durationInSeconds: function () {
            //     this.setInputInitValue();
            // },
            duration: function (val) {
                // alert(val);
                this.setInputInitValue();
            },
            input: function (val) {
                if(parseInt(val) > parseInt(this.stopper)){
                    this.input = this.stopper;
                    return;
                }
                this.$refs.range_slider.style.setProperty('--value', val);
                this.$refs.range_slider.style.setProperty('--text-value', JSON.stringify((+val).toLocaleString()));
                // console.log(this.inputStrHoursAndMinutes);
                this.$emit('change', val);
            },
    	}
    }
</script>

<style lang="scss" scoped>

// $min: var(--min);

////////////////////////////////////////////////
// Gradient slider demo
.range-slider.grad {
    width: 100%;
  --progress-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2) inset;
  --progress-flll-shadow: var(--progress-shadow);
  --fill-color: linear-gradient(to right, LightCyan, var(--primary-color));
  // --thumb-shadow: 0 0 4px rgba(0, 0, 0, 0.3),
  //   -3px 9px 9px rgba(255, 255, 255, 0.33) inset,
  //   -1px 3px 2px rgba(255, 255, 255, 0.33) inset,
  //   0 0 0 99px var(--primary-color) inset;

  input {
    &:hover {
      --thumb-transform: scale(1.2);
    }

    &:active {
      // --thumb-shadow: inherit;
      --thumb-transform: scale(1);
    }
  }
}

////////////////////////////////////////////////
// The main styles

.range-slider {
    $min: var(--min);
  // --primary-color: #0366d6;
  --primary-color: #328bf0;
  // --primary-color-transparency: 0.5;
  // --primary-color: rgba(50, 139, 240, 0.5);
  // --primary-color: rgba(50, 139, 240, var(--primary-color-transparency, 1));

  --value-offset-y: var(--ticks-gap);
  --value-active-color: white;
  --value-background: transparent;
  --value-background-hover: var(--primary-color);
  --value-background-opacity: rgba(50,139,240, var(--output-transparency, 1));
  --value-font: 700 12px/1 Arial;

  --fill-color: var(--primary-color);
  --progress-background: #eee;
  --progress-radius: 20px;
  --track-height: calc(var(--thumb-size) / 2);

  --min-max-font: 12px Arial;
  --min-max-opacity: 0.5;
  --min-max-x-offset: 10%; // 50% to center

  --thumb-size: 16px;
  --thumb-color: white;
  --thumb-shadow: 0 0 3px rgba(0, 0, 0, 0.4), 0 0 1px rgba(0, 0, 0, 0.5) inset,
    0 0 0 99px var(--thumb-color) inset;

  --thumb-shadow-active: 0 0 0 calc(var(--thumb-size) / 4) inset
      var(--thumb-color),
    0 0 0 99px var(--primary-color) inset, 0 0 3px rgba(0, 0, 0, 0.4);

  --thumb-shadow-hover: var(--thumb-shadow);

  --ticks-thickness: 1px;
  --ticks-height: 5px;
  --ticks-gap: var(
    --ticks-height,
    0
  ); // vertical space between the ticks and the progress bar
  --ticks-color: silver;

  // ⚠️ BELOW VARIABLES SHOULD NOT BE CHANGED
  --step: 1;
  --ticks-count: Calc(var(--max) - var(--min)) / var(--step);
  --maxTicksAllowed: 30;
  --too-many-ticks: Min(1, Max(var(--ticks-count) - var(--maxTicksAllowed), 0));
  --x-step: Max(
    var(--step),
    var(--too-many-ticks) * (var(--max) - var(--min))
  ); // manipulate the number of steps if too many ticks exist, so there would only be 2
  --tickInterval: 100/ ((var(--max) - var(--min)) / var(--step)) * var(--tickEvery, 1);
  --tickIntervalPerc: calc(
    (100% - var(--thumb-size)) / ((var(--max) - var(--min)) / var(--x-step)) *
      var(--tickEvery, 1)
  );

  --value-a: Clamp(
    var(--min),
    var(--value, 0),
    var(--max)
  ); // default value ("--value" is used in single-range markup)
  --value-b: var(--value, 0); // default value
  --text-value-a: var(--text-value, "");

  --completed-a: calc(
    (var(--value-a) - var(--min)) / (var(--max) - var(--min)) * 100
  );
  --completed-b: calc(
    (var(--value-b) - var(--min)) / (var(--max) - var(--min)) * 100
  );
  --ca: Min(var(--completed-a), var(--completed-b));
  --cb: Max(var(--completed-a), var(--completed-b));

  // breakdown of the below super-complex brain-breaking CSS math:
  // "clamp" is used to ensure either "-1" or "1"
  // "calc" is used to inflat the outcome into a huge number, to get rid of any value between -1 & 1
  // if absolute diff of both completed % is above "5" (%)
  // ".001" bumps the value just a bit, to avoid a scenario where calc resulted in "0" (then clamp will also be "0")
  --thumbs-too-close: Clamp(
    -1,
    1000 * (Min(1, Max(var(--cb) - var(--ca) - 5, -1)) + 0.001),
    1
  );
  --thumb-close-to-min: Min(1, Max(var(--ca) - 2, 0)); // 2% threshold
  --thumb-close-to-max: Min(1, Max(98 - var(--cb), 0)); // 2% threshold

  @mixin thumb {
    appearance: none;
    height: var(--thumb-size);
    width: var(--thumb-size);
    transform: var(--thumb-transform);
    border-radius: var(--thumb-radius, 50%);
    background: var(--thumb-color);
    box-shadow: var(--thumb-shadow);
    border: none;
    pointer-events: auto;
    transition: 0.1s;
  }

  display: inline-block;
  height: Max(var(--track-height), var(--thumb-size));
  // margin: calc((var(--thumb-size) - var(--track-height)) * -.25) var(--thumb-size) 0;
  background: linear-gradient(
      to right,
      var(--ticks-color) var(--ticks-thickness),
      transparent 1px
    )
    repeat-x;
  background-size: var(--tickIntervalPerc) var(--ticks-height);
  background-position-x: calc(
    var(--thumb-size) / 2 - var(--ticks-thickness) / 2
  );
  background-position-y: var(--flip-y, bottom);

  padding-bottom: var(--flip-y, var(--ticks-gap));
  padding-top: calc(var(--flip-y) * var(--ticks-gap));

  position: relative;
  z-index: 1;

  &[data-ticks-position="top"] {
    --flip-y: 1;
  }

  // mix/max texts
  .min-tick,
  .max-tick {
    --offset: calc(var(--thumb-size) / 2);
    // content: counter(x);
    // display: var(--show-min-max, block);
    display: block;
    font: var(--min-max-font);
    position: absolute;
    bottom: var(--flip-y, -2.5ch);
    top: calc(-2.5ch * var(--flip-y));
    // opacity: Clamp(0, var(--at-edge), var(--min-max-opacity));
    opacity: var(--min-max-opacity);
    transform: translateX(
        calc(var(--min-max-x-offset) * var(--before, -1) * -1)
      )
      scale(var(--at-edge));
    pointer-events: none;
  }

  .min-tick {
    --before: 1;
    // content: '#{$min}';
    
    // content: var(--min) + " /";
    content: calc(var(--min) + " /");
    // --at-edge: var(--thumb-close-to-min);
    // counter-reset: x var(--min);
    left: var(--offset);
  }

  .max-tick {
    // --at-edge: var(--thumb-close-to-max);
    content: var(--max);
    // content: 'dasd';
    // counter-reset: x var(--max);
    right: var(--offset);
  }

  &__values {
    position: relative;
    top: 50%;
    line-height: 0;
    text-align: justify;
    width: 100%;
    pointer-events: none;
    margin: 0 auto;
    z-index: 5;
  
    // trick so "justify" will work
    &::after {
      content: "";
      width: 100%;
      display: inline-block;
      height: 0;
      background: red;
    }
  }

  &__progress {
    --start-end: calc(var(--thumb-size) / 2);
    --clip-end: calc(100% - (var(--cb)) * 1%);
    --clip-start: calc(var(--ca) * 1%);
    // --clip: inset(-20px var(--clip-end) -20px var(--clip-start));
    --clip: inset(-2px var(--clip-end) -2px var(--clip-start));
    position: absolute;
    left: var(--start-end);
    right: var(--start-end);
    top: calc(
      var(--ticks-gap) * var(--flip-y, 0) + var(--thumb-size) / 2 -
        var(--track-height) / 2
    );
    //  transform: var(--flip-y, translateY(-50%) translateZ(0));
    height: calc(var(--track-height));
    background: var(--progress-background, #eee);
    pointer-events: none;
    z-index: -1;
    border-radius: var(--progress-radius);
    
    .stopper{
        position: absolute;
        top: 0px;
        right: 0px;
        // width: 140px!important;
        // width: 35%;
        height: 100%;
        background-color: red;
        z-index: 9999;
        border-radius: 0px 20px 20px 0px;
    }

    // fill area
    &::before {
      content: "";
      position: absolute;
      // left: Clamp(0%, calc(var(--ca) * 1%), 100%); // confine to 0 or above
      // width: Min(100%, calc((var(--cb) - var(--ca)) * 1%)); // confine to maximum 100%
      left: 0;
      right: 0;
      clip-path: var(--clip);
      top: 0;
      bottom: 0;
      background: var(--fill-color, black);
      box-shadow: var(--progress-flll-shadow);
      z-index: 1;
      border-radius: inherit;
    }

    // shadow-effect
    &::after {
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      box-shadow: var(--progress-shadow);
      pointer-events: none;
      border-radius: inherit;
    }
  }

  & > input {
    -webkit-appearance: none;
    width: 100%!important;
    height: var(--thumb-size);
    margin: 0;
    position: absolute;
    left: 0;
    // top: calc(
    //   50% - Max(var(--track-height), var(--thumb-size)) / 2 +
    //     calc(var(--ticks-gap) / 2 * var(--flip-y, -1))
    // );
    top: 0;
    cursor: -webkit-grab;
    cursor: grab;
    outline: none;
    background: none!important;

    &:not(:only-of-type) {
      pointer-events: none;
    }

    &::-webkit-slider-thumb {
      @include thumb;
    }
    &::-moz-range-thumb {
      @include thumb;
    }
    &::-ms-thumb {
      @include thumb;
    }

    &:hover {
      --thumb-shadow: var(--thumb-shadow-hover);
      & + output {
        // --value-background: var(--value-background-hover);
        --value-background: var(--value-background-opacity);
        --y-offset: -5px;
        color: var(--value-active-color);
        box-shadow: 0 0 0 3px var(--value-background);
      }
    }

    &:active {
      --thumb-shadow: var(--thumb-shadow-active);
      cursor: grabbing;
      z-index: 2; // when sliding left thumb over the right or vice-versa, make sure the moved thumb is on top
      + output {
        transition: 0s;
      }
    }

    &:nth-of-type(1) {
      --is-left-most: Clamp(0, (var(--value-a) - var(--value-b)) * 99999, 1);
      & + output {
        &:not(:only-of-type) {
          --flip: calc(var(--thumbs-too-close) * -1);
        }

        --value: var(--value-a);
        --x-offset: calc(var(--completed-a) * -1%);
        &::after {
          content: var(--prefix, "") var(--text-value-a) var(--suffix, "");
        }
      }
    }

    &:nth-of-type(2) {
      --is-left-most: Clamp(0, (var(--value-b) - var(--value-a)) * 99999, 1);
      & + output {
        --value: var(--value-b);
      }
    }

    // non-multiple range should not clip start of progress bar
    &:only-of-type {
      ~ .range-slider__progress {
        --clip-start: 0;
      }
    }
    
    & + output {
      --flip: -1;
      --x-offset: calc(var(--completed-b) * -1%);
      --pos: calc(
        ((var(--value) - var(--min)) / (var(--max) - var(--min))) * 100%
      );

      pointer-events: none;
      position: absolute;
      z-index: 5;
      background: var(--value-background);
      border-radius: 10px;
      padding: 2px 4px;
      left: var(--pos);
      transform: translate(
        var(--x-offset),
        calc(
          150% * var(--flip) - (var(--y-offset, 0px) + var(--value-offset-y)) *
            var(--flip)
        )
      );
      transition: all 0.12s ease-out, left 0s;

      &::after {
        content: var(--prefix, "") var(--text-value-b) var(--suffix, "");
        font: var(--value-font);
      }
    }
  }
}


</style>