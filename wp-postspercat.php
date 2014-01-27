<?php
/*
    WP Posts per Cat list titles of recent posts in boxes for all single categories
    Copyright (C) 2009-2012 Aleksandar Urošević <urke@users.sourceforge.net>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

Plugin Name: Posts per Cat
Plugin URI: http://urosevic.net/wordpress/plugins/posts-per-cat/
Description: Group latest posts by selected category and show post titles w/ or w/o excerpt, featured image and comments number in boxes organized in one, two, three or four columns.
Version: 1.2.1
Author: Aleksandar Urošević
Author URI: http://urosevic.net
License: GNU GPLv3
*/

define( 'POSTS_PER_CAT_VER', '1.2.1' );
define( 'POSTS_PER_CAT_URL', plugin_dir_url(__FILE__) );

// add PPC button to admin menu
add_action( 'admin_menu', 'ppc_postspercat_menu' );
// initialize PPC
add_action( 'ppc', 'ppc_echo');
// define shortcode
add_shortcode( 'ppc', 'posts_per_cat' );

function ppc_echo() { echo posts_per_cat(); }

// insert Settings link on plugins admin page
if ( is_admin() ) {
	$plugin = plugin_basename(__FILE__); 
	add_filter('plugin_action_links_'.$plugin, 'addConfigureLink' );
}
function addConfigureLink( $links ) { 
	$settings_link = '<a href="options-general.php?page=posts-per-cat">'.__('Settings').'</a>'; 
	array_unshift( $links, $settings_link ); 
	return $links; 
}

// start PPC
if ( function_exists('posts_per_cat') ) {
	$blog_url = get_bloginfo('url');
	// init textdomain for localisation
	load_plugin_textdomain( 'ppc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	// call PPC CSS header insertion
	add_action( 'wp_enqueue_scripts', 'ppc_scripts' );
}

// inject PPC CSS in page head
function ppc_scripts(){
	wp_enqueue_style( 'ppc-main', plugins_url('ppc.css', __FILE__) );
	$options = get_option('postspercat');
	if ( $options['ppccss'] ) {
		wp_enqueue_style( 'ppc-list', plugins_url('ppc-list.css', __FILE__) );
	}
    $tsize =  $options['tsize'];
        $custom_css = ".ppc .attachment-{$tsize}x{$tsize} {
	width: {$tsize}px !important;
	height: {$tsize}px !important;
}";
        wp_add_inline_style( 'ppc-main', $custom_css );
}

/* prepare Options page */
function ppc_postspercat_menu() {
	add_options_page(__('Posts per Cat Options', 'ppc'),  __('Posts per Cat', 'ppc'), 'manage_options', 'posts-per-cat', 'ppc_postspercat_options');
}

function ppc_postspercat_options() {
	require_once('inc/options.php');
}

require_once('inc/tools.php');
require_once('inc/widget.php');

