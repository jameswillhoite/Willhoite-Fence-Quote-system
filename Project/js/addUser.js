"use strict";
var addUser = {
    init: function() {
        var self = this;
        var name = jQuery('div#main input#name');
        name.on('keyup', function() {
            self.validateName();
        });
        var email = jQuery('div#main input#email');
        email.on('keyup', function() {
            self.validateEmail();
        });
        var password = jQuery('div#main input#password');
        password.on('keyup', function() {
            self.validatePassword();
        });
        var password2 = jQuery('div#main input#password2');
        password2.on('keyup', function() {
            self.validatePassword2();
        });
    },

    validateName: function() {
        var self = this;
        var name = jQuery('div#main input#name');
        var Reg = new RegExp(/^[a-zA-Z\s]{6,}$/);
        if(Reg.test(name.val())) {
            name.removeClass('is-invalid');
            return true;
        }
        else {
            name.addClass('is-invalid');
            return false;
        }
    },
    validateEmail: function() {
        var self  = this;
        var email = jQuery('div#main input#email');
        var Reg = new RegExp(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/);
        if(Reg.test(email.val())) {
            email.removeClass('is-invalid');
            return true;
        }
        else {
            email.addClass('is-invalid');
            return false;
        }
    },
    validatePassword: function() {
        var self  = this;
        var password = jQuery('div#main input#password');
        var Reg = new RegExp(/^[a-zA-Z0-9\s\!\@\#\$\%\^\&\*\(\)\-\=\_\+\`\~\.\/\?\\\<\>]{6,}$/);
        if(Reg.test(password.val())) {
            password.removeClass('is-invalid');
            return true;
        }
        else {
            password.addClass('is-invalid');
            return false;
        }

    },
    validatePassword2: function() {
        var self  = this;
        var password = jQuery('div#main input#password');
        var password2 = jQuery('div#main input#password2');
        if(password.val() === password2.val()) {
            password2.removeClass('is-invalid');
            return true;
        }
        else {
            password2.addClass('is-invalid');
            return false;
        }
    },
    addUser: function() {
        var self = this;
        var name = jQuery('div#main input#name');
        var email = jQuery('div#main input#email');
        var password = jQuery('div#main input#password');
        var password2 = jQuery('div#main input#password2');

        if(!self.validateName()) {
            self.displayErrorMsg("Name can only contain Letters and Spaces", "danger");
            return;
        }
        else if(!self.validateEmail()) {
            self.displayErrorMsg("Please enter a valid email address", "danger");
            return;
        }
        else if(!self.validatePassword()) {
            self.displayErrorMsg("Password can only contain letters, numbers, and special characters. Must be at least 6 characters long.", "danger");
            return;
        }
        else if(!self.validatePassword2()) {
            self.displayErrorMsg("The passwords do not match", "danger");
            return;
        }


        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "router.php?task=addUserJS.addUser",
            data: {name: name.val(), email: email.val(), password: password.val()},
            dataType: "json",
            cache: false,
            beforeSend: function() {
                jQuery('div.overlay').fadeIn("fast");
            },
            complete: function() {
                jQuery('div.overlay').fadeOut("fast");
            },
            success: function(data) {
                console.log(data);
                if(data.error) {
                    console.log(data.error_msg);
                    return;
                }

                name.val('');
                email.val('');
                password.val('');
                password2.val('');

                self.displayErrorMsg("User was added.", "success");
            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });

    },
    errorID: null,
    errorEl : null ,
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