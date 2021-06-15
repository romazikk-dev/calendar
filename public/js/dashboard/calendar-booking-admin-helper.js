var calendarBookingHelperFunc = function(){
    
    this.filtersList = [
        'dashboard_admin_calendar.hall',
        'dashboard_admin_calendar.template',
        'dashboard_admin_calendar.worker',
        'dashboard_admin_calendar.view'
    ];
    
    // this.movingEventItemsList = [
    //     'hall',
    //     'template',
    //     'worker',
    // ];
    
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
    
    this.getMovingEventItem = function(item = null){
        if(item === null || typeof movingEvent === 'undefined' || movingEvent === null)
            return null;
            
        item = item.split('.');
        if(item.length === 1){
            return (typeof movingEvent[item[0]] !== 'undefined') ? movingEvent[item[0]] : null;
        }else if(item.length === 2){
            return (typeof movingEvent[item[0]] !== 'undefined') && (typeof movingEvent[item[0]][item[1]] !== 'undefined') ?
                movingEvent[item[0]][item[1]] : null;
        }
        
        return null;
    }
    
    this.getDashboardCalendarMoveEvent = function(type){
        let _this = this;
        
        if(isDashboardCalendarMoveEventEmpty() || !this.dashboardCalendarMoveEventItemsList.includes(type))
            return null;
        return dashboardCalendarMoveEvent[type];
        
        function isDashboardCalendarMoveEventEmpty(){
            if(typeof dashboardCalendarMoveEvent === 'undefined' || dashboardCalendarMoveEvent === null)
                return true;
            
            let eventItemsList = _this.dashboardCalendarMoveEventItemsList;
            for(let i = 0; i < eventItemsList.length; i++)
                if(typeof dashboardCalendarMoveEvent[eventItemsList[i]] === 'undefined' || dashboardCalendarMoveEvent[eventItemsList[i]] === null)
                    return true;
            
            return false;
        }
    }
    
}

var calendarBookingHelper = new calendarBookingHelperFunc();