(function($) {
	$(document).ready(function() {

		var $bigimage = $('img.gallery-big-image')[0];

		$('img.attachment-small-thumbs').parent('a').click(function(e) {
			e.preventDefault();
			
			var new_src = $(this).children('img').attr('data-retina');


			$($bigimage).attr('src', new_src);
		});
	});
})(jQuery);
