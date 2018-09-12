"use strict";
var car = {

    init: function() {
        var self = this;
        jQuery('button#submit').on('click', function() {
             self.submitForm();
        });
        jQuery('input[type="text"]').each(function() {
            jQuery(this).on('keyup', function(e) {
                e.preventDefault();
                if(e.keyCode === 13)
                    self.submitForm();
            });
        });
    },

    errorID: null,
    displayError : function(txt, level) {
        if(this.errorID) {
            clearTimeout(this.errorID);
            this.errorID = null;
        }
        var self = this;
        var errorMsg = jQuery('div#errorMsg');
        errorMsg.removeClass('alert alert-success alert-warning alert-info alert-danger');
        errorMsg.addClass('alert alert-'+level);
        errorMsg.empty().html(txt);
        errorMsg.slideDown(500);
        self.errorID = setTimeout(function() {
            errorMsg.slideUp(500);
            self.errorID = null;
        }, 400 * txt.length);
    },
    submitForm : function() {
        var self = this;
        var errors = [];
        //Validation
        var visit = jQuery('input[name="rateVisit"]:checked');
        var salesman = jQuery('input[name="rateSalesman"]:checked');
        var value = jQuery('input[name="ratevalue"]:checked');
        var clean = jQuery('input[name="cleanliness"]:checked');
        var refer = jQuery('select#refer');
        var maintance = jQuery('select#rmaintenance');
        var comments = jQuery('textarea#otherComments');
        var regText = new RegExp(/[a-zA-Z\.\s]+/);
        var regTextNumber = new RegExp(/[a-zA-Z0-9\.\s]+/);
        var regNumber = new RegExp(/[0-9]+/);


        if(!visit.val()) {
            errors.push("Please Rate your Visit");
        }
        if(!salesman.val()) {
            errors.push("Please Rate your Salesman");
        }
        if(!value.val()) {
            errors.push("Please Rate the Value of your Car/Truck");
        }
        if(!clean.val()) {
            errors.push("Please Rate the Cleanliness of your Dealership");
        }
        if(maintance.val() === "null") {
            errors.push("Please Select a response for Maintenance");
        }
        if(refer.val() === "null") {
            errors.push("Please select a response for Refer Friends and Family");
        }



        if(errors.length > 0) {
            var err = '<ul type="none">';
            for(var a = 0; a < errors.length; a++) {
                err += '<li>'+ errors[a] + "</li>";
            }
            err += '</ul>';
            self.displayError(err, 'danger');

            //scroll back to top
            jQuery('body').animate({scrollTop: 0}, 500);
            return;
        }

        var thankYou = "<div class='text-center'><h1>Thank you</h1><br/>For completing this Survey!</div>";
        jQuery('div#survey').empty().html(thankYou);


    }

};