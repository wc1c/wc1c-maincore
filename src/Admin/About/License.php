<?php namespace Wc1c\Main\Admin\About;

defined('ABSPATH') || exit;

use Wc1c\Main\Abstracts\ScreenAbstract;
use Wc1c\Main\Traits\SingletonTrait;

/**
 * License
 *
 * @package Wc1c\Main\Admin\About
 */
class License extends ScreenAbstract
{
	use SingletonTrait;

	/**
	 * Build and output
	 */
	public function output()
	{
        $args = [];

		wc1c()->views()->getView('about/license.php', $args);
	}
}