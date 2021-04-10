
import { View as EnumView } from "./enums/View";

export class DateRange{
    view: String;
    currentDate: Date;
    range: any;
    offset: number;
    dates: any;
    currenyViewIdx: number;
    // EnumView: EnumView = EnumView;
    
    constructor(view = EnumView.MONTH) {
        this.view = view;
        this.offset = 0;
        this.currentDate = new Date();
        this.setRange();
    }
    
    setRange(){
        if(this.view == EnumView.MONTH){
            this.currenyViewIdx = parseInt(this.currentDate.getMonth()) + 1;
            // console.log(this.currenyViewIdx);
            
            
            let firstMonthDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
            let lastMonthDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
            let firstMonthDayWeekday = firstMonthDay.getDay();
            let totalDaysInMonth = lastMonthDay.getDate();
            
            // console.log(firstMonthDay);
            
            
            let startOffset;
            if(firstMonthDayWeekday == 1){
                startOffset = 7;
            }else if(firstMonthDayWeekday == 2){
                startOffset = 8;
            }else if(firstMonthDayWeekday == 3){
                startOffset = 9;
            }else if(firstMonthDayWeekday == 4){
                startOffset = 3;
            }else if(firstMonthDayWeekday == 5){
                startOffset = 4;
            }else if(firstMonthDayWeekday == 6){
                startOffset = 5;
            }else if(firstMonthDayWeekday == 0){
                startOffset = 6;
            }
            
            let firstDate = new Date(firstMonthDay!);
            // console.log(firstDate);
            
            firstDate.setDate(firstDate.getDate() - startOffset);
            // console.log(firstDate);
            
            let lastDate = new Date(firstDate);
            lastDate.setDate(lastDate.getDate() + 41);
            
            // let lastDate = new Date(firstMonthDay);
            // console.log(firstMonthDay);
            // console.log(firstMonthDayWeekday);
            // console.log(totalDaysInMonth);
            // console.log(firstDate);
            // console.log(lastDate);
            this.range = {
                first_date: firstDate,
                last_date: lastDate,
            }
            
            let date = new Date(firstDate);
            let endDate = new Date(lastDate);
            let dates = [];
            do{
                dates.push({
                    year: date.getFullYear(),
                    month: date.getMonth(),
                    day: date.getDate(),
                    weekday: date.getDay(),
                });
                date.setDate(date.getDate() + 1);
            }while(date <= endDate);
            
            this.dates = dates;
        }
    }
}