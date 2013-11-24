=== Posts per Cat ===
Tags: categories, category, posts, archive, archives, date, time, past, listing, plugin, links, excerpt, navigation, simple, css, style, thumbnails, thumbnail, widget, shortcode
Contributors: urkekg
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6Q762MQ97XJ6
Requires at least: 3.5.0
Tested up to: 3.7.1
Stable tag: 1.2.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Group recent posts by category and show them inside boxes organized in one, two, three or four columns.

== Description ==

Posts per Cat is a simple plugin that grab all or only selected categories from blog database, and then list last N posts by category in boxes organised in 1-4 columns.

= Features =
* choose how many boxes per row will be displayed (one, two, three or four)
* define number of post titles to display per category
* define category ID's to exclude
* define category ID's to include
* toggle displaying of child categories
* ordering boxes by category ID, title or custom
* toggle displaying excerpt abowe post title (for first post only, for all posts or none)
* toggle displaying featured image for posts
* toggle displaying number of comments (with link) added to post title
* toggle displaying sticky posts
* toggle usage of custom list CSS
* SEO optimized permalink URI's
* integrate to template file, use shortcode [ppc] with options or widget
* translantable
* produces XHTML 1.1 valid code
* published under terms of GNU GPLv3

= Shortcode options =
You can use shortcode [ppc], with options below (set option in shortcode to override default settings above):

* columns=2 - Number of columns (1, 2, 3 or 4)
* minh=0 - Minimal height of box (in px, set to 0 for auto)
* include=category_ID's - Include category (comma separated category ID's)
* exclude=category_ID's - Exclude category (comma separated category ID's)
* parent=0 - Only top level categories (0 or 1)
* order=ID - Order categories by (ID, name or custom)
* catonly=0 - Only from displayed category archive (0 or 1)
* noctlink=0 - Do not link category name (0 or 1)
* more=0 - Standalone link to archives (0 or 1)
* moretxt="More from" - Archive link prefix
* posts=5 - Number of headlines per category block
* titlelen=34 - Headline length (in characters)
* shorten=0 - Shorten headline (0 or 1)
* commnum=0 - Display comment number (0 or 1)
* nosticky=0 - Hide sticky posts (0 or 1)
* excerpts=none - Show excerpt (none, first or all)
* content=0 - Use post content as excerpt (0 or 1)
* excleng=100 - Excerpt length
* thumb=0 - Show thumbnail with excerpt (0 or 1)
* tsize=60 - Thumbnail size, set size in px for thumbnail width (height is same)

== Installation ==

1. Login to your WordPress site and go to page `Plugins`-->`Add New`
2. Type `posts per cat` to `Search` field and press `Search Plugins` button
3. Click on `Install Now` link below `Posts per Cat` name
4. Click on `OK` as answer to question `Are you sure you want to install this plugin?`
5. Click `Activate Plugin` link after success installation
6. Go to `Settings`-->`Posts per Cat` page and configure plugin's options
7. Put code `<?php do_action('ppc'); ?>` in your template file (for example in index.php just before closing `</div><!-- #content -->` tag, or use widget `Posts per Cat` or shortcode `[ppc]`.

== Frequently Asked Questions ==

= I would like to get a list of posts but just from one category =

Enter category ID into `Include category` field, and leave unchecked `Only top level categories` checkbox.

== Screenshots ==
1. Posts per Cat default settings
2. Posts per Cat widget
3. Posts per Cat: custom cats, 3 column, w/o enabled CSS
4. Posts per Cat: all cats, 3 column, w/ enabled CSS

== Upgrade Notice ==
= 1.2.0 =
* We changed options names for number of columns, number of posts and excerpts visibility. We recommend you to update plugin settings after update.

== Changelog ==
= 1.2.0 (2013-11-24) =
* Add shortcode options to override default settings
* Add widget with settings
* Code optimization

= 1.1.0 (2012-04-05) =
* Adds option to disable link on category title
* Adds shortcode [ppc]
* Adds class to headline title and number of comments

= 1.0.0 (2012-01-16) =
* Adds option to toggle comments number with link
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
