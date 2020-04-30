if (jQuery) {
	jQuery(document).ready(function($) {
		// Move the expand/collapse all links outside of an individual comment and to bottom of widget
		var append_to = '#latest-comments';
		$('.c2c_edrc_all').detach().appendTo(append_to).show();

		function setGlobalControlsState() {
			var expanded  = $('#the-comment-list').find('.c2c_edrc_more.c2c-edrc-hidden');
			var collapsed = $('#the-comment-list').find('.c2c_edrc_less.c2c-edrc-hidden');

			const total_items = expanded.length + collapsed.length;

			var more_all = $('.c2c_edrc_all').find('.c2c_edrc_more_all');
			var less_all = $('.c2c_edrc_all').find('.c2c_edrc_less_all');

			// Unset both of the links from being active.
			more_all.removeClass('c2c-edrc-all-active');
			less_all.removeClass('c2c-edrc-all-active');

			// Determine if either or both links should appear disabled (due to no
			// comment to be able to affect in its way).
			if ( expanded.length && ! collapsed.length ) {
				more_all.addClass('c2c-edrc-all-active');
			}
			else if ( ! expanded.length && collapsed.length ) {
				less_all.addClass('c2c-edrc-all-active');
			}
			else if ( ! expanded.length && ! collapsed.length ) {
				more_all.addClass('c2c-edrc-all-active');
				less_all.addClass('c2c-edrc-all-active');
			}

			more_all.find('.c2c_edrc_more_count').text( `(${collapsed.length})` );
			less_all.find('.c2c_edrc_less_count').text( `(${expanded.length})` );
		}

		setGlobalControlsState();

		// Handle click of link to toggle excerpt/full for individual comment
		$('.c2c_edrc_more, .c2c_edrc_less').click(function(e) {
			$(this).closest('.dashboard-comment-wrap')
				.find('div.excerpt-short, div.excerpt-full, .c2c_edrc_more, .c2c_edrc_less')
				.toggleClass('c2c-edrc-hidden');

			// Determine if a global control should appear disabled.
			setGlobalControlsState();

			e.preventDefault();
		});

		// Handle click of link to expand all excerpted comments
		$('.c2c_edrc_more_all').click(function(e) {
			if ( ! $(this).hasClass('c2c-edrc-all-active') ) {
				$(this).closest('.inside').find('div.excerpt-short, .c2c_edrc_more').addClass('c2c-edrc-hidden');
				$(this).closest('.inside').find('div.excerpt-full, .c2c_edrc_less').removeClass('c2c-edrc-hidden');

				// Switch which global control should appear disabled.
				$(this).addClass('c2c-edrc-all-active');
				$(this).closest('.c2c_edrc_all').find('.c2c_edrc_less_all').removeClass('c2c-edrc-all-active')
			}

			// Determine if a global control should appear disabled and update counts.
			setGlobalControlsState();

			e.preventDefault();
		});

		// Handle click of link to excerpt all expanded comments
		$('.c2c_edrc_less_all').click(function(e) {
			if ( ! $(this).hasClass('c2c-edrc-all-active') ) {
				$(this).closest('.inside').find('div.excerpt-short, .c2c_edrc_more').removeClass('c2c-edrc-hidden');
				$(this).closest('.inside').find('div.excerpt-full, .c2c_edrc_less').addClass('c2c-edrc-hidden');

				// Switch which global control should appear disabled.
				$(this).addClass('c2c-edrc-all-active');
				$(this).closest('.c2c_edrc_all').find('.c2c_edrc_more_all').removeClass('c2c-edrc-all-active')
			}

			// Determine if a global control should appear disabled and update counts.
			setGlobalControlsState();

			e.preventDefault();
		});
	});
}
