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
Description: List latests N article titles from all or top level only categories and group them in category boxes organized in two columns. Configure plugin on <a href="options-general.php?page=wp-posts-per-cat/wp-postspercat.php">Settings</a> page.
Author: Aleksandar Urošević
Version: 0.0.7
Author URI: http://urosevic.net

*/

add_action("admin_menu", "ppc_postspercat_menu");
add_action("ppc", "posts_per_cat");

if ( function_exists('posts_per_cat') ) 
{
	load_plugin_textdomain( 'ppc', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages' );

	add_filter('wp_head', 'ppc_header_css', 10);
	$blog_url = get_bloginfo('url');
}

function ppc_header_css()
{
	global $blog_url;
	echo '<link rel="stylesheet" href="'.$blog_url.'/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/ppc.css" type="text/css" media="screen" />';
	$options = get_option("postspercat");
	if ( $options['list'] ) {
		echo '<link rel="stylesheet" href="'.$blog_url.'/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/ppc-list.css" type="text/css" media="screen" />';
	}
}

function ppc_postspercat_menu() {
	add_options_page(__('Posts per Cat Options', 'ppc'), __('Posts per Cat', 'ppc'), 8, __FILE__, 'ppc_postspercat_options');
}

function ppc_postspercat_options()
{

	if ( $_POST['ppc-submit'] )
	{
		$options['posts']   = htmlspecialchars($_POST['ppc-posts']);
		$options['excerpt'] = htmlspecialchars($_POST['ppc-excerpt']);
		$options['parent']  = htmlspecialchars($_POST['ppc-parent']);
		$options['order']   = htmlspecialchars($_POST['ppc-order']);
		$options['list']    = htmlspecialchars($_POST['ppc-list']);
		update_option("postspercat", $options);
	}

	// inicijalizujem opcije dodatka
	$options = get_option("postspercat");
	if ( !is_array( $options ) )
	{
		$options = array(
			"posts"     => "5",
			"excerpt"   => True,
			"parent"    => False,
			"order"     => "ID",
			"list"      => True
		);
		update_option("postspercat", $options);
	}	
?>
<div class="wrap">
	<h2><?php _e('Posts per Cat', 'ppc'); ?></h2>

	<p><?php _e('This plugin list latests N article titles from all or top level only categories and group them in category boxes organized in two columns.<br />Posts-Per-Cat has initially created for <a href="http://webnovinar.org">Web Journalism School</a>.', 'ppc'); ?></p>
	<p><?php _e('To use Posts-per-Cat plugin add to your template file code above:', 'ppc'); ?></p>
	<pre>&lt;?php do_action("ppc"); ?&gt;</pre>
	<br />
	<form method="post" action="" id="ppc-conf">
	<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ppc-updatesettings'); } ?>

	<table class="form-table">

		<tr valign="top">
			<th scope="row"><?php _e('Number of articles per category:', 'ppc'); ?></th>
			<td><input type="text" value="<?php echo $options['posts']; ?>" name="ppc-posts" id="ppc-posts" size="2" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('List only top level categories?', 'ppc'); ?></th>
			<td><input type="checkbox" <?php echo ($options['parent']) ? ' checked="checked"' : ''; ?> name="ppc-parent" id="ppc-parent" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Order categories by', 'ppc'); ?></th>
			<td>
				<input type="radio" id="ppc-order" name="ppc-order" value="ID" <?php if ( $options['order'] == "ID" ) { echo "checked"; } ?>/> <?php _e('Category ID', 'ppc'); ?><br/>
				<input type="radio" id="ppc-order" name="ppc-order" value="name" <?php if ( $options['order'] == "name" ) { echo "checked"; } ?>/> <?php _e('Category Name', 'ppc'); ?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Display excerpt for first article in category box?', 'ppc'); ?></th>
			<td><input type="checkbox" <?php echo ($options['excerpt']) ? ' checked="checked"' : ''; ?> name="ppc-excerpt" id="ppc-excerpt" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Use Posts-per-Cat list style CSS Stylesheet?', 'ppc'); ?></th>
			<td><input type="checkbox" <?php echo ($options['list']) ? ' checked="checked"' : ''; ?> name="ppc-list" id="ppc-list" /></td>
		</tr>

	</table>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ppc-posts, ppc-excerpt, ppc-parent, ppc-order, ppc-list" />

	<p class="submit">
		<input type="submit" name="ppc-submit" class="button-primary" value="<?php _e('Save Changes', 'ppc') ?>" />
	</p>

	</form>
</div>
<?php
}

function posts_per_cat()
{
	global $blog_url;
	$options = get_option('postspercat');
	$ppc_posts = $options['posts']; // broj članaka za listanje
	$ppc_parent = $options['parent']; // listanje samo kategorija najvišeg nivoa?
	$ppc_excerpt = $options['excerpt']; // da li štampati sažetak?
	$ppc_order = $options['order']; // poredak po ID-u ili nazivu kategorije?

	// uzimamo spisak kategorija iz baze
	$kategorije = get_categories('orderby='.$ppc_order);

	// klasa za raspoređivanje kutija levo/desno
	$position = "left";
	echo '
<!-- start of Posts-per-Cat -->
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
				echo '
				<li><a href="'.$blog_url.'/?p='.$clanak->ID.'" title="'.$clanak->post_date.'">'.$clanak->post_title.'</a>';
				if ( $br++ == 0 && $ppc_excerpt ) { // štampamo sažetak prvog članka ako treba
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

echo '
	</div>
<!-- end of Posts-per-Cat -->
';
} // kraj funkcije posts_per_cat()
?>
