=== Expandable Dashboard Recent Comments ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: dashboard, admin, recent comments, comment, excerpt
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 6.6
Stable tag: 3.0

Enables in-place expansion of excerpts in the admin dashboard 'Comments' section of the 'Activity' widget to view full comments.


== Description ==

By default, the 'Comments' section of the 'Activity' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment actions row (the links for the comment that become visible under the comment when you hover over the comment). The "Show more" link, when clicked, will replace the excerpt with the full comment. The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc). The full comment can be switched back to the excerpt by clicking the "Show less" link (which replaces the "Show more" link when the comment is expanded).

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

*NOTE:* This plugin only works for users who have JavaScript enabled.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/expandable-dashboard-recent-comments/) | [Plugin Directory Page](https://wordpress.org/plugins/expandable-dashboard-recent-comments/) | [GitHub](https://github.com/coffee2code/expandable-dashboard-recent-comments/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `expandable-dashboard-recent-comments.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Visit the admin dashboard and check out the 'Comments' section of the 'Activity' widget (assuming you have recent comments and that one or more of them have been automatically excerpted)


== Frequently Asked Questions ==

= How do I expand an excerpted comment? =

When you hover over the comment, a line of action links will appear (typically "Approve", "Reply", "Edit", "Spam", and "Trash"). If the comment has been automatically excerpted by WordPress, then a "Show more" link will appear. Click it to view the full comment.

= Why don't I see the "Show more" link when hovering over a comment? =

The comment has not been been excerpted; you are already seeing the comment in its entirety so there is no need to be able to "show more".

= Why don't I see the "Expand all" and "Collapse all" links at the bottom of the widget? =

Assuming you are using a supported version of WordPress and you have JavaScript enabled in your browser, this just means that none of the comments being listed have been excerpted, thus there is no need to be able to "Expand all" or "Collapse all" in this instance.

= Why does either of the "Expand all" or "Collapse all" links appear grayed out? =

If all of the comments in the widget are currently collapsed, then the "Collapse all" link will appear grayed out to indicate there is nothing for it to collapse. Likewise, if all of the comments in the widget are currently expanded, then "Expand all" will appear grayed out to indicate there is nothing for it to expand. As comments are expanded and collapsed, these two links will adjust themselves to appear grayed out or active as appropriate.

= Can I make it so all comments initially appear fully expanded without needing to click the links to expand them? =

Yes. Please see the "Hooks" section for documentation on the `c2c_expandable_dashboard_recent_comments_start_expanded` filter which allows for this.

= Does this work if JavaScript has been disabled in my browser? =

If JavaScript is disabled in a visitor's browser, then all of the expand/collapse links and behavior aren't enabled. But rest assured, there aren't any errors. Why not implement a fallback if JS is disabled? If you're going to click a link that causes a page reload to view the full comment, you might as well just click through to the comment.

= Does this plugin include unit tests? =

Yes. The tests are not packaged in the release .zip file or included in plugins.svn.wordpress.org, but can be found in the [plugin's GitHub repository](https://github.com/coffee2code/expandable-dashboard-recent-comments/).



== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard widget with the plugin active, showing comments that have been truncated/excerpted by WordPress (the 2nd, 3rd, and 5th listed) and the entirety of 2 short comments. The truncated comments each display a "(show more)" link allowing for the full comment text to be seen. Also note the 'Expand All' and 'Collapse All' links added to the bottom of the widget.
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the first excerpted comment (the second comment in the list) fully in-place expanded and with markup and formatting applied.


== Hooks ==

The plugin exposes one filter for hooking. Such code should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_expandable_dashboard_recent_comments_start_expanded (filter)**

The 'c2c_expandable_dashboard_recent_comments_start_expanded' hook allows you to configure the 'Recent Comments' admin dashboard widget initially display all comments in their expanded state (i.e. not excerpted).

Arguments:

* $default (boolean): The default state, which is 'false' (therefore comments are initially shown excerpted)
* $comment (object) : The comment object being displayed

Example:

`
// Initially show dashboard comments fully expanded
add_filter( 'c2c_expandable_dashboard_recent_comments_start_expanded', '__return_true' );
`


== Changelog ==

= 3.0 (2024-09-08) =
Highlights:

This notable update features improvements for accessiblity via ARIA attributes, relocates the various expand/collapse links, notes compatibility through WP 6.6+, updates unit tests and removes them from release packaging, updates copyright date (2024), and other internal improvments.

Details:

* UI:
    * New: Add ARIA attributes to improve accessibility
        * Add `aria-controls` and `aria-expanded` attributes to excerpt toggle links
        * Add `aria-hidden` attributes to comments excerpt and full `div`s as appropriate
    * Change: Move the "Show more/less" links inline with the excerpt and full comment body rather than being comment row actions
    * Change: Move the "Collapse/Expand all" links to alongside the comment filter links rather than below
    * Change: Add 'count' class to the "Expand/Collapse All" links to match styling of WP admin
    * Change: Add separator before "Expand All" link since the preceeding "Trash" link omits it
    * Change: Move 'hide-if-no-js' class up into link container classes (added if necessary) so link-adjacent text can be hidden as well
* Documentation:
    * Change: Note compatibility through WP 6.6+
    * Change: Update copyright date (2024)
    * Change: Reduce number of 'Tags' from `readme.txt`
    * Change: Update screenshots
    * Change: Tweak text formatting in `README.md`
* Internals:
    * Change: Add ELLIPSIS constant for ellipsis string and use it rather than harcoding string in various places
    * Change: Prevent translations from containing unintended markup
    * Change: Explicitly assign an array key to the added comment row action links
    * Change: Define a version number when registering the styles
    * Change: Bail earlier in functions when possible
    * Change: Prevent unnecessary tabbed whitespace output
* Packaging/development:
    * Change: Remove development and testing-related files from release packaging
    * New: Add `.gitignore` file
    * Unit tests:
        * Allow tests to run against current versions of WordPress
        * New: Add `composer.json` for PHPUnit Polyfill dependency
        * Hardening: Prevent direct web access to `bootstrap.php`
        * Change: In bootstrap, store path to plugin directory in a constant
        * Change: Prevent PHP warnings due to missing core-related generated files
    * New: Add a potential TODO item

= 2.8.2 (2021-09-16) =
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/` into `tests/`
        * Change: Move `phpunit/bin` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

= 2.8.1 (2021-04-10) =
* Change: Escape text used in markup attributes (hardening)
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/expandable-dashboard-recent-comments/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 3.0 =
Recommended update: improved accessiblity via ARIA attributes, relocated the various expand/collapse links, noted compatibility through WP 6.6+, updated unit tests and removed them from release packaging, updated copyright date (2024), and other internal improvments.

= 2.8.2 =
Trivial update: noted compatibility through WP 5.8+ and minor reorganization and tweaks to unit tests

= 2.8.1 =
Trivial update: minor hardening, noted compatibility through WP 5.7+, and updated copyright date (2021)

= 2.8 =
Minor update: rewrote JavaScript to use vanilla JS instead of jQuery, improved handling of some unlikely edgecase situations, restructured the unit test file structure, and noted compatibility through WP 5.5+.

= 2.7 =
Minor update: Added counts of affected comments next to "Expand all" and "Collapse all" links, minor spacing tweak, fixed a couple of unit tests, switched some links to HTTPS, added TODO.md, and noted compatibility through WP 5.4+.

= 2.6 =
Minor update: improved handling for multi-byte characters, improved unit testing, other minor improvements, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 2.5.3 =
Trivial update: modernized unit tests, created CHANGELOG.md to store historical changelog outside of readme.txt, and noted compatibility through WP 5.2+

= 2.5.2 =
Trivial update: prevented orphaning of up/down arrow from rest of link text, aded more inline documentation, noted compatibility through WP 5.1+, updated copyright date (2019)

= 2.5.1 =
Trivial update: added README.md, noted compatibility through WP 4.9+, and updated copyright date (2018)

= 2.5 =
Recommended update: gray out "Expand all" and/or "Collapse all" links if there are no comments in a state they can affect, compatibility is now WP 4.6-4.7+, updated copyright date (2017), and more

= 2.4.2 =
Trivial update: adjustments to utilize language packs, minor unit test tweaks, noted compatibility through WP 4.4+, and updated copyright date

= 2.4.1 =
Trivial update: noted compatibility with WP 4.3+

= 2.4 =
Recommended update: added dashicons; added unit tests; noted compatibility through WP 4.1+; dropped compatibility with version of WP older than 3.8; added plugin icon

= 2.3 =
Recommended update: fixed compatibility with WP 3.8+

= 2.2 =
Recommended update: Fixed to work for WP 3.6+ due to the change in how core defined the ellipsis.

= 2.1 =
Minor update. Highlights: added argument to filter; noted compatibility through WP 3.5+; explicitly stated license; and more.

= 2.0 =
Significant update: mostly rewritten; now uses "Show more"/"Show less" links in comment row actions instead of appending expand/collapse link; added expand/collapse links that affect all visible comments; added filter to allow initially showing comments expanded; internationalization; enqueue assets; and more

= 1.3.1 =
Trivial update: noted compatibility through WP 3.2+

= 1.3 =
Minor update: don't display expand/collapse links when JavaScript is disabled; use obtrusive JS rather than inline JS

= 1.2.1 =
Trivial update: add link to plugin homepage to description in readme.txt

= 1.2 =
Minor update: noted compatibility with WP 3.1+ and updated copyright date.

= 1.1 =
Minor update. Highlights: adds filter to allow customization of configuration defaults; verified WP 3.0 compatibility.
