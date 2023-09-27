// Utilities
// ==========

// check if element has class
function hasClass(element, className) {
    return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}


function twoDecimalLimit(element) {
  var t = element.value;
  element.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
}

// Mutually exclusive checkbox  and select/unselect all checkbox logic
// ====================================================================


//checks and unchecks all checkboxes by presing checkbox x
function toggle() {
    var checkboxes = document.getElementsByClassName('all-options');
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = x.checked;
    }
}

//if you select the mutually exclusive checkbox all the other are unchecked
function uncheckAll(target) {
    var checkboxes = document.getElementsByClassName('govuk-checkboxes__input');
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = false;
    }
    target.checked = true;
}

//if you select one of the inputs the mutually exclusive checkbox is unchecked
function uncheckMExclusive() {
    var checkbox = document.getElementsByClassName('e-button');
    for (var i = checkbox.length - 1; i >= 0; i--) {
        checkbox[i].checked = false;
    }
}


// When no input is provided from the user this function
// blocks the continue button and displays errors.
function validate() {

    //goes to all errors and resets them on refresh or load
    var errors = document.getElementsByClassName("resetErrors");
    for (var i = 0; i < errors.length; i++) {
        if (!errors.item(i).classList.contains("apiErrorsMsg")) {
            errors.item(i).classList.add("apiErrorsMsg");
        }
    }
    // checkbox and radio buttons validation
    var isValid           = false;

    var NO_SELECTION  = 'noSelection';
    var NO_VALUE      = 'noValue';
    var CHECK_NUMBER  = 'checkNumber';
    var CHECK_WHOLE_NUMBER  = 'checkWholeNumber';
    var   errorType = '';

    var formLayout        = document.getElementById('form-layout');
    var errorNoSelection  = document.getElementById('no-selection');
    var errorSummary      = document.getElementById('error-summary');
    var errorSummaryContainer  = document.getElementById('error-summary-container');
    errorNoSelection.style.display = "none";
    errorSummary.style.display = "none";
    var gmUUID = null;
    var answer = null;

    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked){
            isValid = true;
            gmUUID  = document.getElementsByClassName('v-selector')[i].value.substring(0,36);
            answer  = document.getElementsByClassName('v-selector')[i].value.substring(37);
            break;
        }
    }

    if (isValid === false) {

        formLayout.classList.add('govuk-form-group--error');
        errorNoSelection.style.display = 'block';
        errorSummary.style.display = 'block';        
        var Forminputs = document.getElementsByClassName("govuk-radios__input");
        errorType = NO_SELECTION;
        
        setTimeout(function(){
            
            var isSelected = false;
           
            for(var i in Forminputs){
                if(Forminputs[i].checked){
                    isSelected = true;
                }
            }

            if(!isSelected){
                Forminputs[0].focus();
            }
        }, 2000);

    }

    // conditional input validation logic
    var conditionalInput = document.getElementsByClassName('conditional-input')[0];

    // I check all conditional inputs,
    // if one is not hidden we check if it has a value
    if (typeof conditionalInput !== "undefined") {
        if (!hasClass(conditionalInput, 'govuk-radios__conditional--hidden')) {
            var inputElement = document.getElementsByClassName('conditional-input-selector')[0];
            // if it does not have a value we throw errors and block button
            if (!inputElement.value.trim() || parseFloat(inputElement.value.trim()) <= 0 || isNaN(inputElement.value)) {
                isValid = false;
                inputElement.classList.add('govuk-input--error');
                formLayout.classList.add('govuk-form-group--error');
                conditionalInput.style.borderLeftColor ='#b10e1e';
                errorSummary.style.display = 'block'; 
                errorNoSelection.style.display = 'block';
                errorType = NO_VALUE;
                if (isNaN(inputElement.value)) {
                    errorType = CHECK_NUMBER;
                }
                if (parseFloat(inputElement.value.trim()) <= 0) {
                    errorType = CHECK_WHOLE_NUMBER;
                }
            }
        }  
    }  

    var errors = document.getElementsByClassName("resetErrors");
    var found = false;
    for (var i = 0; i < errors.length; i++) {
        if (errors.item(i).dataset.errorType == errorType) {
            found = true;
            break;
        }
    }
    if (found == false) {
        errorType = CHECK_NUMBER;
    }

    for (var i = 0; i < errors.length; i++) {
        if (errors.item(i).dataset.errorType == errorType) {
            errors.item(i).classList.remove("apiErrorsMsg");
        }
    }

    if (isValid){
        pushDataLayer('continue: User selected '+ gmUUID, 'Continue', null, answer);
    } else {
        pushDataLayer('error', 'Continue', 'Select which area suits your requirements', answer);
    }

    return isValid;
}

//this block of code needs some improvment
function resetErrors(radio) {
    document.getElementById('no-selection').style.display = 'none';
    document.getElementById('no-input').style.display = 'none';
    document.getElementById('error-summary').style.display = 'none';
    document.getElementById('form-layout').classList.remove('govuk-form-group--error');
    document.getElementById('conditional-'.concat(radio.value)).style.borderLeftColor ='#bfc1c3';
    document.getElementById('input-'.concat(radio.value)).classList.remove('govuk-input--error')
}


window.addEventListener('load', function() {

    setTimeout(function(){
        var conditionalInputs = document.getElementsByClassName('conditional-input');
        for(var i in conditionalInputs) {
            if (hasClass(conditionalInputs[i], 'govuk-radios__conditional--hidden')) {
                var inputElement = conditionalInputs[i].firstElementChild.childNodes[7].firstElementChild;
                if (inputElement.firstElementChild.value || inputElement.lastElementChild.value) {
                    conditionalInputs[i].classList.remove("govuk-radios__conditional--hidden");
                }
            }    
        }
    }, 200);
}, false);