// export function helloWorld(): string {
//     return "Hello world!";
// }
import { DateRange } from "./DateRange";
import { View as EnumView } from "./enums/View";

export class Helper{
    view: String;
    range: DateRange;
  
    constructor() {
        this.view = EnumView.MONTH;
        this.range = new DateRange(EnumView.MONTH);
    }
    
    parse(value) {
        return JSON.parse(JSON.stringify(value));
    }

    // getRange(){
    //     return this.range.range;
    // }
    // 
    // getDates(){
    //     return this.range.dates;
    // }
}