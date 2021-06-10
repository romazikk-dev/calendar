var calendarBookingHelperFunc = function(){
    
    this.filtersList = [
        'dashboard_admin_calendar.hall',
        'dashboard_admin_calendar.template',
        'dashboard_admin_calendar.worker',
        'dashboard_admin_calendar.view'
    ]
    
    this.isFiltersEmpty = function(){
        if(typeof filters === 'undefined' || filters === null)
            return true;
        // alert(typeof filters.hall);
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
    
}


var calendarBookingHelper = new calendarBookingHelperFunc();