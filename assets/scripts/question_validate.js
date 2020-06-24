// check if element has class
function hasClass(element, className) {
    return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

// check if string is empty or null or empty space
function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}

// When no input is provided from the user this
// blocks the continue button and displays errors 
function validate() {
    // checkbox and radio buttons validation
    // ======================================
    var isValid           = false;
    var formLayout        = document.getElementById('form-layout');
    var errorNoSelection  = document.getElementById('no-selection');

    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked) isValid = true;
    }

    if (isValid === false) {
        formLayout.classList.add('govuk-form-group--error');
        errorNoSelection.style.display = 'block';
    }

    // conditional input validation logic
    // ===================================
    var errorNoInput      = document.getElementById('no-input');
    var conditionalInputs = document.getElementsByClassName('conditional-input');

    // I check all conditional inputs,
    // if one is not hidden we check if it has a value
    for(var i in conditionalInputs) {
        if (Number.isInteger(Number(i)) == true) {
            if (!hasClass(conditionalInputs[i], 'govuk-radios__conditional--hidden')) {

                // if it does not have a value we throw errors and block button
                if (isEmptyOrSpaces(conditionalInputs[i].firstElementChild.childNodes[7].value)) {
                    isValid = false;
                    conditionalInputs[i].firstElementChild.childNodes[7].classList.add('govuk-input--error');
                    formLayout.classList.add('govuk-form-group--error');
                    conditionalInputs[i].style.borderLeftColor ='#b10e1e';
                    errorNoInput.style.display = 'block';
                }
            }    
        }
    }

    return isValid;
}