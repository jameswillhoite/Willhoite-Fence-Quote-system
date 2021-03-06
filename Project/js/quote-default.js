"use strict";
var quoteDefault = {
    init: function() {
        jQuery('div#main div.mainMenuSelectOptions').click(function() {
            var id = jQuery(this).attr('data-id');

            switch(id) {
                case "newQuote":
                    if (typeof window.newQuote !== "undefined"){
                        window.location.href = window.newQuote;
                    }
                    else {
                        window.location.href = window.baseURL + "/views/quote/newQuote.php";
                    }
                    break;
                case "search":
                    if (typeof window.search !== "undefined"){
                        window.location.href = window.search;
                    }
                    else {
                        window.location.href = window.baseURL + "/views/quote/search";
                    }
                    break;
            }
        });

    }

};