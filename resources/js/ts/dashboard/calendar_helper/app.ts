// export function helloWorld(): string {
//     return "Hello world!";
// }
import { MovingE } from "./MovingE";
import { NewE } from "./NewE";
import { Person } from "./Person";
import { Time } from "./Time";
import { View } from "./View";
import { Filter } from "./Filter";
// import { View as EnumView } from "./enums/View";

declare var window: any;


class Helper{
    movingE?: MovingE; 
    newE?: NewE;
    person?: Person;
    time?: Time;
    view?: View;
    filter?: Filter;
  
    constructor() {
        this.movingE = new MovingE();
        this.newE = new NewE();
        this.person = new Person();
        this.time = new Time();
        this.view = new View();
        this.filter = new Filter();
    }
}

window.calendarHelper = new Helper();