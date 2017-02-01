=== Expandable Dashboard Recent Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: dashboard, admin, recent comments, comment, excerpt, expandable, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 2.4.2

Enables in-place expansion of excerpts in the admin dashboard 'Comments' section of the 'Activity' widget to view full comments.


== Description ==

By default, the 'Comments' section of the 'Activity' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment actions row (the links for the comment that become visible under the comment when you hover over the comment). The "Show more" link, when clicked, will replace the excerpt with the full comment. The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc). The full comment can be switched back to the except by clicking the "Show less" link (which replaces the "Show more" link when the comment is expanded).

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

*NOTE:* This plugin only works for users who have JavaScript enabled.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/expandable-dashboard-recent-comments/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `expandable-dashboard-recent-comments.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Visit the admin dashboard and check out the 'Comments' section of the 'Activity' widget (assuming you have recent comments and that one or more of them have been automatically excerpted)


== Frequently Asked Questions ==

= How do I expand an excepted comment? =

When you hover over the comment, a line of action links will appear (typically "Approve", "Reply", "Edit", "Spam", and "Trash"). If the comment has been automatically excerpted by WordPress, then a "Show more" link will appear. Click it to view the full comment.

= Why don't I see the "Show more" link when hovering over a comment? =

The comment has not been been excerpted; you are already seeing the comment in its entirety so there is no need to be able to "show more".

= Why don't I see the "Expand all" and "Collapse all" links at the bottom of the widget? =

Assuming you are using a supported version of WordPress, this just means that none of the comments being listed have been excerpted, thus there is no need to be able to "Expand all" or "Collapse all" in this instance.


== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard widget with the plugin active, showing comments that have been truncated/excerpted by WordPress (the 2nd and 4th listed) and full, short comments. (Note the 'Expand All' and 'Collapse All' links added to the bottom of the widget.)
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the first excerpted comment fully in-place expanded and with markup and formatting applied.


== Filters ==

The plugin exposes one filter for hooking.

= c2c_expandable_dashboard_recent_comments_start_expanded (filter) =

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

= () =
* Change: Vertically center expand/collapse dashicons.
* Change: Escape translated strings used in attributes.
* Change: Use `sprintf()` to format output markup rather than concatenating strings, variables, and function calls.
* Change: No need to explicitly enqueue jQuery.
* Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable.
* Change: Enable more error output for unit tests.
* Change: Note compatibility through WP 4.7+.
* Change: Remove support for WordPress older than 4.6 (should still work for earlier versions back to WP 3.8)
* New: Add LICENSE file.
* Change: Add inline docs for class variables.
* Change: Update copyright date (2017).

= 2.4.2 (2016-01-09) =
* Change: Add support for language packs:
    * Change textdomain to 'expandable-dashboard-recent-comments' from 'c2c_edrc'.
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).
* Add: Create empty index.php to prevent files from being listed if web server has enabled directory listings.

= 2.4.1 (2015-08-18) =
* Update: Note compatibility through WP 4.3+

= 2.4 (2015-03-06) =
* Use Dashicons icons for all expand/collapse links (removing special character usage from string)
* Remove `get_ellipsis()` since it was only needed for WP < 3.6
* Remove JS support for WP < 3.8
* Remove `is_admin()` check preventing loading of class
* Added meager unit tests
* Reformat plugin header
* Space out admin.css content
* Change documentation links to wp.org to be https
* Minor documentation spacing changes throughout
* Change description
* Note compatibility through WP 4.1+
* Dropped support for versions of WP older than 3.8
* Update copyright date (2015)
* Update screenshots
* Add plugin icon
* Regenerate .pot

= 2.3 (2013-12-24) =
* Fix CSS selectors to properly format full comments under WP 3.8
* Fix JS selectors to show Expand/Collapse All links under WP 3.8
* Add Frequently Asked Questions section to readme.txt
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Add banner
* Minor readme.txt text and formatting tweaks
* Change donate link

= 2.2 =
* Fix support for WP3.6+ due to core's change of '...' to '&hellip;' for the excerpt ellipsis
* Add is_text_excerpted(), get_ellipsis()
* Note compatibility through WP 3.6+

= 2.1 =
* Add 'comment' arg to `is_comment_initially_expanded()` for context
* Add 'comment' as additional arg to 'c2c_expandable_dashboard_recent_comments_start_expanded' filter
* Change description (to shorten)
* Add check to prevent execution of code if file is directly accessed
* Regenerate .pot
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Minor improvements to inline and readme docs
* Minor code reformatting (spacing)
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Move screenshots into repo's assets directory

= 2.0 =
* Use "Show more"/"Show less" links in comment row actions instead of appending expand/collapse link
* Add filter 'c2c_expandable_dashboard_recent_comments_start_expanded' to permit initial display of comments in expanded state
* Remove class configuration array
* Remove filter 'c2c_expandable_dashboard_recent_comments_config'
* Enqueue CSS
* Enqueue JS
* Add register_styles(), enqueue_admin_css(), enqueue_admin_js()
* Remove add_css(), add_js()
* Add support for localization
* Add .pot
* No longer hide the ellipsis
* Hook 'load-index.php' action to initialize plugin rather than checking pagenow
* Add version() to return plugin version
* Minor code reformatting (spacing)
* Note compatibility through WP 3.3+
* Drop support for versions of WP older than 3.1
* Update screenshots (now based on WP 3.3)
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

= 1.3.1 =
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

= 1.3 =
* Don't display expand/collapse links for users without JavaScript and jQuery enabled
* Add admin unobtrusive javascript to handle expand/collapse of comments when links are clicked
* Use substr() instead of preg_match() to detect presence of '...'
* Remove 'onclick' attribute for links (perform via unobtrusive JS)
* Fix plugin links in description in readme.txt

= 1.2.1 =
* Add link to plugin homepage to description in readme.txt

= 1.2 =
* Switch from object instantiation to direct class function invocation
* Explicitly declare all functions public static and class variables public static
* Note compatibility with WP 3.1+
* Update copyright date (2011)

= 1.1 =
* Add filter 'c2c_expandable_dashboard_recent_comments_config' to allow filtering of config options
* Rename class from 'ExpandableDashboardRecentComments' to 'c2c_ExpandableDashboardRecentComments'
* Store plugin instance in global variable, $c2c_expandable_dashboard_recent_comments, to allow for external manipulation
* Move is_admin() check to before class creation
* Add init() and move hooking of actions/filters to there
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor code reformatting (spacing)
* Add Filters and Upgrade Notice sections to readme
* Remove trailing whitespace in header docs

= 1.0.1 =
* Add full PHPDoc documentation
* Minor formatting tweaks
* Note compatibility with WP 2.9+
* Update copyright date
* Update readme.txt (including adding Changelog)

= 1.0 =
* Initial release


== Upgrade Notice ==

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
