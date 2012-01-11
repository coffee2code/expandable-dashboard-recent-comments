=== Expandable Dashboard Recent Comments ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: dashboard, admin, recent comments, comment, excerpt, expandable, coffee2code
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 2.0
Version: 2.0

Adds the ability to do in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget to view full comments.


== Description ==

Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

By default, the 'Recent Comments' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment actions row (the links for the comment that become visible under the comment when you hover over the comment). The "Show more" link, when clicked, will replace the excerpt with the full comment.  The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc).  The full comment can be switched back to the except by clicking the "Show less" link (which replaces the "Show more" link when the comment is expanded).

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

*NOTE:* This plugin only works for users who have JavaScript enabled.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/expandable-dashboard-recent-comments/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `expandable-dashboard-recent-comments.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Visit the admin dashboard and check out the 'Recent Comments' widget


== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, showing comments that have been truncated/excerpted by WordPress (the 2nd and 4th listed) and full, short comments.
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the first excerpted comment fully in-place expanded and with markup and formatting applied.


== Filters ==

The plugin exposes one filter for hooking.

= c2c_expandable_dashboard_recent_comments_start_expanded (filter) =

The 'c2c_expandable_dashboard_recent_comments_start_expanded' hook allows you to configure the 'Recent Comments' admin dashboard widget initially display all comments in their expanded state (i.e. not excerpted).

Arguments:

* $default (boolean): The default state, which is 'false' (therefore comments are initially shown excerpted)

Example:

`
// Initially show dashboard comments fully expanded
add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_true' );
`


== Changelog ==

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