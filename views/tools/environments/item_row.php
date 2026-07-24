<?php defined('ABSPATH') || exit; ?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<tr>
    <td style="width: 40%;">
        <b><?php printf('%s', esc_html($args['title'])); ?></b>
    </td>
    <td class="">
	    <?php
            $data = apply_filters('wc1c_admin_report_data_row_print', $args['data']);
            printf('%s', esc_html($data));
	    ?>
    </td>
</tr>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>