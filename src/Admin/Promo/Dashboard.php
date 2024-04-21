<?php namespace Wc1c\Main\Admin\Promo;

defined('ABSPATH') || exit;

use Wc1c\Main\Traits\SingletonTrait;

/**
 * Dashboard
 *
 * @package Wc1c\Main\Admin\Promo
 */
final class Dashboard
{
	use SingletonTrait;

	/**
	 * Initialized
	 */
	public function __construct()
	{
        wp_add_dashboard_widget( 'wc1c-activation', __('Activation WC1C', 'wc1c-main'), [$this, 'output']);
	}

	/**
	 * Output
	 *
	 * @return void
	 */
	public function output()
	{
		$args['object'] = $this;

		wc1c()->views()->getView('promo/dashboard.php', $args);
	}
}