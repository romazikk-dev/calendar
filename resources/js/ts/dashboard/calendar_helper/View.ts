declare var view: any;

export class View{
    cookieView: any;
  
    constructor() {
        this.cookieView = typeof view !== 'undefined' && view !== null ? view : 'month';
    }
    
    get(){
        return this.cookieView;
    }
    
    all(){
        return ['month','week','day','list'];
    }
}