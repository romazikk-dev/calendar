// alert(111);

var helperFunc = function(){
    
    this.statuses = ['active', 'suspended', 'future_suspension', 'period_suspended'];
    
    this.init = function(){
        // alert(111);
        // this.regSubmitBtn();
        // if(this.enableJsValidation)
        //     this.initValidator();
    }
    
    // helper.isEmpty(prop)
    this.isPropEmpty = function(prop){
        if(typeof prop == 'undefine' || prop == null)
            return true;
        return false;
    }
    
    this.capitalizeFirstLetter = function(str){
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    this.getStatusTooltipTitle = function(suspension){
        if(this.isStatus('period_suspended', suspension)){
            let from = this.formatStatusDate(suspension.from);
            let to = this.formatStatusDate(suspension.to);
            return `
                Suspended<br>
                from <b>${from}</b><br>
                till <b>${to}</b>
            `;
        }else if(this.isStatus('suspended', suspension)){
            return `Completely suspended`;
        }else if(this.isStatus('future_suspension', suspension)){
            let from = this.formatStatusDate(suspension.from);
            let to = this.formatStatusDate(suspension.to);
            return `
                Active<br>
                Will be suspended<br>
                from <b>${from}</b><br>
                till <b>${to}</b>
            `;
        }else if(this.isStatus('active', suspension)){
            return `Active`;
        }
    }
    
    this.formatStatusDate = function(date){
        return moment(date).format('DD-MM-YYYY');
    },
    
    this.formatCreatedDate = function(date){
        return moment(date).format('DD/MM/YYYY');
    },
    
    // helper.isStatus(type, suspension)
    this.isStatus = function(type, suspension){
        if(!this.statuses.includes(type))
            return false;
        
        if(type == 'future_suspension')
            return isSuspentionInFuture();
        if(type == 'suspended')
            return isSuspended();
        if(type == 'period_suspended')
            return isPeriodSuspended();
        if(type == 'active')
            return !isSuspended();
        return false;
        
        function isPeriodSuspended(){
            return isSuspended() && suspension.from != null;
        }
        
        function isSuspentionInFuture(){
            if(suspension == null)
                return false;
            if(suspension.from != null){
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(suspension.from);
                return (currentDateMoment.diff(fromMoment) < 0);
            }
            return false;
        }
    
        function isSuspended(){
            if(suspension == null)
                return false;
            
            if(suspension.from == null && suspension.to == null){
                return true;
            }else if(suspension.from != null && suspension.to == null){
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(suspension.from);
                return currentDateMoment.diff(fromMoment) >= 0;
            }else if(suspension.from != null && suspension.to != null){
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(suspension.from);
                let toMoment = moment(suspension.to);
                return (currentDateMoment.diff(fromMoment) >= 0 && currentDateMoment.diff(toMoment) <= 0);
            }
        }
    }
    
}

var helper = new helperFunc();
helper.init();