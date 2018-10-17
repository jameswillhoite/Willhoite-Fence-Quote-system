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

var MyReg = (function() {
    var testStr;
    function Test(test) {
        this[testStr] = test;
    }

    Test.prototype = {
        constructor: Test,
        setTestStr : function(str) {
            this[testStr] = str;
        },
        alpha : function() {
            var Reg = new RegExp(/^[a-zA-Z\s]+$/);
            return Reg.test(this[testStr]);
        },
        alphaNumeric : function() {
            var Reg = new RegExp(/^[a-zA-Z0-9\s]+$/);
            return Reg.test(this[testStr]);
        },
        numeric: function() {
            var Reg = new RegExp(/^[0-9]+$/);
            return Reg.test(this[testStr]);
        },
        doble: function() {
            var Reg = new RegExp(/^[0-9]+\.?[0-9]{0,2}$/);
            return Reg.test(this[testStr]);
        },
        address : function() {
            var Reg = new RegExp(/^[0-9]+\s?[a-zA-Z\s\.]+$/);
            return Reg.test(this[testStr]);
        },
        state : function() {
            var Reg = new RegExp(/^[a-zA-Z]{2,2}$/);
            return Reg.test(this[testStr]);
        },
        zip : function() {
            var Reg = new RegExp(/^[0-9]{5,5}$|^[0-9]{5,5}\-[0-9]{4,4}$/);
            return Reg.test(this[testStr]);
        },
        date : function() {
            var Reg = new RegExp(/^[0-9]{1,2}[\-\/]{1,1}[0-9]{1,2}[\-\/]{1,1}(?:[0-9]{2,2}|[0-9]{4,4})$/);
            return Reg.test(this[testStr]);
        },
        phone : function() {
            var Reg = new RegExp(/^[0-9\(\)\-\s]+$/);
            return Reg.test(this[testStr]);
        },
        email : function() {
            var Reg = new RegExp(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/);
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

function MasterStyleList() {
    var list = [];
    function Style(styleID, description, type, postSpacing) {
        this.styleID = parseInt(styleID);
        this.description = String(description);
        this.type = String(type);
        this.postSpacing = parseFloat(postSpacing);
        this.heightPrice = [];
        this.gates = [];
    }
    Style.prototype = {
        addHeightPrice: function(heightID, height, pricePerFoot) {
            var temp = {
                heightID: parseInt(heightID),
                height: height,
                pricePerFoot: parseFloat(pricePerFoot)
            };
            this.heightPrice.push(temp);
        },
        addGateAndPrice: function(gateID, heightID, width, price) {
            var temp = {
                gateID: parseInt(gateID),
                heightID: parseInt(heightID),
                width: width,
                price: parseFloat(price)
            };
            this.gates.push(temp);
        },
        getHeightByID: function(id) {
            for(var a = 0; a < this.heightPrice.length; a++) {
                if(this.heightPrice[a].heightID === parseInt(id)) {
                    return this.heightPrice[a];
                }
            }
            return null;
        },
        getAllHeights: function() {
            var temp = [];
            for(var a = 0; a < this.heightPrice.length; a++) {
                temp.push({id: this.heightPrice[a].heightID, height: this.heightPrice[a].height});
            }
            return temp;
        }


    };

    this.addMasterStyle = function(styleID, description, type, postSpacing) {
        var temp = new Style(styleID, description, type, postSpacing);
        list.push(temp);
        return temp;
    };
    this.getStyleByID = function(id) {
        for(var a = 0; a < list.length; a++) {
            if(list[a].styleID === parseInt(id)) {
                return list[a];
            }
        }
        return null;
    };
    this.getStyleArray = function() {
        var temp = [];
        for(var a = 0; a < list.length; a++) {
            temp.push({id: list[a].styleID, desc: list[a].description});
        }
        return temp;
    };
    /**
     * Will loop trough and return the Gate object from the specified styleID
     * @param styleID int - the id for the Style Object
     * @param width string - the width of the Gate
     * @param heightID int - the height of the fence
     * @returns {Style.gates}
     */
    this.getGateByWidthHeight = function(styleID, width, heightID) {
        for(var a = 0; a < list.length; a++) {
            var Style = list[a];
            if(Style.styleID === parseInt(styleID)) {
                for (var b = 0; b < Style.gates.length; b++) {
                    var gate = Style.gates[b];
                    if (gate.width === width && gate.heightID === parseInt(heightID)) {
                        return gate;
                    }
                }
            }
        }
        return null;
    };



}

function Styles() {
    var allStyles = [];

    function Style(id) {
        this.id = parseInt(id);
        this.type = null;               //Type of fence i.e wood, alum
        this.styleID = null;            //the style id from the database
        this.styleDescription = null;   //Textual name for the style i.e Dog Ear fence
        this.measurements = {           //Array to hold each measurement
            frontRight: 0,
            right: 0,
            back: 0,
            left: 0,
            frontLeft: 0,
            extra1: 0,
            extra2: 0,
            extra3: 0};
        this.totalFeetOfFence = 0;      //Total feet of fence
        this.pricePerFoot = 0.00;       //price per foot for fence
        this.heightID = null;           //Height ID from the database
        this.height = 0;                //Height of the fence
        this.postTopID = null;          //Post top ID from database
        this.postTopDescription = null; //The textual description of the type of post top
        this.postSpacing = 0;           //The distance between each post
        this.subTotal = 0.00;           //Value to hold the total for this style of fence
        this.el = {                     //elements, place to keep track of objects to update dynamicly
            style: {
                subtotal: null,
                totalFeet: null,

                pricePerFoot: null,
                pricePerFootAmount: null,

                gate4FootPrice: null,
                gate4FootAmount: null,

                gate5FootPrice: null,
                gate5FootAmount: null,

                gate8FootPrice: null,
                gate8FootAmount: null,

                gate10FootPrice: null,
                gate10FootAmount: null,

                endPostsPrice: null,
                endPostsAmount: null,

                cornerPostsPrice: null,
                cornerPostsAmount: null,

                gatePostsPrice: null,
                gatePostsAmount: null,

                postTopsQty:null,
                postTopsPrice: null,
                postTopsAmount: null,

                temporaryFenceAmount: null,
                temporaryFencePrice: null,

                removalOldFencePrice: null,
                removalOldFenceAmount: null,

                permitPrice: null,
                permitAmount: null,

                removableSectionPrice: null,
                removableSectionAmount: null,

                haulAwayDirtPrice: null,
                haulAwayDirtAmount: null,

                upgradedLatchPrice: null,
                upgradedLatchAmount: null,
                
                upgradedHingePrice: null,
                upgradedHingeAmount: null
            }
        };

        //Gates
        this.gate4FootPrice = 0.00;
        this.gate4FootQty = 0;
        this.gate5FootPrice = 0.00;
        this.gate5FootQty = 0;
        this.gate8FootPrice = 0.00;
        this.gate8FootQty = 0;
        this.gate10FootPrice = 0.00;
        this.gate10FootQty = 0;

        //Misc Prices
        this.endPostsPrice = 0.00;
        this.endPostsQty = 0;
        this.cornerPostsPrice = 0.00;
        this.cornerPostsQty = 0;
        this.gatePostsPrice = 0.00;
        this.gatePostsQty = 0;
        this.postTopsPrice = 0.00;
        this.postTopsQty = 0;
        this.temporaryFencePrice = 0.00;
        this.temporaryFenceQty = 0;
        this.removalOldFencePrice = 0.00;
        this.removalOldFenceQty = 0;
        this.permitPrice = 0.00;
        this.permitQty = 0;
        this.removableSectionPrice = 0.00;
        this.removableSectionQty = 0;
        this.haulAwayDirtPrice = 0.00;
        this.haulAwayDirtQty = 0;
        this.upgradedLatchPrice = 0.00;
        this.upgradedLatchQty = 0;
        this.upgradedHingePrice = 0.00;
        this.upgradedHingeQty = 0;

    }

    Style.prototype = {
        setStyleOfFence: function(styleID, styleDescription, type, postSpacing) {
            this.styleID = parseInt(styleID);
            this.styleDescription = String(styleDescription);
            this.type = String(type).toLowerCase();
            this.postSpacing = parseFloat(postSpacing);
            this.setPricePerFoot(0); //reset the price per foot and amount if style is changed
            this.updatePricePerFootAmount();
        },
        setHeight: function(heightID, height) {
            this.heightID = parseInt(heightID);
            this.height = parseInt(height);
        },
        setPostTop: function(postTopID) {
            this.postTopID = parseInt(postTopID);
            this.updatePostTopsAmount();
        },
        setPricePerFoot: function(pricePerFoot) {
            this.pricePerFoot = parseFloat(pricePerFoot);
            this.el.style.pricePerFoot.html(parseFloat(this.pricePerFoot).toFixed(2));
            this.updatePricePerFootAmount();
        },
        updatePricePerFootAmount: function() {
            this.el.style.pricePerFootAmount.html(parseFloat(this.totalFeetOfFence*this.pricePerFoot).toFixed(2));
            this.updateSubTotal();
        },
        setMeasurement: function(side, measurement) {
            switch(side) {
                case "frontRight":
                    this.measurements.frontRight = parseInt(measurement);
                    break;
                case "right":
                    this.measurements.right = parseInt(measurement);
                    break;
                case "back":
                    this.measurements.back = parseInt(measurement);
                    break;
                case "left":
                    this.measurements.left = parseInt(measurement);
                    break;
                case "frontLeft":
                    this.measurements.frontLeft = parseInt(measurement);
                    break;
                case "extra1":
                    this.measurements.extra1 = parseInt(measurement);
                    break;
                case "extra2":
                    this.measurements.extra2 = parseInt(measurement);
                    break;
                case "extra3":
                    this.measurements.extra3 = parseInt(measurement);
                    break;
            }


            this.totalFeetOfFence = this.measurements.frontRight + this.measurements.right + this.measurements.back + this.measurements.left +
                this.measurements.frontLeft + this.measurements.extra1 + this.measurements.extra2 + this.measurements.extra3;

            this.el.style.totalFeet.html(this.totalFeetOfFence);
            this.updatePricePerFootAmount();
        },

        setGate4FootQty: function(qty) {
            this.gate4FootQty = parseInt(qty);
            this.updateGate4FootAmount();
        },
        setGate4FootPrice: function(price) {
            this.gate4FootPrice = parseFloat(price);
            this.el.style.gate4FootPrice.html(this.gate4FootPrice.toFixed(2));
            this.updateGate4FootAmount();
        },
        updateGate4FootAmount: function() {
            this.el.style.gate4FootAmount.html(parseFloat(this.gate4FootQty*this.gate4FootPrice).toFixed(2));
            this.updateSubTotal();
        },

        setGate5FootQty: function(qty) {
            this.gate5FootQty = parseInt(qty);
            this.updateGate5FootAmount();
        },
        setGate5FootPrice: function(price) {
            this.gate5FootPrice = parseFloat(price);
            this.el.style.gate5FootPrice.html(this.gate5FootPrice.toFixed(2));
            this.updateGate5FootAmount()
        },
        updateGate5FootAmount: function() {
            this.el.style.gate5FootAmount.html((this.gate5FootQty*this.gate5FootPrice).toFixed(2));
            this.updateSubTotal();
        },

        setGate8FootQty: function(qty) {
            this.gate8FootQty = parseInt(qty);
            this.updateGate8FootAmount();
        },
        setGate8FootPrice: function(price) {
            this.gate8FootPrice = parseFloat(price);
            this.el.style.gate8FootPrice.html(this.gate8FootPrice.toFixed(2));
            this.updateGate8FootAmount();
        },
        updateGate8FootAmount: function() {
            this.el.style.gate8FootAmount.html((this.gate8FootQty * this.gate8FootPrice).toFixed(2));
            this.updateSubTotal();
        },

        setGate10FootQty: function(qty) {
            this.gate10FootQty = parseInt(qty);
            this.updateGate8FootAmount();
        },
        setGate10FootPrice: function(price) {
            this.gate10FootPrice = parseFloat(price);
            this.el.style.gate10FootPrice.html(this.gate10FootPrice.toFixed(2));
            this.updateGate10FootAmount();
        },
        updateGate10FootAmount: function() {
            this.el.style.gate10FootAmount.html((this.gate10FootQty * this.gate10FootPrice).toFixed(2));
            this.updateSubTotal();
        },
        
        setEndPostsQty: function(qty) {
            this.endPostsQty = parseInt(qty);
            this.updateEndPostsAmount();
        },
        setEndPostsPrice: function(price) {
            this.endPostsPrice = parseFloat(price);
            this.el.style.endPostsPrice.html(this.endPostsPrice.toFixed(2));
            this.updateEndPostsAmount();
        },
        updateEndPostsAmount: function() {
            this.el.style.endPostsAmount.html((this.endPostsQty * this.endPostsPrice).toFixed(2));
            this.updateSubTotal();
        },

        setCornerPostsQty: function(qty) {
            this.cornerPostsQty = parseInt(qty);
            this.updateCornerPostsAmount();
        },
        setCornerPostsPrice: function(price) {
            this.cornerPostsPrice = parseFloat(price);
            this.el.style.cornerPostsPrice.html(this.cornerPostsPrice.toFixed(2));
            this.updateCornerPostsAmount();
        },
        updateCornerPostsAmount: function() {
            this.el.style.cornerPostsAmount.html((this.cornerPostsQty * this.cornerPostsPrice).toFixed(2));
            this.updateSubTotal();
        },

        setGatePostsQty: function(qty) {
            this.gatePostsQty = parseInt(qty);
            this.updateGatePostsAmount();
        },
        setGatePostsPrice: function(price) {
            this.gatePostsPrice = parseFloat(price);
            this.el.style.gatePostsPrice.html(this.gatePostsPrice.toFixed(2));
            this.updateGatePostsAmount();
        },
        updateGatePostsAmount: function() {
            this.el.style.gatePostsAmount.html((this.gatePostsQty * this.gatePostsPrice).toFixed(2));
            this.updateSubTotal();
        },

        setPostTopsQty: function(qty) {
            this.postTopsQty = parseInt(qty);
            this.updatePostTopsAmount();
        },
        setPostTopsPrice: function(price) {
            this.postTopsPrice = parseFloat(price);
            this.el.style.postTopsPrice.html(this.postTopsPrice.toFixed(2));
            this.updatePostTopsAmount();
        },
        updatePostTopsAmount: function() {
            this.el.style.postTopsAmount.html((this.postTopsQty * this.postTopsPrice).toFixed(2));
            this.updateSubTotal();
        },
        updatePostTopsQty: function() {
            this.postTopsQty = Math.ceil(this.totalFeetOfFence / this.postSpacing) + 3;
            this.el.style.postTopsQty.html(this.postTopsQty);
            this.updatePostTopsAmount();
            this.updateSubTotal();
        },

        setTemporaryFenceQty: function(qty) {
            this.temporaryFenceQty = parseInt(qty);
            this.updateTemporaryFenceAmount();
        },
        setTemporaryFencePrice: function(price) {
            this.temporaryFencePrice = parseFloat(price);
            this.el.style.temporaryFencePrice.html(this.temporaryFencePrice.toFixed(2));
            this.updateTemporaryFenceAmount();
        },
        updateTemporaryFenceAmount: function() {
            this.el.style.temporaryFenceAmount.html((this.temporaryFenceQty * this.temporaryFencePrice).toFixed(2));
            this.updateSubTotal();
        },

        setRemovalOldFenceQty: function(qty) {
            this.removalOldFenceQty = parseInt(qty);
            this.updateRemovalOldFenceAmount();
        },
        setRemovalOldFencePrice: function(price) {
            this.removalOldFencePrice = parseFloat(price);
            this.el.style.removalOldFencePrice.html(this.removalOldFencePrice.toFixed(2));
            this.updateRemovalOldFenceAmount();
        },
        updateRemovalOldFenceAmount: function() {
            this.el.style.removalOldFenceAmount.html((this.removalOldFenceQty * this.removalOldFencePrice).toFixed(2));
            this.updateSubTotal();
        },

        setPermitQty: function(qty) {
            this.permitQty = parseInt(qty);
            this.updatePermitAmount();
        },
        setPermitPrice: function(price) {
            this.permitPrice = parseFloat(price);
            this.el.style.permitPrice.html(this.permitPrice.toFixed(2));
            this.updatePermitAmount();
        },
        updatePermitAmount: function() {
            this.el.style.permitAmount.html((this.permitQty * this.permitPrice).toFixed(2));
            this.updateSubTotal();
        },

        setRemovableSectionQty: function(qty) {
            this.removableSectionQty = parseInt(qty);
            this.updateRemovableSectionAmount();
        },
        setRemovableSectionPrice: function(price) {
            this.removableSectionPrice = parseFloat(price);
            this.el.style.removableSectionPrice.html(this.removableSectionPrice.toFixed(2));
            this.updateRemovableSectionAmount();
        },
        updateRemovableSectionAmount: function() {
            this.el.style.removableSectionAmount.html((this.removableSectionQty * this.removableSectionPrice).toFixed(2));
            this.updateSubTotal();
        },

        setHaulAwayDirtQty: function(qty) {
            this.haulAwayDirtQty = parseInt(qty);
            this.updateHaulAwayDirtAmount();
        },
        setHaulAwayDirtPrice: function(price) {
            this.haulAwayDirtPrice = parseFloat(price);
            this.el.style.haulAwayDirtPrice.html(this.haulAwayDirtPrice.toFixed(2));
            this.updateHaulAwayDirtAmount();
        },
        updateHaulAwayDirtAmount: function() {
            this.el.style.haulAwayDirtAmount.html((this.haulAwayDirtQty * this.haulAwayDirtPrice).toFixed(2));
            this.updateSubTotal();
        },

        setUpgradedLatchQty: function(qty) {
            this.upgradedLatchQty = parseInt(qty);
            this.updateUpgradedLatchAmount();
        },
        setUpgradedLatchPrice: function(price) {
            this.upgradedLatchPrice = parseFloat(price);
            this.el.style.upgradedLatchPrice.html(this.upgradedLatchPrice.toFixed(2));
            this.updateUpgradedLatchAmount();
        },
        updateUpgradedLatchAmount: function() {
            this.el.style.upgradedLatchAmount.html((this.upgradedLatchQty * this.upgradedLatchPrice).toFixed(2));
            this.updateSubTotal();
        },

        setUpgradedHingeQty: function(qty) {
            this.upgradedHingeQty = parseInt(qty);
            this.updateUpgradedHingeAmount();
        },
        setUpgradedHingePrice: function(price) {
            this.upgradedHingePrice = parseFloat(price);
            this.el.style.upgradedHingePrice.html(this.upgradedHingePrice.toFixed(2));
            this.updateUpgradedHingeAmount();
        },
        updateUpgradedHingeAmount: function() {
            this.el.style.upgradedHingeAmount.html((this.upgradedHingeQty * this.upgradedHingePrice).toFixed(2));
            this.updateSubTotal();
        },


        getTotalFeetFence: function() {
            return this.totalFeetOfFence;
        },
        getPricePerFoot: function() {
            return this.pricePerFoot;
        },
        getStyleFence: function() {
            return this.styleDescription;
        },
        getStyleFenceID: function() {
            return this.styleID;
        },

        updateSubTotal: function() {
            this.subTotal = 0.00;
            this.subTotal += (this.totalFeetOfFence * this.pricePerFoot);
            this.subTotal += (this.gate4FootQty * this.gate4FootPrice);
            this.subTotal += (this.gate5FootQty * this.gate5FootPrice);
            this.subTotal += (this.gate8FootQty * this.gate8FootPrice);
            this.subTotal += (this.gate10FootQty * this.gate10FootPrice);
            this.subTotal += (this.endPostsQty * this.endPostsPrice);
            this.subTotal += (this.cornerPostsQty * this.cornerPostsPrice);
            this.subTotal += (this.gatePostsQty * this.gatePostsPrice);
            this.subTotal += (this.postTopsQty * this.postTopsPrice);
            this.subTotal += (this.temporaryFenceQty * this.temporaryFencePrice);
            this.subTotal += (this.removalOldFenceQty * this.removalOldFencePrice);
            this.subTotal += (this.permitQty * this.permitPrice);
            this.subTotal += (this.removableSectionQty * this.removableSectionPrice);
            this.subTotal += (this.haulAwayDirtQty * this.haulAwayDirtPrice);
            this.subTotal += (this.upgradedLatchQty * this.upgradedLatchPrice);
            this.subTotal += (this.upgradedHingeQty * this.upgradedHingePrice);
            
            this.el.style.subtotal.html(this.subTotal.toFixed(2));
        },
        getSubtotal: function() {
            return this.subTotal;
        }


    };

    /**
     * Will add a new style and return the instance of the created style
     * @param id
     * @returns {Style}
     */
    this.addStyle = function(id) {
        var temp = new Style(id);
        allStyles.push(temp);
        return temp;
    };
    /**
     * Will Remove the style from the list and Return the {Style} Object if needed
     * @param id int - the ID to search for and remove
     * @returns {Style}
     */
    this.removeStyle = function(id) {
        var ret = null;
        for(var a = 0; a < allStyles.length; a++) {
            if(allStyles[a].id === parseInt(id)) {
                ret = allStyles.splice(a, 1);
                break;
            }
        }
        return ret;
    };

    /**
     * Get the Style Object from the list and return it to be used
     * @param id int - Style ID to search for
     * @returns {Style}
     */
    this.getStyle = function(id) {
        var ret = null;
        for(var a = 0; a < allStyles.length; a++) {
            if(allStyles[a].id === parseInt(id)) {
                ret = allStyles[a];
                break;
            }
        }
        return ret;
    };

}

"use strict";
var quote = {

    Customer: new Customer(),
    MasterStyleList: new MasterStyleList(),
    numOfStyles: 0,
    Styles: new Styles(),
    postTops: [],
    miscPrices: [],
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
    /**
     * Will display an error message if one is needed to be displayed.
     * @param txt String - The message to display to the user
     * @param level String - the level to display the message (based off Bootstrap) warning, danger, info, success
     * @param time int - the time to display the message, default is the txt length times 400ms
     */
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
    /**
     * Will clear the error message Timeout
     */
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
        self.numOfStyles++;
        var Style = self.Styles.addStyle(self.numOfStyles);
        var measurements = jQuery('div#measurements div#stylesDiv');
        var styleDiv = jQuery('div#styles div#stylesDiv');

        /**
         * Measurement
         */
        var html =
            '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" data-id="'+Style.id+'">' +
                '<fieldset>' +
                    '<legend>Style '+Style.id+'</legend>' +
                    '<div class="row">' +
                        '<div class="col-12"><label for="frontRight">Front Right</label><input type="number" id="frontRight" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="right">Right Side</label><input type="number" id="right" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="back">Back</label><input type="number" id="back" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="left">Left Side</label><input type="number" id="left" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="frontLeft">Front Left</label><input type="number" id="frontLeft" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="extra1">Extra 1</label><input type="number" id="extra1" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="extra2">Extra 2</label><input type="number" id="extra2" pattern="\\d*" class="form-control" /> </div>' +
                        '<div class="col-12"><label for="extra3">Extra 3</label><input type="number" id="extra3" pattern="\\d*" class="form-control" /> </div>' +
                    '</div> '+
                    '<div class="row" style="margin-top:1em;"> ' +
                        '<div id="totalFeet" class="col-12">Total: ' + Style.getTotalFeetFence() + '</div>' +
                    '</div>' +
                '</fieldset>' +
            '</div>';
        measurements.append(html);
        var thisMeasurement = measurements.find('[data-id="'+Style.id+'"]');
        thisMeasurement.find('input').each(function() {
            jQuery(this).on('keyup', function(e) {
                if(e.keyCode === 9 || e.keyCode === 13) { //Ignore TAB and ENTER keys
                    return;
                }
                var value = jQuery(this).val();
                var thisJ = jQuery(this);
                var Reg = new MyReg();
                Reg.setTestStr(value);
                if(value !== null && !Reg.numeric()) {
                    thisJ.addClass('is-invalid');
                }
                else {
                    thisJ.removeClass('is-invalid');
                }
                Style.setMeasurement(thisJ.attr('id'), value);
                thisMeasurement.find('div#totalFeet').html('Total: ' + Style.getTotalFeetFence());
            });
        });


        /**
         * Style
         */

        html = '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" data-id="'+Style.id+'">' +
            '<fieldset>' +
            '<legend>Style ' + Style.id + '</legend>' +
            '<div class="row">' +
                '<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">' +
                '<label for="styleFence">Style</label><select id="styleFence" class="form-control"><option value="none">--Select--</option>';
                var styles = self.MasterStyleList.getStyleArray();
                for(var a = 0; a < styles.length; a++) {
                    html += ' <option value="' + styles[a].id + '">' + styles[a].desc + '</option>';
                }
        html +=     '</select> ' +
                '</div> ' +
                '<div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-offset-1 col-sm-4 col-xs-12">' +
                    '<label for="styleHeight">Height</label><select id="styleHeight" class="form-control"><option value="none">--Select--</option> </select> ' +
                '</div> ' +
            '</div> ' +
            '<div class="row">' +
                '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive"> '+
                    '<table class="table table-bordered table-sm">' +
                        '<thead><tr><th>Qty</th><th>Description</th><th>Unit Price</th><th>Amount</th></tr></thead>' +
                        '<tbody>' +
                        //Total Feet
                            '<tr><td id="Style' +Style.id+'TotalFeet">0</td><td>Ft Fence</td><td id="Style' +Style.id+'PricePerFoot">0.00</td><td id="Style'+Style.id+'PricePerFootAmount">0.00</td></tr>' +
                        //Gates
                            //4 Foot Wide
                            '<tr><td><select id="Style'+Style.id+'Gate4Foot" class="form-control">';
                            for(var a = 0; a < 11; a++) { html+= '<option value="' +a+'">'+a+'</option>';}
        html +=             '</select> </td><td>Gate 4 Foot Wide</td><td id="Style'+Style.id+'Gate4FootPrice">0.00</td><td id="Style'+Style.id+'Gate4FootAmount">0.00</td></tr>' +
                            //5 Foot Wide
                            '<tr><td><select id="Style'+Style.id+'Gate5Foot" class="form-control">';
                            for(a = 0; a < 11; a++) { html+= '<option value="' +a+'">'+a+'</option>';}
        html +=             '</select> </td><td>Gate 5 Foot Wide</td><td id="Style'+Style.id+'Gate5FootPrice">0.00</td><td id="Style'+Style.id+'Gate5FootAmount">0.00</td></tr>' +
                            //8 Foot Wide
                            '<tr><td><select id="Style'+Style.id+'Gate8Foot" class="form-control">';
                            for( a = 0; a < 11; a++) { html+='<option value="'+a+'">'+a+'</option>';}
        html+=              '</select></td><td>Double Drive 8 Foot Wide</td><td id="Style'+Style.id+'Gate8FootPrice">0.00</td><td id="Style'+Style.id+'Gate8FootAmount">0.00</td></tr>' +
                            //10 Foot Wide
                            '<tr><td><select id="Style'+Style.id+'Gate10Foot" class="form-control">';
                            for(a = 0; a < 11; a++) { html+='<option value="'+a+'">'+a+'</option>';}
        html +=              '</select></td><td>Double Drive 10 Foot Wide</td><td id="Style'+Style.id+'Gate10FootPrice">0.00</td><td id="Style'+Style.id+'Gate10FootAmount">0.00</td></tr>' +
                        //End, gate, corner posts
                            //End Posts
                            '<tr><td><select id="Style'+Style.id+'EndPosts" class="form-control">';
                            for(a = 0; a < 11; a++) { html +='<option value="'+a+'">'+a+'</option>';}
        html +=             '</select></td><td>End Posts</td><td id="Style'+Style.id+'EndPostsPrice">0.00</td><td id="Style'+Style.id+'EndPostsAmount">0.00</td></tr>' +
                            //Corner Posts
                            '<tr><td><select id="Style'+Style.id+'CornerPosts" class="form-control">';
                            for(a = 0; a < 11; a++) { html +='<option value="'+a+'">'+a+'</option>';}
        html +=             '</select></td><td>Corner Posts</td><td id="Style'+Style.id+'CornerPostsPrice">0.00</td><td id="Style'+Style.id+'CornerPostsAmount">0.00</td></tr>' +
                            //Gate Posts
                            '<tr><td><select id="Style'+Style.id+'GatePosts" class="form-control">';
                            for(a = 0; a < 11; a++) { html +='<option value="'+a+'">'+a+'</option>';}
        html +=             '</select></td><td>Gate Posts</td><td id="Style'+Style.id+'GatePostsPrice">0.00</td><td id="Style'+Style.id+'GatePostsAmount">0.00</td></tr>' +
                        //Post Tops
                            '<tr><td id="Style'+Style.id+'PostTopsQty">0</td><td>Post Tops <select id="Style'+Style.id+'PostTopsSelect" class="form-control">';
                            for(a = 0; a < self.postTops.length; a++) { html += '<option value="'+self.postTops[a].id+'">'+self.postTops[a].description+'</option>';}
        html +=             '</select></td><td id="Style'+Style.id+'PostTopsPrice">0.00</td><td id="Style'+Style.id+'PostTopsAmount">0.00</td></tr>' +
                        //Temporary Fence
                            '<tr><td><input type="number" id="Style'+Style.id+'TemporaryFence" pattern="\\d*" placeholder="Qty" class="form-control"></td><td>Temporary Fence</td>' +
                            '<td id="Style'+Style.id+'TemporaryFencePrice">0.00</td><td id="Style'+Style.id+'TemporaryFenceAmount">0.00</td></tr>' +
                        //Removal Old Fence
                            '<tr><td><input type="number" id="Style'+Style.id+'RemovalOldFence" pattern="\\d*" placeholder="Qty" class="form-control"></td><td>Removal Old Fence</td>' +
                            '<td id="Style'+Style.id+'RemovalOldFencePrice">0.00</td><td id="Style'+Style.id+'RemovalOldFenceAmount">0.00</td></tr>' +
                        //Permit
                            '<tr><td><select id="Style'+Style.id+'Permit" class="form-control">';
                            for(a = 0; a < 2; a++) { html +='<option value="'+a+'">'+a+'</option>'; }
        html +=             '</select></td><td>Permit</td><td id="Style'+Style.id+'PermitPrice">0.00</td><td id="Style'+Style.id+'PermitAmount">0.00</td></tr>' +
                        //Removable Section
                            '<tr><td><select id="Style'+Style.id+'RemovableSection" class="form-control">';
                            for(a = 0; a < 6; a++) { html +='<option value="'+a+'">'+a+'</option>'; }
        html +=             '</select></td><td>Removable Section</td><td id="Style'+Style.id+'RemovableSectionPrice">0.00</td><td id="Style'+Style.id+'RemovableSectionAmount">0.00</td></tr>' +
                        //Haul Away Dirt
                            '<tr><td><select id="Style'+Style.id+'HaulAwayDirt" class="form-control">';
                            for(a = 0; a < 6; a++) { html +='<option value="'+a+'">'+a+'</option>'; }
        html +=             '</select></td><td>Haul Away Dirt</td><td id="Style'+Style.id+'HaulAwayDirtPrice">0.00</td><td id="Style'+Style.id+'HaulAwayDirtAmount">0.00</td></tr>' +
                        //Upgraded Latch
                            '<tr><td><select id="Style'+Style.id+'UpgradedLatch" class="form-control">';
                            for(a = 0; a < 6; a++) { html +='<option value="'+a+'">'+a+'</option>'; }
        html +=             '</select></td><td>Upgraded Latch</td><td id="Style'+Style.id+'UpgradedLatchPrice">0.00</td><td id="Style'+Style.id+'UpgradedLatchAmount">0.00</td></tr>' +
                        //Upgraded Hinge
                            '<tr><td><select id="Style'+Style.id+'UpgradedHinge" class="form-control">';
                            for(a = 0; a < 6; a++) { html +='<option value="'+a+'">'+a+'</option>'; }
        html +=             '</select></td><td>Upgraded Hinge</td><td id="Style'+Style.id+'UpgradedHingePrice">0.00</td><td id="Style'+Style.id+'UpgradedHingeAmount">0.00</td></tr>' +


            '<tr><td colspan="3" class="text-right">Subtotal</td><td id="Style'+Style.id+'SubTotal">0.00</td> </tr>' +
                        '</tbody>' +
                    '</table> ' +
                '</div>' +
            '</div> ' +
            '</fieldset>' +
            '</div>';
        styleDiv.append(html);
        var thisStyleDiv = styleDiv.find('[data-id="'+Style.id+'"]');
        //initaliaze all popovers
        jQuery('[data-toggle="popover"]').popover();
    //Generate the Height from the selection of the Style
        thisStyleDiv.find('select#styleFence').on('change', function() {
            var val = jQuery(this).val();
            var height = thisStyleDiv.find('select#styleHeight');
            if(val === 'none') {
                height.html('<option value="none">--Select--</option>');
                return;
            }
            var StyleFence = self.MasterStyleList.getStyleByID(val);
            Style.setStyleOfFence(StyleFence.styleID, StyleFence.styleDescription, StyleFence.type, StyleFence.postSpacing);
            var heights = StyleFence.getAllHeights();
            var html = '<option value="none">--Select--</option>';
            for(var a = 0; a < heights.length; a++) {
                html += '<option value="' + heights[a].id + '">' + heights[a].height + '</option>';
            }
            height.html(html);
            if(Style.type === "wood") {
                Style.updatePostTopsQty();
            }
            else {
                Style.setPostTopsQty(0);
            }

        });
        thisStyleDiv.find('select#styleHeight').on('change', function() {
            var val = jQuery(this).val();
            if(val === 'none')
                return;
            var styleFenceID = thisStyleDiv.find('select#styleFence').val();
            var StyleFence = self.MasterStyleList.getStyleByID(styleFenceID);
            var Height = StyleFence.getHeightByID(val);
            Style.setHeight(Height.heightID, Height.height);
            Style.setPricePerFoot(Height.pricePerFoot);
        });
    //Place the total feet in Style for reference
        Style.el.style.totalFeet = thisStyleDiv.find('table td#Style'+Style.id+'TotalFeet');
        Style.el.style.pricePerFoot = thisStyleDiv.find('table td#Style'+Style.id+'PricePerFoot');
        Style.el.style.pricePerFootAmount = thisStyleDiv.find('table td#Style'+Style.id+'PricePerFootAmount');
//4 Foot Wide Gate
        thisStyleDiv.find('table select#Style'+Style.id+'Gate4Foot').on('change', function() {
            var value = jQuery(this).val();
            var gate = self.MasterStyleList.getGateByWidthHeight(Style.id, "4", Style.heightID);
            Style.setGate4FootQty(value);
            Style.setGate4FootPrice(gate.price);
        });
        Style.el.style.gate4FootPrice = thisStyleDiv.find('table td#Style'+Style.id+'Gate4FootPrice');
        Style.el.style.gate4FootAmount = thisStyleDiv.find('table td#Style'+Style.id+'Gate4FootAmount');
//5 Foot Wide gate
        thisStyleDiv.find('table select#Style'+Style.id+'Gate5Foot').on('change', function() {
            var value = jQuery(this).val();
            var gate = self.MasterStyleList.getGateByWidthHeight(Style.id, "5", Style.heightID);
            Style.setGate5FootQty(value);
            Style.setGate5FootPrice(gate.price);
        });
        Style.el.style.gate5FootPrice = thisStyleDiv.find('table td#Style'+Style.id+'Gate5FootPrice');
        Style.el.style.gate5FootAmount = thisStyleDiv.find('table td#Style'+Style.id+'Gate5FootAmount');
//8 Foot Wide Gate
        thisStyleDiv.find('table select#Style'+Style.id+'Gate8Foot').on('change', function() {
            var value = jQuery(this).val();
            var gate = self.MasterStyleList.getGateByWidthHeight(Style.id, "8", Style.heightID);
            Style.setGate8FootQty(value);
            Style.setGate8FootPrice(gate.price);
        });
        Style.el.style.gate8FootPrice = thisStyleDiv.find('table td#Style'+Style.id+'Gate8FootPrice');
        Style.el.style.gate8FootAmount = thisStyleDiv.find('table td#Style'+Style.id+'Gate8FootAmount');
//10 Foot Wide Gate
        thisStyleDiv.find('table select#Style'+Style.id+'Gate10Foot').on('change', function() {
            var value = jQuery(this).val();
            var gate = self.MasterStyleList.getGateByWidthHeight(Style.id, "10", Style.heightID);
            Style.setGate10FootQty(value);
            Style.setGate10FootPrice(gate.price);
        });
        Style.el.style.gate10FootPrice = thisStyleDiv.find('table td#Style'+Style.id+'Gate10FootPrice');
        Style.el.style.gate10FootAmount = thisStyleDiv.find('table td#Style'+Style.id+'Gate10FootAmount');
//End Posts
        thisStyleDiv.find('table select#Style'+Style.id+'EndPosts').on('change', function() {
            var value = jQuery(this).val();
            Style.setEndPostsQty(value);
            var reg = new RegExp(/^End\s?Posts$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setEndPostsPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.endPostsPrice = thisStyleDiv.find('table td#Style'+Style.id+'EndPostsPrice');
        Style.el.style.endPostsAmount = thisStyleDiv.find('table td#Style'+Style.id+'EndPostsAmount');
//Corner Posts
        thisStyleDiv.find('table select#Style'+Style.id+'CornerPosts').on('change', function() {
            var value = jQuery(this).val();
            Style.setCornerPostsQty(value);
            var reg = new RegExp(/^Corner\s?(Posts|Post)$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setCornerPostsPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.cornerPostsPrice = thisStyleDiv.find('table td#Style'+Style.id+'CornerPostsPrice');
        Style.el.style.cornerPostsAmount = thisStyleDiv.find('table td#Style'+Style.id+'CornerPostsAmount');
//Gate Posts
        thisStyleDiv.find('table select#Style'+Style.id+'GatePosts').on('change', function() {
            var value = jQuery(this).val();
            Style.setGatePostsQty(value);
            var reg = new RegExp(/^Gate\s?(Posts|Post)$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setGatePostsPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.gatePostsPrice = thisStyleDiv.find('table td#Style'+Style.id+'GatePostsPrice');
        Style.el.style.gatePostsAmount = thisStyleDiv.find('table td#Style'+Style.id+'GatePostsAmount');
//Post Tops
        thisStyleDiv.find('table select#Style'+Style.id+'PostTopsSelect').on('change', function() {
            var value = jQuery(this).val();
            Style.setPostTop(value);
            for(var a = 0; a < self.postTops.length; a++) {
                if(self.postTops[a].id === parseInt(value)) {
                    Style.setPostTopsPrice(self.postTops[a].price);
                    break;
                }
            }
        });
        Style.el.style.postTopsPrice = thisStyleDiv.find('table td#Style'+Style.id+'PostTopsPrice');
        Style.el.style.postTopsAmount = thisStyleDiv.find('table td#Style'+Style.id+'PostTopsAmount');
        Style.el.style.postTopsQty = thisStyleDiv.find('table td#Style'+Style.id+'PostTopsQty');
//Temporary Fence
        thisStyleDiv.find('table input#Style'+Style.id+'TemporaryFence').on('keyup', function(e) {
            var value = jQuery(this).val();
            if(e.keyCode === 9 || e.keyCode === 13) {
                return; //Don't do anything
            }
            var Reg = new MyReg(value);
            if(!Reg.numeric()) {
                jQuery(this).addClass('is-invalid');
                Style.setTemporaryFencePrice(0);
                return;
            }
            else {
                jQuery(this).removeClass('is-invalid');
            }
            Style.setTemporaryFenceQty(value);
            var reg = new RegExp(/^Temporary\s?Fence$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setTemporaryFencePrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.temporaryFencePrice = thisStyleDiv.find('table td#Style'+Style.id+'TemporaryFencePrice');
        Style.el.style.temporaryFenceAmount = thisStyleDiv.find('table td#Style'+Style.id+'TemporaryFenceAmount');
//Removal Old Fence
        thisStyleDiv.find('table input#Style'+Style.id+'RemovalOldFence').on('keyup', function(e) {
            var value = jQuery(this).val();
            if(e.keyCode === 9 || e.keyCode === 13) {
                return; //Don't do anything
            }
            var Reg = new MyReg(value);
            if(!Reg.numeric()) {
                jQuery(this).addClass('is-invalid');
                Style.setRemovalOldFencePrice(0);
                return;
            }
            else {
                jQuery(this).removeClass('is-invalid');
            }
            Style.setRemovalOldFenceQty(value);
            var reg = new RegExp(/^Removal\s?Old\s?Fence$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setRemovalOldFencePrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.removalOldFencePrice = thisStyleDiv.find('table td#Style'+Style.id+'RemovalOldFencePrice');
        Style.el.style.removalOldFenceAmount = thisStyleDiv.find('table td#Style'+Style.id+'RemovalOldFenceAmount');
//Permit
        thisStyleDiv.find('table select#Style'+Style.id+'Permit').on('change', function() {
            var value = jQuery(this).val();
            Style.setPermitQty(value);
            var reg = new RegExp(/^Permit$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setPermitPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.permitPrice = thisStyleDiv.find('table td#Style'+Style.id+'PermitPrice');
        Style.el.style.permitAmount = thisStyleDiv.find('table td#Style'+Style.id+'PermitAmount');
//Removable Section
        thisStyleDiv.find('table select#Style'+Style.id+'RemovableSection').on('change', function() {
            var value = jQuery(this).val();
            Style.setRemovableSectionQty(value);
            var reg = new RegExp(/^Removable\s?Section$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setRemovableSectionPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.removableSectionPrice = thisStyleDiv.find('table td#Style'+Style.id+'RemovableSectionPrice');
        Style.el.style.removableSectionAmount = thisStyleDiv.find('table td#Style'+Style.id+'RemovableSectionAmount');
//Haul Away Dirt
        thisStyleDiv.find('table select#Style'+Style.id+'HaulAwayDirt').on('change', function() {
            var value = jQuery(this).val();
            Style.setHaulAwayDirtQty(value);
            var reg = new RegExp(/^Haul\s?Away\s?Dirt$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setHaulAwayDirtPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.haulAwayDirtPrice = thisStyleDiv.find('table td#Style'+Style.id+'HaulAwayDirtPrice');
        Style.el.style.haulAwayDirtAmount = thisStyleDiv.find('table td#Style'+Style.id+'HaulAwayDirtAmount');
//Upgraded Latch
        thisStyleDiv.find('table select#Style'+Style.id+'UpgradedLatch').on('change', function() {
            var value = jQuery(this).val();
            Style.setUpgradedLatchQty(value);
            var reg = new RegExp(/^Upgraded\s?Latch$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setUpgradedLatchPrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.upgradedLatchPrice = thisStyleDiv.find('table td#Style'+Style.id+'UpgradedLatchPrice');
        Style.el.style.upgradedLatchAmount = thisStyleDiv.find('table td#Style'+Style.id+'UpgradedLatchAmount');
//Upgraded Hinge
        thisStyleDiv.find('table select#Style'+Style.id+'UpgradedHinge').on('change', function() {
            var value = jQuery(this).val();
            Style.setUpgradedHingeQty(value);
            var reg = new RegExp(/^Upgraded\s?Hinge$/i);
            for(var a = 0; a < self.miscPrices.length; a++) {
                if(reg.test(self.miscPrices[a].description)) {
                    Style.setUpgradedHingePrice(self.miscPrices[a].price);
                    return;
                }
            }
        });
        Style.el.style.upgradedHingePrice = thisStyleDiv.find('table td#Style'+Style.id+'UpgradedHingePrice');
        Style.el.style.upgradedHingeAmount = thisStyleDiv.find('table td#Style'+Style.id+'UpgradedHingeAmount');

        Style.el.style.subtotal = thisStyleDiv.find('table td#Style'+Style.id+'SubTotal');

    },
    removeStyleMeasurement : function(id) {
        var self = this;

    }

};