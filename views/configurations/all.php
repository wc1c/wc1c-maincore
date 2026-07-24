<?php defined('ABSPATH') || exit;?>

<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>

<div class="configurations-all">
    <form method="post" action="">
		<?php
		    $list_table = $args['object'];
		    $list_table->prepareItems();
		?>

		<?php $list_table->display(); ?>
    </form>
</div>

<?php // phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound ?>