<?php
/*
Plugin Name: Keyword Optimizer
Plugin URI: http://www.spunkyjones.com/wordpress/introducing-keyword-optimizer-wordpress/
Description: A plugin that lets users optimize a selected list of keywords using the HTML strong, em; and i; tags. This plugin was design for the Spunky Jones Blog and will be distrubuted to the general public from Spunky Jones.
Version: 1.0
Author: Naif Amoodi
Author URI: http://www.naif.in
*/

function k_o_menu() {
?>
	<div class="wrap">
		<h2>Keyword Optimizer Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'k-o-options' ); ?>
			The following fields except comma separated values example: <em>seo, blogging</em>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">&lt;strong&gt; Keywords</th>
					<td><input type="text" name="k_o_keywords_strong" value="<?php echo get_option('k_o_keywords_strong'); ?>" /></td>
					<td>Limit: <input type="text" name="k_o_keywords_strong_limit" size="2" value="<?php if( get_option('k_o_keywords_strong_limit') ) echo get_option('k_o_keywords_strong_limit'); else echo '1'; ?>" /></td>
					<td><input type="checkbox" name="k_o_keywords_strong_enable" <?php if ( get_option('k_o_keywords_strong_enable') == 'on' ) echo ' checked="checked" '; ?> /> Enable?</td>
				</tr>

				<tr valign="top">
					<th scope="row">&lt;em&gt; Keywords</th>
					<td><input type="text" name="k_o_keywords_em" value="<?php echo get_option('k_o_keywords_em'); ?>" /></td>
					<td>Limit: <input type="text" name="k_o_keywords_em_limit" size="2" value="<?php if( get_option('k_o_keywords_em_limit') ) echo get_option('k_o_keywords_em_limit'); else echo '1'; ?>" /></td>
					<td><input type="checkbox" name="k_o_keywords_em_enable" <?php if ( get_option('k_o_keywords_em_enable') == 'on' ) echo ' checked="checked" '; ?> /> Enable?</td>
				</tr>

				<tr valign="top">
					<th scope="row">&lt;u&gt; Keywords</th>
					<td><input type="text" name="k_o_keywords_u" value="<?php echo get_option('k_o_keywords_u'); ?>" /></td>
					<td>Limit: <input type="text" name="k_o_keywords_u_limit" size="2" value="<?php if( get_option('k_o_keywords_u_limit') ) echo get_option('k_o_keywords_u_limit'); else echo '1'; ?>" /></td>
					<td><input type="checkbox" name="k_o_keywords_u_enable" <?php if ( get_option('k_o_keywords_u_enable') == 'on' ) echo ' checked="checked" '; ?> /> Enable?</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>
	</div>
<?php
}

function k_o_admin_actions() {
	add_options_page("Keyword Optimizer", "Keyword Optimizer", 1, basename(__FILE__), "k_o_menu");
}

function k_o_check_limit($limit) {
	return $limit >= 1 ? $limit : 1;
}

function k_o_register_settings() {
	register_setting('k-o-options', 'k_o_keywords_strong');
	register_setting('k-o-options', 'k_o_keywords_strong_enable');
	register_setting('k-o-options', 'k_o_keywords_strong_limit', 'k_o_check_limit');
	register_setting('k-o-options', 'k_o_keywords_em');
	register_setting('k-o-options', 'k_o_keywords_em_enable');
	register_setting('k-o-options', 'k_o_keywords_em_limit', 'k_o_check_limit');
	register_setting('k-o-options', 'k_o_keywords_u');
	register_setting('k-o-options', 'k_o_keywords_u_enable');
	register_setting('k-o-options', 'k_o_keywords_u_limit', 'k_o_check_limit');
}

function k_o_do_it($content) {
	if ( get_option('k_o_keywords_strong_enable') == 'on' ) {
		$strong = split(',', get_option('k_o_keywords_strong'));
		foreach($strong as $k) {
			$k = trim($k);
			$content = preg_replace('~\b' . preg_quote($k, '~') . '\b(?![^<]*?>)~i', '<strong>$0</strong>', $content, get_option('k_o_keywords_strong_limit'));
		}
	}

	if ( get_option('k_o_keywords_em_enable') == 'on' ) {
		$em = split(',', get_option('k_o_keywords_em'));
		foreach($em as $k) {
			$k = trim($k);
			$content = preg_replace('~\b' . preg_quote($k, '~') . '\b(?![^<]*?>)~i', '<em>$0</em>', $content, get_option('k_o_keywords_em_limit'));
		}
	}

	if ( get_option('k_o_keywords_u_enable') == 'on' ) {
		$u = split(',', get_option('k_o_keywords_u'));
		foreach($u as $k) {
			$k = trim($k);
			$content = preg_replace('~\b' . preg_quote($k, '~') . '\b(?![^<]*?>)~i', '<u>$0</u>', $content, get_option('k_o_keywords_u_limit'));
		}
	}

	return $content;
}

if ( is_admin() ) {
	add_action('admin_menu', 'k_o_admin_actions');
	add_action('admin_init', 'k_o_register_settings');
}
else {
	add_filter('the_content', 'k_o_do_it');
}
?>