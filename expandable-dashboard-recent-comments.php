<?php
/**
 * @package Expandable_Dashboard_Recent_Comments
 * @author Scott Reilly
 * @version 2.0
 */
/*
Plugin Name: Expandable Dashboard Recent Comments
Version: 2.0
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
	private static $_start_expanded       = null;
	private static $_has_output_all_links = false;

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.0
	 */
	public static function version() {
		return '2.0';
	}

	/**
	 * Initialization.
	 */
	public static function init() {
		add_action( 'load-index.php', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Loads text domain and registers actions/filters.
	 */
	public static function do_init() {
		load_plugin_textdomain( 'c2c_edrc', false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );

		// Hook the comment excerpt to do our magic
		add_filter( 'comment_excerpt',            array( __CLASS__, 'expandable_comment_excerpts' ) );
		// Add action link to comment row
		add_filter( 'comment_row_actions',        array( __CLASS__, 'comment_row_action' ), 10, 2 );
		// Enqueues JS for admin page
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_js' ) );
		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_css' ) );
	}

	/**
	 * Registers styles.
	 *
	 * @since 2.0
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__, plugins_url( 'assets/admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets.
	 *
	 * @since 2.0
	 */
	public static function enqueue_admin_css() {
		wp_enqueue_style( __CLASS__ );
	}

	/**
	 * Enqueues JS.
	 *
	 * @since 2.0
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

	/**
	 * Indicates if the given comment should be initially shown expanded.
	 *
	 * @since 2.0
	 *
	 * @return boolean
	 */
	private static function is_comment_initially_expanded() {
		if ( null === self::$_start_expanded )
			self::$_start_expanded = apply_filters( 'c2c_expandable_dashboard_recent_comments_start_expanded', false );
		return self::$_start_expanded;
	}

	/**
	 * Adds comment row action.
	 *
	 * @since 2.0
	 */
	public static function comment_row_action( $actions, $comment ) {
		$excerpt = get_comment_excerpt( $comment->comment_ID );

		$start_expanded = self::is_comment_initially_expanded();
		$excerpt_full   = $start_expanded ? 'style="display:none;"' : '';
		$excerpt_short  = $start_expanded ? '' : 'style="display:none;"';

		// Only show the action links if the comment was excerpted
		if ( substr( $excerpt, -3 ) == '...' ) {
			$links = '<a href="#" class="c2c_edrc_more hide-if-no-js" title="' . __( 'Show full comment', 'c2c_edrc' ) . '" ' . $excerpt_full . '>' . __( 'Show more', 'c2c_edrc' ). '</a>';
			$links .= '<a href="#" class="c2c_edrc_less hide-if-no-js" title="' . __( 'Show excerpt', 'c2c_edrc' ). '" ' . $excerpt_short . '>' . __( 'Show less', 'c2c_edrc' ) . '</a>';
			$actions[] = $links;
		}
		return $actions;
	}

	/**
	 * Returns class name to be used for specific comment
	 *
	 * @since 1.3
	 *
	 * @param int|string|null $comment_id The comment ID (or null to get the ID for the current comment)
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
	 * Modifies a comment excerpt to add the full comment so it is available for expansion.
	 *
	 * @param string $excerpt Excerpt
	 * @return string The $excerpt modified to have full comment when applicable
	 */
	public static function expandable_comment_excerpts( $excerpt ) {
		global $comment;
		if ( substr( $excerpt, -3 ) == '...' ) {
			$body       = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
			$class      = self::get_comment_class( $comment->comment_ID );

			$start_expanded = self::is_comment_initially_expanded();
			$excerpt_full   = $start_expanded ? '' : 'style="display:none;"';
			$excerpt_short  = $start_expanded ? 'style="display:none;"' : '';

			$links = '';
			if ( false == self::$_has_output_all_links ) {
				// These links apply to the entire widget. Due to lack of hooks in WP, they
				// are being embedded here with the intent of being relocated via JS.
				$links .= '<ul class="subsubsub c2c_edrc_all">';
				$links .= '<li><a href="#" class="c2c_edrc_more_all hide-if-no-js" title="' . __( 'Show all comments in full', 'c2c_edrc' ) . '">' . __( '&#x25bc; Expand all', 'c2c_edrc' ). '</a> |</li>';
				$links .= '<li><a href="#" class="c2c_edrc_less_all hide-if-no-js" title="' . __( 'Show all comments as excerpts', 'c2c_edrc' ). '">' . __( '&#x25b2; Collapse all', 'c2c_edrc' ) . '</a></li>';
				$links .= '</ul>';
				self::$_has_output_all_links = true;
			}

			$extended = <<<HTML
			<div class='c2c_edrc'>
				<div class='{$class}-short excerpt-short' {$excerpt_short}>
					$excerpt
				</div>
				<div class='{$class}-full excerpt-full' {$excerpt_full}>
					$body
					$links
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