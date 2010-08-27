=== Expandable Dashboard Recent Comments ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: dashboard, admin, recent comments, comment, excerpt, expandable, coffee2code
Requires at least: 2.6
Tested up to: 3.0.1
Stable tag: 1.1
Version: 1.1

Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.


== Description ==

Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

By default, the 'Recent Comments' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment excerpt (a ">>" (aka `&raquo;`)) that when clicked will replace the excerpt with the full comment.  The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc).  The full comment can be switched back to the except by clicking a "<<" (`&laquo;`) link.

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

*NOTE:* This plugin only works for users who have JavaScript enabled.


== Installation ==

1. Unzip `expandable-dashboard-recent-comments.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress


== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, showing a comment that has been truncated/excerpted by WordPress and a full, short comment.
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the excerpted comment fully in-place expanded and with markup and formatting applied.


== Filters ==

The plugin exposes one filter for hooking.

= c2c_expandable_dashboard_recent_comments_config (filter) =

The 'c2c_expandable_dashboard_recent_comments_config' hook allows you to customize some of the configuration options used by the plugin.

The configuration options in the array passed through the filter consist of:

* 'remove-ellipsis' : (bool) Should the ellipsis be removed from truncated excerpts?  Default is false,
* 'more-text' : (string) The string used for the link to be clicked to view more (i.e. the full comment text).  Default is '&raquo;'.
* 'less-text' : (string) The string used for the link to be clicked to view less (i.e. the comment excerpt).  Default is '&laquo;'.

Arguments:

* $config (array): Array of configuration options (see description for keys and default values)

Example:

`add_filter( 'c2c_expandable_dashboard_recent_comments_config', 'my_edrc_changes' );
function my_edrc_changes( $config ) {
	$config['more-text'] = '(see more)';
	$config['less-text'] = '(see less)';
	return $config; /* Important! */
}`


== Changelog ==

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

= 1.1 =
Minor update. Highlights: adds filter to allow customization of configuration defaults; verified WP 3.0 compatibility.