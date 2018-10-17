
var car = {

    validateForm: function() {
        var passed = true;

        //Get and Check the Users name
        var name = document.getElementById('fullName');
        var reg = new RegExp(/^[a-zA-Z\.\s]{4,}$/);
        if(!reg.test(name.value)) {
            name.classList.add('is-invalid');
            passed = false;
        }
        else {
            name.classList.remove('is-invalid');
        }

        //get and check rate visit
        var rateVisit = null;
        var rateVisitElements = document.getElementsByName('rateVisit');
        rateVisitElements.forEach(function (t) {
            if(t.checked) {
                rateVisit = t;
                return;
            }
        });
        if(!rateVisit) {
            rateVisitElements[rateVisitElements.length - 1].classList.add('is-invalid');
            passed = false;
        }
        else {
            rateVisitElements[rateVisitElements.length - 1].classList.remove('is-invalid');
        }

        //get and rate salesman
        var rateSalesman = null;
        var salesmanElements = document.getElementsByName('rateSalesman');
        salesmanElements.forEach(function (t) {
            if(t.checked) {
                rateSalesman = t;
                return;
            }
        });
        if(!rateSalesman) {
            salesmanElements[salesmanElements.length - 1].classList.add('is-invalid');
            passed = false;
        }
        else {
            salesmanElements[salesmanElements.length - 1].classList.remove('is-invalid');
        }

        //get value
        var rateValue = null;
        var valueElements = document.getElementsByName('ratevalue');
        valueElements.forEach(function (t) {
            if(t.checked) {
                rateValue = t;
                return;
            }
        });
        if(!rateValue) {
            valueElements[valueElements.length - 1].classList.add('is-invalid');
            passed = false;
        }
        else {
            valueElements[valueElements.length - 1].classList.remove('is-invalid');
        }

        //get cleanliness
        var clean = null;
        var cleanElements = document.getElementsByName('cleanliness');
        cleanElements.forEach(function (t) {
            if(t.checked) {
                clean = t;
                return;
            }
        });
        if(!clean) {
            cleanElements[cleanElements.length - 1].classList.add('is-invalid');
            passed = false;
        }
        else {
            cleanElements[cleanElements.length - 1].classList.remove('is-invalid');
        }

        //refer to friend
        var refer = document.getElementById('refer');
        if(refer.value === 'null') {
            refer.classList.add('is-invalid');
            passed = false;
        }
        else {
            refer.classList.remove('is-invalid');
        }

        //maintenance
        var maintenance = document.getElementById('maintenance');
        if(maintenance.value === 'null') {
            maintenance.classList.add('is-invalid');
            passed = false;
        }
        else {
            maintenance.classList.remove('is-invalid');
        }

        //other comments /**No validation**/
        var otherComments = document.getElementById('otherComments');



        var error = document.getElementById('errorMsg');
        if(!passed) {
            window.scrollTo(0,0);
            error.classList.add('alert');
            error.classList.add('alert-danger');
            error.innerHTML ='Please correct the elements in red below.';
            error.style.display = 'block';
            return false;
        }
        else {
            error.style.display = 'none';
            this.submitForm(name.value, rateVisit.value, rateSalesman.value, rateValue.value, clean.value, refer.value, maintenance.value, otherComments.value);
        }



    },

    submitForm: function(name, visit, salesman, value, clean, refer, maintenance, other) {
        document.getElementById('submitName').innerHTML = name;
        document.getElementById('submitVisit').innerHTML = visit;
        document.getElementById('submitSalesman').innerHTML = salesman;
        document.getElementById('submitPrice').innerHTML = value;
        document.getElementById('submitClean').innerHTML = clean;
        document.getElementById('submitRefer').innerHTML = refer;
        document.getElementById('submitMaintenance').innerHTML = maintenance;
        document.getElementById('submitComments').innerHTML = other;


        $('div#submissionModal').modal('show');
        document.getElementById('survey').innerHTML = "<div class='col text-center'>Thank you " + name + '</div>';


    }

};