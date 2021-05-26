$(document).ready(function(){
    let temporaryMessageFunc = function(){
        
        this.disBtnClass = 'disable-temp-msg';
        this.scriptId = 'scriptTemporaryMessage';
        this.keyAttrData = 'msg-key';
        this.disMsgUrl = null;
        
        this.init = function(){
            this.setDisMsgUrl();
            this.regBtnClick();
        }
        
        this.setDisMsgUrl = function(){
            this.disMsgUrl = $('#' + this.scriptId).data('disable-msg-url');
        }
        
        this.regBtnClick = function(){
            let _this = this;
            
            $('.' + this.disBtnClass).click(function(e){
                e.preventDefault();
                
                // console.log($("." + _this.disBtnClass).closest('.alert').find('.close').click());
                // $("#" + _this.disBtnClass).closest('.alert').find('.close').click();
                // return;
                
                $.ajax({
                    url: _this.disMsgUrl,
                    dataType: "json",
                    type: "Post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // async: true,
                    data: {
                        key: $(this).data(_this.keyAttrData),
                    },
                    success: function (data) {
                        if(typeof data.status !== 'undefined' && data.status === 'success')
                            $("." + _this.disBtnClass).closest('.alert').find('.close').click();
                    },
                    // error: function (xhr, exception) {
                    //     var msg = "";
                    //     if (xhr.status === 0) {
                    //         msg = "Not connect.\n Verify Network." + xhr.responseText;
                    //     } else if (xhr.status == 404) {
                    //         msg = "Requested page not found. [404]" + xhr.responseText;
                    //     } else if (xhr.status == 500) {
                    //         msg = "Internal Server Error [500]." +  xhr.responseText;
                    //     } else if (exception === "parsererror") {
                    //         msg = "Requested JSON parse failed.";
                    //     } else if (exception === "timeout") {
                    //         msg = "Time out error." + xhr.responseText;
                    //     } else if (exception === "abort") {
                    //         msg = "Ajax request aborted.";
                    //     } else {
                    //         msg = "Error:" + xhr.status + " " + xhr.responseText;
                    //     }
                    // 
                    // }
                });
                
            });
        }
        
    }

    let temporaryMsg = new temporaryMessageFunc();
    temporaryMsg.init();
});