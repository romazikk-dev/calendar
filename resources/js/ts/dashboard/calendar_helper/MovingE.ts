declare var movingEvent: any;

export class MovingE{
    movingEvent: any;
    // range: DateRange;
  
    constructor() {
        this.movingEvent = movingEvent;
    }
    
    parse() { 
        // console.log(movingEvent);
        // console.log(JSON.parse(movingEvent));
        // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
    }
    
    getItem(item:any = null){
        if(item === null || typeof this.movingEvent === 'undefined' || this.movingEvent === null)
            return null;
            
        item = item.split('.');
        if(item.length === 1){
            return (typeof this.movingEvent[item[0]] !== 'undefined') ? this.movingEvent[item[0]] : null;
        }else if(item.length === 2){
            return (typeof this.movingEvent[item[0]] !== 'undefined') && (typeof this.movingEvent[item[0]][item[1]] !== 'undefined') ?
                this.movingEvent[item[0]][item[1]] : null;
        }
        
        return null;
    }
}