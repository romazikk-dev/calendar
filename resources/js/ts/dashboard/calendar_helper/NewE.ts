declare var newEvent: any;

export class NewE{
    newEvent: any;
    // range: DateRange;
  
    constructor() {
        this.newEvent = newEvent;
    }
    
    parse() { 
        // console.log(movingEvent);
        // console.log(JSON.parse(movingEvent));
        // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
    }
    
    getItem(item:any = null){
        if(item === null || typeof this.newEvent === 'undefined' || this.newEvent === null)
            return null;
            
        item = item.split('.');
        if(item.length === 1){
            return (typeof this.newEvent[item[0]] !== 'undefined') ? this.newEvent[item[0]] : null;
        }else if(item.length === 2){
            return (typeof this.newEvent[item[0]] !== 'undefined') && (typeof this.newEvent[item[0]][item[1]] !== 'undefined') ?
                this.newEvent[item[0]][item[1]] : null;
        }
        
        return null;
    }
}