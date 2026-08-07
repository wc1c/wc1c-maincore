<?php namespace Wc1c\Main\Admin\Configurations;

defined('ABSPATH') || exit;

use Wc1c\Main\Admin\Traits\ProcessConfigurationTrait;
use Wc1c\Main\Exceptions\RuntimeException;
use Wc1c\Main\Traits\ConfigurationsUtilityTrait;
use Wc1c\Main\Traits\DatetimeUtilityTrait;
use Wc1c\Main\Traits\SectionsTrait;
use Wc1c\Main\Traits\SingletonTrait;
use Wc1c\Main\Traits\UtilityTrait;

/**
 * MainUpdate
 *
 * @package Wc1c\Main\Admin
 */
class MainUpdate
{
	use SingletonTrait;
	use DatetimeUtilityTrait;
	use UtilityTrait;
	use SectionsTrait;
	use ProcessConfigurationTrait;
	use ConfigurationsUtilityTrait;

	/**
	 * Update processing
	 */
	public function process()
	{
		$configuration = $this->getConfiguration();
		$form = new MainUpdateForm();
        $cap_check = true;

		$form_data = $configuration->getOptions();

		$form_data['status'] = $configuration->isEnabled() ? 'yes' : 'no';
        $form_data['status'] = $configuration->isDraft() ? 'yes' : $form_data['status'];

		$form->loadSavedData($form_data);

        $data = $form->save();

        if($configuration->getUserId() !== get_current_user_id() && !current_user_can('edit_others_products'))
        {
            $cap_check = false;

            wc1c()->admin()->notices()->create
            (
                [
                    'type' => 'error',
                    'data' => esc_html__('Error. You do not have permission to update this configuration.', 'wc1c-maincore')
                ]
            );
        }

        if($cap_check && !empty($data))
        {
            // Галка стоит
            if($data['status'] === 'yes')
            {
                if($configuration->isEnabled() === false)
                {
                    $configuration->setStatus('active');
                }
            }
            // галка не стоит
            else
            {
                $configuration->setStatus('inactive');
            }
            unset($data['status']);

            $configuration->setDateModify(time());
            $configuration->setOptions($data);

            $saved = $configuration->save();

            if($saved)
            {
                wc1c()->admin()->notices()->create
                (
                    [
                        'type' => 'update',
                        'data' => esc_html__('Configuration update success.', 'wc1c-maincore')
                    ]
                );
            }
            else
            {
                wc1c()->admin()->notices()->create
                (
                    [
                        'type' => 'error',
                        'data' => esc_html__('Configuration update error. Please retry saving or change fields.', 'wc1c-maincore')
                    ]
                );
            }
        }

		add_action('wc1c_admin_configurations_update_sidebar_show', [$this, 'outputSidebar'], 10);
		add_action('wc1c_admin_configurations_update_show', [$form, 'output'], 10);
	}

