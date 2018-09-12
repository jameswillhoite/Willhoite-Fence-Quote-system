var Customer = (function() {
    var fullname;
    var address;
    var city;
    var taxCity;
    var state;
    var zip;
    var phones = [];
    var emails = [];

    function Customer() {

    }

    Customer.prototype = {
        constructor: Customer,
        setName : function(fullName) {
            this[fullname] = fullName;
        },
        getName : function() {
            return this[fullname];
        },
        setAddress : function(address) {
            this[address] = address;
        },
        getAddress : function() {
            return this[address];
        },
        setCity : function(city) {
            this[city] = city;
        },
        getCity : function() {
            return this[city];
        },
        setTaxCity : function(taxCity) {
            this[taxCity] = taxCity;
        },
        getTaxCity : function() {
            return this[taxCity];
        },
        setState : function(state) {
            this[state] = state;
        },
        getState : function() {
            return this[state];
        },
        setZip : function(zip) {
            this[zip] = zip;
        },
        getZip : function() {
            return this[zip];
        },
        addPhone : function(type, phone) {
            var self =  this;
            var t = jQuery.grep(self[phones], function(p) {
                return p.type === type && p.phone === phone;
            });
            if(t.length === 0)
                this[phones].push({type: type, phone: phone});
            else
                return false;
        },
        addEmail : function(email) {
            var t = jQuery.grep(self[emails], function(e) {
                return e === email;
            });
            if(t.length === 0)
                this[email].push(email);
            else
                return false;
        }

    };

    return Customer;

}());


