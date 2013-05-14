(function($) {
	$(document).ready(function() {

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
		$('#tagline').delay(8000).fadeOut(2000).delay(4000).queue(function(next) {
			$(this).removeClass('active');
			next();	
		});

	});
})(jQuery);
