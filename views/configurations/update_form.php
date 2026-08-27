<?php defined('ABSPATH') || exit;?>

<div class="row g-0">
    <div class="col-24 col-lg-17 p-0">
        <div class="pe-0 pe-lg-2">
            <form method="post" action="<?php echo esc_url(add_query_arg('form', $args['object']->getId())); ?>">
                <?php
                // Get configuration_id from the object or URL
                $configuration_id = isset($args['object']) && method_exists($args['object'], 'getConfiguration') 
                    ? $args['object']->getConfiguration()->getId() 
                    : (isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0);
                
                // Add unique nonce for this specific configuration update
                wp_nonce_field('wc1c_update_configuration_' . $configuration_id, '_wc1c_nonce');
                ?>
                <input type="hidden" name="configuration_id" value="<?php echo esc_attr($configuration_id); ?>">
                <div class="bg-white p-2 rounded-3 wc1c-toc-container">
                    <table class="form-table wc1c-admin-form-table">
                        <?php $args['object']->generateHtml($args['object']->getFields(), true); ?>
                    </table>
                </div>
                <p class="submit mt-0">
                    <input type="submit" name="submit" id="submit" class="button button-primary p-2 pt-1 pb-1 fs-6" value="<?php esc_attr_e('Save configuration', 'wc1c-maincore'); ?>">
                </p>
            </form>
        </div>
    </div>
    <div class="col-24 col-lg-7 p-0">
		<?php do_action('wc1c_admin_configurations_update_sidebar_show'); ?>
    </div>
</div>