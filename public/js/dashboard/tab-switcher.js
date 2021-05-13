// alert(111);

// $(document).ready(function(){
//     $('.nav-tabs a.nav-link').on('shown.bs.tab', function(){
//         // alert('The new tab is now fully shown.');
//         let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
//         let newHallFormUrl = hallFormUrl + '?tab=' + tab;
//         $("#hallForm").attr('action', newHallFormUrl);
// 
//         let currentUrl = location.protocol + '//' + location.host + location.pathname + '?tab=' + tab;
//         window.history.pushState({}, null, currentUrl);
// 
//         console.log('The new tab is now fully shown.');
//     });
// 
//     let params = (new URL(document.location)).searchParams;
//     let tab = params.get("tab");
//     if(tab != null && tab != 'main'){
// 
//         let newHallFormUrl = hallFormUrl + '?tab=' + tab;
//         $("#hallForm").attr('action', newHallFormUrl);
//         // $("#" + tab + "-tab").click();
//     }
// 
//     $('[data-toggle=tooltip').tooltip({
//         boundary: 'window',
//         html: true
//     });
// });

$(document).ready(function(){
    
    let scriptTag = $('script[name="tab-switcher"]');
    if(scriptTag.length <= 0)
        return;
    
    var tabSwitcherFunc = function(){
        
        this.formUrl = scriptTag.attr('form_url');
        this.formId = scriptTag.attr('form_id');
        
        this.init = function(){
            this.regTabClick();
            this.putUrlWithTabIntoFormAction();
        }
        
        this.regTabClick = function(){
            let _this = this;
            $('.nav-tabs a.nav-link').on('shown.bs.tab', function(){
                let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
                let newFormUrl = _this.formUrl + '?tab=' + tab;
                $("#" + _this.formId).attr('action', newFormUrl);
            
                let currentUrl = location.protocol + '//' + location.host + location.pathname + '?tab=' + tab;
                window.history.pushState({}, null, currentUrl);
            
                console.log('The new tab is now fully shown.');
            });
        }
        
        this.putUrlWithTabIntoFormAction = function(){
            let _this = this;
            let params = (new URL(document.location)).searchParams;
            let tab = params.get("tab");
            if(tab != null && tab != 'main'){
                let newFormUrl = _this.formUrl + '?tab=' + tab;
                $("#" + _this.formId).attr('action', newFormUrl);
            }
        }
        
    }

    var tabSwitcher = new tabSwitcherFunc();
    tabSwitcher.init();
});