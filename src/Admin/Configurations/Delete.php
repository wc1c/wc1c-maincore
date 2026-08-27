<?php namespace Wc1c\Main\Admin\Configurations;

defined('ABSPATH') || exit;

use Wc1c\Main\Admin\Traits\ProcessConfigurationTrait;
use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Traits\SingletonTrait;
use Wc1c\Main\Traits\UtilityTrait;

/**
 * Delete
 *
 * @package Wc1c\Main\Admin\Configurations
 */
class Delete
{
	use SingletonTrait;
	use ProcessConfigurationTrait;
	use UtilityTrait;

	/**
	 * Delete constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
        $cap_check = true;

        // Check nonce - must be present in GET request
        if (!isset($_GET['_wc1c_nonce']))
        {
            $cap_check = false;
        }

        $nonce = sanitize_text_field(wp_unslash($_GET['_wc1c_nonce']));

        // Get configuration_id from GET to build nonce action
        $configuration_id = isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0;

        // If no configuration_id, nonce verification fails
        if ($configuration_id === 0)
        {
            $cap_check = false;
        }

        // Use unique nonce action per configuration for better security
        $nonce_action = 'wc1c_delete_configuration_' . $configuration_id;

        if (!wp_verify_nonce($nonce, $nonce_action)) {
            $cap_check = false;
        }

        // Check user capability
        if (!current_user_can('edit_others_products'))
        {
            $cap_check = false;
        }

        // Verify nonce and permissions BEFORE using any GET parameters
        if ($cap_check === false)
        {
            add_action('wc1c_admin_show', [$this, 'outputError'], 10);
            return;
        }

		$configuration_id = isset($_GET['configuration_id']) ? absint(wp_unslash($_GET['configuration_id'])) : 0;
		
		$error = $this->setConfiguration($configuration_id);

		if($error)
		{
			add_action('wc1c_admin_show', [$this, 'outputError'], 10);
		}
		else
		{
			$this->process();
		}
	}

	/**
	 * Delete processing
	 *
	 * @throws Exception
	 */
	public function process()
	{
		$configuration = $this->getConfiguration();

		$delete = false;
		$redirect = true;
		$force_delete = false;
		$configuration_status = $configuration->getStatus();
        $cap_check = true;

		$notice_args['type'] = 'error';
		$notice_args['data'] = esc_html__('Error. The configuration to be deleted is active and cannot be deleted.', 'wc1c-maincore');

        if($configuration->getUserId() !== get_current_user_id() && !current_user_can('delete_others_products'))
        {
            $notice_args['data'] = esc_html__('Error. You do not have permission to delete this configuration.', 'wc1c-maincore');
            $cap_check = false;
        }

		/**
		 * Защита от удаления активных соединений
		 */
		if($cap_check && !$configuration->isStatus('active') && !$configuration->isStatus('processing'))
		{
			/**
			 * Окончательное удаление черновиков без корзины
			 */
			if($configuration_status === 'draft' && 'yes' === wc1c()->settings()->get('configurations_draft_delete', 'yes'))
			{
				$delete = true;
				$force_delete = true;
			}

			/**
			 * Помещение в корзину без удаления
			 */
			if($configuration_status !== 'deleted' && $force_delete === false)
			{
				$delete = true;
			}

			/**
			 * Окончательное удаление из корзины - вывод формы для подтверждения удаления
			 */
			if($configuration_status === 'deleted')
			{
				$redirect = false;
				$delete_form = new DeleteForm();

				if(!$delete_form->save())
				{
					add_action('wc1c_admin_configurations_form_delete_show', [$delete_form, 'output']);
					add_action('wc1c_admin_show', [$this, 'output'], 10);
				}
				else
				{
					$delete = true;
					$force_delete = true;
					$redirect = true;
				}
			}

			/**
			 * Удаление с переносом в список всех учетных записей и выводом уведомления об удалении
			 */
			if($delete)
			{
				$notice_args =
				[
					'type' => 'update',
					'data' => esc_html__('The configuration has been marked as deleted.', 'wc1c-maincore')
				];

				if($force_delete)
				{
					wc1c()->filesystem()->deleteDirectory($configuration->getUploadDirectory());

					$notice_args =
					[
						'type' => 'update',
						'data' => esc_html__('The configuration has been successfully deleted.', 'wc1c-maincore')
					];
				}

				if(!$configuration->delete($force_delete))
				{
					$notice_args['type'] = 'error';
					$notice_args['data'] = esc_html__('Configuration deleting error. Please retry again.', 'wc1c-maincore');
				}
			}
		}

		if($redirect)
		{
			wc1c()->admin()->notices()->create($notice_args);
			wp_safe_redirect($this->utilityAdminConfigurationsGetUrl());
			die;
		}
	}

	/**
	 * Output error
	 */
	public function outputError()
	{
		wc1c()->views()->getView('configurations/delete_error.php');
	}

	/**
	 * Output permanent remove
	 *
	 * @return void
	 */
	public function output()
	{
		$args['back_url'] = $this->utilityAdminConfigurationsGetUrl('all');
		$args['configuration'] = $this->getConfiguration();

		wc1c()->views()->getView('configurations/delete.php', $args);
	}
}