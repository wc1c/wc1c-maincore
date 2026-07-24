<?php defined('ABSPATH') || exit; ?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<?php
	if(empty($_REQUEST['s'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	{
?>
    <div class="alert alert-primary fs-6">
        <?php esc_html_e('To exchange data, at least one configuration must be created and configured.', 'wc1c-maincore'); ?>
        <?php esc_html_e('The number of settings and method of data exchange in the configuration depends on the scheme selected for exchange during creation.', 'wc1c-maincore'); ?>
    </div>
<?php
	}
?>

<h2 class="mt-0">
<?php
	if(!empty($_REQUEST['s'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	{
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $search_text = sanitize_text_field(wp_unslash($_REQUEST['s']));

        echo '<br/>';
        printf('%1$s %2$s', esc_html__( 'Configurations by query is not found, query:', 'wc1c-maincore' ), esc_html($search_text));
	}
    else
    {
	    esc_html_e('Configurations not found', 'wc1c-maincore');
    }
?>
</h2>

<?php
    if(empty($_REQUEST['s'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    {
?>
    <p class="fs-6">
        <?php esc_html_e('For flexible exchange distribution, an unlimited number of configurations can be created.', 'wc1c-maincore'); ?>
        <?php esc_html_e('It is recommended to create at least two configurations: 1. To exchange nomenclature data, 2. To exchange orders data.', 'wc1c-maincore'); ?>
    </p>
    <a href="<?php echo esc_url_raw($args['url_create']); ?>" class="mt-2 mx-0 fs-6 btn-lg d-inline-block page-title-action">
        <?php esc_html_e('New configuration', 'wc1c-maincore'); ?>
    </a>
<?php
    }
?>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>