
function pushDataLayer(interaction_type, link_text, interaction_detail, step){

    window.dataLayer.push({
        "event": 'gm_journey',
        "interaction_type":     interaction_type !== null ? interaction_type : null,
        "link_text":            link_text !== null ? link_text : null,
        "interaction_detail":   interaction_detail !== null ? interaction_detail : null,
        "step":                 step !== null ? step : null
    });
}

function questionType(type){
    var answer = null;

    switch(type){
        case 'list':     
            answer = list();
            break;
        case 'multiSelect':
            answer = multiSelect();
            break;
        case 'boolean':
            // we dont have any boolean quesiton on GM. They have all been archived.
            break;
    }

    if(answer !== null){
        pushDataLayer('continue','continue',null,answer);
    }
}

function list(){
    for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
        if (document.getElementsByClassName('v-selector')[i].checked){
            return document.getElementsByClassName('v-selector')[i].labels[0].textContent;
        }
    }
    return null;
}

function multiSelect(){
    var checkboxes = document.getElementsByName('uuid[]');
    var checkboxesChecked = [];

    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checkboxesChecked.push(checkboxes[i].labels[0].textContent);
        }
    }

    return checkboxesChecked.join(' | ')
}

function printResultPage(){
    pushDataLayer('print','Print this page', null, null);
    window.print()
}

function contact(){
    pushDataLayer('contact', 'Contact CCS', null, null);
}

function back(){
    pushDataLayer('back', 'Back', null, null);
}
