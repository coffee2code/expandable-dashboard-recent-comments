<?php

class Expandable_Dashboard_Recent_Comments_Test extends WP_UnitTestCase {

	function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_ExpandableDashboardRecentComments' ) );
	}

	function test_get_version() {
		$this->assertEquals( '2.4.1', c2c_ExpandableDashboardRecentComments::version() );
	}

	function test_hooks_action_admin_menu() {
		$this->assertEquals( 10, add_action( 'load-index.php', array( 'c2c_ExpandableDashboardRecentComments', 'do_init' ) ) );
	}

	/*
	 * Ensure nothing is affected on the front-end.
	 */

	function test_not_hooks_filter_comment_excerpt() {
		$this->assertFalse( has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	function test_not_hooks_filter_comment_row_action() {
		$this->assertFalse( has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	function test_not_hooks_action_admin_enqueue_script_for_js() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	function test_not_hooks_action_admin_enqueue_scripts_for_css() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

	/*
	 * Ensure nothing is affected o the back-end.
	 */

	// Note: All tests that follow must assume they are in the admin.
	function tesst_is_admin() {
		define( 'WP_ADMIN', true );

		$this->assertTrue( is_admin() );
	}

	function test_admin_not_hooks_filter_comment_excerpt() {
		$this->assertFalse( has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	function test_admin_not_hooks_filter_comment_row_action() {
		$this->assertFalse( has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	function test_admin_not_hooks_action_admin_enqueue_script_for_js() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	function test_admin_not_hooks_action_admin_enqueue_scripts_for_css() {
		$this->assertFalse( has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

	/*
	 * Ensure it does its thing on the admin dashboard.
	 */

	function test_admin_hooks_filter_comment_excerpt() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_filter( 'comment_excerpt', array( 'c2c_ExpandableDashboardRecentComments', 'expandable_comment_excerpts' ) ) );
	}

	function test_admin_hooks_filter_comment_row_action() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_filter( 'comment_row_actions', array( 'c2c_ExpandableDashboardRecentComments', 'comment_row_action' ), 10, 2 ) );
	}

	function test_admin_hooks_action_admin_enqueue_script_for_js() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_js' ) ) );
	}

	function test_admin_hooks_action_admin_enqueue_scripts_for_css() {
		do_action( 'load-index.php' );

		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( 'c2c_ExpandableDashboardRecentComments', 'enqueue_admin_css' ) ) );
	}

}
