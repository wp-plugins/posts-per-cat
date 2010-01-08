=== Posts per Cat ===
Tags: categories, category, posts, archive, archives, date, time, past, listing, plugin, links, excerpt, navigation, simple, css, style
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
4. Insert code `<?php do_action("ppc"); ?>` in your template files (for example in index.php after pagination code)

or in WordPress 2.7+

1. Go to your `Plugins` &rarr; `Add New`
2. Search for `posts-per-cat`
3. Click on `Install` link on right of `Posts per Cat`
4. Click on red button `Install Now`
5. Click on `Activate plugin` or `Install Update Now`
6. Configure plugin options on `Settings` &rarr; `Posts per Cat` menu
7. Insert code `<?php do_action("ppc"); ?>` in your template files (for example in index.php after pagination code)

== Frequently Asked Questions ==

= I would like to get a list of posts but just from one category =

Enter category ID into `Include category` field, and leave unchecked `Only top level categories` checkbox.

== Screenshots ==
1. Posts per Cat Settings menu
2. Posts per Cat Settings page
3. Posts per Cat plugin with two columns per row (default)
4. Posts per Cat plugin with one column per row (full width)

== Changelog ==

= 0.0.11 (2010-01-08) =
* Added option to display standalone link to category archive
* Added support for post thumbnails (require WP 2.9)
* Better terminology

= 0.0.10 (2010-01-07) =
* Added option to display two or only one column per row (full width)

= 0.0.9 (2009-10-09) =
* Fixed XHTML validation error for div class
* Added full post title as link title

= 0.0.8 (2009-09-26) =
* Replaced category and post URI with permalink
* Added option to display excerpt for first article only, for all articles or not display at all
* Added option to shorten excerpts to specified length (in characters)
* Added option to shorten post title to specified length (in characters)
* Added options for custom categories to include/exclude
* Moved Settings links in plugin actions
* Fixed and improved ppc.css and ppc-list.css StyleSheets

= 0.0.7 (2009-03-04) =
* Posts per Cat added to WordPress plugin repository
* Fixed broken category URI on non-index pages
* Added ppc action hook

= 0.0.6 (2009-03-03) =
* Fixed SCC URI on non-index pages

= 0.0.5 (2009-02-20) =
* Added plugin option to disable usage of CSS StyleSheet for list styles

= 0.0.4 (2009-02-20) =
* Fixed CSS positioning problem

= 0.0.3 (2009-02-16) =
* Published first public release
* Added Settings page
* Gettexturized (enabled localisation)

= 0.0.2 (2009-02-10) =
* Code packed from functions.php to plugin

= 0.0.1 (2009-02-02) =
* Project initialized

== Upgrade Notice ==

= 0.0.8 =
Fixed problem with wrong links on blogs installed in subdirectory.