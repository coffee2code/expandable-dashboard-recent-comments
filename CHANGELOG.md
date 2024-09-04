# Changelog

## _(in-progress)_
* Change: Note compatibility through WP 6.6+
* Change: Update copyright date (2024)

## 2.8.2 _(2021-09-16)_
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/` into `tests/`
        * Change: Move `phpunit/bin` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

## 2.8.1 _(2021-04-10)_
* Change: Escape text used in markup attributes (hardening)
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 2.8 _(2020-08-21)_

### Highlights:

This minor update features a rewrite of the JavaScript to use vanilla JS instead of jQuery, improves handling of some unlikely edgecase situations, restructures the unit test file structure, and notes compatibility through WP 5.5+.

### Details:

* Change: Rewrite JavaScript into vanilla JS and away from using jQuery
* Change: Prevent edgecase issues with `get_comment_class()`
    * Verify global comment is an actual comment object before use
    * Return an empty string if no comment ID is known
* Fix: Remove stray double-quote from comment row markup
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Unit tests:
    * New: Add unit tests for `comment_row_action()`, `enqueue_admin_css()`, `enqueue_admin_js()`, `get_comment_class()`, `register_styles()`
    * New: Add another unit test for `c2c_expandable_dashboard_recent_comments_start_expanded` filter
    * Change: Add comment separators to document test groupings
    * Change: Tweak code spacing
* Change: Note compatibility through WP 5.5+
* Change: Change `get_comments_class()` from `private` to `protected` to facilitate unit testing
* Change: Supplement FAQ with info regarding behavior when JS is disabled
* Change: Fix some typos in readme.txt

## 2.7 _(2020-04-30)_
* New: Add count of comments that could be affected by either "Expand all" or "Collapse all" next to both link, respectively
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add more items to the list)
* Change: Add more space between the expand/collapse down/up icons and their associated text
* Unit tests:
    * Fix: Fix typo in that an `add_action()` should be a `has_action()`
    * Fix: Fix typo in unit test function name preventing it from being run as a unit test
    * Change: Remove unnecessary `remove_filter()`
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS

## 2.6 _(2019-11-21)_
* New: Handle comments containing multi-byte characters that may not have been excerpted by WordPress
* New: Add `reset()` to reset plugin's internal state
* Change: Don't memoize value for if comment should appear initially expanded since it may vary based on comment
* Change: Switch `is_comment_initially_expanded()` and `is_text_excerpted()` from private to protected to facilitate unit testing
* Unit tests:
    * New: Add tests for admin dashboard comment excerpts
    * New: Add tests for `is_comment_initially_expanded()`
    * New: Add tests for `is_text_excerpted()`
    * New: Add tests for `expandable_comment_excerpts()`
    * New: Add test for the filter `c2c_expandable_dashboard_recent_comments_start_expanded`
    * Change: Remove unused test
    * Change: Invoke plugin's `reset()` after every test
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

## 2.5.3 _(2019-06-26)_
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * New: Test that plugin hooks `plugins_loaded` action to initialize itself
* Change: Note compatibility through WP 5.2+
* Change: Split paragraph in README.md's "Support" section into two

## 2.5.2 _(2019-03-03)_
* Fix: Ensure preceding up/down arrow for collapse/expand link does not get orphaned from the associated text
* New: Add inline documentation for hooks
* Change: Initialize plugin on `plugins_loaded` action instead of on load
* Change: Cast return value of filter `c2c_expandable_dashboard_recent_comments_start_expanded` as boolean
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 2.5.1 _(2018-04-30)_
* New: Add README.md
* Change: Add GitHub link to readme
* Change: Unit tests: Minor whitespace tweaks to bootstrap
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)

## 2.5 _(2017-02-02)_
* Change: Gray out global expand and/or collapse link if the action doesn't have a comment to expand/collapse, respecively.
* Change: Remove box-shadow highlight from links, which were visible after being clicked.
* Change: Vertically center expand/collapse dashicons.
* Change: Escape translated strings used in attributes.
* Change: Use class rather than inline styles to indicate which controls should be initially hidden.
* Change: Use `sprintf()` to format output markup rather than concatenating strings, variables, and function calls.
* Change: No need to explicitly enqueue jQuery.
* Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable.
* Change: Enable more error output for unit tests.
* Change: Note compatibility through WP 4.7+.
* Change: Remove support for WordPress older than 4.6 (should still work for earlier versions back to WP 3.8)
* Change: Minor inline code documentation reformatting.
* Change: Minor readme.txt improvements.
* New: Add LICENSE file.
* Change: Add inline docs for class variables.
* Change: Update copyright date (2017).
* Change: Update screenshots.

## 2.4.2 _(2016-01-09)_
* Change: Add support for language packs:
    * Change textdomain to 'expandable-dashboard-recent-comments' from 'c2c_edrc'.
    * Don't load textdomain from file.
    * Remove .pot file and /lang subdirectory.
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).
* Add: Create empty index.php to prevent files from being listed if web server has enabled directory listings.

## 2.4.1 _(2015-08-18)_
* Update: Note compatibility through WP 4.3+

## 2.4 _(2015-03-06)_
* Use Dashicons icons for all expand/collapse links (removing special character usage from string)
* Remove `get_ellipsis()` since it was only needed for WP < 3.6
* Remove JS support for WP < 3.8
* Remove `is_admin()` check preventing loading of class
* Added meager unit tests
* Reformat plugin header
* Space out admin.css content
* Change documentation links to wp.org to be https
* Minor documentation spacing changes throughout
* Change description
* Note compatibility through WP 4.1+
* Dropped support for versions of WP older than 3.8
* Update copyright date (2015)
* Update screenshots
* Add plugin icon
* Regenerate .pot

## 2.3 _(2013-12-24)_
* Fix CSS selectors to properly format full comments under WP 3.8
* Fix JS selectors to show Expand/Collapse All links under WP 3.8
* Add Frequently Asked Questions section to readme.txt
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Add banner
* Minor readme.txt text and formatting tweaks
* Change donate link

## 2.2
* Fix support for WP3.6+ due to core's change of '...' to '&hellip;' for the excerpt ellipsis
* Add `is_text_excerpted()`, `get_ellipsis()`
* Note compatibility through WP 3.6+

## 2.1
* Add 'comment' arg to `is_comment_initially_expanded()` for context
* Add 'comment' as additional arg to `c2c_expandable_dashboard_recent_comments_start_expanded` filter
* Change description (to shorten)
* Add check to prevent execution of code if file is directly accessed
* Regenerate .pot
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Minor improvements to inline and readme docs
* Minor code reformatting (spacing)
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Move screenshots into repo's assets directory

## 2.0
* Use "Show more"/"Show less" links in comment row actions instead of appending expand/collapse link
* Add filter `c2c_expandable_dashboard_recent_comments_start_expanded` to permit initial display of comments in expanded state
* Remove class configuration array
* Remove filter `c2c_expandable_dashboard_recent_comments_config`
* Enqueue CSS
* Enqueue JS
* Add `register_styles()`, `enqueue_admin_css()`, `enqueue_admin_js()`
* Remove `add_css()`, `add_js()`
* Add support for localization
* Add .pot
* No longer hide the ellipsis
* Hook 'load-index.php' action to initialize plugin rather than checking pagenow
* Add version() to return plugin version
* Minor code reformatting (spacing)
* Note compatibility through WP 3.3+
* Drop support for versions of WP older than 3.1
* Update screenshots (now based on WP 3.3)
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

## 1.3.1
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

## 1.3
* Don't display expand/collapse links for users without JavaScript and jQuery enabled
* Add admin unobtrusive javascript to handle expand/collapse of comments when links are clicked
* Use `substr()` instead of `preg_match()` to detect presence of '...'
* Remove 'onclick' attribute for links (perform via unobtrusive JS)
* Fix plugin links in description in readme.txt

## 1.2.1
* Add link to plugin homepage to description in readme.txt

## 1.2
* Switch from object instantiation to direct class function invocation
* Explicitly declare all functions public static and class variables public static
* Note compatibility with WP 3.1+
* Update copyright date (2011)

## 1.1
* Add filter `c2c_expandable_dashboard_recent_comments_config` to allow filtering of config options
* Rename class from `ExpandableDashboardRecentComments` to `c2c_ExpandableDashboardRecentComments`
* Store plugin instance in global variable, `$c2c_expandable_dashboard_recent_comments`, to allow for external manipulation
* Move `is_admin()` check to before class creation
* Add `init()` and move hooking of actions/filters to there
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor code reformatting (spacing)
* Add Filters and Upgrade Notice sections to readme
* Remove trailing whitespace in header docs

## 1.0.1
* Add full PHPDoc documentation
* Minor formatting tweaks
* Note compatibility with WP 2.9+
* Update copyright date
* Update readme.txt (including adding Changelog)

## 1.0
* Initial release
