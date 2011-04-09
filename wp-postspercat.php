<?php
/*
    WP Posts-per-Cat lists titles of recent posts in boxes for all single categories
    Copyright (C) 2009-2011 Aleksandar Urošević <urke@users.sourceforge.net>

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

Plugin Name: Posts-per-Cat
Plugin URI: http://blog.urosevic.net/wordpress/posts-per-cat/
Description: List latests N article titles from categories and group them as category boxes organized in two columns.
Version: 0.0.14
Author: Aleksandar Urošević
Author URI: http://urosevic.net
License: GNU GPLv3
*/

define( 'POSTS_PER_CAT_VER', '0.0.14' );
define( 'POSTS_PER_CAT_URL', plugin_dir_url(__FILE__) );
define( 'POSTS_PER_CAT_DIR', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) );

add_action("admin_menu", "ppc_postspercat_menu");
add_action("ppc", "posts_per_cat");


if ( is_admin() ) {
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'addConfigureLink' );
}
function addConfigureLink( $links ) { 
  $settings_link = '<a href="options-general.php?page=posts-per-cat">'.__('Settings').'</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}

if ( function_exists('posts_per_cat') ) 
{
	$blog_url = get_bloginfo('url');
	//$ppc_dir = PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
//	load_plugin_textdomain( 'ppc', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages' );
//	load_plugin_textdomain( "ppc", "$ppc_dir/languages" );
	load_plugin_textdomain( 'ppc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	add_filter('wp_head', 'ppc_header_css', 10);
}

function ppc_header_css()
{
	//global $blog_url, $ppc_dir;
	//echo '<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/posts-per-cat/ppc.css" type="text/css" media="screen" />';
	echo '<link rel="stylesheet" href="'.POSTS_PER_CAT_DIR.'/ppc.css" type="text/css" media="screen" />';
	$options = get_option("postspercat");
	if ( $options['ppccss'] ) {
		//echo '<link rel="stylesheet" href="'.WP_PLUGIN_URL.'/posts-per-cat/ppc-list.css" type="text/css" media="screen" />';
		echo '<link rel="stylesheet" href="'.POSTS_PER_CAT_DIR.'/ppc-list.css" type="text/css" media="screen" />';
	}
}

function ppc_postspercat_menu() {
	//add_options_page(__('Posts per Cat Options', 'ppc'), __('Posts per Cat', 'ppc'), 8, __FILE__, 'ppc_postspercat_options');
	add_options_page(__('Posts per Cat Options', 'ppc'),  __('Posts per Cat', 'ppc'), 'manage_options', 'posts-per-cat', 'ppc_postspercat_options');
}

function ppc_postspercat_options()
{
	if ( isset($_POST['ppc-submit']) )
	{
		$options['posts']     = htmlspecialchars($_POST['ppc-posts']);
		$options['titlelength'] = htmlspecialchars($_POST['ppc-titlelength']);
		$options['shorten']   = isset($_POST['ppc-shorten']);
		$options['excerpt']   = htmlspecialchars($_POST['ppc-excerpt']);
		$options['excleng']   = htmlspecialchars($_POST['ppc-excleng']);
		$options['parent']    = isset($_POST['ppc-parent']);
		$options['order']     = htmlspecialchars($_POST['ppc-order']);
		$options['nosticky']  = isset($_POST['ppc-nosticky']);
		$options['include']   = htmlspecialchars($_POST['ppc-include']);
		$options['exclude']   = htmlspecialchars($_POST['ppc-exclude']);
		$options['ppccss']    = isset($_POST['ppc-ppccss']);
		$options['minh']      = htmlspecialchars($_POST['ppc-minh']);
		$options['column']    = htmlspecialchars($_POST['ppc-column']);
		$options['more']      = isset($_POST['ppc-more']);
		$options['moretxt']   = htmlspecialchars($_POST['ppc-moretxt']);
		$options['thumb']     = isset($_POST['ppc-thumb']);
		$options['tsize']     = htmlspecialchars($_POST['ppc-tsize']);
		$options['catonly']   = isset($_POST['ppc-catonly']);
		
		update_option("postspercat", $options);
	}

	// inicijalizujem opcije dodatka
	$options = get_option("postspercat");
	if ( !is_array( $options ) )
	{
		$options = array(
			"posts"     => "5",
			"titlelength" => "",
			"shorten"   => False,
			"excerpt"   => "none",
			"excleng"   => "100",
			"parent"    => False,
			"order"     => "ID",
			"include"   => "",
			"exclude"   => "",
			"ppccss"    => True,
			"minh"      => "",
			"nosticky"	=> False,
			"column"      => "2",
			"more"      => False,
			"moretxt"   => __("More from", "ppc"),
			"thumb"     => False,
			"tsize"     => "60",
			"catonly"   => False
		);
		update_option("postspercat", $options);
	}
?>
<div class="wrap">
	<h2><?php _e('Posts per Cat', 'ppc'); ?></h2>
	<form method="post" action="" id="ppc-conf">
	<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ppc-updatesettings'); } ?>
	<p><?php echo sprintf(__("Current version: <strong>%s</strong>", "ppc"), POSTS_PER_CAT_VER); ?></p>
	<p><?php _e('This plugin list latests N article titles from categories and group them in category boxes organized in two columns.', 'ppc'); ?></p>
	<h3><?php _e("Usage", "ppc"); ?></h3>
	<p><?php _e('Put next code to template files in place where you wish to display PPC boxes (but not in Loop!):', 'ppc'); ?></p>
	<code>&lt;?php do_action("ppc"); ?&gt;</code>

	<h3><?php _e("Boxes", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Number of Columns", "ppc"); ?></label></th>
			<td>
				<input type="radio" id="ppc-column" name="ppc-column" value="2" <?php checked( $options['column'], 2 ); ?>/> <?php _e("Two columns per row", "ppc"); ?><br/>
				<input type="radio" id="ppc-column" name="ppc-column" value="1" <?php checked( $options['column'], 1 ); ?>/> <?php _e("One column per row (full width)", "ppc"); ?><br/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Minimal height of box", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['minh']; ?>" name="ppc-minh" id="ppc-minh" size="2" /> <?php _e("px (leave empty to disable min-height)", "ppc"); ?></td>
		</tr>
	</table>

	<h3><?php _e("Categoryes", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Include category", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['include']); ?>" name="ppc-include" id="ppc-include" /> <?php _e("comma separated category ID's", "ppc"); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"></label><?php _e("Exclude category", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['exclude']); ?>" name="ppc-exclude" id="ppc-exclude" /> <?php _e("comma separated category ID's", "ppc"); ?></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Only top level categories", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['parent'], true ); ?> name="ppc-parent" id="ppc-parent" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Order categories by", "ppc"); ?></label></th>
			<td>
				<input type="radio" id="ppc-order" name="ppc-order" value="ID" <?php checked( $options['order'], "ID" ); ?>/> <?php _e("Category ID", "ppc"); ?><br/>
				<input type="radio" id="ppc-order" name="ppc-order" value="name" <?php checked( $options['order'], "name" ); ?>/> <?php _e("Category Name", "ppc"); ?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Only from displayed category archive", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['catonly'], true ); ?> name="ppc-catonly" id="ppc-catonly" /> <?php _e("exclude categories different from currently displayed on category archive and ignore first category rules on category archive", "ppc"); ?></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Standalone link to archives", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['more'], true ); ?> name="ppc-more" id="ppc-more" /> <?php _e("leave unchecked to link category title to archive", "ppc"); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Archive link prefix", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['moretxt']) ? $options['moretxt'] : _e("More from", "ppc"); ?>" name="ppc-moretxt" id="ppc-moretxt" size="25" /></td>
		</tr>
	</table>

	<h3><?php _e("Headlines", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Number of headlines", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['posts']; ?>" name="ppc-posts" id="ppc-posts" size="2" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Headline length", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['titlelength']; ?>" name="ppc-titlelength" id="ppc-titlelength" size="2" /> <?php _e("leave blank for full post title length, optimal 34 characters", "ppc"); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Shorten headline", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['shorten'], true ); ?> name="ppc-shorten" id="ppc-shorten" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Hide sticky posts", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['nosticky'], true ); ?> name="ppc-nosticky" id="ppc-nosticky" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Show excerpt", "ppc"); ?></label></th>
			<td>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="none" <?php checked( $options['excerpt'], "none" ); ?>/> <?php _e("Don't display", "ppc"); ?><br/>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="first" <?php checked( $options['excerpt'], "first" ); ?>/> <?php _e("For first article only", "ppc"); ?><br/>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="all" <?php checked( $options['excerpt'], "all" ); ?>/> <?php _e("For all articles", "ppc"); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Excerpt length", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['excleng']; ?>" name="ppc-excleng" id="ppc-excleng" size="2" /> <?php _e("leave empty for full excerpt length", "ppc"); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Show thumbnail with excerpt", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['thumb'], true ); ?> name="ppc-thumb" id="ppc-thumb" /> <?php _e("thumbnail is shown only if theme support it, and excerpt is enabled", "ppc"); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Thumbnail size", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['tsize']) ? $options['tsize'] : "60"; ?>" name="ppc-tsize" id="ppc-tsize" size="2" /> (<?php _e("enter size in px for thumbnail width (height is same)", "ppc"); ?>)</td>
		</tr>
	</table>

	<h3><?php _e("Styling", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Use PPC for styling list?", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['ppccss'], true ); ?> name="ppc-ppccss" id="ppc-ppccss" /> (<?php _e("enable this option if U see ugly lists in PPC boxes", "ppc"); ?>)</td>
		</tr>
	</table>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ppc-posts, ppc-titlelength, ppc-shorten, ppc-excerpt, ppc-excleng, ppc-parent, ppc-order, ppc-include, ppc-exclude, ppc-ppccss, ppc-minh, ppc-column, ppc-more, ppc-moretxt, ppc-thumb, ppc-tsize, ppc-catonly" />

	<p class="submit">
		<input type="submit" name="ppc-submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	</form>
	<h3><?php _e("Support", "ppc"); ?></h3>
	<p><?php echo sprintf(__('For all questions, feature request and communication with author and users of this plugin, use our <a href="%s">support forum</a>.', 'ppc'), "http://wordpress.org/tags/posts-per-cat?forum_id=10"); ?>
	<h3><?php _e("Donate", "ppc"); ?></h3>
	<p><?php echo sprintf(__('If you like <a href="%s">Posts per Cat</a> and my other <a href="%s">WordPress extensions</a>, feel free to support my work with <a href="%s">donation</a>.', 'ppc'), "http://wordpress.org/extend/plugins/posts-per-cat/", "http://profiles.wordpress.org/users/urkekg/", "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6Q762MQ97XJ6"); ?></p>

</div>
<?php
}

function posts_per_cat()
{
	global $blog_url;
	$options     = get_option('postspercat');
	$ppc_posts   = $options['posts'];   // broj članaka za listanje
	$ppc_shorten = $options['shorten'];   // da li treba skrećivati naslov?
	$ppc_titlelength = $options['titlelength'];   // dužina naslova u karakterima
	$ppc_parent  = $options['parent'];  // listanje samo kategorija najvišeg nivoa?
	$ppc_excerpt = $options['excerpt']; // da li i za koje članke štampati sažetak?
	$ppc_excleng = $options['excleng']; // dužina sažetka u karakterima
	$ppc_order   = $options['order'];   // poredak po ID-u ili nazivu kategorije?
	$ppc_nosticky = $options['nosticky'];   // listati ili ne lepljive članke?
	$ppc_more    = $options['more']; // posebnaveza do arhive kategorije?
	$ppc_moretxt = $options['moretxt']; // prefiks za vezu do arhive kategorije
	$ppc_include = $options['include']; // kategorije koje će biti izlistane
	$ppc_exclude = $options['exclude']; // kategorije koje će biti ignorisane
	$ppc_thumb   = $options['thumb']; // da li treba prikazati thumbnail?
	$ppc_catonly = $options['catonly']; // da li treba prikazati samo članke iz kategorije u arhivi kategorije?
	
	if ( $options['tsize'] != "60" ) { $ppc_tsize = array($options['tsize'],$options['tsize']); } else { $ppc_tsize = array(60,60); } // visina i sirina thumbnaila u px
	if ( $options['column'] == "1" ) { $ppc_column = " class=\"full\""; } else { $ppc_column = ""; }

	if ( $options['minh'] > 0 ) { $ppc_minh = 'style="min-height: '.$options['minh'].'px !important;"'; } else { $ppc_minh = ""; }

	// da li treba filtrirati kategorije na arhivi kategorija?
	if ( $ppc_catonly && is_category() && !is_home() ) {
		$kategorije = get_categories('orderby='.$ppc_order.'&include='.get_query_var('cat'));
	} else {
		// uzimamo spisak kategorija iz baze
		$kategorije = get_categories('orderby='.$ppc_order.'&include='.$ppc_include.'&exclude='.$ppc_exclude);
	}

	// klasa za raspoređivanje kutija levo/desno
	$position = "left";
	echo '
<!-- start of Posts-per-Cat version '.POSTS_PER_CAT_VER.' -->
	<div id="ppc-box"'.$ppc_column.'>
';

	foreach ( $kategorije as $kat ) { // procesiramo svaku kategoriju niza
		if ( $kat->count > 0 && ( ($ppc_parent == True && $kat->category_parent == 0) || $ppc_parent == False || ( $ppc_catonly == True && is_category() ) ) ) { // uzimamo samo kategorije sa člancima
			// da li treba veza da ide na naslov kategorije ili na posebnu vezu?
			if ( $ppc_more ) {
				$ppc_cattitle = $kat->cat_name;
				$ppc_moreadd = '<div class="ppc-more"><a href="'.get_category_link( $kat->cat_ID ).'">'.$ppc_moretxt.' '.__('&#8220;', 'ppc').$ppc_cattitle.__('&#8221;', 'ppc').'</a></div>';
			} else {
				$ppc_cattitle = '<a href="'.get_category_link( $kat->cat_ID ).'">'.$kat->cat_name.'</a>';
				$ppc_moreadd = "";
			}
			echo '
		<!-- start of Category Box -->
		<div class="ppc-box '.$position.'">
			<div class="ppc" '.$ppc_minh.'>
			<h3>'.$ppc_cattitle.'</h3>
			<ul>';

			// uzimamo najnovijih N članaka iz kategorije $kat
			if ( $ppc_nosticky ) {
				$clanci = get_posts(array(
					'post__not_in' => get_option("sticky_posts"),
					'numberposts' => $ppc_posts,
					'order' => "DSC",
					'orderby' => "date",
					'category' => $kat->cat_ID
				));
			} else {
				$clanci = get_posts('numberposts='.$ppc_posts.'&order=DSC&orderby=date&category='.$kat->cat_ID);
			}
			
			// procesiramo svaki članak u kategoriji $kat
			$br = 0; // kontrolni brojač za sažetak prvog članka

			foreach ( $clanci as $clanak ) {
				if ( $ppc_shorten ) {
					if ( $ppc_titlelength && mb_strlen_dh($clanak->post_title) > ($ppc_titlelength+1) ) { $naslov = substr_utf8($clanak->post_title, 0, $ppc_titlelength)."&hellip;"; } else { $naslov = $clanak->post_title; }
					$naslov_title = " - ".$clanak->post_title;
				} else {
					$naslov = $clanak->post_title;
					$naslov_title = "";
				}
				echo '
				<li><a href="'.get_permalink($clanak->ID).'" title="'.$clanak->post_date.$naslov_title.'">'.$naslov.'</a>';
				if ( $ppc_excleng && mb_strlen_dh($clanak->post_excerpt) > ($ppc_excleng+1) ) { $sazetak = substr_utf8($clanak->post_excerpt, 0, $ppc_excleng)."&hellip;"; } else { $sazetak = $clanak->post_excerpt;}
				if ( $br++ == 0 && ($ppc_excerpt == "first") ) { // štampamo sažetak prvog članka ako treba
					echo "<p>";
					if ( $ppc_thumb ) { // ako treba thumbnail, ubacujemo i njega
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail($clanak->ID) ) {
						echo wp_get_attachment_image( get_post_thumbnail_id($clanak->ID), $ppc_tsize ); }
					}
					echo $sazetak."</p>";
				} elseif ( $br++ > 0 && $ppc_excerpt == "all" ) { // štampamo sažetak za ostale članke
					echo "<p>";
					if ( $ppc_thumb ) { // ako treba thumbnail, ubacujemo i njega
						if ( function_exists('has_post_thumbnail') && has_post_thumbnail($clanak->ID) ) {
						echo wp_get_attachment_image( get_post_thumbnail_id($clanak->ID), $ppc_tsize ); }
					}
					echo $sazetak."</p>";
				}
				echo "</li>";
			} // kraj procesiranja svakog članaka u kategoriji $kat

			echo '
			</ul>
			'.$ppc_moreadd.'
			</div>
		</div>
		<!-- end of Category Box -->
';
			if ( $position == "left" ) {
				$position = "right";
			} else {
				$position = "left";
				if ( $ppc_column == "" ) {
					echo '<div class="clear"></div>';
				}
			}
		} // kraj uzimanja samo kategorija sa člancima
	} // kraj foreach petlje $kategorije as $kat

	// ako je poslednja kućica leva, dodaje clear fix iza nje
	if ( $position == "right" ) { echo '<div class="clear"></div>'; }
	echo '
	</div>
<!-- end of Posts-per-Cat -->
';
} // kraj funkcije posts_per_cat()

// unicode substr workaround from http://en.jinzorahelp.com/forums/viewtopic.php?f=18&t=6231
function substr_utf8($str,$from,$len)
{
	# utf8 substr
	# http://www.yeap.lv
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
'$1',$str);
}

// dirty hack for missing mb_strlen() found at http://www.php.net/manual/en/function.mb-strlen.php#87114
function mb_strlen_dh($utf8str)
{
	if ( function_exists("mb_strlen") ) {
		return mb_strlen($utf8str);
	} else {
		return preg_match_all("/.{1}/us",$utf8str,$dummy);
	}
}
?>
