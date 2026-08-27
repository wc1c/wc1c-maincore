<?php namespace Wc1c\Main\Admin\Configurations;

defined('ABSPATH') || exit;

use Wc1c\Main\Abstracts\FormAbstract;

/**
 * DeleteForm
 *
 * @package Wc1c\Main\Admin\Configurations
 */
class DeleteForm extends FormAbstract
{
	/**
	 * DeleteForm constructor.
	 */
	public function __construct()
	{
		$this->setId('configurations-delete');

		add_filter('wc1c_' . $this->getId() . '_form_load_fields', [$this, 'init_fields_main'], 10);

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
		$fields['accept'] =
		[
			'title' => esc_html__('Delete confirmation', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => sprintf
            (
				"%s<hr>%s",
                esc_html__('I confirm that Configuration will be permanently and irrevocably deleted from WooCommerce.', 'wc1c-maincore'),
                esc_html__('The directory with files for configuration from the FILE system will be completely removed.', 'wc1c-maincore')
			),
			'default' => 'no',
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

		wc1c()->views()->getView('configurations/delete_form.php', $args);
	}

	/**
	 * Save
	 *
	 * @return bool
	 */
	public function save(): bool
    {
		$post_data = $this->getPostedData();

		if(!isset($post_data['_wc1c_nonce']))
		{
			return false;
		}

		// Get configuration_id from POST data
		$configuration_id = isset($post_data['configuration_id']) ? absint(wp_unslash($post_data['configuration_id'])) : 0;
		
        $message = esc_html__('Configuration deleting error. Please retry.', 'wc1c-maincore');

		if(empty($post_data) || !wp_verify_nonce(sanitize_text_field(wp_unslash($post_data['_wc1c_nonce'])), 'wc1c_delete_configuration_' . $configuration_id))
		{
			wc1c()->admin()->notices()->create
			(
				[
					'type' => 'error',
					'data' => $message
				]
			);

            wc1c()->log()->warning($message, ['user_id' => get_current_user_id(), 'form_id' => $this->getId()]);

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
			catch(\Throwable $e)
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

		$data = $this->getSavedData();

		if(!isset($data['accept']) || $data['accept'] !== 'yes')
		{
            $message = esc_html__('Configuration deleting error. Confirmation of final deletion is required.', 'wc1c-maincore');

			wc1c()->admin()->notices()->create
			(
				[
					'type' => 'error',
					'data' => $message
				]
			);

            wc1c()->log()->warning($message, ['user_id' => get_current_user_id(), 'form_id' => $this->getId()]);

			return false;
		}

		return true;
	}
}