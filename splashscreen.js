(function($) {
	$(document).ready(function() {

		if (typeof(sessionStorage)) {
		
			// Check if the page has already been visited during this browser session.
			// If not, show the intro animation.
			if (sessionStorage.getItem('nordin_intro') === null) {

				sessionStorage.setItem('nordin_intro', 'watched');
				intro_animation();

			}
		}




		function intro_animation() {
			// Seperate the tagline in multiple <span>'s
			var tagline = $('#tagline')[0];

			var wordlist = tagline.innerText.split(/ +/);
			var newtagline = "<p>";
			for (word in wordlist) {
				newtagline = newtagline + '<span>' + wordlist[word] + '</span> ';
			}
			newtagline = newtagline + '</p>';
			tagline.innerHTML = newtagline;

			$('#tagline').height($(document).height());
			$('#tagline').addClass('active');
			$('#tagline').delay(5000).fadeOut(1250).delay(2500).queue(function(next) {
				$(this).removeClass('active');
				next();	
			});

		}

	});
})(jQuery);
