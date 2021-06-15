// export function helloWorld(): string {
//     return "Hello world!";
// }
import { movingE } from "./movingE";
// import { View as EnumView } from "./enums/View";



class Helper{
    movingE: any;
    // range: DateRange;
  
    constructor() {
        this.movingE = new movingE();
        // alert(1111); 
        // this.view = EnumView.MONTH;
        // this.range = new DateRange(EnumView.MONTH);
    }
    
    parse() { 
        // return JSON.parse(JSON.stringify(value));
        alert(2222); 
    }

    // getRange(){
    //     return this.range.range;
    // }
    // 
    // getDates(){
    //     return this.range.dates;
    // }
}

window.calendarHelper = new Helper();