<?php namespace Wc1c\Main\Extensions;

defined('ABSPATH') || exit;

use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Extensions\Contracts\ExtensionContract;
use Wc1c\Main\Traits\SingletonTrait;

/**
 * Core
 *
 * @package Wc1c\Main\Extensions
 */
final class Core
{
	use SingletonTrait;

	/**
	 * @var array All loaded
	 */
	private array $extensions = [];

	/**
	 * Set
	 *
	 * @param array $extensions
	 *
	 * @return void
     */
	public function set(array $extensions)
	{
		$this->extensions = $extensions;
	}

	/**
	 * Initializing extensions
	 *
	 * @param string $extension_id If an extension ID is specified, only the specified extension is loaded
	 *
	 * @return void
	 * @throws Exception
	 */
	public function init(string $extension_id = '')
	{
		$extensions = $this->get();

		/**
		 * Init specified extension
		 */
		if('' !== $extension_id)
		{
            wc1c()->log()->debug(sprintf('%s: %s', esc_html__('Initialization of the extension by identifier', 'wc1c-main'), esc_attr($extension_id)), ['extension_id' => esc_attr($extension_id)]);

			if(!array_key_exists($extension_id, $extensions))
			{
                wc1c()->log()->warning(esc_html__('Extension not found by identifier.', 'wc1c-main'), ['extension_id' => esc_attr($extension_id)]);
				return;
			}

			if(!$extensions[$extension_id] instanceof ExtensionContract)
			{
                wc1c()->log()->warning(esc_html__('Extension is not implementation ExtensionContract. Initialization skipped.', 'wc1c-main'), ['extension_id' => esc_attr($extension_id)]);
                return;
			}

			if($extensions[$extension_id]->isInitialized())
			{
                wc1c()->log()->warning(esc_html__('The extension has already been initialized previously. Re-initialization is skipped.', 'wc1c-main'), ['extension_id' => esc_attr($extension_id)]);
                return;
			}

			try
			{
				$extensions[$extension_id]->init();
				$extensions[$extension_id]->setInitialized(true);
			}
			catch(\Throwable $e)
			{
				throw new Exception(sprintf('%s: %s', esc_html__('The extension threw an exception on initialization', 'wc1c-main'), esc_html($e->getMessage())));
			}

			$this->set($extensions);

            wc1c()->log()->debug(sprintf('%s "%s" %s', esc_html__('Initialization of the extension by identifier', 'wc1c-main'), esc_attr($extension_id), esc_html__('is completed.', 'wc1c-main')), ['extension_id' => esc_attr($extension_id)]);

            return;
		}

        wc1c()->log()->debug(esc_html__('Initialization of available extensions.', 'wc1c-main'));

		/**
		 * Init all extensions
		 */
		foreach($extensions as $extension => $extension_object)
		{
			try
			{
				$this->init($extension);
			}
			catch(\Throwable $e)
			{
				wc1c()->log()->warning(esc_html($e->getMessage()), ['exception' => $e]);
			}
		}

        wc1c()->log()->debug(esc_html__('Initialization of available extensions is completed.', 'wc1c-main'));
	}

	/**
	 * Get initialized extensions
	 *
	 * @param string $extension_id
	 *
	 * @return array|ExtensionContract
	 * @throws Exception
	 */
	public function get(string $extension_id = '')
	{
		if('' !== $extension_id)
		{
			if(array_key_exists($extension_id, $this->extensions))
			{
				return $this->extensions[$extension_id];
			}

			throw new Exception(esc_html__('Get extension by id is unavailable.', 'wc1c-main'));
		}

		return $this->extensions;
	}

	/**
	 * Extensions load
	 *
	 * @return void
	 * @throws Exception
	 */
	public function load()
	{
        wc1c()->log()->debug(esc_html__('Extensions loading.', 'wc1c-main'));

        if('yes' !== wc1c()->settings('main')->get('extensions', 'yes'))
        {
            wc1c()->log()->info(esc_html__('Extension loading is turned off in global settings. Extension loading is skipped.', 'wc1c-main'));
            return;
        }

		$extensions = [];

		if(has_filter(wc1c()->context()->getSlug() . '_extensions_loading'))
		{
			try
			{
				$extensions = apply_filters(wc1c()->context()->getSlug() . '_extensions_loading', $extensions);
			}
			catch(\Error $e)
			{
				throw new Exception(esc_html__('Extensions load error:', 'wc1c-main') . ' ' . esc_html($e->getMessage()));
			}
			catch(\Throwable $e)
			{
				throw new Exception(esc_html__('Extensions load exception:', 'wc1c-main') . ' ' . esc_html($e->getMessage()));
			}
		}

		$this->set($extensions);

        wc1c()->log()->debug(esc_html__('Extensions loading is completed.', 'wc1c-main'), ['extensions' => $extensions]);
	}
}