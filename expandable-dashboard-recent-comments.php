<?php
/**
 * Plugin Name: Expandable Dashboard Recent Comments
 * Version:     2.5.3
 * Plugin URI:  http://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: expandable-dashboard-recent-comments
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Enables in-place expansion of excerpts in the admin dashboard 'Comments' section of the 'Activity' widget to view full comments.
 *
 * Compatible with WordPress 4.6+ through 5.3+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/expandable-dashboard-recent-comments/
 *
 * @package Expandable_Dashboard_Recent_Comments
 * @author  Scott Reilly
 * @version 2.5.3
 */

/*
 * TODO:
 * - Move the Expand all/Collapse all links inside the existing subsubsub action list?
 * - Include count of how many comments would be affected by each of "Expand all"
 *   and "Collapse all".
 * - Add unit tests for the filter and currently-private functions
 * - Switch from using 'a' tag to maybe 'button'
 */

/*
	Copyright (c) 2009-2020 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_ExpandableDashboardRecentComments' ) ) :

class c2c_ExpandableDashboardRecentComments {
	/**
	 * Memoized state indicating if there is need to putput links for controls
	 * for multiple comments.
	 *
	 * @access private
	 * @var    bool
	 */
	private static $_has_output_all_links = false;

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.0
	 */
	public static function version() {
		return '2.5.3';
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
		// Load textdomain
		load_plugin_textdomain( 'expandable-dashboard-recent-comments' );

		// Hook the comment excerpt to do our magic
		add_filter( 'comment_excerpt',            array( __CLASS__, 'expandable_comment_excerpts' )        );
		// Add action link to comment row
		add_filter( 'comment_row_actions',        array( __CLASS__, 'comment_row_action'          ), 10, 2 );
		// Enqueues JS for admin page
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_js'            )        );
		// Modify default WP behavior to ensure comments with multi-byte characters get excerpted.
		add_filter( 'get_comment_excerpt',        array( __CLASS__, 'fix_multibyte_comment_excerpts' )     );

		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts',      array( __CLASS__, 'enqueue_admin_css'           )        );
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
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

	/**
	 * Indicates if the given comment should be initially shown expanded.
	 *
	 * @since 2.0
	 *
	 * @param  object $comment The comment being displayed
	 * @return bool
	 */
	protected static function is_comment_initially_expanded( $comment ) {
		/**
		 * Filters whether a comment should be initially shown expanded or not.
		 *
		 * @since 2.0.0
		 *
		 * @param bool   $initially_expanded Initially show comment as expanded? Default false.
		 * @param object $comment            The comment object.
		 */
		return (bool) apply_filters( 'c2c_expandable_dashboard_recent_comments_start_expanded', false, $comment );
	}

	/**
	 * Determines if text has been truncated as an excerpt.
	 *
	 * Note: This is by no means a comprehensive check. Currently, text that gets
	 * excerpted gets appended with "&hellip;". This merely checks for the
	 * presence of that string at the end of the text and assumes the text has
	 * been excerpted if found.
	 *
	 * @since 2.2
	 *
	 * @param  string $text The text.
	 * @return bool
	 */
	protected static function is_text_excerpted( $text ) {
		return ( substr( $text, -8 ) === '&hellip;' );
	}

	/**
	 * Adds comment row action.
	 *
	 * @since 2.0
	 *
	 * @param  array      $actions The actions being displayed for the comment entry.
	 * @param  WP_Comment $comment The comment being displayed.
	 * @return array               The actions for the comment entry.
	 */
	public static function comment_row_action( $actions, $comment ) {
		$excerpt = get_comment_excerpt( $comment->comment_ID );

		$start_expanded = self::is_comment_initially_expanded( $comment );
		$excerpt_full_class  = $start_expanded ? 'c2c-edrc-hidden' : '';
		$excerpt_short_class = $start_expanded ? '' : 'c2c-edrc-hidden"';

		// Only show the action links if the comment was excerpted
		if ( self::is_text_excerpted( $excerpt ) ) {
			$links = sprintf(
				'<a href="#" class="c2c_edrc_more hide-if-no-js %s" title="%s">%s</a>',
				$excerpt_full_class,
				esc_attr__( 'Show full comment', 'expandable-dashboard-recent-comments' ),
				__( 'Show more', 'expandable-dashboard-recent-comments' )
			);
			$links .= sprintf(
				'<a href="#" class="c2c_edrc_less hide-if-no-js %s" title="%s">%s</a>',
				$excerpt_short_class,
				esc_attr__( 'Show excerpt', 'expandable-dashboard-recent-comments' ),
				__( 'Show less', 'expandable-dashboard-recent-comments' )
			);
			$actions[] = $links;
		}
		return $actions;
	}

	/**
	 * Returns class name to be used for specific comment.
	 *
	 * @since 1.3
	 *
	 * @param  int|string|null $comment_id The comment ID (or null to get the ID for the current comment).
	 * @return string                      The class.
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
	 * @param  string  $excerpt Excerpt.
	 * @return string           The excerpt modified to have full comment when applicable.
	 */
	public static function expandable_comment_excerpts( $excerpt ) {
		global $comment;
		if ( self::is_text_excerpted( $excerpt ) ) {
			$replace = '&hellip;';
			/** This filter documented in wp-includes/comment-template.php */
			$body    = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
			$class   = self::get_comment_class( $comment->comment_ID );

			$start_expanded = self::is_comment_initially_expanded( $comment );
			$excerpt_full_class  = $start_expanded ? '' : 'c2c-edrc-hidden';
			$excerpt_short_class = $start_expanded ? 'c2c-edrc-hidden' : '';

			$links = '';
			if ( false == self::$_has_output_all_links ) {
				// These links apply to the entire widget. Due to lack of hooks in WP, they
				// are being embedded here with the intent of being relocated via JS.
				$links .= '<ul class="subsubsub c2c_edrc_all">';
				$links .= sprintf(
					'<li><a href="#" class="c2c_edrc_more_all hide-if-no-js" title="%s">%s</a> |</li>',
					esc_attr__( 'Show all comments in full', 'expandable-dashboard-recent-comments' ),
					__( 'Expand all', 'expandable-dashboard-recent-comments' )
				);
				$links .= sprintf(
					'<li><a href="#" class="c2c_edrc_less_all hide-if-no-js" title="%s">%s</a></li>',
					esc_attr__( 'Show all comments as excerpts', 'expandable-dashboard-recent-comments' ),
					__( 'Collapse all', 'expandable-dashboard-recent-comments' )
				);
				$links .= '</ul>';
				self::$_has_output_all_links = true;
			}

			$extended = <<<HTML
			<div class='c2c_edrc'>
				<div class='{$class}-short excerpt-short {$excerpt_short_class}'>
					$excerpt
				</div>
				<div class='{$class}-full excerpt-full {$excerpt_full_class}'>
					$body
					$links
				</div>
			</div>

HTML;

			$excerpt = preg_replace( '/' . preg_quote( $replace ) . '$/', $excerpt, $extended );
		}

		return $excerpt;
	}

	/**
	 * Modifies default WP behavior to ensure comments with multi-byte characters get excerpted.
	 *
	 * @since 2.6
	 *
	 * @param  string  $excerpt Excerpt.
	 * @return string  The excerpt, potentially modified to actually be excerpted
	 *                 if it contains multi-byte characters.
	 */
	public static function fix_multibyte_comment_excerpts( $excerpt ) {
		if (
			// Excerpt not already excerpted.
			! self::is_text_excerpted( $excerpt )
		&&
			// Excerpt contains multi-byte characters and wasn't truncated.
			! mb_check_encoding( $excerpt, 'ASCII' ) && mb_check_encoding( $excerpt, 'UTF-8' )
		) {
			/* translators: Maximum number of words used in a comment excerpt. */
			$comment_excerpt_length = intval( _x( '20', 'comment_excerpt_length', 'expandable-dashboard-recent-comments' ) );

			/**
			 * This filter is documented in wp-includes/comment-template.php.
			 */
			$comment_excerpt_length = apply_filters( 'comment_excerpt_length', $comment_excerpt_length );

			// Cribbed from wp_trim_words().
			$excerpt = trim( preg_replace( "/[\n\r\t ]+/", ' ', $excerpt ), ' ' );
			preg_match_all( '/./u', $excerpt, $words_array );
			$char_count = count( $words_array[0] );
			$words_array = array_slice( $words_array[0], 0, $comment_excerpt_length + 1 );
			$excerpt = implode( '', $words_array );

			if ( $char_count > $comment_excerpt_length ) {
				$excerpt .= '&hellip;';
			}
		}

		return $excerpt;
	}

} // end c2c_ExpandableDashboardRecentComments

add_action( 'plugins_loaded', array( 'c2c_ExpandableDashboardRecentComments', 'init' ) );

endif; // end if !class_exists()
