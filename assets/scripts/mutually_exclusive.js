function exclude() {
    var checkboxes = document.getElementsByClassName('v-selector');
    // console.log(checkboxes);
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = false;
    }
}

function unExlcusive() {
    var checkbox = document.getElementsByClassName('e-button')[0];
    checkbox.checked = false;
}