function NewStyle() {

    function Style() {
        this.id = null;
        this.totalFeet = 0;
        this.frontRight = 0.0;
        this.right = 0.0;
        this.back = 0.0;
        this.left = 0.0;
        this.frontLeft = 0.0;
        this.extra1 = 0.0;
        this.extra2 = 0.0;
        this.extra3 = 0.0;

    }

    Style.prototype = {
        constructor: Style,
        measurementEl: null,
        styleEl: null,
        style: null,
        Style: null,
        type: null,
        price: 0.00, //per foot
        subTotal: 0.00,

        addMeasurement : function(side, feet) {
            var self = this;
            if(!feet || isNaN(feet))
                feet = 0;
            switch(side) {
                case "frontRight":
                    this.frontRight = parseFloat(feet);
                    break;
                case "right":
                    this.right = parseFloat(feet);
                    break;
                case "back":
                    this.back = parseFloat(feet);
                    break;
                case "left":
                    this.left = parseFloat(feet);
                    break;
                case "frontLeft":
                    this.frontLeft = parseFloat(feet);
                    break;
                case "extra1":
                    this.extra1 = parseFloat(feet);
                    break;
                case "extra2" :
                    this.extra2 = parseFloat(feet);
                    break;
                case "extra3":
                    this.extra3 = parseFloat(feet);
                    break;
                default:
                    console.log("Could not add measurement " + side);
            }
            this.totalFeet = 0.0;
            this.totalFeet += this.frontRight + this.frontLeft + this.right + this.back + this.left + this.extra1 + this.extra2 + this.extra3;
        },
        getTotalFeet : function() {
            return this.totalFeet;
        },
        setStyle : function (style) {
            var self = this;
            self.style = style;
        },
        setPrice : function(price) {
            var self = this;
            self.price = parseFloat(price);
        },

        generateDiv: function(id) {
            var self = this;
            self.id = parseInt(id);
            var html = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
                '<fieldset id="style'+id+'" data-styleid="'+id+'">'+
                '<legend>Style <span id="styleNumber">'+ (parseInt(id)+1) +'</span>&nbsp;'+
                ((parseInt(id) > 0) ? ('<a id="removeStyle" role="button" href="#"><i class="fas fa-minus-circle" style="color: red; font-size: 0.55em;"></i></a>') : '') +' </legend>'+
                '<div>'+
                '<label for="frontRight">Front Right</label>'+
                '<input type="number" id="frontRight" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="right">Right Side</label>'+
                '<input type="number" id="right" class="form-control" pattern="[0-9*]"/>'+
                '</div>'+

                '<div>'+
                '<label for="back">Back</label>'+
                '<input type="number" id="back" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="left">Left Side</label>'+
                '<input type="number" id="left" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="frontLeft">Front Left</label>'+
                '<input type="number" id="frontLeft" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="extra1">Extra 1</label>'+
                '<input type="number" id="extra1" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="extra2">Extra 2</label>'+
                '<input type="number" id="extra2" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>'+
                '<label for="extra3">Extra 3</label>'+
                '<input type="number" id="extra3" class="form-control" pattern="[0-9]*"/>'+
                '</div>'+

                '<div>Total: <span id="totalStyle"></span></div>'+
                '</fieldset>'+
                '</div>';

            jQuery('div#measurements div#stylesDiv').append(html);
            self.measurementEl = jQuery('div#measurements div#stylesDiv fieldset#style'+id).parent();
            self.measurementEl.find('input[type="number"]').each(function() {
                jQuery(this).on('keyup', function() {
                    var val = jQuery(this).val();
                    var id = jQuery(this).attr('id');
                    var fieldset = jQuery(this).parent().parent();
                    var styleID = parseInt(fieldset.attr('data-styleid'));
                    //check the regex
                    var Reg = new MyReg(val);
                    if(!Reg.float()) {
                        jQuery(this).addClass('is-invalid');
                        quote.displayErrorMsg("Invalid Number", 'danger');
                        return;
                    }
                    else {
                        jQuery(this).removeClass('is-invalid');
                        quote.clearErrorMsg();
                    }


                    self.addMeasurement(id, val);
                    self.updateTotalFeet();
                });
            });
            self.measurementEl.find('a#removeStyle').on('click', function() {
                quote.removeStyleMeasurement(self.id);
            });

            self.generateStyleDiv();

        },
        getMeasurementsDiv: function() {
            var self = this;
            return self.measurementEl;
        },
        generateStyleDiv: function(){
            var self = this;
            var html = '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">' +
            '<fieldset id="style'+self.id+'" data-styleid="'+self.id+'">' +
                '<legend>Style <span id="styleNumber">'+(self.id+1)+'</span></legend>' +
                '<div class="row"> ' +
                    '<div class="col-lg-2 col-md-1 col-sm-2 col-xs-2" style="display: inline-flex;">' +
                        '<input id="repair" class="form-control" type="checkbox">' +
                        '<label for="repair" style="margin-top: 7px;">Repair</label>' +
                    '</div> ' +
                '</div>' +
                '<div class="row">' +
                    '<div class="col-8">' +
                        '<label for="styleFence">Style Fence</label>' +
                        '<select id="styleFence" class="form-control"><option value="none">--Select Style--</option>';
                        for(var a = 0; a < quote.stylesOfFence.getAllStyles().length; a++) {
                            var t = quote.stylesOfFence.getAllStyles()[a];
                            html += '<option value="' + t.fenceID + '">' + t.style + '</option>';
                        }
                    html += '</select>  ' +
                    '</div> ' +
                    '<div class="col-4">' +
                        '<label for="heightFence">Height</label>' +
                        '<select id="heightFence" class="form-control"><option value="none">--Select Height--</option>';
                        for(var a = 0; a < quote.heights.length; a++) {
                            var t = quote.heights[a];
                            html += '<option value="' + t.heightID + '">' + t.height + '</option>';
                        }
                        html += '</select>' +
                    '</div>' +
                '</div>' +
                '<div class="row">' +
                    '<div id="pricesAndTotals" class="col-12"> ' +
                        '<table class="table table-bordered table-sm table-hover">' +
                            '<thead><tr><th>Qty</th><th>Description</th><th>Price</th><th>Amount</th></tr></thead>' +
                            '<tbody></tbody>' +
                        '</table> ' +
                    '</div>' +
                '</div> ' +
            '</fieldset>' +
            '</div>';
            var stylesDiv = jQuery('div#formSelectionContent div#styles div#stylesDiv');
            stylesDiv.append(html);
            self.styleEl = stylesDiv.find('fieldset#style'+self.id).parent();
            stylesDiv.find('select#styleFence').on('change', function() {
                var val = jQuery(this).val();
                var Style = quote.stylesOfFence.getStyle(val);
                self.style = val;
                self.Style = Style;
                if(Style.type === "wood") {
                    self.getDivForWood();
                }
            })

        },
        getStyleDiv: function() {
            return this.styleEl;
        },
        updateTotalFeet: function() {
            var self = this;
            self.measurementEl.find('span#totalStyle').html(self.totalFeet);
            self.styleEl.find('');
        },
        updateID : function(id) {
            var self = this;
            self.id = parseInt(id);
            //change the ID in the measurements section
            if(self.measurementEl) {
                self.measurementEl.find('span#styleNumber').html(self.id+1).attr('data-styleid', id).attr('style', id);
            }
            if(self.styleEl) {
                self.styleEl.find('span#styleNumber').html(self.id+1).attr('data-styleid', id).attr('style', id);
            }
        },

        addGeneral: function() {
            var html = '<tr><td>' + this.totalFeet + '</td><td>Ft. Fence</td><td>' + this.price + '</td><td>' + (this.totalFeet * this.price) + '</td></tr>';
        },
        getDivForWood: function() {
            var self = this;
            //figure out the number of posts
            var totalPosts = Math.ceil(self.totalFeet/self.Style.postSpacing) + 4; //round up and add 4 posts


        }
    };

    return new Style();

}

