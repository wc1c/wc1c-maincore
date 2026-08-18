<?php defined('ABSPATH') || exit; ?>

<h2><?php esc_html_e( 'Found a bug?', 'wc1c-maincore' ); ?></h2>

<p>
    <?php esc_html_e('First of all, you need to make sure that a bug has been found and that it has not been fixed in updates before.', 'wc1c-maincore'); ?>
	<?php esc_html_e('If the bug is fixed in the updates, you just need to install the corrected version.', 'wc1c-maincore'); ?>
</p>
<p>
	<?php esc_html_e('Before reporting an error need to check:', 'wc1c-maincore'); ?>
</p>

<ul>
	<li><?php esc_html_e('Whether the settings for WordPress, WooCommerce, WC1C and their extensions are correct.', 'wc1c-maincore'); ?></li>
    <li><?php esc_html_e('Whether compatible versions of WordPress, WooCommerce, WC1C and their extensions are used. Compatibility can be found in the Environments section.', 'wc1c-maincore'); ?></li>
</ul>

<p>
	<?php esc_html_e('If all settings are made correctly and compatible products of the latest versions are used, but the error is still present, you must report it.', 'wc1c-maincore'); ?>
	<?php esc_html_e('Report a bug using the methods available to you. When reporting a bug, you must have a valid technical support code for the project on which the bug occurred.', 'wc1c-maincore'); ?>
</p>

<p>
	<a href="<?php echo esc_attr(esc_url(admin_url('admin.php?page=wc1c&section=tools&tool_id=environments'))); ?>" class="button">
		<?php esc_html_e('Environments', 'wc1c-maincore'); ?>
	</a>
</p>