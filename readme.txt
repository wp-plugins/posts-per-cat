=== Posts per Cat ===
Tags: categories, posts, archive, time, past
Contributors: urkekg
Donate link: http://urosevic.net/kontakt
Requires at least: 2.6.0
Tested up to: 2.9.1
Stable tag: 0.0.10

List latests N article titles from all or top level only categories and group them in category boxes organized in two columns.

== Description ==

Posts per Cat is a simple plugin that get all categories from database, then list last N posts from all category in boxes organised in two columns.

= Features =
* you can chose two columns per row, or only one (full width)
* configurable number of post titles to display per category
* include or exclude child categories
* ordering boxes by category ID or title
* toggle displaying excerpt for first post in list per category
* toggle displaying sticky posts
* toggle usage of custom list CSS StyleSheet
* category boxes organised in two columns
* SEO optimized permalink URI's
* translantable
* produces XHTML 1.1 valid code
* published under terms of GNU GPLv3

== Installation ==

1. Put `posts-per-cat` directory into `[wordpress_dir]/wp-content/plugins/`
2. Go into the `WordPress` admin interface and activate the plugin
3. Configure plugin options on `Settings` &rarr; `Posts per Cat` menu
4. Add to template file code `<?php do_action("ppc"); ?>` (for example in index.php after pagination code)

or in WordPress 2.7+

1. Go to your `Plugins` &rarr; `Add New`
2. Search for `posts-per-cat`
3. Click on `Install` link on right of `Posts per Cat`
4. Click on red button `Install Now`
5. Click on `Activate plugin` or `Install Update Now`
6. Configure plugin options on `Settings` &rarr; `Posts per Cat` menu
7. Place `<?php do_action("ppc"); ?>` in your templates (for example in index.php after paggination code)

== Frequently Asked Questions ==

= I would like to get a list of posts but just from one category =

Enter category ID into `Include category` field, and leave unchecked `Only top level categories` checkbox.

== Screenshots ==
1. Posts per Cat Settings menu
2. Posts per Cat Settings page
3. Posts per Cat plugin with two columns per row (default)
4. Posts per Cat plugin with one column per row (full width)