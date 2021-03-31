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

    // getRange(){
    //     return this.range.range;
    // }
    // 
    // getDates(){
    //     return this.range.dates;
    // }
}