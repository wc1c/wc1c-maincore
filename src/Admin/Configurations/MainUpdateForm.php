<?php namespace Wc1c\Main\Admin\Configurations;

defined('ABSPATH') || exit;

use Exception;
use Wc1c\Main\Abstracts\FormAbstract;
use Wc1c\Main\Traits\ConfigurationsUtilityTrait;

/**
 * UpdateForm
 *
 * @package Wc1c\Main\Admin\Configurations
 */
class MainUpdateForm extends FormAbstract
{
    use ConfigurationsUtilityTrait;

	/**
	 * UpdateForm constructor.
	 */
	public function __construct()
	{
		$this->setId('configurations-update');

		add_filter('wc1c_' . $this->getId() . '_form_load_fields', [$this, 'init_fields_main'], 3);
		add_action('wc1c_admin_configurations_update_sidebar_show', [$this, 'outputNavigation'], 20);

		$this->loadFields();
	}

	/**
	 * Add for Main
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function init_fields_main($fields): array
	{
		$fields['status'] =
        [
            'title' => esc_html__('Status', 'wc1c-maincore'),
            'type' => 'checkbox',
            'label' => esc_html__('Check the box if you want to enable this configuration. Disabled by default.', 'wc1c-maincore'),
            'default' => 'yes',
            'description' => sprintf
            (
	            '%s',
                    esc_html__('The configuration is either enabled or disabled. In the off state, all configuration mechanisms will not work.', 'wc1c-maincore')
            ),
        ];

		return $fields;
	}

	/**
	 * Form show
	 */
	public function output()
	{
		$args =
		[
			'object' => $this
		];

		wc1c()->views()->getView('configurations/update_form.php', $args);
	}

	/**
	 * Save
	 *
	 * @return array|boolean
	 */
	public function save()
	{
		$post_data = $this->getPostedData();

		if(!isset($post_data['_wc1c_nonce']))
		{
			return false;
		}

		// Get configuration_id from POST data or URL
		$configuration_id = isset($post_data['configuration_id']) ? absint(wp_unslash($post_data['configuration_id'])) : 
			(isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0);
		
		if(empty($post_data) || !wp_verify_nonce(sanitize_text_field(wp_unslash($post_data['_wc1c_nonce'])), 'wc1c_update_configuration_' . $configuration_id))
		{
			wc1c()->admin()->notices()->create
			(
				[
					'type' => 'error',
					'data' => esc_html__('Update error. Please retry.', 'wc1c-maincore')
				]
			);

			return false;
		}

		foreach($this->getFields() as $key => $field)
		{
			$field_type = $this->getFieldType($field);

			if('title' === $field_type || 'raw' === $field_type)
			{
				continue;
			}

			try
			{
				$this->saved_data[$key] = $this->getFieldValue($key, $field, $post_data);
			}
			catch(Exception $e)
			{
				wc1c()->admin()->notices()->create
				(
					[
						'type' => 'error',
						'data' => $e->getMessage()
					]
				);

				return false;
			}
		}

		return $this->getSavedData();
	}

	/**
	 * Navigation show
	 */
	public function outputNavigation()
	{
        $show = false;

		$args =
        [
            'header' => '<h3 class="p-0 m-0">' . esc_html__('Fast navigation', 'wc1c-maincore') . '</h3>',
            'object' => $this
        ];

		$body = '<div class="wc1c-toc m-0">';

		$form_fields = $this->getFields();

		foreach($form_fields as $k => $v)
		{
			$type = $this->getFieldType($v);

			if($type !== 'title')
			{
				continue;
			}

			if(method_exists($this, 'generateNavigationHtml'))
			{
                $show = true;
				$body .= $this->{'generateNavigationHtml'}($k, $v);
			}
		}

		$body .= '</div>';

        if($show)
        {
	        $args['body'] = $body;

	        wc1c()->views()->getView('configurations/update_sidebar_toc.php', $args);
        }
	}

	/**
	 * Generate navigation HTML
	 *
	 * @param string $key - field key
	 * @param array $data - field data
	 *
	 * @return string
	 */
	public function generateNavigationHtml(string $key, array $data): string
	{
		$field_key = $this->getPrefixFieldKey($key);

		$defaults =
		[
			'title' => '',
			'class' => '',
		];

		$data = wp_parse_args($data, $defaults);

		ob_start();
		?>
		<a class="list-group-item m-0 border-0" href="#<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?></a>
		<?php

		return ob_get_clean();
	}
}