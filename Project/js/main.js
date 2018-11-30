"use strict";
var main = {
    init: function() {
        jQuery('div#main div.mainMenuSelectOptions').click(function() {
            var id = jQuery(this).attr('data-id');

            switch(id) {
                case "quote":
                    if (typeof window.quote !== "undefined"){
                        window.location.href = window.quote;
                    }
                    else {
                        window.location.href = window.baseURL + "views/quote/default.php";
                    }
                    break;
                case "users":
                    if (typeof window.users !== "undefined"){
                        window.location.href = window.users;
                    }
                    else {
                        window.location.href = window.baseURL + "views/users/users.php";
                    }
                    break;
            }
        });

    },

    errorID: null,
    /**
     * Will display an error message if one is needed to be displayed.
     * @param txt String - The message to display to the user
     * @param level String - the level to display the message (based off Bootstrap) warning, danger, info, success
     * @param time int - the time to display the message, default is the txt length times 400ms
     */
    displayErrorMsg : function(txt, level, time) {
        var self = this;
        if(this.errorID) {
            this.errorEl.slideUp('500');
            clearTimeout(self.errorID);
            this.errorID = null;
            this.errorEl = null;
        }
        var errorMsg;
        var timeout;
        if(jQuery('div.modal.show').length > 0)
            errorMsg = jQuery('div.modal.show div#error_msg');
        else
            errorMsg = jQuery('div#main-content div#mainErrorMsg div#error_msg');

        errorMsg.removeClass('alert-success alert-danger alert-info alert-warning');
        errorMsg.empty();
        errorMsg.html(txt);
        errorMsg.addClass('alert-' + level);
        self.errorEl = errorMsg;

        if(time)
            timeout = time * 1000;
        else
            timeout = 400 * txt.length;

        errorMsg.slideDown(500);
        self.errorID = setTimeout(function() {
            errorMsg.slideUp(500);
        }, timeout);
    },
};