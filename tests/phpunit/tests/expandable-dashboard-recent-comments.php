<?php

defined( 'ABSPATH' ) or die();

class unittest_c2c_ExpandableDashboardRecentComments extends c2c_ExpandableDashboardRecentComments {
	public static function is_comment_initially_expanded( $comment ) {
		return parent::is_comment_initially_expanded( $comment );
	}

	public static function is_text_excerpted( $text ) {
		return parent::is_text_excerpted( $text );
	}

	public static function get_comment_class( $comment_id = null ) {
		return parent::get_comment_class( $comment_id );
	}
}


class Expandable_Dashboard_Recent_Comments_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();

		c2c_ExpandableDashboardRecentComments::reset();

		unset( $GLOBALS['comment'] );

		wp_deregister_style( 'c2c_ExpandableDashboardRecentComments' );
		wp_dequeue_style( 'c2c_ExpandableDashboardRecentComments' );
		wp_deregister_script( 'c2c_ExpandableDashboardRecentComments' );
		wp_dequeue_script( 'c2c_ExpandableDashboardRecentComments' );
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_ExpandableDashboardRecentComments' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '2.8.2', c2c_ExpandableDashboardRecentComments::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_ExpandableDashboardRecentComments', 'init' ) ) );
	}

	public function test_hooks_action_admin_menu() {
		$this->assertEquals( 10, has_action( 'load-index.php', array( 'c2c_ExpandableDashboardRecentComments', 'do_init' ) ) );
	}

	//
	// Ensure nothing is affected on the front-end.
	//

	public function test_not_hooks_filter_comment_excerpt() {
		$this->assertFalse( has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	public function test_not_hooks_filter_comment_row_action() {
		$this->assertFalse( has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	public function test_not_hooks_action_admin_enqueue_script_for_js() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	public function test_not_hooks_action_admin_enqueue_scripts_for_css() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

	public function test_comment_excerpt_does_not_add_markup_outside_of_admin() {
		$text = 'This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the excerpt generated for the comment will be a truncated version of the full comment.';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );

		$expected = wp_trim_words( $text, 20 );

		$this->expectOutputString( $expected );

		comment_excerpt();
	}

	//
	// Ensure nothing is affected on the back-end.
	//

	public function test_admin_not_hooks_filter_comment_excerpt() {
		$this->assertFalse( has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	public function test_admin_not_hooks_filter_comment_row_action() {
		$this->assertFalse( has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	public function test_admin_not_hooks_action_admin_enqueue_script_for_js() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	public function test_admin_not_hooks_action_admin_enqueue_scripts_for_css() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

	public function test_admin_not_hooks_action_get_comment_excerpt() {
		$this->assertFalse( has_action( 'get_comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'fix_multibyte_comment_excerpts' ) ) );
	}

	/*
	 * register_styles()
	 */

	public function test_register_styles() {
		$this->assertFalse( wp_style_is( 'c2c_ExpandableDashboardRecentComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_ExpandableDashboardRecentComments', 'enqueued' ) );

		c2c_ExpandableDashboardRecentComments::register_styles();

		$this->assertTrue( wp_style_is( 'c2c_ExpandableDashboardRecentComments', 'registered' ) );
		$this->assertFalse( wp_style_is( 'c2c_ExpandableDashboardRecentComments', 'enqueued' ) );
	}

	/*
	 * enqueue_admin_css()
	 */

	public function test_enqueue_admin_css() {
		$this->test_register_styles();

		c2c_ExpandableDashboardRecentComments::enqueue_admin_css();

		$this->assertTrue( wp_style_is( 'c2c_ExpandableDashboardRecentComments', 'enqueued' ) );
	}

	/*
	 * enqueue_admin_js()
	 */

	public function test_enqueue_admin_js() {
		$this->assertFalse( wp_script_is( 'c2c_ExpandableDashboardRecentComments', 'registered' ) );
		$this->assertFalse( wp_script_is( 'c2c_ExpandableDashboardRecentComments', 'enqueued' ) );

		c2c_ExpandableDashboardRecentComments::enqueue_admin_js();

		$this->assertTrue( wp_script_is( 'c2c_ExpandableDashboardRecentComments', 'registered' ) );
		$this->assertTrue( wp_script_is( 'c2c_ExpandableDashboardRecentComments', 'enqueued' ) );
	}

	/*
	 * Constant: ELLIPSIS
	 */

	public function test_ellipsis_constant() {
		$this->assertEquals( '&hellip;', c2c_ExpandableDashboardRecentComments::ELLIPSIS );
	}

	//
	// Ensure it does its thing on the admin dashboard.
	//

	public function test_admin_hooks_filter_comment_excerpt() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	public function test_admin_hooks_filter_comment_row_action() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	public function test_admin_hooks_action_admin_enqueue_script_for_js() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	public function test_admin_hooks_action_admin_enqueue_scripts_for_css() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

	public function test_admin_hooks_action_get_comment_excerpt() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_action( 'get_comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'fix_multibyte_comment_excerpts' ) ) );
	}

	/*
	 * is_comment_initially_expanded()
	 */

	public function test_is_comment_initially_expanded() {
		$comment = $this->factory->comment->create( array( 'comment_approved' => '1' ) );

		$this->assertFalse( unittest_c2c_ExpandableDashboardRecentComments::is_comment_initially_expanded( $comment ) );
	}

	/*
	 * filter: c2c_expandable_dashboard_recent_comments_start_expanded
	 */

	public function test_filter_c2c_expandable_dashboard_recent_comments_start_expanded() {
		add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_true' );

		$comment = $this->factory->comment->create( array( 'comment_approved' => '1' ) );

		$this->assertTrue( unittest_c2c_ExpandableDashboardRecentComments::is_comment_initially_expanded( $comment ) );
	}

	public function test_filter_c2c_expandable_dashboard_recent_comments_start_collapsed() {
		add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_false' );

		$comment = $this->factory->comment->create( array( 'comment_approved' => '1' ) );

		$this->assertFalse( unittest_c2c_ExpandableDashboardRecentComments::is_comment_initially_expanded( $comment ) );
	}

	/*
	 * is_text_excerpted()
	 */

	public function test_is_text_excerpted() {
		$this->assertTrue( unittest_c2c_ExpandableDashboardRecentComments::is_text_excerpted( 'This is excerpted&hellip;' ) );
		$this->assertFalse( unittest_c2c_ExpandableDashboardRecentComments::is_text_excerpted( 'This is not excerpted.' ) );
	}

	/*
	 * comment_row_action()
	 */

	public function test_comment_row_action_for_non_excerpted_comment() {
		$comment = $this->factory->comment->create_and_get( array( 'comment_approved' => '1', 'comment_content' => 'Short comment.' ) );

		$expected = array( 'action1' => 'action1 markup', 'action2' => 'action2 markup' );

		$this->assertEquals( $expected, c2c_ExpandableDashboardRecentComments::comment_row_action( $expected, $comment ) );
	}

	public function test_comment_row_action_for_excerpted_comment() {
		$text = 'This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the excerpt generated for the comment will be a truncated version of the full comment.';
		$comment = $this->factory->comment->create_and_get( array( 'comment_approved' => '1', 'comment_content' => $text ) );

		$links = '<a href="#" aria-controls="excerpt-full-' . $comment->comment_ID . '" aria-expanded="true" class="c2c_edrc_more hide-if-no-js " title="Show full comment">Show more</a>'
			. '<a href="#" aria-controls="excerpt-short-' . $comment->comment_ID . '" aria-expanded="false" class="c2c_edrc_less hide-if-no-js c2c-edrc-hidden" title="Show excerpt">Show less</a>';
		$base_expected = array( 'action1' => 'action1 markup', 'action2' => 'action2 markup' );
		$expected = array_merge( $base_expected, array( 'expand-collapse' => $links ) );

		$this->assertEquals( $expected, c2c_ExpandableDashboardRecentComments::comment_row_action( $base_expected, $comment ) );
	}

	public function test_comment_row_action_for_excerpted_comment_with_initial_expansion() {
		add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_true' );
		$text = 'This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the excerpt generated for the comment will be a truncated version of the full comment.';
		$comment = $this->factory->comment->create_and_get( array( 'comment_approved' => '1', 'comment_content' => $text ) );

		$links = '<a href="#" aria-controls="excerpt-full-' . $comment->comment_ID . '" aria-expanded="false" class="c2c_edrc_more hide-if-no-js c2c-edrc-hidden" title="Show full comment">Show more</a>'
			. '<a href="#" aria-controls="excerpt-short-' . $comment->comment_ID . '" aria-expanded="true" class="c2c_edrc_less hide-if-no-js " title="Show excerpt">Show less</a>';
		$base_expected = array( 'action1' => 'action1 markup', 'action2' => 'action2 markup' );
		$expected = array_merge( $base_expected, array( 'expand-collapse' => $links ) );

		$this->assertEquals( $expected, c2c_ExpandableDashboardRecentComments::comment_row_action( $base_expected, $comment ) );
	}

	/*
	 * get_comment_class()
	 */

	public function test_get_comment_class() {
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => 'Short comment.' ) );

		$this->assertEquals( "excerpt-{$comment_id}", unittest_c2c_ExpandableDashboardRecentComments::get_comment_class( $comment_id ) );
	}

	public function test_get_comment_class_for_global_comment() {
		global $comment;
		$comment = $this->factory->comment->create_and_get( array( 'comment_approved' => '1', 'comment_content' => 'Short comment.' ) );

		$this->assertEquals( "excerpt-{$comment->comment_ID}", unittest_c2c_ExpandableDashboardRecentComments::get_comment_class() );

		unset( $GLOBALS['comment'] );
	}

	public function test_get_comment_class_for_unknown_comment() {
		$this->assertEmpty( unittest_c2c_ExpandableDashboardRecentComments::get_comment_class() );
	}

	/*
	 * expandable_comment_excerpts()
	 */

	public function test_expandable_comment_excerpts_does_not_change_non_truncated_excerpts() {
		$text = 'This is a comment.';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );

		$this->assertEquals( $text, c2c_ExpandableDashboardRecentComments::expandable_comment_excerpts( $text ) );
	}

	public function test_expandable_comment_excerpts_changes_truncated_excerpts() {
		$text = 'This is an excerpted comment&hellip;';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );

		$output = c2c_ExpandableDashboardRecentComments::expandable_comment_excerpts( $text );

		$this->assertNotEquals( $text, $output );
		$this->assertRegExp( "/<div class='c2c_edrc'>/", $output );
	}

	/*
	 * Comment excerpts
	 */

	public function test_comment_excerpt_has_markup_for_expansion() {
		$text = 'This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the excerpt generated for the comment will be a truncated version of the full comment.';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );

		$expected = <<<HTML
		<div class='c2c_edrc'>
			<div id="excerpt-short-{$comment_id}" class="excerpt-{$comment_id}-short excerpt-short " aria-hidden="false">
				This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the&hellip;
			</div>
			<div id="excerpt-full-{$comment_id}" class="excerpt-{$comment_id}-full excerpt-full c2c-edrc-hidden" aria-hidden="true">
				<p>{$text}</p>

				<ul class="c2c_edrc_all"><li>| <a href="#" aria-controls="the-comment-list" aria-expanded="true" class="c2c_edrc_more_all hide-if-no-js" title="Show all comments in full">Expand all <span class="count c2c_edrc_more_count"></span></a> |</li><li><a href="#" aria-controls="the-comment-list" aria-expanded="false" class="c2c_edrc_less_all hide-if-no-js" title="Show all comments as excerpts">Collapse all <span class="count c2c_edrc_less_count"></span></a></li></ul>
			</div>
		</div>

HTML;

		$this->expectOutputString( $expected );

		do_action( 'load-index.php' );

		comment_excerpt();
	}

	public function test_comment_excerpt_omits_show_all_links_once_initially_output() {
		$text = 'This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the excerpt generated for the comment will be a truncated version of the full comment.';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );

		$expected = <<<HTML
		<div class='c2c_edrc'>
			<div id="excerpt-short-{$comment_id}" class="excerpt-{$comment_id}-short excerpt-short " aria-hidden="false">
				This is a longer comment that will exceed the number of words that are permitted for excerpts. As such, the&hellip;
			</div>
			<div id="excerpt-full-{$comment_id}" class="excerpt-{$comment_id}-full excerpt-full c2c-edrc-hidden" aria-hidden="true">
				<p>{$text}</p>

			</div>
		</div>

HTML;

		do_action( 'load-index.php' );

		// Do initial invocation as a first call.
		ob_start();
		comment_excerpt();
		ob_get_clean();

		$this->expectOutputString( $expected );

		comment_excerpt();
	}

	/*
	 * fix_multibyte_comment_excerpts()
	 */

	public function test_fix_multibyte_comment_excerpts_warranting_no_change() {
		$long_text = 'aaa bbb ccc ddd eee fff ggg hhh iii jjj kkk lll mmm nnn ooo ppp qqq rrr sss ttt uuu vvv www xxx yyy zzz';
		$text = array(
			'This is plain text.',
			'Short 創於頭安片我樣外.',
			'創於頭安片我樣外市第興強有輕注該仍也天筆',
			"Already truncated {$long_text}&hellip;",
			$long_text,
		);

		foreach ( $text as $t ) {
			$this->assertEquals( $t, c2c_ExpandableDashboardRecentComments::fix_multibyte_comment_excerpts( $t ) );
		}
	}

	public function test_fix_multibyte_comment_excerpts_warranting_change() {
		$this->assertEquals( '創於頭安片我樣外市第興強有輕注該仍也天筆國&hellip;', c2c_ExpandableDashboardRecentComments::fix_multibyte_comment_excerpts( '創於頭安片我樣外市第興強有輕注該仍也天筆國花門一' ) );
	}

	public function test_comment_excerpt_with_multibyte_comment() {
		$text = '創於頭安片我樣外市第興強有輕注該仍也天筆國花門一。

影沒跟流人在車教腦他近隨、的城一了是大？溫之速學引衣。不動第變答、三的。

拿下產後國花門一下二是醫成一斷有產，護場好？科求回離流首就的的明會復題的不背！來一是導路你斷大代細機裡生似北好外然，女勢機雖可望是真。公升我被人微嗎來那提類好發些一民方示童，吃的受生展管產白關告強生長報來部所下之區位車電門計孩度皮報便去己教代，人沒也好中值：是難一動功者兒也客天二產絕方。因在的離民或見臺想草的示作更容學，技是公戲他講一到電雖離他腦是的水度？光操面吃血布和西少會女，天建的中上學到能人態白樹如爭方點有情的情片對？是義讓要把不時達回求國。自只滿省國往真專能了。大全入士已當人高；車本卻：製收的政民，層卻些意：照員里難國加是來以太顯樓推海化教，能車共其頭？聯有會，許研時對加像身家說性？華會會研，影些你長西軍和：一他算學料時今件不基是，我畫起；治神去小……多理機了科方覺草那重例力更金社，拉國史在集起程一陸。心去期但相如來見準汽我！世你金大了先客百不。造相術對如不你世畫一有！那大主來樣孩不；望一總王成；相生火足復天手香人圖件處魚一。父亞了樣的喜教生提才愛然吃要道足無，不社質，這造子成，存合子電喜場，魚子出法會、一度於生聽，做單的公角系考，故夠童響活？害地表是的分議候場新良就響能出這創音和制裡本古為市給的規送指裡，了服量聲始濟，手馬意高是土解代行歷層外，聯德假顧，古主人多、決是明奇講中認認常滿發軍難命但三。們雖這坡關送道是影由大初同太縣後我土當接，你流負酒：是林龍人地們者才於子動引看公買天地。友麼定……會西步……青手驚積';
		$comment_id = $this->factory->comment->create( array( 'comment_approved' => '1', 'comment_content' => $text ) );
		$GLOBALS['comment'] = get_comment( $comment_id );
		$text = wpautop( $text );

		$expected = <<<HTML
		<div class='c2c_edrc'>
			<div id="excerpt-short-{$comment_id}" class="excerpt-{$comment_id}-short excerpt-short " aria-hidden="false">
				創於頭安片我樣外市第興強有輕注該仍也天筆國&hellip;
			</div>
			<div id="excerpt-full-{$comment_id}" class="excerpt-{$comment_id}-full excerpt-full c2c-edrc-hidden" aria-hidden="true">
				{$text}
				<ul class="c2c_edrc_all"><li>| <a href="#" aria-controls="the-comment-list" aria-expanded="true" class="c2c_edrc_more_all hide-if-no-js" title="Show all comments in full">Expand all <span class="count c2c_edrc_more_count"></span></a> |</li><li><a href="#" aria-controls="the-comment-list" aria-expanded="false" class="c2c_edrc_less_all hide-if-no-js" title="Show all comments as excerpts">Collapse all <span class="count c2c_edrc_less_count"></span></a></li></ul>
			</div>
		</div>

HTML;

		$this->expectOutputString( $expected );

		do_action( 'load-index.php' );

		comment_excerpt();
	}

}
