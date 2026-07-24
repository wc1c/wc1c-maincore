<?php defined('ABSPATH') || exit;?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<div class="row">
    <div class="col p-0">
        <div class="px-2">
			<?php
			$label = esc_html__('Back to all configurations', 'wc1c-maincore');
			wc1c()->views()->adminBackLink($label, $args['back_url']);
			?>
        </div>
    </div>
</div>

<div class="bg-white p-2 pt-3 pb-3 rounded-2">
	<?php
	    printf('%1$s <b>%2$s</b>', esc_html__('ID of the configuration to be deleted:', 'wc1c-maincore'), esc_html($args['configuration']->getId()));
	?>
    <br/>
    <?php
        printf('%1$s <b>%2$s</b>', esc_html__('Name of the configuration to be deleted:', 'wc1c-maincore'), esc_html($args['configuration']->getName()));
    ?>
    <br/>
    <?php
        printf('%1$s <b>%2$s</b>', esc_html__('Path of the configuration directory to be deleted:', 'wc1c-maincore'), esc_html($args['configuration']->getUploadDirectory()));
    ?>
</div>

<div class="">
	<?php do_action('wc1c_admin_configurations_form_delete_show'); ?>
</div>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>