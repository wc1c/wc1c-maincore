<?php defined('ABSPATH') || exit;

$activation_url = get_home_url('', add_query_arg(['section' => 'settings', 'do_settings' => 'activation']));

$text = sprintf
(
'%s %s <hr>%s<br><a href="%s">%s</a>',
    __('Your copy of the free software has not been activated.', 'wc1c-main'),
    __('We recommend that you activate your copy of the free software for stable updates and better performance.', 'wc1c-main'),
    __('After activation, this section will disappear and will no longer be shown.', 'wc1c-main'),
    $activation_url, $activation_url
);

?>

<div class="fs-6 alert wc1c-yellow-alert p-2"><?php echo wp_kses_post($text); ?></div>