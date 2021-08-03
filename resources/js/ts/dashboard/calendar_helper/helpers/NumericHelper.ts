// declare var movingEvent: any;

export class NumericHelper{
    // movingEvent: any;
    // range: DateRange;
  
    constructor() {
        // this.movingEvent = movingEvent;
    }
    
    parse() { 
        // console.log(movingEvent);
        // console.log(JSON.parse(movingEvent));
        // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
    }
    
    getRandomInt(max: number = 1000) {
        return Math.floor(Math.random() * max);
    }
}