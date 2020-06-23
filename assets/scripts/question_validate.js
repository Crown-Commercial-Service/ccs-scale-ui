
// check if element has class
function hasClass(element, className) {
    return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

// check if string is empty or null or empty space
function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}

function validate() {
    var returnValue  = false;
    var formLayout   = document.getElementById('form-layout');
    var err          = document.getElementById('no-selection');
    var errInput     = document.getElementById('no-input');
    var inputs       = document.getElementsByClassName('conditional-input');

    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked) returnValue = true;
    }

    if (returnValue === false) {
        formLayout.classList.add('govuk-form-group--error');
        err.style.display = 'block';
    }

    // I check all conditional inputs if one is not hidden
    for(var i in inputs) {
        if (Number.isInteger(Number(i)) == true) {
            if (!hasClass(inputs[i], 'govuk-radios__conditional--hidden')) {
                if (isEmptyOrSpaces(inputs[i].firstElementChild.childNodes[5].value)) {
                    returnValue = false;
                    inputs[i].firstElementChild.childNodes[5].classList.add('govuk-input--error');
                    formLayout.classList.add('govuk-form-group--error');
                    inputs[i].style.borderLeftColor ='#b10e1e';
                    errInput.style.display = 'block';
                }
            }    
        }
    }

    return returnValue;
}