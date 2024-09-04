window.onload = function() {
	// Move the expand/collapse all links outside of an individual comment and to bottom of widget
	const edrc_all = document.querySelector('.c2c_edrc_all');
	document.querySelector('#latest-comments').insertAdjacentElement('afterend', edrc_all);
	edrc_all.style.display = 'block';

	function setGlobalControlsState() {
		const expanded  = document.querySelectorAll('#the-comment-list .c2c_edrc_more.c2c-edrc-hidden');
		const collapsed = document.querySelectorAll('#the-comment-list .c2c_edrc_less.c2c-edrc-hidden');

		const total_items = expanded.length + collapsed.length;

		const more_all = document.querySelector('.c2c_edrc_all .c2c_edrc_more_all');
		const less_all = document.querySelector('.c2c_edrc_all .c2c_edrc_less_all');

		// Unset both of the links from being active.
		more_all.classList.remove('c2c-edrc-all-active');
		less_all.classList.remove('c2c-edrc-all-active');

		// Determine if either or both links should appear disabled (due to no
		// comment to be able to affect in its way).
		if ( expanded.length && ! collapsed.length ) {
			more_all.classList.add('c2c-edrc-all-active');
		}
		else if ( ! expanded.length && collapsed.length ) {
			less_all.classList.add('c2c-edrc-all-active');
		}
		else if ( ! expanded.length && ! collapsed.length ) {
			more_all.classList.add('c2c-edrc-all-active');
			less_all.classList.add('c2c-edrc-all-active');
		}

		more_all.querySelector('.c2c_edrc_more_count').textContent = `(${collapsed.length})`;
		less_all.querySelector('.c2c_edrc_less_count').textContent = `(${expanded.length})`;
	}

	setGlobalControlsState();

	// Handle click of link to toggle excerpt/full for individual comment
	function toggleEDRC(e) {
		e.currentTarget.closest('.dashboard-comment-wrap')
			.querySelectorAll('div.excerpt-short, div.excerpt-full, .c2c_edrc_more, .c2c_edrc_less')
			.forEach( i => {
				i.classList.toggle('c2c-edrc-hidden');
				const ariaExpanded = ! i.classList.contains('c2c-edrc-hidden');
				i.setAttribute('aria-expanded', ariaExpanded ? 'true' : 'false');
				i.setAttribute('aria-hidden', ariaExpanded ? 'false' : 'true' );
			} );

		// Determine if a global control should appear disabled.
		setGlobalControlsState();

		e.preventDefault();
	}
	document.querySelectorAll('.c2c_edrc_more').forEach( i => i.addEventListener('click', toggleEDRC) );
	document.querySelectorAll('.c2c_edrc_less').forEach( i => i.addEventListener('click', toggleEDRC) );

	// Handle click of link to expand all excerpted comments
	document.querySelector('.c2c_edrc_more_all').addEventListener('click', e => {
		if ( ! e.currentTarget.classList.contains('c2c-edrc-all-active') ) {
			e.currentTarget.closest('.inside')
				.querySelectorAll('div.excerpt-short, .c2c_edrc_more')
				.forEach( i => i.classList.add('c2c-edrc-hidden') );
			e.currentTarget.closest('.inside')
				.querySelectorAll('div.excerpt-full, .c2c_edrc_less')
				.forEach( i => i.classList.remove('c2c-edrc-hidden') );

			// Switch which global control should appear disabled.
			e.currentTarget.classList.add('c2c-edrc-all-active');
			e.currentTarget.closest('.c2c_edrc_all')
				.querySelector('.c2c_edrc_less_all')
				.classList.remove('c2c-edrc-all-active')
		}

		// Determine if a global control should appear disabled and update counts.
		setGlobalControlsState();

		e.preventDefault();
	});

	// Handle click of link to excerpt all expanded comments
	document.querySelector('.c2c_edrc_less_all').addEventListener('click', e => {
		if ( ! e.currentTarget.classList.contains('c2c-edrc-all-active') ) {
			e.currentTarget.closest('.inside')
				.querySelectorAll('div.excerpt-short, .c2c_edrc_more')
				.forEach( i => i.classList.remove('c2c-edrc-hidden') );
			e.currentTarget.closest('.inside')
				.querySelectorAll('div.excerpt-full, .c2c_edrc_less')
				.forEach( i => i.classList.add('c2c-edrc-hidden') );

			// Switch which global control should appear disabled.
			e.currentTarget.classList.add('c2c-edrc-all-active');
			e.currentTarget.closest('.c2c_edrc_all')
				.querySelector('.c2c_edrc_more_all')
				.classList.remove('c2c-edrc-all-active')
		}

		// Determine if a global control should appear disabled and update counts.
		setGlobalControlsState();

		e.preventDefault();
	});
}
