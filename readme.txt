=== Posts per Cat ===
Tags: categories, category, posts, archive, archives, date, time, past, listing, plugin, links, excerpt, navigation, simple, css, style, thumbnails, thumbnail
Contributors: urkekg
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6Q762MQ97XJ6
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 0.0.14

Posts per Cat list latest `N` articles from all, top level only or manually choosen categories and group them in category boxes organized in one, two, three or four columns.

== Description ==

Posts per Cat is a simple plugin that get all categories from database, then list last N posts from all category in boxes organised in two columns.

= Features =
* you can choose two columns per row, or only one (full width)
* configurable number of post titles to display per category
* include or exclude child categories
* ordering boxes by category ID, title or custom
* toggle displaying excerpt for first post in list per category
* toggle displaying sticky posts
* toggle usage of custom list CSS StyleSheet
* category boxes organised in two columns
* SEO optimized permalink URI's
* translantable
* produces XHTML 1.1 valid code
* published under terms of GNU GPLv3

== Installation ==

= Manual =
1. Upload the entire `posts-per-cat` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Configure `Posts per Cat` options.
1. Insert `<?php do_action("ppc"); ?>` in your template files (for example in index.php after pagination code)

= Automatic =
1. Go to `Plugins` → `Add New` and search for `posts per cat`.
1. Click on `Install Now` link bellow `Posts per Cat` search result and aswer `Yes` on popup question.
1. If you need enter FTP parameters for your host and click on `Proceed`.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Configure `Posts per Cat` options.
1. Insert `<?php do_action("ppc"); ?>` in your template files (for example in index.php after pagination code)

== Frequently Asked Questions ==

= I would like to get a list of posts but just from one category =

Enter category ID into `Include category` field, and leave unchecked `Only top level categories` checkbox.

== Screenshots ==
1. Posts per Cat on Plugins page
2. Posts per Cat Options page
3. Posts per Cat in action (sutom orderet categories in three columns)

== Changelog ==

= 1.0.0 =
* Adds option to use post content in stead of post excerpt
* Adds option for custom category ordering (as listed in Include category)
* Adds option to display PPC in one, two, three or four columns
* Adds available category list in Options page
* All coments in code translated to English
* Follow WP coding standards
* Renamed from Posts-per-Cat to Posts per Cat

= 0.0.14 (2011-04-09) =
* Fixed debug errors
* Fixed (I hope I do) image URI's in CSS
* Adapted for WordPress 3.1
* Added French localisation thanks to Pepita Pop (2010-06-23)

= 0.0.13 (2010-05-27) =
* Fixed path to CSS on nonroot WP installations

= 0.0.12 (2010-05-18) =
* Fixed full width problem in IE6

= 0.0.11 (2010-04-24) =
* Added dirty hack for seervers w/o mb_strlen()
* Added option to filter categories on category archive
* Added option to display thumbnails with excerpts
* Added option to display standalone link to category archive
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

