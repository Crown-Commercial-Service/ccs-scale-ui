
function pushDataLayer(interaction_type, link_text, interaction_detail, step){

    console.log("dada");
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
            for (var i = 0; i < document.getElementsByClassName('v-selector').length; i++) {
                if (document.getElementsByClassName('v-selector')[i].checked){
                    answer  = document.getElementsByClassName('v-selector')[i].labels[0].textContent;
                    break;
                }
            }
            break;
        case 'multiSelect':
                var checkboxes = document.getElementsByName('uuid[]');
                var checkboxesChecked = [];
    
                for (var i=0; i<checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        checkboxesChecked.push(checkboxes[i].labels[0].textContent);
                    }
                }
    
                answer = checkboxesChecked.join(' | ')
            break;
        case 'boolean':
            // we dont have any boolean quesiton on GM. They have all been archived.
            break;
    }

    if(answer !== null){
        pushDataLayer('continue','continue',null,answer);
    }

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
