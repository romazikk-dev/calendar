declare var filters: any;

export class Filter{
    filters: any;
  
    constructor() {
        this.filters = filters;
    }
    
    get(filter:any = null){
        if(typeof this.filters === 'undefined' || this.filters === null)
            return null;
            
        if(filter === null)
            return this.filters;
            
        if(typeof this.filters[filter] !== 'undefined' && this.filters[filter] !== null)
            return this.filters[filter];
            
        return null;
    }
    
    getOnlyIdsAsArrayFromFilterItem(item:any){
        let itemIds = [];
        for(let idx in item)
            itemIds.push(item[idx].id);
        return itemIds;
    }
}