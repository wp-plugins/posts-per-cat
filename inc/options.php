<?php
// function ppc_postspercat_options() {
	// update options on submit
	if ( isset($_POST['ppc-submit']) ) {
		$options['posts']    = htmlspecialchars($_POST['ppc-posts']);
		$options['titlelen'] = htmlspecialchars($_POST['ppc-titlelen']);
		$options['shorten']  = isset($_POST['ppc-shorten']);
		$options['excerpts'] = htmlspecialchars($_POST['ppc-excerpts']);
		$options['content']  = isset($_POST['ppc-content']);
		$options['excleng']  = htmlspecialchars($_POST['ppc-excleng']);
		$options['parent']   = isset($_POST['ppc-parent']);
		$options['order']    = htmlspecialchars($_POST['ppc-order']);
		$options['nosticky'] = isset($_POST['ppc-nosticky']);
		$options['include']  = htmlspecialchars($_POST['ppc-include']);
		$options['exclude']  = htmlspecialchars($_POST['ppc-exclude']);
		$options['ppccss']   = isset($_POST['ppc-ppccss']);
		$options['minh']     = htmlspecialchars($_POST['ppc-minh']);
		$options['columns']  = htmlspecialchars($_POST['ppc-columns']);
		$options['noctlink'] = isset($_POST['ppc-noctlink']);
		$options['more']     = isset($_POST['ppc-more']);
		$options['moretxt']  = htmlspecialchars($_POST['ppc-moretxt']);
		$options['thumb']    = isset($_POST['ppc-thumb']);
		$options['tsize']    = htmlspecialchars($_POST['ppc-tsize']);
		$options['catonly']  = isset($_POST['ppc-catonly']);
		$options['commnum']  = isset($_POST['ppc-commnum']);

		update_option('postspercat', $options);
	}

	// get options
	$options = get_option('postspercat');

	// set default options
	if ( !is_array( $options ) ) {
		$options = array(
			'posts'    => '5',
			'titlelen' => '',
			'shorten'  => false,
			'excerpts' => 'none',
			'content'  => false,
			'excleng'  => '100',
			'parent'   => false,
			'order'    => 'ID',
			'include'  => '',
			'exclude'  => '',
			'ppccss'   => true,
			'minh'     => '',
			'nosticky' => false,
			'columns'  => '2',
			'noctlink' => false,
			'more'     => false,
			'moretxt'  => __('More from', 'ppc'),
			'thumb'    => false,
			'tsize'    => '60',
			'catonly'  => false,
			'commnum'  => false
		);
		update_option('postspercat', $options);
	}

	// migrate to 1.2.0+
	if ( empty($options['posts']) && !empty($options['post']) ){
		$options['posts'] = $options['post'];
		unset($options['post']);
	}
	if ( empty($options['columns']) && !empty($options['column']) ){
		$options['columns'] = $options['column'];
		unset($options['column']);
	}
	if ( empty($options['excerpts']) && !empty($options['excerpt']) ){
		$options['excerpts'] = $options['excerpt'];
		unset($options['excerpt']);
	}

	// Now print Options page

