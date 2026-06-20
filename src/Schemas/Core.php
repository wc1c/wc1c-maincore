<?php namespace Wc1c\Main\Schemas;

defined('ABSPATH') || exit;

use Wc1c\Main\Configuration;
use Wc1c\Main\Data\Storage;
use Wc1c\Main\Data\Storages\ConfigurationsStorage;
use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Exceptions\RuntimeException;
use Wc1c\Main\Schemas\Contracts\SchemaContract;
use Wc1c\Main\Traits\SingletonTrait;

/**
 * Core
 *
 * @package Wc1c\Main\Schemas
 */
final class Core
{
	use SingletonTrait;

	/**
	 * @var array All loaded
	 */
	private $schemas = [];

	/**
	 * Set
	 *
	 * @param array $schemas
	 *
	 * @return void
	 */
	public function set(array $schemas)
	{
		$this->schemas = $schemas;
	}

	/**
	 * Initializing schemas
	 *
	 * @param integer|Configuration $configuration
	 *
	 * @return SchemaContract
	 * @throws Exception
	 */
	public function init($configuration): SchemaContract
	{
        wc1c()->log()->debug(esc_html__('Initializing the schema for configuration.', 'wc1c-main'));

		if(false === $configuration)
		{
			throw new Exception(esc_html__('$configuration is false', 'wc1c-main'));
		}

		if(!is_object($configuration))
		{
			/** @var ConfigurationsStorage $storage_configurations */
			$storage_configurations = Storage::load('configuration');

			if(!$storage_configurations->isExistingById($configuration))
			{
				throw new Exception(esc_html__('$configuration is not exists', 'wc1c-main'));
			}

			$configuration = new Configuration($configuration);
		}

		if(!$configuration instanceof Configuration)
		{
			throw new Exception(esc_html__('$configuration is not instanceof Configuration', 'wc1c-main'));
		}

		$schemas = $this->get();

		if(!is_array($schemas))
		{
			throw new Exception(esc_html__('$schemas is not array.', 'wc1c-main'));
		}

		$schema_id = $configuration->getSchema();

		if(!array_key_exists($schema_id, $schemas))
		{
			throw new Exception(esc_html__('Schema not found by id:', 'wc1c-main') . ' ' . esc_html($schema_id));
		}

		if(!is_object($schemas[$schema_id]))
		{
			throw new Exception(esc_html__('$schemas[$schema_id] is not object', 'wc1c-main'));
		}

		$init_schema = $schemas[$schema_id];

		if($init_schema->isInitialized())
		{
			throw new Exception(esc_html__('Old initialized, $schema_id:', 'wc1c-main') . ' ' . esc_html($schema_id));
		}

		if(!method_exists($init_schema, 'init'))
		{
			throw new Exception(esc_html__('Method init not found in schema, $schema_id:', 'wc1c-main') . ' ' . esc_html($schema_id));
		}

		$current_configuration_id = $configuration->getId();

		$init_schema->setPrefix(wc1c()->context()->getSlug() . '_prefix_' . esc_html($schema_id) . '_' . esc_html($current_configuration_id));
		$init_schema->setConfiguration($configuration);
		$init_schema->setConfigurationPrefix(wc1c()->context()->getSlug() . '_configuration_' . esc_html($current_configuration_id));

		try
		{
			$init_schema_result = $init_schema->init();
		}
		catch(\Throwable $e)
		{
			throw new Exception(esc_html($e->getMessage()));
		}

		if(true !== $init_schema_result)
		{
			throw new Exception(esc_html__('Schema is not initialized.', 'wc1c-main'));
		}

		$init_schema->setInitialized(true);

        wc1c()->log()->debug(esc_html__('Initializing the schema for configuration is completed.', 'wc1c-main'), ['configuration_id' => esc_html($current_configuration_id), 'schema_id' => esc_html($schema_id)]);

		return $init_schema;
	}

	/**
	 * Get schemas
	 *
	 * @param string $schema_id
	 *
	 * @return array|SchemaContract
	 * @throws RuntimeException
	 */
	public function get(string $schema_id = '')
	{
		$schema_id = strtolower($schema_id);

		if('' !== $schema_id)
		{
			if(array_key_exists($schema_id, $this->schemas))
			{
				return $this->schemas[$schema_id];
			}

			throw new RuntimeException(esc_html__('Schema by ID is unavailable.', 'wc1c-main'));
		}

		return $this->schemas;
	}

	/**
	 * Schemas loading
	 *
	 * @throws RuntimeException
	 */
	public function load()
	{
        wc1c()->log()->debug(esc_html__('Schemas loading.', 'wc1c-main'));

		add_action(wc1c()->context()->getSlug() . '_default_schemas_loading', [$this, 'loadProductsCml'], 10, 1);
		add_action(wc1c()->context()->getSlug() . '_default_schemas_loading', [$this, 'loadProductsCleanerCml'], 10, 1);

		$schemas = apply_filters(wc1c()->context()->getSlug() . '_default_schemas_loading', []);

		if('yes' === wc1c()->settings()->get('extensions_schemas', 'yes'))
		{
			$schemas = apply_filters(wc1c()->context()->getSlug() . '_schemas_loading', $schemas);
		}
        else
        {
            wc1c()->log()->info(esc_html__('Loading of external schemes is disabled through the settings. Only standard schemas are loaded.', 'wc1c-main'));
        }

		wc1c()->log()->debug(esc_html__('Schemas loading is completed.', 'wc1c-main'), ['schemas' => $schemas]);

		try
		{
			$this->set($schemas);
		}
		catch(\Throwable $e)
		{
			throw new RuntimeException(esc_html($e->getMessage()));
		}
	}

	/**
	 * Load schema: productscml
	 *
	 * @param $schemas
	 *
	 * @return array
	 */
	public function loadProductsCml($schemas): array
	{
		try
		{
			$schema = new Productscml\Core();
		}
		catch(\Throwable $e)
		{
			wc1c()->log('schemas')->error(esc_html__('Schema ProductsCML is not loaded.', 'wc1c-main'), ['exception' => $e]);
			return $schemas;
		}

		$schemas[$schema->getId()] = $schema;

		return $schemas;
	}

	/**
	 * Load schema: productscleanercml
	 *
	 * @param $schemas
	 *
	 * @return array
	 */
	public function loadProductsCleanerCml($schemas): array
	{
		try
		{
			$schema = new Productscleanercml\Core();
		}
		catch(\Throwable $e)
		{
			wc1c()->log('schemas')->error(esc_html__('Schema ProductsCleanerCML is not loaded.', 'wc1c-main'), ['exception' => $e]);
			return $schemas;
		}

		$schemas[$schema->getId()] = $schema;

		return $schemas;
	}
}