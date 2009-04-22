=== Expandable Dashboard Recent Comments ===
Contributors: Scott Reilly
Donate link: http://coffee2code.com
Tags: dashboard, admin, recent comments, comment, excerpt, expandable
Requires at least: 2.6
Tested up to: 2.7.1
Stable tag: trunk
Version: 1.0

Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

== Description ==

Adds the ability to do an in-place expansion of comment excerpts on the admin dashboard 'Recent Comments' widget.

By default, the 'Recent Comments' admin dashboard widget only shows an excerpt for the comments, truncating the content of the comments to the first 20 words while at the same time stripping out all markup.

This plugin adds a link at the end of the comment excerpt (a ">>" (aka &raquo;)) that when clicked will replace the excerpt with the full comment.  The full comment will include all markup, including originally utilized markup and changes applied via filters, plugins, etc (such as shortcode expansion, smilies, paragraphing, etc).  The full comment can be switched back to the except by clicking a "<<" (&laquo;) link.

"In-place expansion" refers to the ability to click the link to see the full comment and it will be presented in place of the excerpt without requiring a page reload or navigation.

NOTE: This plugin only works for users who have JavaScript enabled.


== Installation ==

1. Unzip `expandable-dashboard-recent-comments-v1.0.zip` inside the `/wp-content/plugins/` directory for your site
1. Activate the plugin through the 'Plugins' admin menu in WordPress

== Screenshots ==

1. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, showing a comment that has been truncated/excerpted by WordPress and a full, short comment.
2. A screenshot of the 'Recent Comments' admin dashboard page with the plugin active, now showing the excerpted comment fully in-place expanded and with markup and formatting applied.


