var calendarBookingHelperFunc = function(){
    
    this.filtersList = ['hall','template','worker','view']
    
    this.isFiltersEmpty = function(){
        if(typeof filters === 'undefined')
            return true;
            
        for(let i = 0; i < this.filtersList.length; i++)
            if(typeof filters[this.filtersList[i]] === 'undefined' || filters[this.filtersList[i]] === null)
                return true;
        
        return false;
    }
    
    this.getFilter = function(type){
        if(this.isFiltersEmpty() || !this.filtersList.includes(type))
            return null;
        return filters[type];
    }
    
    // this.getTokenFromCookie = function(type){
    //     let token = cookie.get('token');
    //     return typeof token !== 'undefined' ? token : null;
    // }
    
}


var calendarBookingHelper = new calendarBookingHelperFunc();
// calendarBookingHelper.init();