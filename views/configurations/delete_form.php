<?php defined('ABSPATH') || exit;?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<form method="post" action="">
	<?php
        // Add unique nonce for this specific configuration deletion
        $configuration_id = isset($args['object']) && method_exists($args['object'], 'getConfiguration') ? $args['object']->getConfiguration()->getId() : 0;

        wp_nonce_field('wc1c_delete_configuration_' . $configuration_id, '_wc1c_nonce');
	?>
	<input type="hidden" name="configuration_id" value="<?php echo esc_attr($configuration_id); ?>">
    <div class="pt-2 rounded-3">
        <table class="form-table wc1c-admin-form-table">
            <?php
                if(isset($args) && is_array($args))
                {
                    $args['object']->generateHtml($args['object']->getFields(), true);
                }
            ?>
        </table>
    </div>
    <p class="submit p-1 pt-0 mt-1">
	    <input type="submit" name="submit" id="submit" class="button button-danger" value="<?php esc_html_e('Delete', 'wc1c-maincore'); ?>">
    </p>
</form>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>