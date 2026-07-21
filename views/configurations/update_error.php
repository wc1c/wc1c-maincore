<?php defined('ABSPATH') || exit;

    $label = __('Back to configurations list', 'wc1c-maincore');
    wc1c()->views()->adminBackLink($label, $args['back_url']);

    $title = esc_html__('Error', 'wc1c-maincore');
    $title = apply_filters('wc1c_admin_configurations_update_error_title', $title);
    $text = esc_html__('Update is not available. Configuration not found or unavailable.', 'wc1c-maincore');
    $text = apply_filters('wc1c_admin_configurations_update_error_text', $text);
?>

<div class="wc1c-configurations-alert mb-2 mt-2">
    <h3><?php printf('%s', esc_html($title)); ?></h3>
    <p><?php printf('%s', esc_html($text)); ?></p>
</div>