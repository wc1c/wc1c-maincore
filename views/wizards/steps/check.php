<?php defined('ABSPATH') || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

use Wc1c\Main\Admin\Wizards\Setup\Check;

if(!isset($args['step']))
{
    return;
}

/** @var Check $wizard */
$step = $args['step'];
$available = true;
?>

<h1><?php esc_html_e('Welcome to WC1C!', 'wc1c-maincore'); ?></h1>
<p><?php esc_html_e('Thank you for choosing WC1C to website! This is only complete solution for integrating WooCommerce with 1C.', 'wc1c-maincore'); ?></p>

<p><?php esc_html_e('This quick setup wizard will help you configure the basic settings.', 'wc1c-maincore'); ?></p>

<?php if(0 < (int)wc1c()->environment()->get('php_max_execution_time') && 10 > (int)wc1c()->environment()->get('php_max_execution_time')) : ?>
    <?php $available = false; ?>
    <p><?php esc_html_e('PHP scripts execution time is less than 10 seconds. WC1C requires at least 20. Set php_max_execution_time to more than 20 seconds.', 'wc1c-maincore'); ?></p>
<?php endif; ?>

<?php if($available) : ?>
    <p><strong><?php esc_html_e('Its should not take longer than five minutes.', 'wc1c-maincore'); ?></strong></p>
    <p class="mt-4 actions step">
        <a href="<?php echo esc_url($step->wizard()->getNextStepLink()); ?>" class="button button-primary button-large button-next">
            <?php esc_html_e('Lets Go!', 'wc1c-maincore'); ?>
        </a>
    </p>
<?php endif; ?>

<?php if(!$available) : ?>
    <p><strong><?php esc_html_e('Need to fix the compatibility errors and return to the setup wizard.', 'wc1c-maincore'); ?></strong></p>
<?php endif;

// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound