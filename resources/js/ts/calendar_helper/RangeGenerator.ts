export class RangeGenerator{
    x: number;
    y: number;
    
    constructor(x = 1, y = 2) {
        this.x = x;
        this.y = y;
    }
    
    get(){
        return {
            start: this.x,
            end: this.y
        };
    }
}