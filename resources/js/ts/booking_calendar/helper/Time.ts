declare var moment: any;
declare var timezone: any;

export class Time{
    timezone: any;
    
    constructor() {
        this.timezone = timezone;
    }
    
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
        let minutesStr: string, hoursStr: string, 
            minutes: number, hours: number;
        minutes = mins%60;
        hours = Math.floor(mins/60);
        
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
        
        return hoursStr + ':' + minutesStr;
    }
    
}