<?php
/**
 * Plugin Name: Expandable Dashboard Recent Comments
 * Version:     3.0
 * Plugin URI:  https://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: expandable-dashboard-recent-comments
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Enables in-place expansion of excerpts in the admin dashboard 'Comments' section of the 'Activity' widget to view full comments.
 *
 * Compatible with WordPress 4.6+ through 6.8+, and PHP through at least 8.3+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/expandable-dashboard-recent-comments/
 *
 * @package Expandable_Dashboard_Recent_Comments
 * @author  Scott Reilly
 * @version 3.0
 */

/*
	Copyright (c) 2009-2025 by Scott Reilly (aka coffee2code)

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
	 * The string used to represent an ellipsis.
	 *
	 * This is used by core and shouldn't be changed.
	 *
	 * @since 2.9
	 * @var string
	 */
	const ELLIPSIS = '&hellip;';

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
		return '3.0';
	}

	/**
	 * Initialization.
	 */
	public static function init() {
		add_action( 'load-index.php', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Resets plugin state.
	 *
	 * @since 2.6.0
	 */
	public static function reset() {
		self::$_has_output_all_links = false;
	}

	/**
	 * Loads text domain and registers actions/filters.
	 */
	public static function do_init() {
		// Hook the comment excerpt to do our magic
		add_filter( 'comment_excerpt',            array( __CLASS__, 'expandable_comment_excerpts' )        );
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
		wp_register_style( __CLASS__, plugins_url( 'assets/admin.css', __FILE__ ), array(), self::version() );
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
		wp_enqueue_script( __CLASS__, plugins_url( 'assets/admin.js', __FILE__ ), array(), self::version(), true );
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
		return ( substr( $text, -8 ) === self::ELLIPSIS );
	}

	/**
	 * Returns class name to be used for specific comment.
	 *
	 * @access protected
	 * @since 1.3
	 * @since 2.8 Changed from private to protected.
	 *
	 * @param  int|string|null $comment_id The comment ID (or null to get the ID for the current comment).
	 * @return string                      The class.
	 */
	protected static function get_comment_class( $comment_id = null ) {
		if ( ! $comment_id ) {
			global $comment;
			if ( $comment instanceof WP_Comment ) {
				$comment_id = $comment->comment_ID;
			}
		}

		return $comment_id ? "excerpt-{$comment_id}" : '';
	}

	/**
	 * Modifies a comment excerpt to add the full comment so it is available for expansion.
	 *
	 * @param  string  $excerpt Excerpt.
	 * @return string           The excerpt modified to have full comment when applicable.
	 */
	public static function expandable_comment_excerpts( $excerpt ) {
		global $comment;

		// Bail if text is not excerpted.
		if ( ! self::is_text_excerpted( $excerpt ) ) {
			return $excerpt;
		}

		/** This filter documented in wp-includes/comment-template.php */
		$body    = apply_filters( 'comment_text', apply_filters( 'get_comment_text', $comment->comment_content ), '40' );
		$comment_id = $comment->comment_ID;
		$class      = self::get_comment_class( $comment_id );

		$start_expanded = self::is_comment_initially_expanded( $comment );
		$excerpt_full_class  = $start_expanded ? '' : 'c2c-edrc-hidden';
		$excerpt_short_class = $start_expanded ? 'c2c-edrc-hidden' : '';
		$excerpt_short_hidden = $start_expanded ? 'true' : 'false';
		$excerpt_long_hidden  = $start_expanded ? 'false' : 'true';

		$links = '';

		// Append "show more" link to excerpt.
		$excerpt .= sprintf(
			' <span class="hide-if-no-js">(<a href="#" aria-controls="%s" aria-expanded="%s" class="c2c_edrc_more" title="%s">%s</a>)</span>',
			esc_attr( "excerpt-full-{$comment_id}" ),
			esc_attr( $excerpt_short_hidden ),
			esc_attr__( 'Show full comment', 'expandable-dashboard-recent-comments' ),
			esc_html__( 'show more', 'expandable-dashboard-recent-comments' )
		);

		// Append "show less" link to full body.
		$body .= sprintf(
			"\t\t" . '<p class="hide-if-no-js">(<a href="#" aria-controls="%s" aria-expanded="%s" class="c2c_edrc_less" title="%s">%s</a>)</p>',
			esc_attr( "excerpt-short-{$comment_id}" ),
			esc_attr( $excerpt_long_hidden ),
			esc_attr__( 'Show excerpt', 'expandable-dashboard-recent-comments' ),
			esc_html__( 'show less', 'expandable-dashboard-recent-comments' )
		);

		// Append the Expand/Collapse All links once.
		if ( false === self::$_has_output_all_links ) {
			// These links apply to the entire widget. Due to lack of hooks in WP, they
			// are being embedded here with the intent of being relocated via JS.
			$links .= "\n\t\t";
			$links .= '<ul class="c2c_edrc_all hide-if-no-js">';
			$links .= sprintf(
				'<li>| <a href="#" aria-controls="the-comment-list" aria-expanded="true" class="c2c_edrc_more_all" title="%s">%s <span class="count c2c_edrc_more_count"></span></a> |</li>',
				esc_attr__( 'Show all comments in full', 'expandable-dashboard-recent-comments' ),
				esc_html__( 'Expand all', 'expandable-dashboard-recent-comments' )
			);
			$links .= sprintf(
				'<li><a href="#" aria-controls="the-comment-list" aria-expanded="false" class="c2c_edrc_less_all" title="%s">%s <span class="count c2c_edrc_less_count"></span></a></li>',
				esc_attr__( 'Show all comments as excerpts', 'expandable-dashboard-recent-comments' ),
				esc_html__( 'Collapse all', 'expandable-dashboard-recent-comments' )
			);
			$links .= '</ul>';
			self::$_has_output_all_links = true;
		}

		$extended = "<div class='c2c_edrc'>\n";

		$extended .= sprintf(
			"\t" . '<div id="excerpt-short-%s" class="%s-short excerpt-short %s" aria-hidden="%s">' . "\n",
			esc_attr( $comment_id ),
			esc_attr( $class ),
			esc_attr( $excerpt_short_class ),
			esc_attr( $excerpt_short_hidden )
		);
		$extended .= "\t\t" . $excerpt . "\n";
		$extended .= "\t</div>\n";

		$extended .= sprintf(
			"\t" . '<div id="excerpt-full-%s" class="%s-full excerpt-full %s" aria-hidden="%s">' . "\n",
			esc_attr( $comment_id ),
			esc_attr( $class ),
			esc_attr( $excerpt_full_class ),
			esc_attr( $excerpt_long_hidden )
		);
		$extended .= "\t\t" . $body . $links . "\n";
		$extended .= "\t</div>\n";

		$extended .= "</div>\n";

		$excerpt = preg_replace( '/' . preg_quote( self::ELLIPSIS ) . '$/', $excerpt, $extended );

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
		// Bail if text is excerpted.
		if ( self::is_text_excerpted( $excerpt ) ) {
			return $excerpt;
		}

		// Bail if excerpt doesn't contain multi-byte characters.
		if ( mb_check_encoding( $excerpt, 'ASCII' ) || ! mb_check_encoding( $excerpt, 'UTF-8' ) ) {
			return $excerpt;
		}

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
			$excerpt .= self::ELLIPSIS;
		}

		return $excerpt;
	}

} // end c2c_ExpandableDashboardRecentComments

add_action( 'plugins_loaded', array( 'c2c_ExpandableDashboardRecentComments', 'init' ) );

endif; // end if !class_exists()
