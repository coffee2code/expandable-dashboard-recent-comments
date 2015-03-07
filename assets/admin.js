if (jQuery) {
	jQuery(document).ready(function($) {
		// Move the expand/collapse all links outside of an individual comment and to bottom of widget
		var append_to = '#latest-comments';
		$('.c2c_edrc_all').detach().appendTo(append_to).show();

		// Handle click of link to toggle excerpt/full for individual comment
		$('.c2c_edrc_more, .c2c_edrc_less').click(function(e) {
			$(this).closest('.dashboard-comment-wrap').find('div.excerpt-short, div.excerpt-full, .c2c_edrc_more, .c2c_edrc_less').toggle();
			e.preventDefault();
		});

		// Handle click of link to expand all excerpted comments
		$('.c2c_edrc_more_all').click(function(e) {
			$(this).closest('.inside').find('div.excerpt-short, .c2c_edrc_more').hide();
			$(this).closest('.inside').find('div.excerpt-full, .c2c_edrc_less').show();
			e.preventDefault();
		});

		// Handle click of link to excerpt all expanded comments
		$('.c2c_edrc_less_all').click(function(e) {
			$(this).closest('.inside').find('div.excerpt-short, .c2c_edrc_more').show();
			$(this).closest('.inside').find('div.excerpt-full, .c2c_edrc_less').hide();
			e.preventDefault();
		});
	});
}
