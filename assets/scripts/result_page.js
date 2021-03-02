window.addEventListener('DOMContentLoaded', () => {
	var observer = new IntersectionObserver(entries => {
		entries.forEach(entry => {
			var id = entry.target.getAttribute('id');
			if (entry.intersectionRatio > 0) {
				document.querySelector(`ul li a[href="#${id}"]`).classList.add('active-id');
			} else {
				document.querySelector(`ul li a[href="#${id}"]`).classList.remove('active-id');
			}
		});
	});

//call function only for screens la
if (window.matchMedia("(min-width: 601px)").matches){
		// Track all sections that have an `id` applied
	document.querySelectorAll('section[id]').forEach((section) => {
		observer.observe(section);
	});
}

	
});

function toggleAccordionControll(controllerElement) {
    if (controllerElement.textContent == "Show details") {
        controllerElement.textContent = "Hide details";
    } else {
        controllerElement.textContent = "Show details";
    }

}

window.onload = function(){
    //transforms govuk accordeon in custom  designed one
    var accordionElements = document.getElementsByClassName('govuk-accordion__section-button');
    var accordionIcons = document.getElementsByClassName("govuk-accordion__icon");
    var accordionOpenAll = document.getElementsByClassName("govuk-accordion__open-all")[0];
    for (var i = accordionElements.length - 1; i >= 0; i--) {
        var isExtended = accordionElements[i].getAttribute('aria-expanded');
        var elementRoot = accordionElements[i].parentElement.parentElement;
        var controller = document.createElement("p");
        controller.style.textDecoration = "underline";
        controller.style.color = "#007194";
        controller.classList.add("govuk-link");
        controller.href="";
        if (isExtended == "true") {
            controller.textContent = "Hide details";
        } else {
            controller.textContent = "Show details";
        }
        elementRoot.appendChild(controller);

        accordionElements[i].addEventListener("click", toggleAccordionControll.bind(this, accordionElements[i].parentElement.parentElement.children[1]));
    }
    for (var i = accordionIcons.length - 1; i >= 0; i--) {
        accordionIcons[i].parentNode.removeChild(accordionIcons[i]);
    }
    accordionOpenAll.parentNode.removeChild(accordionOpenAll);
}