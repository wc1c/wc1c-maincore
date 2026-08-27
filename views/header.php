<?php defined('ABSPATH') || exit; ?>

<h1 class="wp-heading-inline"><?php esc_html_e('Integration with 1C', 'wc1c-maincore'); ?></h1>

<a href="<?php echo esc_attr(esc_url($args['url_create'])); ?>" class="page-title-action">
	<?php esc_html_e('New configuration', 'wc1c-maincore'); ?>
</a>

<hr class="wp-header-end">

<?php
    if(wc1c()->context()->isAdmin())
    {
        wc1c()->admin()->notices()->output();
    }
?>