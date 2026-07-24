<?php namespace Wc1c\Main;

defined('ABSPATH') || exit;

/**
 * Context
 *
 * @package Wc1c\Main
 */
final class Context extends \Digiom\Woplucore\Context
{
	/**
	 * Is Receiver request?
	 *
	 * @return bool
	 */
    public function isReceiver(): bool
    {
        return filter_has_var(INPUT_GET, 'wc1c-receiver') || filter_has_var(INPUT_POST, 'wc1c-receiver');
    }
}