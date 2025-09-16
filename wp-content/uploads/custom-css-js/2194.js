<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
// Header Menu Dropdown
document.addEventListener("DOMContentLoaded", function() {
	const menuBars = document.querySelector(".menu-bar-btn");
	const mobileMenuItems = document.querySelector(".mobile-menu-items");
	
	menuBars.addEventListener("click", function() {
		mobileMenuItems.classList.toggle("toggle-mobile-menu");
	})
	
	// Page Curso3 
	const misCursos = document.querySelectorAll(".page-curso-toggle-btn");
	misCursos.forEach((btn, index) => {
		btn.addEventListener("click", function(e) {
			const clickedText = e.currentTarget.querySelector(".elementor-button-text").textContent.trim();

			misCursos.forEach(b => {
				const btnText = b.querySelector(".elementor-button-text").textContent.trim();

				if (btnText === clickedText) {
					// style only the matching button's <a>
					b.querySelector("a").style.background = "#592D36";
					b.querySelector("a").style.color = "#fff";
				} else {
					// reset others
					b.querySelector("a").style.background = "";
					b.querySelector("a").style.color = "#592D36";
				}
			});
		});
	});
})



</script>
<!-- end Simple Custom CSS and JS -->
