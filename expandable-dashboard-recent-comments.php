<?php
/**
 * @package Expandable_Dashboard_Recent_Comments
 * @author Scott Reilly
 * @version 1.1
 */
/*
Plugin Name: Expandable Dashboard Recent Comments
Version: 1.1
Plugin URI: http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

Compatible with WordPress 2.6+, 2.7+, 2.8+, 2.9+, 3.0+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/hide-broken-shortcodes/

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

if ( is_admin() && !class_exists( 'c2c_ExpandableDashboardRecentComments' ) ) :
class c2c_ExpandableDashboardRecentComments {
	// This just defines the default config. Values can be filtered via the filter 'c2c_expandable_dashboard_recent_comments_config'
	var $config = array(
		'remove-ellipsis' => false,
		'more-text' => ' &raquo;',
		'less-text' => ' &laquo;'
	);

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	function c2c_ExpandableDashboardRecentComments() {
		global $pagenow;
		if ( 'index.php' == $pagenow )
			add_action( 'admin_init', array( &$this, 'init' ) );
	}

	/**
	 * Initialize the config and register actions/filters
	 */
	function init() {
		$this->config = apply_filters( 'c2c_expandable_dashboard_recent_comments_config', $this->config );
		add_action( 'admin_head', array( &$this, 'add_css' ) );
		add_filter( 'comment_excerpt', array( &$this, 'expandable_comment_excerpts' ) );
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
		if ( preg_match( '/\.\.\.$/', $excerpt ) ) {
			$body = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
			$class = "excerpt-{$comment->comment_ID}";
			$extended = "<div class='{$class}-short excerpt-short'>" .
				( $this->config['remove-ellipsis'] ? substr( $excerpt, 0, -3 ) : $excerpt ) .
				"<a href='#' onclick=\"javascript:jQuery('.{$class}-short, .{$class}-full').toggle();return false;\" title='" .
				__( 'Show full comment' ) . "'>{$this->config['more-text']}</a></div>" .
				"<div class='{$class}-full excerpt-full' style='display:none;'>" .
				$body . 
				" <a href='#' onclick=\"javascript:jQuery('.{$class}-full, .{$class}-short').toggle();return false;\" title='" .
				__( 'Show excerpt' ) . "'>{$this->config['less-text']}</a></div>";
			$excerpt = preg_replace( '/\.\.\.$/', $excerpt, $extended );
		}
		return $excerpt;
	}

} // end c2c_ExpandableDashboardRecentComments

$GLOBALS['c2c_expandable_dashboard_recent_comments'] = new c2c_ExpandableDashboardRecentComments();

endif; // end if !class_exists()

?>