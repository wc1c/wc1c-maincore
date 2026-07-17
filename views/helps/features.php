<?php defined('ABSPATH') || exit; ?>

<h2><?php esc_html_e( 'Not a feature?', 'wc1c-main' ); ?></h2>

<p>
	<?php esc_html_e('First of all, you need to make sure - whether the necessary opportunity is really missing.', 'wc1c-main'); ?>
	<?php esc_html_e('It may be worth looking at the available settings or reading the documentation.', 'wc1c-main'); ?>
</p>

<p>
	<?php esc_html_e('Also, before requesting an opportunity, you need to make sure that:', 'wc1c-main'); ?>
</p>

<ul>
    <li><?php esc_html_e('Is the required feature added in WC1C updates.', 'wc1c-main'); ?></li>
    <li><?php esc_html_e('Whether the possibility is implemented by an additional extension to WC1C.', 'wc1c-main'); ?></li>
    <li><?php esc_html_e('Whether the desired opportunity is waiting for its implementation.', 'wc1c-main'); ?></li>
</ul>

<p>
	<?php esc_html_e('If the feature is added in WC1C updates, you just need to install the updated version.', 'wc1c-main'); ?>
</p>

<p>
	<?php esc_html_e('But if the feature is implemented in an extension to WC1C, then this feature should not be expected as part of WC1C and you need to install the extension.', 'wc1c-main'); ?>
	<?php esc_html_e('Because the feature implemented in the extension is so significant that it needed to create an extension for it.', 'wc1c-main'); ?>
</p>

<p>
	<a href="//wc1c.info/features" class="button" target="_blank">
		<?php esc_html_e('Features', 'wc1c-main'); ?>
	</a>
    <a href="//wc1c.info/extensions" class="button" target="_blank">
		<?php esc_html_e('Extensions', 'wc1c-main'); ?>
    </a>
</p>