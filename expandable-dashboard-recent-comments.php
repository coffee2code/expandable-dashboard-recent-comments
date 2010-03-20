<?php
/**
 * @package Expandable_Dashboard_Recent_Comments
 * @author Scott Reilly
 * @version 1.0.1
 */
/*
Plugin Name: Expandable Dashboard Recent Comments
Version: 1.0.1
Plugin URI: http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

By default, the 'Recent Comments' admin dashboard widget only shows an excerpt for the comments, truncating the content of the
comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment excerpt (a ">>" (aka &raquo;)) that when clicked will replace the excerpt with
the full comment.  The full comment will include all markup, including originally utilized markup and changes applied via filters,
plugins, etc (such as shortcode expansion, smilies, paragraphing, etc).  The full comment can be switched back to the except by
clicking a "<<" (&laquo;) link.

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the
excerpt without requiring a page reload or navigation.

NOTE: This plugin only works for users who have JavaScript enabled.

Compatible with WordPress 2.6+, 2.7+, 2.8+, 2.9+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments.zip and unzip it into your 
/wp-content/plugins/ directory (or install via the built-in WordPress plugin installer).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
*/

/*
Copyright (c) 2009-2010 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists('ExpandableDashboardRecentComments') ) :
class ExpandableDashboardRecentComments {
	var $config = array(
		'remove-ellipsis' => false,
		'more-text' => ' &raquo;',
		'less-text' => ' &laquo;'
	);

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	function ExpandableDashboardRecentComments() {
		global $pagenow;
		if ( is_admin() && 'index.php' == $pagenow ) {
			add_action('admin_head', array(&$this, 'add_css'));
			add_filter('comment_excerpt', array(&$this, 'expandable_comment_excerpts'));
		}
	}

	/**
	 * Echoes the CSS for this plugin within style tags
	 *
	 */
	function add_css() {
		echo <<<CSS
		<style type="text/css">
		#the-comment-list .comment-item blockquote .excerpt-full p {
			display:block;
			margin:1em 0;
		}
		</style>

CSS;
	}

	/**
	 * Modifies a comment excerpt to add link to expand comments (using JavaScript).
	 *
	 * @param string $excerpt Excerpt
	 * @return string The $excerpt modified to have show more/less links when applicable
	 */
	function expandable_comment_excerpts( $excerpt ) {
		global $comment;
		if ( preg_match('/\.\.\.$/', $excerpt) ) {
			$body = apply_filters('comment_text', apply_filters('get_comment_text', $comment->comment_content), '40');
			$class = "excerpt-{$comment->comment_ID}";
			$extended = "<div class='{$class}-short excerpt-short'>" .
				( $this->config['remove-ellipsis'] ? substr($excerpt, 0, -3) : $excerpt ) .
				"<a href='#' onclick=\"javascript:jQuery('.{$class}-short, .{$class}-full').toggle();return false;\" title='" .
				__('Show full comment') . "'>{$this->config['more-text']}</a></div>" .
				"<div class='{$class}-full excerpt-full' style='display:none;'>" .
				$body . 
				" <a href='#' onclick=\"javascript:jQuery('.{$class}-full, .{$class}-short').toggle();return false;\" title='" .
				__('Show excerpt') . "'>{$this->config['less-text']}</a></div>";
			$excerpt = preg_replace('/\.\.\.$/', $excerpt, $extended);
		}
		return $excerpt;
	}

} // end ExpandableDashboardRecentComments

endif; // end if !class_exists()

if ( class_exists('ExpandableDashboardRecentComments') )
	new ExpandableDashboardRecentComments();

?>