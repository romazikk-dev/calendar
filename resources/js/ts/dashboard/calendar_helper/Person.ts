export class Person{
  
    // constructor() {
    //     this.movingEvent = movingEvent;
    // }
    
    fullName(obj:any){
        if(obj === null ||
        typeof obj.first_name === 'undefined' ||
        typeof obj.last_name === 'undefined')
            return null;
            
        let fullName = obj.first_name;
        
        if(obj.last_name !== null && typeof obj.last_name === 'string' &&
        obj.last_name.length > 0)
            fullName += ' ' + obj.last_name;
            
        return fullName;
    }
    
}