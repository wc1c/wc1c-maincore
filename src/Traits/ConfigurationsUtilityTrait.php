<?php namespace Wc1c\Main\Traits;

defined('ABSPATH') || exit;

/**
 * ConfigurationsUtilityTrait
 *
 * @package Wc1c\Main\Traits
 */
trait ConfigurationsUtilityTrait
{
	/**
	 * Get all available configurations statuses
	 *
	 * @return array
	 */
	public function utilityConfigurationsGetStatuses()
	{
		$statuses =
		[
			'draft',
			'inactive',
			'active',
			'processing',
			'error',
			'deleted',
		];

		return apply_filters('wc1c_configurations_get_statuses', $statuses);
	}

	/**
	 * Get normal configuration status
	 *
	 * @param string $status
	 *
	 * @return string
	 */
	public function utilityConfigurationsGetStatusesLabel($status)
	{
		$default_label = __('Undefined', 'wc1c-maincore');

		$statuses_labels = apply_filters
		(
			'wc1c_configurations_get_statuses_labels',
			[
				'draft' => __('Draft', 'wc1c-maincore'),
				'active' => __('Active', 'wc1c-maincore'),
				'inactive' => __('Inactive', 'wc1c-maincore'),
				'error' => __('Error', 'wc1c-maincore'),
				'processing' => __('Processing', 'wc1c-maincore'),
				'deleted' => __('Deleted', 'wc1c-maincore'),
			]
		);

		if(empty($status) || !array_key_exists($status, $statuses_labels))
		{
			$status_label = $default_label;
		}
		else
		{
			$status_label = $statuses_labels[$status];
		}

		return apply_filters('wc1c_configurations_get_statuses_label_return', $status_label, $status, $statuses_labels);
	}

	/**
	 * Get folder name for configuration statuses
	 *
	 * @param string $status
	 *
	 * @return string
	 */
	public function utilityConfigurationsGetStatusesFolder($status)
	{
		$default_folder = __('Undefined', 'wc1c-maincore');

		$statuses_folders = apply_filters
		(
			'wc1c_configurations_get_statuses_folders',
			[
				'draft' => __('Drafts', 'wc1c-maincore'),
				'active' => __('Activated', 'wc1c-maincore'),
				'inactive' => __('Deactivated', 'wc1c-maincore'),
				'error' => __('With errors', 'wc1c-maincore'),
				'processing' => __('In processing', 'wc1c-maincore'),
				'deleted' => __('Trash', 'wc1c-maincore'),
			]
		);

		$status_folder = $default_folder;

		if(!empty($status) || array_key_exists($status, $statuses_folders))
		{
			$status_folder = $statuses_folders[$status];
		}

		return apply_filters('wc1c_configurations_get_statuses_folder_return', $status_folder, $status, $statuses_folders);
	}
}