?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.allcats').hide();
	$('#togglecattable').click(function(){
		$('.allcats').fadeToggle('slow');
		btn_title = $('#togglecattable').val();
		if ( btn_title == "<?php _e('Show available categories', 'ppc'); ?>" ) {
			$('#togglecattable').val("<?php _e('Hide available categories', 'ppc'); ?>");
		} else {
			$('#togglecattable').val("<?php _e('Show available categories', 'ppc'); ?>");
		}
	});
});
</script>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php echo sprintf(__('Posts per Cat v%s', 'ppc'), POSTS_PER_CAT_VER); ?></h2>
	<form method="post" action="" id="ppc-conf">
	<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ppc-updatesettings'); } ?>
	<p><?php _e('This plugin list latest N articles from all, top level only or manually choosen categories and group them in category boxes organized in one, two, three or four columns.', 'ppc'); ?></p>
	<h3><?php _e('Usage', 'ppc'); ?></h3>

	<p><?php _e('Insert code below to template file in place where you wish to display PPC boxes (but not inside Loop!):', 'ppc'); ?></p>
	<code>&lt;?php do_action('ppc'); ?&gt;</code>
	<p><?php _e('You can also use shortcode <strong>[ppc]</strong> (see shortcode options <a href="#ppcshopts">here</a>) or <a href="widget.php">widget</a> <strong>Posts per Cat</strong>.', 'ppc'); ?></p>

	<h3><?php _e('Boxes', 'ppc'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e('Number of columns', 'ppc'); ?></label></th>
			<td>
				<input type="radio" id="ppc-columns" name="ppc-columns" value="1" <?php checked( $options['columns'], 1 ); ?>/> <?php _e('One column per row (full width)', 'ppc'); ?><br />
				<input type="radio" id="ppc-columns" name="ppc-columns" value="2" <?php checked( $options['columns'], 2 ); ?>/> <?php _e('Two columns per row', 'ppc'); ?><br />
				<input type="radio" id="ppc-columns" name="ppc-columns" value="3" <?php checked( $options['columns'], 3 ); ?>/> <?php _e('Three columns per row', 'ppc'); ?><br />
				<input type="radio" id="ppc-columns" name="ppc-columns" value="4" <?php checked( $options['columns'], 4 ); ?>/> <?php _e('Four columns per row', 'ppc'); ?>
				<input type="radio" id="ppc-columns" name="ppc-columns" value="5" <?php checked( $options['columns'], 5 ); ?>/> <?php _e('Five columns per row', 'ppc'); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Minimal height of box', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo $options['minh']; ?>" name="ppc-minh" id="ppc-minh" size="2" /> <?php _e('px (leave empty to disable min-height)', 'ppc'); ?></td>
		</tr>
	</table>

	<h3><?php _e('Categories', 'ppc'); ?></h3>
	<table class="form-table">
		<tr valign="top">
		<th scope="row"><input type="button" id="togglecattable" class="button-secondary" value="<?php _e('Show available categories', 'ppc'); ?>" /> <!-- onclick="jQuery('.allcats').slideToggle('slow');" / --></th>
			<td>
				<table class="wp-list-table widefat fixed allcats" cellspacing="0">
					<thead>
						<?php print_cat_headfoot(); ?>
					</thead>
					<tfoot>
						<?php print_cat_headfoot(); ?>
					</tfoot>
					<tbody class="list:tag">
						<?php
						// get top level categories
						$categories = get_categories('hide_empty=0&orderby=id&parent=0');
						foreach( $categories as $category ) {
							print_cat_row($category);
							// get subcategories
							$subcats = get_categories('hide_empty=0&orderby=id&child_of='.$category->term_id);
							if ( $subcats ) {
								foreach( $subcats as $subcat ) {
									if ( $subcat != '' ) { // print subcategory row if subcat is not empty
										print_cat_row($subcat);
									}
								} // foreach $subcats
							} // if $subcats
						} // foreach $categories
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Include category', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo ($options['include']); ?>" name="ppc-include" id="ppc-include" /> <?php _e('comma separated category ID\'s', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"></label><?php _e('Exclude category', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo ($options['exclude']); ?>" name="ppc-exclude" id="ppc-exclude" /> <?php _e('comma separated category ID\'s', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Only top level categories', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['parent'], true ); ?> name="ppc-parent" id="ppc-parent" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Order categories by', 'ppc'); ?></label></th>
			<td>
				<input type="radio" id="ppc-order" name="ppc-order" value="ID" <?php checked( $options['order'], "ID" ); ?>/> <?php _e('Category ID', 'ppc'); ?><br />
				<input type="radio" id="ppc-order" name="ppc-order" value="name" <?php checked( $options['order'], "name" ); ?>/> <?php _e('Category Name', 'ppc'); ?><br />
				<input type="radio" id="ppc-order" name="ppc-order" value="custom" <?php checked( $options['order'], "custom" ); ?>/> <?php echo sprintf(__('Custom, as listed in <em>%s</em>', 'ppc'), __('Include category', 'ppc') ); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Only from displayed category archive', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['catonly'], true ); ?> name="ppc-catonly" id="ppc-catonly" /> <?php _e('exclude categories different from currently displayed on category archive and ignore first category rules on category archive', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Do not link category name', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['noctlink'], true ); ?> name="ppc-noctlink" id="ppc-noctlink" /> <?php _e('leave unchecked to link category title to archive', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Standalone link to archives', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['more'], true ); ?> name="ppc-more" id="ppc-more" /> <?php _e('check to print "read more" link bellow list of headlines', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Archive link prefix', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo ($options['moretxt']) ? $options['moretxt'] : _e('More from', 'ppc'); ?>" name="ppc-moretxt" id="ppc-moretxt" size="25" /></td>
		</tr>
	</table>

	<h3><?php _e('Headlines', 'ppc'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e('Number of headlines', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo $options['posts']; ?>" name="ppc-posts" id="ppc-posts" size="2" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Headline length', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo $options['titlelen']; ?>" name="ppc-titlelen" id="ppc-titlelen" size="2" /> <?php _e('leave blank for full post title length, optimal 34 characters', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Shorten headline', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['shorten'], true ); ?> name="ppc-shorten" id="ppc-shorten" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Display comment number', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['commnum'], true ); ?> name="ppc-commnum" id="ppc-commnum" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Hide sticky posts', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['nosticky'], true ); ?> name="ppc-nosticky" id="ppc-nosticky" /></td>
		</tr>
	</table>

	<h3><?php _e('Content', 'ppc'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e('Show excerpt', 'ppc'); ?></label></th>
			<td>
				<input type="radio" id="ppc-excerpts" name="ppc-excerpts" value="none" <?php checked( $options['excerpts'], 'none' ); ?>/> <?php _e('Don\'t display', 'ppc'); ?><br />
				<input type="radio" id="ppc-excerpts" name="ppc-excerpts" value="first" <?php checked( $options['excerpts'], 'first' ); ?>/> <?php _e('For first article only', 'ppc'); ?><br />
				<input type="radio" id="ppc-excerpts" name="ppc-excerpts" value="all" <?php checked( $options['excerpts'], 'all' ); ?>/> <?php _e('For all articles', 'ppc'); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Use post content as excerpt', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['content'], true ); ?> name="ppc-content" id="ppc-content" /> <?php _e('use post content in stead of post excerpt', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Excerpt length', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo $options['excleng']; ?>" name="ppc-excleng" id="ppc-excleng" size="2" /> <?php _e('leave empty for full excerpt length', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Show thumbnail with excerpt', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['thumb'], true ); ?> name="ppc-thumb" id="ppc-thumb" /> <?php _e('thumbnail is shown only if theme support it, and excerpt is enabled', 'ppc'); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label><?php _e('Thumbnail size', 'ppc'); ?></label></th>
			<td><input type="text" value="<?php echo ($options['tsize']) ? $options['tsize'] : "60"; ?>" name="ppc-tsize" id="ppc-tsize" size="2" /> <?php _e('enter size in px for thumbnail width (height is same)', 'ppc'); ?></td>
		</tr>
	</table>

	<h3><?php _e('Styling', 'ppc'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label><?php _e('Use PPC for styling list?', 'ppc'); ?></label></th>
			<td><input type="checkbox" <?php checked( (bool) $options['ppccss'], true ); ?> name="ppc-ppccss" id="ppc-ppccss" /> <?php _e('enable this option if U see ugly lists in PPC boxes', 'ppc'); ?></td>
		</tr>
	</table>

	<input type="hidden" name="action" value="update" />

	<p class="submit">
		<input type="submit" name="ppc-submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	</form>

	<h3 id="ppcshopts"><?php _e('Shortcode', 'ppc'); ?></h3>
	<p><?php _e('You can use shortcode <strong>[ppc]</strong>, with options below (set option in shortcode to override default settings above):', 'ppc');?></p>
	<ul>
	<li><em>columns</em>=2 - Number of columns (1, 2, 3 or 4)</li>
	<li><em>minh</em>=0 - Minimal height of box (in px, set to 0 for auto)</li>

	<li><em>include</em>=category_ID's - Include category (comma separated category ID's)</li>
	<li><em>exclude</em>=category_ID's - Exclude category (comma separated category ID's)</li>
	<li><em>parent</em>=0 - Only top level categories (0 or 1)</li>
	<li><em>order</em>=ID - Order categories by (ID, name or custom)</li>
	<li><em>catonly</em>=0 - Only from displayed category archive (0 or 1)</li>
	<li><em>noctlink</em>=0 - Do not link category name (0 or 1)</li>
	<li><em>more</em>=0 - Standalone link to archives (0 or 1)</li>
	<li><em>moretxt</em>="More from" - Archive link prefix</li>

	<li><em>posts</em>=5 - Number of headlines per category block</li>
	<li><em>titlelen</em>=34 - Headline length (in characters)</li>
	<li><em>shorten</em>=0 - Shorten headline (0 or 1)</li>
	<li><em>commnum</em>=0 - Display comment number (0 or 1)</li>
	<li><em>nosticky</em>=0 - Hide sticky posts (0 or 1)</li>

	<li><em>excerpts</em>=none - Show excerpt (none, first or all)</li>
	<li><em>content</em>=0 - Use post content as excerpt (0 or 1)</li>
	<li><em>excleng</em>=100 - Excerpt length</li>
	<li><em>thumb</em>=0 - Show thumbnail with excerpt (0 or 1)</li>
	<li><em>tsize</em>=60 - Thumbnail size, set size in px for thumbnail width (height is same)</li>
	</ul>

	<table class="widefat">
		<thead><tr><th><?php _e('Support', 'ppc'); ?></th></tr></thead>
		<tbody>
		<tr>
			<td>
				<p><?php echo sprintf(__('For all questions, feature request and communication with author and users of this plugin, use our <a href="%s">support forum</a>.', 'ppc'), 'http://wordpress.org/support/plugin/posts-per-cat'); ?>
				<p><?php echo sprintf(__('If you like <a href="%s">Posts per Cat</a> and my other <a href="%s">WordPress extensions</a>, feel free to support my work with <a href="%s">donation</a>.', 'ppc'), 'http://wordpress.org/plugins/posts-per-cat/', 'http://profiles.wordpress.org/urkekg/', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6Q762MQ97XJ6'); ?></p>
			</td>
		</tr>
		</tbody>
	</table>

</div>
