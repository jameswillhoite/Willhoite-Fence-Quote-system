"use strict";
var login = {
    init: function() {
        var self = this;
        jQuery('input#password').on('keyup', function (e) {
            if(e.keyCode === 13)
                self.login();
        });
    },

    login: function() {
        var self = this;
        var user = jQuery('div#login input#username');
        var pass = jQuery('div#login input#password');

        if(!user.val() || user.val().length === 0) {
            user.addClass('is-invalid');
            self.displayErrorMsg("Please enter a vaild username", "danger");
            return;
        }
        else {
            user.removeClass('is-invalid');
        }

        if(!pass.val()  || pass.val().length === 0) {
            pass.addClass('is-invalid');
            self.displayErrorMsg("Please enter a password", "danger");
            return;
        }
        else {
            pass.removeClass('is-invalid');
        }

        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "router.php?task=mainJS.login",
            data: {email: user.val(), password: pass.val()},
            dataType: "json",
            cache: false,
            beforeSend: function() {
                jQuery('div.overlay').fadeIn('fast');
            },
            complete: function() {
                jQuery('div.overlay').fadeOut('fast');
            },
            success: function(data) {
                console.log(data);
                if(data.error) {
                    self.displayErrorMsg(data.error_msg, "danger");
                    return;
                }

                window.location.href = window.baseURL + 'index.php';


            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });
    },
    errorID: null,
    errorEl: null,
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
    }
};