	/**
	 * Sidebar show
	 */
	public function outputSidebar()
	{
		$configuration = $this->getConfiguration();

		$configuration_options = $configuration->getOptions();
		if(isset($configuration_options['logger_level']))
		{
            $args = [];

			if((int)$configuration_options['logger_level'] === 100)
			{
				$args =
				[
					'type' => 'danger',
					'header' => '<h4 class="alert-heading mt-0 mb-1">' . esc_html__('Debug is enabled!', 'wc1c-maincore') . '</h4>',
					'object' => $this,
					'body' => esc_html__('The current configuration has debug mode enabled. You must disable this mode after debugging is complete.', 'wc1c-maincore')
				];
			}

			if((int)$configuration_options['logger_level'] === 200)
			{
				$args =
				[
					'type' => 'warning',
					'header' => '<h4 class="alert-heading mt-0 mb-1">' . esc_html__('Info is enabled!', 'wc1c-maincore') . '</h4>',
					'object' => $this,
					'body' => esc_html__('The extended information recording mode is enabled for the current configuration. It is recommended to disable this mode after debugging is complete.', 'wc1c-maincore')
				];
			}

			if((int)$configuration_options['logger_level'] <= 200)
			{
				wc1c()->views()->getView('configurations/update_sidebar_alert_item.php', $args);
			}
		}

		$args =
		[
			'header' => '<h3 class="p-0 m-0">' . esc_html__('About configuration', 'wc1c-maincore') . '</h3>',
			'object' => $this
		];

		$body = '<ul class="list-group m-0 list-group-flush">';
		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('ID:', 'wc1c-maincore') . ' <b>' . esc_attr($configuration->getId()) . '</b>';
		$body .= '</li>';
		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Schema ID:', 'wc1c-maincore') . ' <b>' . esc_attr($configuration->getSchema()) . '</b>';
		$body .= '</li>';

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Status', 'wc1c-maincore') . ': <b>' . esc_html($this->utilityConfigurationsGetStatusesLabel($configuration->getStatus())) . '</b>';
		$body .= '</li>';

		$body .= '<li class="list-group-item p-2 m-0">';
		$user_id = $configuration->getUserId();
		$user = get_userdata($user_id);
		if($user instanceof \WP_User && $user->exists())
		{
			$body .= esc_html__('Owner:', 'wc1c-maincore') . ' <b>' . esc_html($user->get('nickname')) . '</b> (' . esc_attr($user_id) . ')';
		}
		else
		{
			$body .= esc_html__('User is not exists.', 'wc1c-maincore');
		}
		$body .= '</li>';

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Date active:', 'wc1c-maincore') . '<div class="p-1 mt-1 bg-light">' . $this->utilityPrettyDate($configuration->getDateActivity());

		if($configuration->getDateActivity())
		{
            /* translators: human-readable time difference */
            $body .= sprintf(_x(' (%s ago).', '%s = human-readable time difference', 'wc1c-maincore'), human_time_diff($configuration->getDateActivity()->getOffsetTimestamp(), current_time('timestamp')));
		}

		$body .= '</div></li>';

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Date create:', 'wc1c-maincore') . '<div class="p-1 mt-1 bg-light">' . $this->utilityPrettyDate($configuration->getDateCreate());

		if($configuration->getDateCreate())
		{
            /* translators: human-readable time difference */
			$body .= sprintf(_x(' (%s ago).', '%s = human-readable time difference', 'wc1c-maincore'), human_time_diff($configuration->getDateCreate()->getOffsetTimestamp(), current_time('timestamp')));
		}

		$body .= '</div></li>';
		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Date modify:', 'wc1c-maincore') . '<div class="p-1 mt-1 bg-light">'. $this->utilityPrettyDate($configuration->getDateModify());

		if($configuration->getDateModify())
		{
            /* translators: human-readable time difference */
			$body .= sprintf(_x(' (%s ago).', '%s = human-readable time difference', 'wc1c-maincore'), human_time_diff($configuration->getDateModify()->getOffsetTimestamp(), current_time('timestamp')));
		}

		$body .= '</div></li>';

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Directory:', 'wc1c-maincore') . '<div class="p-1 mt-1 bg-light">' . wp_normalize_path($configuration->getUploadDirectory()) . '</div>';
		$body .= '</li>';

		$size = 0;
		$files = wc1c()->filesystem()->files($configuration->getUploadDirectory() . '/catalog');

		foreach($files as $file)
		{
			$size += wc1c()->filesystem()->size($file);
		}

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Directory size:', 'wc1c-maincore') . ' <b>' . size_format($size) . '</b>';
		$body .= '</li>';

		$size = 0;
		$files = wc1c()->filesystem()->files($configuration->getUploadDirectory('logs'));

		foreach($files as $file)
		{
			$size += wc1c()->filesystem()->size($file);
		}

		$body .= '<li class="list-group-item p-2 m-0">';
		$body .= esc_html__('Logs directory size:', 'wc1c-maincore') . ' <b>' . size_format($size) . '</b>';
		$body .= '</li>';

		$body .= '</ul>';

		$args['body'] = $body;

		wc1c()->views()->getView('configurations/update_sidebar_item.php', $args);

		try
		{
			$schema = wc1c()->schemas()->get($configuration->getSchema());

			$args =
			[
				'header' => '<h3 class="p-0 m-0">' . esc_html__('About schema', 'wc1c-maincore') . '</h3>',
				'object' => $this
			];

			$body = '<ul class="list-group m-0 list-group-flush">';
			$body .= '<li class="list-group-item p-2 m-0">';
			$body .= $schema->getDescription();
			$body .= '</li>';

			$body .= '</ul>';

			$args['body'] = $body;

			wc1c()->views()->getView('configurations/update_sidebar_item.php', $args);
		}
		catch(RuntimeException $e){}
	}
}