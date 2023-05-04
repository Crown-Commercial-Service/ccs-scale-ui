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