"use strict";
var quoteSearch = {
    init: function () {
        var self = this;
        self.modalListeners();
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

    /**
     * Search Modal
     */
    modalListeners: function () {
        var self = this;
        var sModal = jQuery('div#main-content div#main');
        var searchFor = sModal.find('input#searchValue');
        var styles = sModal.find('select#fenceStyles');
        var zip = sModal.find('input#zipCode');
        //sModal.modal('show');

        //When the Clear button is pressed, clear the form
        sModal.find('button#clear').on('click', function () {
            self.clearSearch();
        });

        //On Modal Load, clear and focus on the Search Value
        sModal.on('shown.bs.modal', function() {
            sModal.find('input#searchValue')
                .val('')
                .focus();
        });

        //When the Search by is changed load some other elements
        sModal.find('select#searchBy').on('change', function() {
            var val = jQuery(this).val();
            switch(val) {
                case "jobID":
                    zip.val('').parent().hide();
                    styles.parent().hide();
                    searchFor.parent().show();
                    break;
                case "customerName":
                    searchFor.parent().show();
                    zip.parent().show();
                    styles.parent().hide();
                    break;
                case "styleName":
                    searchFor.parent().hide();
                    zip.parent().show();
                    styles.parent().show();
                    break;
                case "phone":
                    styles.parent().hide();
                    searchFor.parent().show();
                    zip.parent().show();
                    break;
            }
        });

        //Little Validation
        zip.on('keyup', function () {
            if(zip.val().length > 0 && !/^[0-9]{5,5}$/.test(zip.val())) {
                zip.addClass('is-invalid');
            }
            else {
                zip.removeClass('is-invalid');
            }
        });
        searchFor.on('keyup', function (e) {
            var searchBy = sModal.find('select#searchBy');
            switch (searchBy.val()) {
                case "jobID":
                    if(!/^[0-9]*$/.test(searchFor.val())) {
                        searchFor.addClass('is-invalid');
                    }
                    else {
                        searchFor.removeClass('is-invalid');
                    }
                    break;
                case "customerName":
                    if(!/^[a-zA-Z\s\&]*$/.test(searchFor.val())) {
                        searchFor.addClass('is-invalid');
                    }
                    else {
                        searchFor.removeClass('is-invalid');
                    }
                    break;
                case "phone":
                    if(!/^(?:\(?[0-9]{0,3}\)?\s?)[|\-|\.]?[0-9]{3,3}[\-|\.]?[0-9]{4,4}$/.test(searchFor.val())) {
                        searchFor.addClass('is-invalid');
                    }
                    else {
                        searchFor.removeClass('is-invalid');
                    }
                    break;
            }

            if(e.keyCode === 13)
                self.doSearch();

        });

        //Listen for the Search Click
        sModal.find('button#search').on('click', function () {
            self.doSearch();
        });

        //Listen for the row click
        sModal.find('table#searchTable tbody').on('click', 'tr', function() {
            var jobID = jQuery(this).attr('data-id');
            //self.getJob(jobID);
            self.loadPDF(jobID);
            sModal.modal('hide');
        });

    },
    clearSearch: function() {
        var sModal = jQuery('div#main-content div#main');
        sModal.find('input#searchValue').val('');
        sModal.find('select#fenceStyles').val(sModal.find('select#fenceStyles option:first').val());
        sModal.find('input#zipCode').val('');
    },
    doSearch: function() {
        var self = this;
        var sModal = jQuery('div#main-content div#main');
        var searchBy = sModal.find('select#searchBy').val();
        var searchFor = sModal.find('input#searchValue');
        var styles = sModal.find('select#fenceStyles');
        var zip = sModal.find('input#zipCode');
        var table = sModal.find('table#searchTable tbody');
        var passed = true;

        //Validate the Zip
        if(zip.val().length > 0 && (searchBy === "customerName" || searchBy === "styleName" || searchBy === "phone") ) {
            if(zip.val().length > 0 && !/^[0-9]{5,5}$/.test(zip.val())) {
                zip.addClass('is-invalid');
                passed = false;
            }
            else {
                zip.removeClass('is-invalid');
            }
        }
        else {
            zip.removeClass('is-invalid');
        }

        //Validate the search for based on what search by is
        switch (searchBy) {
            case "jobID":
                if(!/^[0-9]*$/.test(searchFor.val())) {
                    searchFor.addClass('is-invalid');
                    passed = false;
                }
                else {
                    searchFor.removeClass('is-invalid');
                }
                break;
            case "customerName":
                if(!/^[a-zA-Z\s\&]*$/.test(searchFor.val())) {
                    searchFor.addClass('is-invalid');
                    passed = false;
                }
                else {
                    searchFor.removeClass('is-invalid');
                }
                break;
            case "phone":
                if(!/^(?:\(?[0-9]{0,3}\)?\s?)[|\-|\.]?[0-9]{3,3}[\-|\.]?[0-9]{4,4}$/.test(searchFor.val())) {
                    searchFor.addClass('is-invalid');
                    passed = false;
                }
                else {
                    searchFor.removeClass('is-invalid');
                }
                break;
            case "styleName":
                searchFor = styles;
                break;
        }

        if(!passed) {
            self.displayErrorMsg("Please correct the Errors below", "danger");
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "/router.php?task=viewJS.doSearch",
            data: {searchBy: searchBy, searchFor: searchFor.val(), zip: zip.val()},
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
                    self.displayErrorMsg(data.error_msg, "danger");
                    return;
                }

                if(!data.data || data.data.length === 0) {
                    self.displayErrorMsg("I don't have anything in my Database.", "info");
                    return;
                }

                var html = '';
                for(var a = 0; a < data.data.length; a++) {
                    var c = data.data[a];
                    html += '<tr data-id="' + c.JobID + '"><td>' + c.JobID + '</td><td>' + c.CustomerName + '</td><td>' + c.CustomerCity + '</td><td>' + c.CustomerZip + '</td><td>';
                    var i = 0;
                    for(var key in c.Styles) {
                        if(i > 0)
                            html += "<br/>";
                        html += c.Styles[key].Style;
                        i++;
                    }

                    html += '</td></tr>';
                }

                table.html(html);


            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });


    },

    /**
     * Load the PDF in the iFrame
     */
    loadPDF: function (jobID) {
        if(!jobID || isNaN(jobID))
            return false;
        var html = window.baseURL + '/docs/GenerateQuote.php?view=browser&jobID=' + jobID;
        window.open(html, '__BLANK');
    },

    /**
     * get the searched job
     */
    getJob: function(jobID) {
        var self = this;
        if(!jobID) {
            jQuery('div#main-content div#searchModal').modal('show');
            return;
        }

        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "/router.php?task=viewJS.getJobInfo",
            data: {jobID: jobID},
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
                    self.displayErrorMsg(data.error_msg, "danger");
                    return;
                }

                var c = data.data;
                self.fillHeader(c.DateSold, c.CustomerName, c.CustomerAddress, c.CustomerCity, c.CustomerState, c.CustomerZip,
                    c.CustomerPhoneType, c.CustomerPhone, c.CustomerEmail);

            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });

    },

    fillHeader: function(dateSold, customerName, customerAddress, customerCity, customerState, customerZip,
                         customerPhoneType, customerPhone, customerEmail)
    {
        var div = jQuery('div#main-content div#main');
        div.find('p#dateSold').html(dateSold);
    }
};