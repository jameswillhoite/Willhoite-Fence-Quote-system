"use strict";
var listUsers = {

    init: function () {

        var self = this;
        var userName = jQuery('div#main div#user-tab input#name');

        //Listen for checkbox changes
        jQuery('div#main div#groups input[type="checkbox"]').on('change', function() {
            var userID = userName.attr('data-id');
            var value = jQuery(this).val();
            if(jQuery(this).is(':checked')) {
                self.updateGroup(userID, value, "add");
            }
            else {
                self.updateGroup(userID, value, "remove");
            }
        });
        
    },
    users: [],
    addRow: function(id, name, email, groupArray) {
        this.users.push({id: parseInt(id), name: name, email: email, groups: groupArray});
        var table = jQuery('div#main table#users tbody');
        var html = '<tr><td>' + id + '</td><td>' + name + '</td><td>' + email + '</td><td><a href="#" onclick="listUsers.loadUser(' + id +');" title="Edit User"><i class="fas fa-user-edit"></i></a> </td>';
        table.append(html);
    },

    hideList: function() {
        var listOfUsers = jQuery('div#main div#listOfUsers');
        listOfUsers.hide();
    },
    showList: function() {
        this.hideUser();
        var listOfUsers = jQuery('div#main div#listOfUsers');
        listOfUsers.show();
    },

    hideUser: function() {
        var user = jQuery('div#main div#editUser');
        user.hide();
    },
    showUser: function() {
        this.hideList();
        var user = jQuery('div#main div#editUser');
        user.show();
    },

    loadUser: function(id) {
        var user = jQuery.grep(this.users, function(t) {return t.id === parseInt(id);})[0];
        if(!user)
            return false;

        jQuery('div#main div#editUser input#name').val(user.name).attr('data-id', id);
        jQuery('div#main div#editUser input#email').val(user.email);
        var groups = jQuery('div#main div#editUser div#groups');

        for(var i = 0; i < user.groups.length; i++) {
            var gid = user.groups[i];
            groups.find('input#groupID'+gid).attr("checked", true);
        }

        this.showUser();
    },

    clearUser: function () {
        var name = jQuery('div#main div#editUser input#name');
        var email = jQuery('div#main div#editUser input#email');
        var password = jQuery('div#main div#editUser input#password');
        var password2 = jQuery('div#main div#editUser input#password2');
        
        name.val('');
        name.attr('data-id', '');
        email.val('');
        password.val('');
        password2.val('');

        jQuery('div#groups input[type="checkbox"]').each(function () {
            jQuery(this).attr('checked', false);
        });
    },

    updateGroup: function(uid, gid, addRemove) {
        if(!uid && !gid)
            return false;

        var self = this;

        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "/router.php?task=addUserJS.updateGroup",
            data: {uid: uid, gid: gid, addRemove: addRemove},
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                console.log(data);
                if(data.error) {
                    console.log(data.error_msg);
                    return;
                }

                var user = jQuery.grep(self.users, function(t) {return t.id === parseInt(uid);})[0];

                user.groups = data.data;

            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });
    },

    updateUser: function() {
        var self = this;
        var name = jQuery('div#main div#editUser input#name');
        var email = jQuery('div#main div#editUser input#email');
        var password = jQuery('div#main div#editUser input#password');
        var password2 = jQuery('div#main div#editUser input#password2');
        var id = name.attr('data-id');
        var passed = true;
        var errors = [];
        var fd = {};
        fd.uid = id;
        var user = jQuery.grep(this.users, function(t) {return t.id === parseInt(id);})[0];


        if(user.name !== name.val()) {
            if (!/^[a-zA-Z\s]{6,}$/.test(name.val())) {
                name.addClass('is-invalid');
                errors.push("Name is invalid");
                passed = false;
            }
            else {
                name.removeClass('is-invalid');
                fd.name =  name.val();
            }
        }
        if(user.email !== email.val()) {
            if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(email.val())) {
                email.addClass('is-invalid');
                errors.push("Email address is invalid");
                passed = false;
            }
            else {
                email.removeClass('is-invalid');
                fd.email = email.val();
            }
        }
        if(password.val()) {
            if(!/^[a-zA-Z0-9\s\!\@\#\$\%\^\&\*\(\)\-\=\_\+\`\~\.\/\?\\\<\>]{6,}$/.test(password.val())) {
                password.addClass('is-invalid');
                errors.push("Password must be at least 6 characters or contains invalid characters");
                passed = false;
            }
            else {
                if(password.val() !== password2.val()) {
                    password2.addClass('is-invalid');
                    passed = false;
                    errors.push("The password do not match");
                }
                else {
                    password2.removeClass('is-invalid');
                    password.removeClass('is-invalid');
                    fd.password = password.val();
                    fd.password2 = password2.val();
                }
            }
        }

        if(passed === false) {
            var html = '<ul>';
            for(var i = 0; i < errors.length; i++) {
                html += '<li>' + errors[i] + '</li>';
            }
            html += '</ul>';
            self.displayErrorMsg(html, 'danger', 12);
            return;
        }


        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "/router.php?task=addUserJS.updateUser",
            data: fd,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                jQuery('div.overlay').fadeIn('fast');
            },
            complete: function() {
                jQuery('div.overlay').fadeOut('fast');
            },
            success: function(data) {
                if(data.error) {
                    self.displayErrorMsg(data.error_msg, "danger");
                    return;
                }
                user.name = name.val();
                user.email = email.val();

                self.displayErrorMsg("The user has been updated.", "success");


            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });


    },

    closeUser: function () {
        this.clearUser();
        this.showList();
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