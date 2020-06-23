function validate() {
    var return_value = false;
    var formLayout   = document.getElementById('form-layout');
    var err          = document.getElementById('changed-name-error');

    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked) return_value = true;
    }

    if (return_value === false) {
        formLayout.classList.add('govuk-form-group--error');
        err.style.display = 'block';
    }

    return return_value;
}