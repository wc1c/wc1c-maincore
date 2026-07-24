<?php defined('ABSPATH') || exit; ?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<div class="row">
    <div class="col p-0">
        <div class="px-2">
            <?php
                $label = __('Back to all configurations', 'wc1c-maincore');
                wc1c()->views()->adminBackLink($label, $args['back_url']);
            ?>
        </div>
    </div>
</div>

<div class="">
	<?php do_action('wc1c_admin_configurations_form_create_show'); ?>
</div>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>