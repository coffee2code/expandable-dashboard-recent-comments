<?php
/**
 * @package Expandable_Dashboard_Recent_Comments
 * @author Scott Reilly
 * @version 1.3.1
 */
/*
Plugin Name: Expandable Dashboard Recent Comments
Version: 1.3.1
Plugin URI: http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds the ability to do in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget to view full comments.

Compatible with WordPress 2.6+, 2.7+, 2.8+, 2.9+, 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/expandable-dashboard-recent-comments/

TODO:
	* Make it possible for comments to start off expanded rather than collapsed?

*/

/*
Copyright (c) 2009-2011 by Scott Reilly (aka coffee2code)

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

if ( is_admin() && ! class_exists( 'c2c_ExpandableDashboardRecentComments' ) ) :

class c2c_ExpandableDashboardRecentComments {
	// This just defines the default config. Values can be filtered via the filter 'c2c_expandable_dashboard_recent_comments_config'
	public static $config = array(
		'remove-ellipsis' => false,
		'more-text' => ' &raquo;',
		'less-text' => ' &laquo;'
	);

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	public static function init() {
		global $pagenow;
		if ( 'index.php' == $pagenow )
			add_action( 'admin_init', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Initialize the config and register actions/filters
	 */
	public static function do_init() {
		self::$config = apply_filters( 'c2c_expandable_dashboard_recent_comments_config', self::$config );
		add_action( 'admin_print_styles',         array( __CLASS__, 'add_css' ) );
		add_action( 'admin_print_footer_scripts', array( __CLASS__, 'add_js' ) );
		add_filter( 'comment_excerpt',            array( __CLASS__, 'expandable_comment_excerpts' ) );
	}

	/**
	 * Echoes the CSS for this plugin within style tags
	 *
	 */
	public static function add_css() {
		echo <<<CSS
		<style type="text/css">
		#the-comment-list .comment-item blockquote .excerpt-full p { display:block; margin:1em 0; }
		#dashboard_recent_comments .excerpt-short a { display:none; }
		</style>

CSS;
	}

	/**
	 * Returns class name to be used for specific comment
	 *
	 * @since 1.3
	 * @param int|string|null The comment ID (or null to get the ID for the current comment)
	 * @return string The class
	 */
	private static function get_comment_class( $comment_id = null ) {
		if ( !$comment_id ) {
			global $comment;
			$comment_id = $comment->comment_ID;
		}
		return "excerpt-$comment_id";
	}

	/**
	 * Echoes the JS for this plugin within script tags
	 *
	 * @since 1.3
	 */
	public static function add_js() {
			echo <<<JS
		<script type="text/javascript">
		if (jQuery) {
			jQuery(document).ready(function($) {
				$('.excerpt-ellipsis').hide();
				$('#dashboard_recent_comments div.excerpt-short a').show();
				$('#dashboard_recent_comments div.excerpt-short a, #dashboard_recent_comments div.excerpt-full a').click(function() {
					$(this).parent().parent().find('div.excerpt-short, div.excerpt-full').toggle();
					return false;
				})
			});
		}
		</script>

JS;
	}

	/**
	 * Modifies a comment excerpt to add link to expand comments (using JavaScript).
	 *
	 * @param string $excerpt Excerpt
	 * @return string The $excerpt modified to have show more/less links when applicable
	 */
	public static function expandable_comment_excerpts( $excerpt ) {
		global $comment;
		if ( substr( $excerpt, -3 ) == '...' ) {
			$body = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
			$class = self::get_comment_class( $comment->comment_ID );
			$extended = self::$config['remove-ellipsis'] ? '<span class="excerpt-ellipsis">...</span>' : ''; // Will only be seen if JS is disabled
			$extended .= "<div class='{$class}-short excerpt-short'>" .
				( self::$config['remove-ellipsis'] ? substr( $excerpt, 0, -3 ) : $excerpt ) .
				"<a href='#' title='" . __( 'Show full comment' ) . "'>" . self::$config['more-text'] . '</a></div>' .
				"<div class='{$class}-full excerpt-full' style='display:none;'>" .
				$body . 
				" <a href='#' title='" . __( 'Show excerpt' ) . "'>" . self::$config['less-text'] . '</a></div>';
			$excerpt = preg_replace( '/\.\.\.$/', $excerpt, $extended );
		}
		return $excerpt;
	}

} // end c2c_ExpandableDashboardRecentComments

c2c_ExpandableDashboardRecentComments::init();

endif; // end if !class_exists()

?>