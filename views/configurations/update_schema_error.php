<?php defined('ABSPATH') || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$title = esc_html__('Warning', 'wc1c-maincore');
$title = apply_filters('wc1c_admin_configurations_update_schema_error_title', $title);

$text = esc_html__('Update is not available.', 'wc1c-maincore');
$text = apply_filters('wc1c_admin_configurations_update_schema_error_text', $text);

?>

<div class="wc1c-configurations-alert mb-2">
    <h3><?php printf('%s', esc_html(sanitize_text_field($title))); ?></h3>
    <p class="fs-6"><?php printf('%s', wp_kses_post($text)); ?></p>
</div>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>