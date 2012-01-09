<?php
/**
 * @package Expandable_Dashboard_Recent_Comments
 * @author Scott Reilly
 * @version 1.5
 */
/*
Plugin Name: Expandable Dashboard Recent Comments
Version: 1.5
Plugin URI: http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/
Author: Scott Reilly
Author URI: http://coffee2code.com/
Text Domain: expandable-dashboard-recent-comments
Domain Path: /lang/
Description: Adds the ability to do in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget to view full comments.

Compatible with WordPress 3.1+, 3.2+, 3.3+

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/expandable-dashboard-recent-comments/

TODO:
	* Make it possible for comments to start off expanded rather than collapsed?

*/

/*
Copyright (c) 2009-2012 by Scott Reilly (aka coffee2code)

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
		'more-text' => '&#x25bc;',
		'less-text' => '&#x25b2;'
	);

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.5
	 */
	public static function version() {
		return '1.5';
	}

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	public static function init() {
		add_action( 'load-index.php', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Initialize the config and register actions/filters
	 */
	public static function do_init() {
		load_plugin_textdomain( 'c2c_edrc', false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );
		self::$config = apply_filters( 'c2c_expandable_dashboard_recent_comments_config', self::$config );

		// Hook the comment excerpt to do our magic
		add_filter( 'comment_excerpt',            array( __CLASS__, 'expandable_comment_excerpts' ) );
		// Enqueues JS for admin page
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_js' ) );
		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * Registers styles.
	 *
	 * @since 1.5
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__, plugins_url( 'assets/admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets.
	 *
	 * @since 1.5
	 */
	public static function enqueue_admin_css() {
		wp_enqueue_style( __CLASS__ );
	}

	/**
	 * Enqueues JS.
	 *
	 * @since 1.5
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

	/**
	 * Returns class name to be used for specific comment
	 *
	 * @since 1.3
	 * @param int|string|null The comment ID (or null to get the ID for the current comment)
	 * @return string The class
	 */
	private static function get_comment_class( $comment_id = null ) {
		if ( ! $comment_id ) {
			global $comment;
			$comment_id = $comment->comment_ID;
		}
		return "excerpt-$comment_id";
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
			$body       = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
			$class      = self::get_comment_class( $comment->comment_ID );
//			$ellipsis   = self::$config['remove-ellipsis'] ? '<span class="excerpt-ellipsis">&hellip;</span>' : '';
//			$_excerpt   = self::$config['remove-ellipsis'] ? substr( $excerpt, 0, -3 ) : $excerpt;
			$more       = self::$config['more-text'];
			$more_title = __( 'Show full comment', 'c2c_edrc' );
			$less       = self::$config['less-text'];
			$less_title = __( 'Show excerpt', 'c2c_edrc' );

			$extended = <<<HTML
			<div class='c2c_edrc'>
				<div class='{$class}-short excerpt-short'>
					$excerpt
					<div class='c2c_edrc_more'><a href='#' title='{$more_title}'>{$more}</a></div>
				</div>
				<div class='{$class}-full excerpt-full' style='display:none;'>
					$body
					<div class='c2c_edrc_less'><a href='#' title='{$less_title}'>{$less}</a></div>
				</div>
			</div>

HTML;

			$excerpt = preg_replace( '/\.\.\.$/', $excerpt, $extended );
		}
		return $excerpt;
	}

} // end c2c_ExpandableDashboardRecentComments

c2c_ExpandableDashboardRecentComments::init();

endif; // end if !class_exists()

?>