function validate() {
    console.log(document.question);
    var return_value = false;
    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked) return_value = true;
    }
    var formLayout = document.getElementById('form-layout');
    var err = document.getElementById('changed-name-error');
    if (return_value === false) {
        formLayout.classList.add('govuk-form-group--error');
        err.style.display = 'block';
    }
    return return_value;
}