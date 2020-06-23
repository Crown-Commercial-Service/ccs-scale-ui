function exclude() {
    var checkboxes = document.getElementsByClassName('all-options');
    // console.log(checkboxes);
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = false;
    }
}

function unExlcusive() {
    var checkbox = document.getElementsByClassName('e-button')[0];
    checkbox.checked = false;
}

function toggle(x) {
    var checkboxes = document.getElementsByClassName('all-options');
    // console.log(checkboxes);
    for(var checbox in checkboxes){
        checkboxes[checbox].checked = x.checked;
    }
}