<?php defined('ABSPATH') || exit; ?>

<?php
    printf
    (
        '<p>%1$s %2$s</p>',
            esc_html__('If no understand how Integration with 1C works, how to use and supplement it, can view the documentation.', 'wc1c-main'),
            esc_html__('Documentation contains all kinds of resources such as code snippets, user guides and more.', 'wc1c-main')
    );
?>

<a href="https://wc1c.info/docs" target="_blank" class="button button-primary">
    <?php esc_html_e('Documentation', 'wc1c-main'); ?>
</a>

<?php
if(has_action('wc1c_admin_help_main_show'))
{
    echo '<hr>';
    do_action('wc1c_admin_help_main_show');
}
?>