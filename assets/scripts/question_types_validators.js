// Utilities
// ==========

// check if element has class
function hasClass(element, className) {
    return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

// Mutually exclusive checkbox  and select/unselect all checkbox logic
// ====================================================================

function twoDecimalLimit(element) {
  var t = element.value;
  element.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
}


//checks and unchecks all checkboxes by presing checkbox x
function toggle(x) {
    var checkboxes = document.getElementsByClassName('all-options');
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = x.checked;
    }
}

//if you select the mutually exclusive checkbox all the other are unchecked
function uncheckAll() {
    var checkboxes = document.getElementsByClassName('all-options');
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = false;
    }
}

//if you select one of the inputs the mutually exclusive checkbox is unchecked
function uncheckMExclusive() {
    var checkbox = document.getElementsByClassName('e-button')[0];
    checkbox.checked = false;
}


// When no input is provided from the user this function
// blocks the continue button and displays errors.
function validate() {
    // checkbox and radio buttons validation
    var isValid           = false;
    var formLayout        = document.getElementById('form-layout');
    var errorNoSelection  = document.getElementById('no-selection');

    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked) isValid = true;
    }

    if (isValid === false) {
        formLayout.classList.add('govuk-form-group--error');
        errorNoSelection.style.display = 'block';
        errorNoSelection.textContent = "You need to select something.";
    }

    // conditional input validation logic
    var errorNoInput      = document.getElementById('no-input');
    var conditionalInputs = document.getElementsByClassName('conditional-input');

    // I check all conditional inputs,
    // if one is not hidden we check if it has a value
    for(var i in conditionalInputs) {
        if (Number(i)) {
            if (!hasClass(conditionalInputs[i], 'govuk-radios__conditional--hidden')) {
                var inputElement = conditionalInputs[i].firstElementChild.childNodes[7];
                // if it does not have a value we throw errors and block button
                if (!inputElement.value.trim() || parseFloat(inputElement.value.trim()) <= 0) {
                    isValid = false;
                    inputElement.classList.add('govuk-input--error');
                    formLayout.classList.add('govuk-form-group--error');
                    conditionalInputs[i].style.borderLeftColor ='#b10e1e';
                    errorNoInput.style.display = 'block';
                    errorNoSelection.style.display = 'none';
                    errorNoInput.textContent = "Please provide input.";
                    if (parseFloat(inputElement.value.trim()) <= 0) {
                        errorNoInput.textContent = "Enter a value greater than zero.";
                    }
                }
            }    
        }
    }

    return isValid;
}

//this block of code needs some improvment
function resetErrors(radio) {
    document.getElementById('no-selection').style.display = 'none';
    document.getElementById('no-input').style.display = 'none';
    document.getElementById('form-layout').classList.remove('govuk-form-group--error');
    document.getElementById('conditional-'.concat(radio.value)).style.borderLeftColor ='#bfc1c3';
    document.getElementById('input-'.concat(radio.value)).classList.remove('govuk-input--error')
}

window.onload = function(){
    setTimeout(function(){
    var conditionalInputs = document.getElementsByClassName('conditional-input');
    for(var i in conditionalInputs) {
        if (hasClass(conditionalInputs[i], 'govuk-radios__conditional--hidden')) {
            var inputElement = conditionalInputs[i].firstElementChild.childNodes[7];
            if (inputElement.value) {
                conditionalInputs[i].classList.remove("govuk-radios__conditional--hidden");
            }
        }    
        
    }
}, 200);
};