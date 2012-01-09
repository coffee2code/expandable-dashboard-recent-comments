if (jQuery) {
	jQuery(document).ready(function($) {
		$('.c2c_edrc_more a').show();
		$('.c2c_edrc_more a, .c2c_edrc_less a').click(function(e) {
			$(this).closest('.c2c_edrc').find('div.excerpt-short, div.excerpt-full').toggle();
			e.preventDefault();
		})
	});
}
