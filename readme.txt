=== Expandable Dashboard Recent Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: dashboard, admin, recent comments, comment, excerpt, expandable, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 5.4
Stable tag: 2.6

Enables in-place expansion of excerpts in the admin dashboard 'Comments' section of the 'Activity' widget to view full comments.


== Description ==

By default, the 'Comments' section of the 'Activity' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment actions row (the links for the comment that become visible under the comment when you hover over the comment). The "Show more" link, when clicked, will replace the excerpt with the full comment. The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc). The full comment can be switched back to the except by clicking the "Show less" link (which replaces the "Show more" link when the comment is expanded).

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

*NOTE:* This plugin only works for users who have JavaScript enabled.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/expandable-dashboard-recent-comments/) | [GitHub](https://github.com/coffee2code/expandable-dashboard-recent-comments/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `expandable-dashboard-recent-comments.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Visit the admin dashboard and check out the 'Comments' section of the 'Activity' widget (assuming you have recent comments and that one or more of them have been automatically excerpted)


== Frequently Asked Questions ==

= How do I expand an excepted comment? =

When you hover over the comment, a line of action links will appear (typically "Approve", "Reply", "Edit", "Spam", and "Trash"). If the comment has been automatically excerpted by WordPress, then a "Show more" link will appear. Click it to view the full comment.

= Why don't I see the "Show more" link when hovering over a comment? =

The comment has not been been excerpted; you are already seeing the comment in its entirety so there is no need to be able to "show more".

= Why don't I see the "Expand all" and "Collapse all" links at the bottom of the widget? =

Assuming you are using a supported version of WordPress, this just means that none of the comments being listed have been excerpted, thus there is no need to be able to "Expand all" or "Collapse all" in this instance.

= Why does either of the "Expand all" or "Collapse all" links appear grayed out? =

If all of the comments in the widget are currently collapsed, then the "Collapse all" link will appear grayed out to indicate there is nothing for it to collapse. Likewise, if all of the comments in the widget are currently expanded, then "Expand all" will appear grayed out to indicate there is nothing for it to expand. As comments are expanded and collapsed, these two links will adjust themselves to appear grayed out or active as appropriate.

= Can I make it so all comments initially appear fully expanded without needing to click the links to expand them? =

Yes. Please see the "Hooks" section for documentation on the 'c2c_expandable_dashboard_recent_comments_start_expanded' filter which allows for this.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard widget with the plugin active, showing comments that have been truncated/excerpted by WordPress (the 2nd and 4th listed) and full, short comments. The third comment has the mouse over it (though the cursor doesn't appear in the screenshot) so you can see the action links, including the "Show more" link. Note, also, the 'Expand All' and 'Collapse All' links added to the bottom of the widget.
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the second excerpted comment (the third comment in the list) fully in-place expanded and with markup and formatting applied.


== Hooks ==

The plugin exposes one filter for hooking. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_expandable_dashboard_recent_comments_start_expanded (filter)**

The 'c2c_expandable_dashboard_recent_comments_start_expanded' hook allows you to configure the 'Recent Comments' admin dashboard widget initially display all comments in their expanded state (i.e. not excerpted).

Arguments:

* $default (boolean): The default state, which is 'false' (therefore comments are initially shown excerpted)
* $comment (object) : The comment object being displayed

Example:

`
// Initially show dashboard comments fully expanded
add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_true' );
`


== Changelog ==

= 2.6 (2019-11-21) =
* New: Handle comments containing multi-byte characters that may not have been excerpted by WordPress
* New: Add `reset()` to reset plugin's internal state
* Change: Don't memoize value for if comment should appear initially expanded since it may vary based on comment
* Change: Switch `is_comment_initially_expanded()` and `is_text_excerpted()` from private to protected to facilitate unit testing
* Unit tests:
    * New: Add tests for admin dashboard comment excerpts
    * New: Add tests for `is_comment_initially_expanded()`
    * New: Add tests for `is_text_excerpted()`
    * New: Add tests for `expandable_comment_excerpts()`
    * New: Add test for the filter `c2c_expandable_dashboard_recent_comments_start_expanded`
    * Change: Remove unused test
    * Change: Invoke plugin's `reset()` after every test
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

= 2.5.3 (2019-06-26) =
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * New: Test that plugin hooks `plugins_loaded` action to initialize itself
* Change: Note compatibility through WP 5.2+
* Change: Split paragraph in README.md's "Support" section into two

= 2.5.2 (2019-03-03) =
* Fix: Ensure preceding up/down arrow for collapse/expand link does not get orphaned from the associated text
* New: Add inline documentation for hooks
* Change: Initialize plugin on 'plugins_loaded' action instead of on load
* Change: Cast return value of filter 'c2c_expandable_dashboard_recent_comments_start_expanded' as boolean
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/expandable-dashboard-recent-comments/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.6 =
Minor update: improved handling for multi-byte characters, improved unit testing, other minor improvements, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 2.5.3 =
Trivial update: modernized unit tests, created CHANGELOG.md to store historical changelog outside of readme.txt, and noted compatibility through WP 5.2+

= 2.5.2 =
Trivial update: prevented orphaning of up/down arrow from rest of link text, aded more inline documentation, noted compatibility through WP 5.1+, updated copyright date (2019)

= 2.5.1 =
Trivial update: added README.md, noted compatibility through WP 4.9+, and updated copyright date (2018)

= 2.5 =
Recommended update: gray out "Expand all" and/or "Collapse all" links if there are no comments in a state they can affect, compatibility is now WP 4.6-4.7+, updated copyright date (2017), and more

= 2.4.2 =
Trivial update: adjustments to utilize language packs, minor unit test tweaks, noted compatibility through WP 4.4+, and updated copyright date

= 2.4.1 =
Trivial update: noted compatibility with WP 4.3+

= 2.4 =
Recommended update: added dashicons; added unit tests; noted compatibility through WP 4.1+; dropped compatibility with version of WP older than 3.8; added plugin icon

= 2.3 =
Recommended update: fixed compatibility with WP 3.8+

= 2.2 =
Recommended update: Fixed to work for WP 3.6+ due to the change in how core defined the ellipsis.

= 2.1 =
Minor update. Highlights: added argument to filter; noted compatibility through WP 3.5+; explicitly stated license; and more.

= 2.0 =
Significant update: mostly rewritten; now uses "Show more"/"Show less" links in comment row actions instead of appending expand/collapse link; added expand/collapse links that affect all visible comments; added filter to allow initially showing comments expanded; internationalization; enqueue assets; and more

= 1.3.1 =
Trivial update: noted compatibility through WP 3.2+

= 1.3 =
Minor update: don't display expand/collapse links when JavaScript is disabled; use obtrusive JS rather than inline JS

= 1.2.1 =
Trivial update: add link to plugin homepage to description in readme.txt

= 1.2 =
Minor update: noted compatibility with WP 3.1+ and updated copyright date.

= 1.1 =
Minor update. Highlights: adds filter to allow customization of configuration defaults; verified WP 3.0 compatibility.