function AllFences() {
    var arrayFences = [];

    function Fence(fenceID, styleName, pricePerFoot, type, postSpacing) {
        this.fenceID = parseInt(fenceID);
        this.style = styleName;
        this.pricePerFoot = parseFloat(pricePerFoot);
        this.type = type;
        this.postSpacing = parseFloat(postSpacing);
    }

    this.addStyle = function(fenceID, styleName, pricePerFoot, type, postSpacing) {
        var temp = new Fence(fenceID, styleName, pricePerFoot, type, postSpacing);
        arrayFences.push(temp);
        arrayFences.sort(function(a,b) {
            if(a.type > b.type) return 1;
            if(a.type < b.type) return -1;
            return 0;
        });
        return temp;
    };

    this.getStyle = function(styleID) {
        for( var a = 0; a < arrayFences.length; a++) {
            if(arrayFences[a].fenceID === parseInt(styleID))
                return arrayFences[a];
        }
        return null;
    };

    this.getAllStyles = function() {
        return arrayFences;
    }

}

var MyReg = (function() {
    var testStr;
    function Test(test) {
        this[testStr] = test;
    }

    Test.prototype = {
        constructor: Test,
        setTestStr : function(str) {
            this[test] = str;
        },
        alpha : function() {
            var reg = /^[a-zA-Z\s]+$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        alphaNumeric : function() {
            var reg = /^[a-zA-Z0-9\s]+$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        numeric: function() {
            var reg = /^[0-9]+$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        float: function() {
            var reg = /^[0-9]+\.?[0-9]{0,2}$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        address : function() {
            var reg = /^[0-9]+\s?[a-zA-Z\s\.]+$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        state : function() {
            var reg = /^[a-zA-Z]{2,2}$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        zip : function() {
            var reg = /^[0-9]{5,5}$|^[0-9]{5,5}\-[0-9]{4,4}$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        date : function() {
            var reg = /^[0-9]{1,2}[\-\/]{1,1}[0-9]{1,2}[\-\/]{1,1}(?:[0-9]{2,2}|[0-9]{4,4})$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        phone : function() {
            var reg = /^[0-9\(\)\-\s]+$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        },
        email : function() {
            var reg = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            var Reg = new RegExp(reg);
            return Reg.test(this[testStr]);
        }


    };

    return Test;
}());

var Draw = (function() {
    var canvas;
    function init() {
        var canvasDiv = document.getElementById('fenceDrawingDiv');
        canvas = document.createElement('canvas');
        canvas.setAttribute('id', 'fenceDrawing');
        canvas.setAttribute('class', 'form-control');
        canvasDiv.appendChild(canvas);
        var canvasJQ = jQuery('canvas#fenceDrawing');

        //Change the width and height to what it is so the mouseXY will match
        var tempW = canvasJQ.outerWidth();
        var tempH = canvasJQ.outerHeight();

        canvasJQ.attr('width', tempW);
        canvasJQ.attr('height', tempH);


        if (typeof G_vmlCanvasManager != 'undefined') {
            canvas = G_vmlCanvasManager.initElement(canvas);
        }
        context = canvas.getContext("2d");


        canvasJQ.mousedown(function (e) {
            getMousePos(e);
            paint = true;
            addClick(mouseX, mouseY);
            redraw();
        });
        canvasJQ.mousemove(function (e) {
            getMousePos(e);
            if (paint) {
                addClick(mouseX, mouseY, true);
                redraw();
            }
        });
        canvasJQ.mouseup(function (e) {
            paint = false;
        });
        canvasJQ.mouseleave(function (e) {
            paint = false;
        });

        //listen for the size changes
        var tools = jQuery('div#canvasTools');
        tools.find('select#size').on('change', function() {
            curSize = jQuery(this).val();
        });
        var erase = false;
        tools.find('button#erase').on('click', function() {
            if(!erase) {
                prevColor = curColor;
                curColor = "#ffffff";
                erase = true;
                jQuery(this).addClass('active');
            }
            else {
                curColor = prevColor;
                erase = false;
                jQuery(this).removeClass('active');
            }
        });

        drawHouse();


    }
    var context;
    var clickX = [];
    var clickY = [];
    var clickDrag = [];
    var color = [];
    var curColor = "#000000";
    var prevColor = null;
    var clickSize = [];
    var curSize = 1;
    var mouseX, mouseY;
    var paint = false;

    function addClick(x, y, dragging) {
        clickX.push(x);
        clickY.push(y);
        clickDrag.push(dragging);
        clickSize.push(curSize);
        color.push(curColor);
    }
    function redraw() {
        context.clearRect(0, 0, context.canvas.width, context.canvas.height); // Clears the canvas

        context.lineJoin = "round";
        drawHouse();

        for(var i = 0; i < clickX.length; i++) {
            context.beginPath();
            if(clickDrag[i] && i) {
                context.moveTo(clickX[i-1], clickY[i-1]);
            }
            else {
                context.moveTo(clickX[i]-1, clickY[i]);
            }

            context.lineTo(clickX[i], clickY[i]);
            context.closePath();
            context.strokeStyle = color[i];
            context.lineWidth = clickSize[i];
            context.stroke();
        }
    }
    // Get the current mouse position relative to the top-left of the canvas
    function getMousePos(e) {
        if (!e)
            var e = event;

        if (e.offsetX) {
            mouseX = e.offsetX;
            mouseY = e.offsetY;
        }
        else if (e.layerX) {
            mouseX = e.layerX;
            mouseY = e.layerY;
        }
        showXY(mouseX, mouseY);
    }

    function showXY(x, y) {
        jQuery('div#showXY').html(x+','+y);
    }

    function drawHouse() {
        context.strokeRect(173,169, 185, 60);
        context.font = "24px Arial";
        context.strokeText("HOUSE", 225, 208);
    }


    init.prototype = {
        constructor : init
    };


    return init;


}());

"use strict";
var quote = {

    Customer: new Customer(),
    numOfStyles: 0,
    Styles: [],
    stylesOfFence: new AllFences(),
    heights: [],
    Draw: null,
    init: function() {
        var self = this;
        // Add the Datepicker to each Date Element
        jQuery('input[type="date"]').each(function() {
            jQuery(this).datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                assumeNearbyYear: true,
                disableTouchKeyboard: true,
                todayHighlight: true
            });
        });

        //Add a style
        self.addStyleMeasurement();

        //Set listeners for the different elements for customer Info
        var customerDiv = jQuery('div#customerInfo');
        customerDiv.find('input#contractDate').on('change', function() {
            var val = jQuery(this).val();
            var Reg = new MyReg(val);
            if(!Reg.date())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#customerName').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setName(val);
            var Reg = new MyReg(val);
            if(!Reg.alpha())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#address').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setAddress(val);
            var Reg = new MyReg(val);
            if(!Reg.address())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#city').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setCity(val);
            var Reg = new MyReg(val);
            if(!Reg.alpha())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#taxCity').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setTaxCity(val);
            var Reg = new MyReg(val);
            if(!Reg.alpha())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#state').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setState(val);
            var Reg = new MyReg(val);
            if(!Reg.state())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#zip').on('keyup', function() {
            var val = jQuery(this).val();
            this.Customer.setZip(val);
            var Reg = new MyReg(val);
            if(!Reg.zip())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#phoneChoice').on('keyup', function() {
            var val = jQuery(this).val();
            var phoneType = jQuery(this).parent().find('select#phoneChoice').val();
            var Reg = new MyReg(val);
            if(!Reg.phone())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });
        customerDiv.find('input#email').on('keyup', function() {
            var val = jQuery(this).val();
            var Reg = new MyReg(val);
            if(!Reg.email())
                jQuery(this).addClass('is-invalid');
            else
                jQuery(this).removeClass('is-invalid');
        });

        //set listeners for the measurements
        var measurements = jQuery('div#measurements');


        jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
           if(e.target.id === 'drawing-Tab' && !self.Draw) {
               self.Draw = new Draw();
           }
        });

        /**
         * via of w3Schools
         */
        jQuery(document).on('scroll', function() {
            var btn = jQuery('button#scrollToTop');
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                btn.css("display", "block");
            } else {
                btn.css("display", "none");
            }
        });
        jQuery('button#scrollToTop').on('click', function() {
            jQuery('html body').animate({
                scrollTop: "0"
            },500);
        });

    },

    /**
     * General Functions
     */
    errorID: null,
    errorEl: null,
    displayErrorMsg : function(txt, level, time) {
        var self = this;
        if(this.errorID) {
            this.errorEl.slideUp('500', function() {
                clearTimeout(self.errorID);
            });
            this.errorID = null;
            this.errorEl = null;
        }
        var errorMsg;
        var timeout;
        if(jQuery('div.modal.in').length > 0)
            errorMsg = jQuery('div.modal.in div#error_msg');
        else
            errorMsg = jQuery('div#error_msg');

        errorMsg.removeClass('alert-successalert-dangeralert-infoalert-warning');
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
    clearErrorMsg : function() {
        if(this.errorID) {
            this.errorEl.slideUp('500', function() {
                clearTimeout(self.errorID);
            });
            this.errorID = null;
            this.errorEl = null;
        }
    },

    /**
     * Customer Info Section
     */


    /**
     * Measurements
     */
    addStyleMeasurement : function() {
        var self = this;
        var nextID = self.Styles.length;
        var S = new NewStyle();
        var stylesDiv = jQuery('div#measurements div#stylesDiv');
        S.generateDiv(nextID); //Will append in the propper area
        self.Styles.push(S);
    },
    removeStyleMeasurement : function(id) {
        var self = this;
        var S,a;
        id = parseInt(id);
        for(a = 0; a < self.Styles.length; a++) {
            if (self.Styles[a].id === id) {
                S = self.Styles[a];
                break;
            }
        }


        //remove the listeners
        S.getMeasurementsDiv().find('input').each(function() {
            jQuery(this).unbind('keyup');
        });
        S.getMeasurementsDiv().remove();
        S.getStyleDiv().remove();
        self.Styles.splice(a, 1);

        //Update the styles past this point
        for(a = 0; a < self.Styles.length; a++) {
            self.Styles[a].updateID(a);
        }
    }

};