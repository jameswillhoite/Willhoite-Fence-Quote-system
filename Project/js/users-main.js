"use strict";
var users = {
    init: function() {
        jQuery('div#main div.mainMenuSelectOptions').click(function() {
            var id = jQuery(this).attr('data-id');

            switch(id) {
                case "addUser":
                    if (typeof window.addUser !== "undefined"){
                        window.location.href = window.addUser;
                    }
                    else {
                        window.location.href = window.baseURL + "/views/users/addUser.php";
                    }
                    break;
                case "listUsers":
                    if (typeof window.listUsers !== "undefined"){
                        window.location.href = window.listUsers;
                    }
                    else {
                        window.location.href = window.baseURL + "/views/users/addUser.php";
                    }
                    break;
            }
        });

    }
};