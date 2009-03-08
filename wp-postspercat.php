<?php
/*
    WP Posts-per-Cat lists titles of recent posts in boxes for all single categories
    Copyright (C) 2009 Aleksandar Urošević <urke@users.sourceforge.net>

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
Description: List latests N article titles from categories and group them to category boxes organized in two columns.
Author: Aleksandar Urošević
Version: 0.0.8
Author URI: http://urosevic.net
*/
$ppc_version = "0.0.8";

add_action("admin_menu", "ppc_postspercat_menu");
add_action("ppc", "posts_per_cat");
if ( is_admin() ) {
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'addConfigureLink' );
}

function addConfigureLink( $links ) { 
  $settings_link = '<a href="options-general.php?page=posts-per-cat/wp-postspercat.php">'.__('Settings').'</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}

if ( function_exists('posts_per_cat') ) 
{
	$blog_url = get_bloginfo('url');
	$ppc_dir = PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
//	load_plugin_textdomain( 'ppc', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages' );
	load_plugin_textdomain( "ppc", "$ppc_dir/languages" );

	add_filter('wp_head', 'ppc_header_css', 10);
}

function ppc_header_css()
{
	global $blog_url, $ppc_dir;
	echo '<link rel="stylesheet" href="'.$blog_url.'/'.$ppc_dir.'/ppc.css" type="text/css" media="screen" />';
	$options = get_option("postspercat");
	if ( $options['list'] ) {
		echo '<link rel="stylesheet" href="'.$blog_url.'/'.$ppc_dir.'/ppc-list.css" type="text/css" media="screen" />';
	}
}

function ppc_postspercat_menu() {
	add_options_page(__('Posts per Cat Options', 'ppc'), __('Posts per Cat', 'ppc'), 8, __FILE__, 'ppc_postspercat_options');
}

function ppc_postspercat_options()
{
	global $ppc_version;
	if ( $_POST['ppc-submit'] )
	{
		$options['posts']   = htmlspecialchars($_POST['ppc-posts']);
		$options['titlelength']   = htmlspecialchars($_POST['ppc-titlelength']);
		$options['shorten']   = htmlspecialchars($_POST['ppc-shorten']);
		$options['excerpt'] = htmlspecialchars($_POST['ppc-excerpt']);
		$options['parent']  = htmlspecialchars($_POST['ppc-parent']);
		$options['order']   = htmlspecialchars($_POST['ppc-order']);
		$options['list']    = htmlspecialchars($_POST['ppc-list']);
		$options['include'] = htmlspecialchars($_POST['ppc-include']);
		$options['exclude'] = htmlspecialchars($_POST['ppc-exclude']);
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
			"parent"    => False,
			"order"     => "ID",
			"list"      => True,
			"include"   => "",
			"exclude"   => ""
		);
		update_option("postspercat", $options);
	}
?>
<div class="wrap">
	<h2><?php _e('Posts per Cat', 'ppc'); ?></h2>
	<form method="post" action="" id="ppc-conf">
	<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ppc-updatesettings'); } ?>
	<p><?php echo sprintf(__("Currently installed version: <strong>%s</strong>", "ppc"), $ppc_version); ?></p>
	<p><?php _e('This plugin list latests N article titles from categories and group them in category boxes organized in two columns.<br />Posts-Per-Cat has initially created for <a href="http://webnovinar.org">Web Journalism School</a>.', 'ppc'); ?></p>
	<h3><?php _e("Usage", "ppc"); ?></h3>
	<p><?php _e('Put next code to template files in place where you wish to display PPC boxes (but not in Loop!):', 'ppc'); ?></p>
	<code>&lt;?php do_action("ppc"); ?&gt;</code>

	<h3><?php _e("Category options", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Include category", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['include']); ?>" name="ppc-include" id="ppc-include" /> (<?php _e("comma separated category ID's", "ppc"); ?>)</td>
		</tr>
		<tr valign="top">
			<th scope="row"></label><?php _e("Exclude category", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo ($options['exclude']); ?>" name="ppc-exclude" id="ppc-exclude" /> (<?php _e("comma separated category ID's", "ppc"); ?>)</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Only top level categories", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php echo ($options['parent']) ? ' checked="checked"' : ''; ?> name="ppc-parent" id="ppc-parent" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Order categories by", "ppc"); ?></label></th>
			<td>
				<input type="radio" id="ppc-order" name="ppc-order" value="ID" <?php if ( $options['order'] == "ID" ) { echo "checked"; } ?>/> <?php _e("Category ID", "ppc"); ?><br/>
				<input type="radio" id="ppc-order" name="ppc-order" value="name" <?php if ( $options['order'] == "name" ) { echo "checked"; } ?>/> <?php _e("Category Name", "ppc"); ?>
			</td>
		</tr>

	</table>

	<h3><?php _e("Posts options", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Articles per category", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['posts']; ?>" name="ppc-posts" id="ppc-posts" size="2" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Post title length", "ppc"); ?></label></th>
			<td><input type="text" value="<?php echo $options['titlelength']; ?>" name="ppc-titlelength" id="ppc-titlelength" size="2" /> (<?php _e("leave blank for full post title length, optimal 34 characters", "ppc"); ?>)</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e("Shorten post title", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php echo ($options['shorten']) ? ' checked="checked"' : ''; ?> name="ppc-shorten" id="ppc-shorten" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><label><?php _e("Show excerpt", "ppc"); ?></label></th>
			<td>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="none" <?php if ( $options['excerpt'] == "none" ) { echo "checked"; } ?>/> <?php _e("Don't display", "ppc"); ?><br/>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="first" <?php if ( $options['excerpt'] == "first" ) { echo "checked"; } ?>/> <?php _e("For first article only", "ppc"); ?><br/>
				<input type="radio" id="ppc-excerpt" name="ppc-excerpt" value="all" <?php if ( $options['excerpt'] == "all" ) { echo "checked"; } ?>/> <?php _e("For all articles", "ppc"); ?>
			</td>
		</tr>
	</table>

	<h3><?php _e("Optional", "ppc"); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e("Use PPC CSS StyleSheet?", "ppc"); ?></label></th>
			<td><input type="checkbox" <?php echo ($options['list']) ? ' checked="checked"' : ''; ?> name="ppc-list" id="ppc-list" /> (<?php _e("enable this option if U see ugly lists in PPC boxes", "ppc"); ?>)</td>
		</tr>
	</table>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ppc-posts, ppc-titlelength, ppc-shorten, ppc-excerpt, ppc-parent, ppc-order, ppc-list, ppc-include, ppc-exclude" />

	<p class="submit">
		<input type="submit" name="ppc-submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	</form>
</div>
<?php
}

function posts_per_cat()
{
	global $blog_url, $ppc_version;
	$options     = get_option('postspercat');
	$ppc_posts   = $options['posts'];   // broj članaka za listanje
	$ppc_shorten = $options['shorten'];   // da li treba skrećivati naslov?
	$ppc_titlelength = $options['titlelength'];   // dužina naslova u karakterima
	$ppc_parent  = $options['parent'];  // listanje samo kategorija najvišeg nivoa?
	$ppc_excerpt = $options['excerpt']; // da li i za koje članke štampati sažetak?
	$ppc_order   = $options['order'];   // poredak po ID-u ili nazivu kategorije?

	$ppc_include = $options['include']; // kategorije koje će biti izlistane
	$ppc_exclude = $options['exclude']; // kategorije koje će biti ignorisane

	// uzimamo spisak kategorija iz baze
	$kategorije = get_categories('orderby='.$ppc_order.'&include='.$ppc_include.'&exclude='.$ppc_exclude);

	// klasa za raspoređivanje kutija levo/desno
	$position = "left";
	echo '
<!-- start of Posts-per-Cat version '.$ppc_version.' -->
	<div id="ppc-box">
';
	foreach ( $kategorije as $kat ) { // procesiramo svaku kategoriju niza
		if ( $kat->count > 0 && ( ($ppc_parent == True && $kat->category_parent == 0) || $ppc_parent == False) ) { // uzimamo samo kategorije sa člancima
			echo '
		<!-- start of Category Box -->
		<div class="ppc-box '.$position.'">
			<div class="ppc">
			<h3><a href="'.$blog_url.'/?cat='.$kat->cat_ID.'">'.$kat->cat_name.'</a></h3>
			<ul>';

			// uzimamo najnovijih N članaka iz kategorije $kat
			$clanci = get_posts('numberposts='.$ppc_posts.'&order=DSC&orderby=date&category='.$kat->cat_ID);

			// procesiramo svaki članak u kategoriji $kat
			$br = 0; // kontrolni brojač za sažetak prvog članka

			foreach ( $clanci as $clanak ) {
				if ( $ppc_shorten ) {
					if ( $ppc_titlelength && mb_strlen($clanak->post_title) > ($ppc_titlelength+1) ) { $naslov = substr_utf8($clanak->post_title, 0, $ppc_titlelength)."&hellip;"; } else { $naslov = $clanak->post_title; }
				} else {
					$naslov = $clanak->post_title;
				}
				echo '
				<li><a href="'.$blog_url.'/?p='.$clanak->ID.'" title="'.$clanak->post_date.'">'.$naslov.'</a>';
				if ( $br++ == 0 && ($ppc_excerpt == "first") ) { // štampamo sažetak prvog članka ako treba
					echo "<p>".$clanak->post_excerpt."</p>";
				} elseif ( $br++ > 0 && $ppc_excerpt == "all" ) { // štampamo sažetak za ostale članke
					echo "<p>".$clanak->post_excerpt."</p>";
				}
				echo "</li>";
			} // kraj procesiranja svakog članaka u kategoriji $kat

			echo '
			</ul>
			</div>
		</div>
		<!-- end of Category Box -->
';
			if ( $position == "left" ) {
				$position = "right";
			} else {
				$position = "left";
				echo '<div class="clear"></div>';
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
?>
