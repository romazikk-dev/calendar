// export function helloWorld(): string {
//     return "Hello world!";
// }
import { MovingE } from "./MovingE";
import { NewE } from "./NewE";
import { Person } from "./Person";
import { Time } from "./Time";
// import { View as EnumView } from "./enums/View";

declare var window: any;


class Helper{
    movingE?: MovingE; 
    newE?: NewE;
    person?: Person;
    time?: Time;
    // range: DateRange;
  
    constructor() {
        this.movingE = new MovingE();
        this.newE = new NewE();
        this.person = new Person();
        this.time = new Time();
        // alert(1111); 
        // this.view = EnumView.MONTH;
        // this.range = new DateRange(EnumView.MONTH);
    }
    
    parse() { 
        // return JSON.parse(JSON.stringify(value));
        alert(2222);
    }
}

window.calendarHelper = new Helper();