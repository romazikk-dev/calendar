export class Time{
  
    // constructor() {
    //     // this.movingEvent = movingEvent;
    // } 
    
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