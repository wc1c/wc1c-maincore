<?php namespace Wc1c\Main\Admin\Configurations;

defined('ABSPATH') || exit;

use Wc1c\Main\Admin\Traits\ProcessConfigurationTrait;
use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Traits\DatetimeUtilityTrait;
use Wc1c\Main\Traits\SectionsTrait;
use Wc1c\Main\Traits\SingletonTrait;
use Wc1c\Main\Traits\UtilityTrait;

/**
 * Update
 *
 * @package Wc1c\Main\Admin
 */
class Update
{
	use SingletonTrait;
	use ProcessConfigurationTrait;
	use DatetimeUtilityTrait;
	use UtilityTrait;
	use SectionsTrait;

	/**
	 * Update constructor.
	 */
	public function __construct()
	{
		// Verify nonce and permissions BEFORE using any GET parameters
		if (!$this->verify_nonce_and_permissions()) {
			add_action('wc1c_admin_show', [$this, 'outputError'], 10);
			wc1c()->log()->notice('Configuration update nonce or permission check failed.');
			return;
		}
		
		$configuration_id = isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0;

		$this->setSectionKey('update_section');

		$default_sections['main'] =
		[
			'title' => esc_html__('Settings', 'wc1c-maincore'),
			'visible' => true,
			'callback' => [MainUpdate::class, 'instance']
		];

		if(has_action('wc1c_admin_configurations_update_sections'))
		{
			$default_sections = apply_filters('wc1c_admin_configurations_update_sections', $default_sections);
		}

		$this->initSections($default_sections);
		$this->setCurrentSection('main');

		if(false === $this->setConfiguration($configuration_id))
		{
            if($this->getConfiguration()->getUserId() !== get_current_user_id() && !current_user_can('edit_others_products'))
            {
                add_action('wc1c_admin_show', [$this, 'outputError'], 10);
                wc1c()->log()->notice('Configuration update is not available.', ['configuration_id' => $configuration_id]);
                return;
            }

			try
			{
				wc1c()->schemas()->init($this->getConfiguration());
			}
			catch(Exception $e)
			{
				add_action('wc1c_admin_configurations_update_show', [$this, 'outputSchemaError'], 10);
				add_filter('wc1c_admin_configurations_update_schema_error_text', [$this, 'outputSchemaErrorText'], 10, 1);

				wc1c()->log()->notice('Schema is not initialized.', ['exception' => $e]);
			}

			$this->process();
		}
		else
		{
			add_action('wc1c_admin_show', [$this, 'outputError'], 10);
			wc1c()->log()->notice('Configuration update is not available.', ['configuration_id' => $configuration_id]);
			return;
		}

		$this->route();

		add_action('wc1c_admin_show', [$this, 'output'], 10);
	}

	/**
	 * Verify nonce and user permissions for update action
	 *
	 * @return bool
	 */
	private function verify_nonce_and_permissions(): bool
	{
		// Check nonce - must be present in GET request
		if (!isset($_GET['_wc1c_nonce'])) {
			return false;
		}

		$nonce = sanitize_text_field(wp_unslash($_GET['_wc1c_nonce']));
		
		// Get configuration_id from GET to build nonce action
		$configuration_id = isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0;
		
		// If no configuration_id, nonce verification fails
		if ($configuration_id === 0) {
			return false;
		}
		
		// Use unique nonce action per configuration for better security
		$nonce_action = 'wc1c_update_configuration_' . $configuration_id;

		if (!wp_verify_nonce($nonce, $nonce_action)) {
			return false;
		}

		// Check user capability
		if (!current_user_can('edit_others_products')) {
			return false;
		}

		return true;
	}

	/**
	 * @param $text
	 *
	 * @return string
	 */
	public function outputSchemaErrorText($text): string
	{
		$new_text = esc_html__('The exchange schema based on which the configuration was created is not available.', 'wc1c-maincore');

		$new_text .= '<br /> ' . esc_html__('Install the missing schema to work this configuration, change the status and name or delete the configuration.', 'wc1c-maincore');

		return $new_text;
	}

	/**
	 *  Routing
	 */
	public function route()
	{
		$sections = $this->getSections();
		$current_section = $this->initCurrentSection();

		if(!array_key_exists($current_section, $sections) || !isset($sections[$current_section]['callback']))
		{
			add_action('wc1c_admin_configurations_update_show', [$this, 'wrapError']);
		}
		else
		{
			add_action('wc1c_admin_before_configurations_update_show', [$this, 'wrapSections'], 5);

			$callback = $sections[$current_section]['callback'];

			if(is_callable($callback, false, $callback_name))
			{
				$callback_obj = $callback_name();
				$callback_obj->setConfiguration($this->getConfiguration());
				$callback_obj->process();
			}
		}
	}

	/**
	 * Update processing
	 */
	public function process()
	{
		$configuration = $this->getConfiguration();

		$inline_fields['name'] =
		[
			'title' => esc_html__('Configuration name', 'wc1c-maincore'),
			'type' => 'text',
			'description' => esc_html__('Used for convenient distribution of multiple configurations.', 'wc1c-maincore'),
			'default' => '',
			'class' => 'form-control form-control-sm rounded-0',
            'button_class' => 'rounded-0',
			'button' => esc_html__('Rename', 'wc1c-maincore'),
		];

		$inline_args =
		[
			'id' => 'configurations-name',
			'fields' => $inline_fields,
            'configuration' => $configuration,
		];

		$inline_form = new InlineForm($inline_args);

		$inline_form->loadSavedData(['name' => $configuration->getName()]);
        $inline_form->save();

		add_action('wc1c_admin_configurations_update_header_show', [$inline_form, 'output'], 10);
	}

	/**
	 * Error
	 */
	public function wrapError()
	{
		wc1c()->views()->getView('error.php');
	}

	/**
	 * Output error
	 */
	public function outputError()
	{
		$args['back_url'] = $this->utilityAdminConfigurationsGetUrl('all');

		wc1c()->views()->getView('configurations/update_error.php', $args);
	}

	/**
	 * Output schema error
	 */
	public function outputSchemaError()
	{
		wc1c()->views()->getView('configurations/update_schema_error.php');
	}

	/**
	 * Sections
	 *
	 * @return void
	 */
	public function wrapSections()
	{
		$args['object'] = $this;

		wc1c()->views()->getView('configurations/update_sections.php', $args);
	}

	/**
	 * Output
	 *
	 * @return void
	 */
	public function output()
	{
		$args['back_url'] = $this->utilityAdminConfigurationsGetUrl('all');

		wc1c()->views()->getView('configurations/update.php', $args);
	}
}