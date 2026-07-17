<?php defined('ABSPATH') || exit; ?>

<div class="extensions-alert mb-2 mt-2">
    <h3><?php esc_html_e('Extensions not found.', 'wc1c-main'); ?></h3>
    <p><?php esc_html_e('As soon as the extensions are installed, they will appear in this section.', 'wc1c-main'); ?></p>

	<?php
	printf
	(
		'<p>%1$s %2$s</p>',
            esc_html__('Information about all available official extensions is available on the website:', 'wc1c-main'),
		'<a href="https://wc1c.info/extensions" target=_blank>https://wc1c.info/extensions</a>'
	);
	?>
</div>