function posts_per_cat($attr = null) {
	global $blog_url;

	$options		= get_option('postspercat');
// [ppc posts="" shorten=0 titlelen=0 parent=0 excerpts=]
	extract( shortcode_atts( array(
		'posts'		=> $options['posts'],
		'shorten'	=> $options['shorten'],
		'titlelen'	=> $options['titlelen'],
		'parent'	=> $options['parent'],
		'excerpts'	=> $options['excerpts'],
		'content'	=> $options['content'],
		'excleng'	=> $options['excleng'],
		'order'		=> $options['order'],
		'nosticky'	=> $options['nosticky'],
		'noctlink'	=> $options['noctlink'],
		'more'		=> $options['more'],
		'moretxt'	=> $options['moretxt'],
		'include'	=> $options['include'],
		'exclude'	=> $options['exclude'],
		'thumb'		=> $options['thumb'],
		'catonly'	=> $options['catonly'],
		'commnum'	=> $options['commnum'],
		'tsize'		=> $options['tsize'],
		'columns'	=> (!empty($options['columns'])) ? $options['columns'] : $options['column'],
		'minh'		=> $options['minh']
    ), $attr ) );

	// default thumbnail size
	$ppc_tsize = ( empty($tsize) ) ? array(60,60) : array($tsize,$tsize);

	switch ( $columns ) { // setup number of columns
		case 1:		$ppc_column = "one"; break;
		case 3:		$ppc_column = "three"; break;
		case 4:		$ppc_column = "four"; break;
		default:	$ppc_column = "two";
	}

	// do we need to force minimal height of box?
	$ppc_minh = ( $minh > 0 ) ? 'style="min-height: '.$minh.'px !important;"' : "";

	// do we need to display only current category on archive?
	if ( $catonly && is_category() && !is_home() ) {
		$cats = get_categories('orderby='.$order.'&include='.get_query_var('cat'));
	} else {
		// custom or other category ordering?
		if ( $order == "custom" ) { // custom
			$custom_order = split(",", $include);
			foreach ( $custom_order as $custom_order_cat_ID ) {
				$custom_order_cat_object = get_categories('include='.$custom_order_cat_ID);
				$custom_order_cat[] = $custom_order_cat_object[0];
			}
			$cats = $custom_order_cat;
		} else { // by cat_ID or name
			$cats = get_categories('orderby='.$order.'&include='.$include.'&exclude='.$exclude);
		}
	}

	// set number of boxes for clear fix
	$boxnum = 0;
	// print PPC body header
	$ppc_str = '
<!-- start of Posts per Cat version '.POSTS_PER_CAT_VER.' -->
	<div id="ppc-box">
';

	foreach ( $cats as $cat ) { // process all category
		// get only non-empty categories
		if ( $cat->count > 0 && ( ($parent == true && $cat->category_parent == 0) || $parent == false || ( $catonly == true && is_category() ) ) ) {
			// where to put category archive link
			if ( $more ) { // add more link
				$ppc_cattitle = $cat->cat_name;
				$ppc_moreadd = '<div class="ppc-more"><a href="'.get_category_link( $cat->cat_ID ).'">'.$moretxt.' '.__('&#8220;', 'ppc').$ppc_cattitle.__('&#8221;', 'ppc').'</a></div>';
			} else { // on category title
				if ( $noctlink ) {
					$ppc_cattitle = '<a href="'.get_category_link( $cat->cat_ID ).'">'.$cat->cat_name.'</a>';
					$ppc_moreadd = "";
				} else {
					$ppc_cattitle = $cat->cat_name;
					$ppc_moreadd = "";

				}
			}
			// start category box
			$ppc_str .= '
		<!-- start of Category Box -->
		<div class="ppc-box '.$ppc_column.'">
			<div class="ppc" '.$minh.'>
			<h3>'.$ppc_cattitle.'</h3>
			<ul>';

			// get latest N posts from category $cat
			if ( $nosticky ) { // exclude sticky posts
				$posts_arr = get_posts(array(
					'post__not_in' => get_option("sticky_posts"),
					'numberposts' => $posts,
					'order' => "DSC",
					'orderby' => "date",
					'category' => $cat->cat_ID
				));
			} else { // include sticky posts
				$posts_arr = get_posts('numberposts='.$posts.'&order=DSC&orderby=date&category='.$cat->cat_ID);
			}
			
			$br = 0; // control number for number of excerpts

			// process all posts from category
			foreach ( $posts_arr as $post ) {
				// do we need to cut down post title?
				if ( $shorten ) {
					if ( $titlelen && mb_strlen_dh($post->post_title) > ($titlelen+1) ) {
						$title = substr_utf8($post->post_title, 0, $titlelen)."&hellip;";
					} else {
						$title = $post->post_title;
					}
					$title_full = $post->post_title;
				} else {
					$title_full = $title = $post->post_title;
				}

				$ppc_str .= '
				<li><a href="'.get_permalink($post->ID).'" class="ppc-post-title" title="'.sprintf(__('Article %s published at %s', 'ppc'), $title_full, date_i18n(__('F j, Y g:i a'), strtotime($post->post_date)) ).'">'.$title.'</a>';
				if ( $commnum ) {
					$comments_num = get_comments_number($post->ID);
						$ppc_str .= ' <span class="ppc-comments-num">(<a href="'.get_permalink($post->ID);
					if ( $comments_num == 0 ) {
						$ppc_str .= '#respond" title="'.sprintf(__('Be first to comment %s', 'ppc'), $title_full);
					} else {
						$ppc_str .= '#comments" title="'.sprintf(__('Read comments on %s', 'ppc'), $title_full);
					}
					$ppc_str .= '">'.get_comments_number($post->ID).'</a>)</span>';
				}
				if ( $content ) {
					$excerpt = strip_tags(substr_utf8($post->post_content, 0, 500)).'&hellip;';
					if ( $excleng && mb_strlen_dh($excerpt) > ($excleng+1) ) {
						$excerpt = substr_utf8($excerpt, 0, $excleng).'&hellip;';
					}
				} else {
					if ( $excleng && mb_strlen_dh($post->post_excerpt) > ($excleng+1) ) {
						$excerpt = substr_utf8($post->post_excerpt, 0, $excleng).'&hellip;';
					} else {
						$excerpt = $post->post_excerpt;
					}
				}

				if ( $br++ == 0 && ($excerpts == 'first') ) { // print excerpt for first post
					$ppc_str .= '<p>';
					if ( $thumb ) { // print thumbnail
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
							$ppc_str .= wp_get_attachment_image( get_post_thumbnail_id($post->ID), $ppc_tsize);
						}
					}
					$ppc_str .= $excerpt.'</p>';
				} elseif ( $br++ > 0 && $excerpts == 'all' ) { // print excerpt for other posts
					$ppc_str .= '<p>';
					if ( $thumb ) { // print thumbnails
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
						$ppc_str .= wp_get_attachment_image( get_post_thumbnail_id($post->ID), $ppc_tsize ); }
					}
					$ppc_str .= $excerpt.'</p>';
				}
				$ppc_str .= '</li>';
			} // end of processing every post from category $cat

			// close category box
			$ppc_str .= '
			</ul>
			'.$ppc_moreadd.'
			</div>
		</div>
		<!-- end of Category Box -->
			';

			// print row cleaner
			$boxnum++;
			if ( $boxnum == $columns ) {
				$ppc_str .= '<div class="clear"></div>';
				$boxnum = 0;
			} // boxnum equal to number of columns
		} // end of processing non-empty categories
	} // end foreach $cats as $cat

	// print row cleaner at end of PPC
	if ( $boxnum != $columns ) {
		$ppc_str .= '<div class="clear"></div>';
	}
	
	// close PPC container
	$ppc_str .= '
	</div>
