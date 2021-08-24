declare var freeGetDataParams: any;

export class FreeGetDataParams{
    freeGetDataParams: any;
    // range: DateRange;
  
    constructor() {
        this.freeGetDataParams = freeGetDataParams;
    }
    
    parse() { 
        // console.log(movingEvent);
        // console.log(JSON.parse(movingEvent));
        // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
    }
    
    getItem(item:any = null){
        if(item === null || typeof this.freeGetDataParams === 'undefined' || this.freeGetDataParams === null)
            return null;
            
        item = item.split('.');
        if(item.length === 1){
            return (typeof this.freeGetDataParams[item[0]] !== 'undefined') ? this.freeGetDataParams[item[0]] : null;
        }else if(item.length === 2){
            return (typeof this.freeGetDataParams[item[0]] !== 'undefined') && (typeof this.freeGetDataParams[item[0]][item[1]] !== 'undefined') ?
                this.freeGetDataParams[item[0]][item[1]] : null;
        }
        
        return null;
    }
}