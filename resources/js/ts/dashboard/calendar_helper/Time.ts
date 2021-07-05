declare var moment: any;

export class Time{
  
    // constructor() {
    //     // this.movingEvent = movingEvent;
    // } 
    
    // jsWeekdayToIsoWeekday(jsWeekday: number) {
    //     if(jsWeekday == 0)
    //         return 7;
    //     return Number(jsWeekday);
    // }
    
    getEventDate(event: any) {
        if(event === null || typeof event.time === 'undefined' || event.time === null)
            return null;
        let momentDate = moment(event.date, 'YYYY-MM-DD');
        return momentDate.format('D MMMM YYYY, ddd');
        // return this.e.year + '-' + this.e.month + '-' + this.e.day;
    }
    
    parseStringHourMinutesToMinutes(hourMinutesStr: string) {
        let arr, elHours, elMinutes;
        
        arr = hourMinutesStr.split(':');
        elHours = Number(arr[0]);
        elMinutes = Number(arr[1]);
        
        return (elHours * 60) + elMinutes;
    }
    
    composeHourMinuteTimeFromMinutes(mins: number) {
        // alert(mins);
        // console.log(mins);
        
        let minutesStr: string, hoursStr: string, 
            minutes: number, hours: number;
        minutes = mins%60;
        hours = Math.floor(mins/60);
        
        // alert(hours);
        
        
        
        if(minutes <= 0){
            minutesStr = '00';
        }else if(minutes > 0 && minutes < 10){
            minutesStr = '0' + String(minutes);
        }else{
            minutesStr = String(minutes);
        }
        
        if(hours <= 0){
            hoursStr = '00'
        }else if(hours > 0 && hours < 10){
            hoursStr = '0' + String(hours);
        }else{
            hoursStr = String(hours);
        }
        
        // console.log(mins);
        // console.log(hours);
        // console.log(minutes);
        
        // alert(minutesStr);
        
        return hoursStr + ':' + minutesStr;
    }
    
}