<!-- end of Posts per Cat -->
';

return $ppc_str;
} // posts_per_cat()

// category table row print
function print_cat_row($category) {
?>
	<tr>
		<td class="name column-name">
		<a href="edit-tags.php?action=edit&taxonomy=category&tag_ID=<?php echo $category->cat_ID; ?>&post_type=post"><?php if ( $category->parent != 0 ) { echo "— "; } echo $category->cat_name; ?></a></td>
		<td class="column-description description"><?php echo $category->category_description; ?></td>
		<td class="slug column-slug"><?php echo $category->slug; ?></td>
		<td class="num column-rating"><?php echo $category->cat_ID; ?></td>
		<td class="num column-posts"><a href="edit.php?category_name=<?php echo $category->slug; ?>&post_type=post"><?php echo $category->count; ?></a></td>
	</tr>
<?php
}

function print_cat_headfoot() {
?>
			<tr>
				<th scope="col" class="manage-column column-name row-title"><?php _e('Name'); ?></th>
				<th scope="col" class="manage-column column-description desc row-title"><?php _e('Description'); ?></th>
				<th scope="col" class="manage-column column-slug slug row-title"><?php _e('Slug'); ?></th>
				<th scope="col" class="manage-column column-rating num row-title"><?php _e('ID'); ?></th>
				<th scope="col" class="manage-column column-posts num row-title"><?php _e('Posts'); ?></th>
			</tr>
<?php
}

?>
