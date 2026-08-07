<?php namespace Wc1c\Main\Admin\Settings;

defined('ABSPATH') || exit;

use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Settings\LogsSettings;

/**
 * LogsForm
 *
 * @package Wc1c\Main\Admin
 */
class LogsForm extends Form
{
	/**
	 * LogsForm constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->setId('settings-logs');
		$this->setSettings(new LogsSettings());

		add_filter('wc1c_' . $this->getId() . '_form_load_fields', [$this, 'init_fields_logger'], 10);

		$this->init();
	}

	/**
	 * Add settings for logger
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function init_fields_logger(array $fields): array
	{
		$fields['logger_level'] =
		[
			'title' => esc_html__('Level for main events', 'wc1c-maincore'),
			'type' => 'select',
			'description' => sprintf
            (
                '%s %s<hr><b>%s</b>: %s<br/><b>%s</b>: %s<br/><b>%s</b>: %s<br/><b>%s</b>: %s<br/><b>%s</b>: %s<hr>%s',
                esc_html__('Events of the selected level will be recorded in the log file. The bigger the level (numbers), the less data is recorded.', 'wc1c-maincore'),
                esc_html__('If the plugin is stable, you will need to use at least the NOTICE level (250), otherwise the event logs will become very large.', 'wc1c-maincore'),
                esc_html__('DEBUG (100)', 'wc1c-maincore'),
                esc_html__('Data from the algorithm development process with debugging information. It is used by administrators in case of some unclear errors in the process of plugin operation.', 'wc1c-maincore'),
                esc_html__('INFO (200)', 'wc1c-maincore'),
                esc_html__('Data from the algorithm development process, but without debugging information. In this mode, less data is written than in debugging mode, but still a lot.', 'wc1c-maincore'),
                esc_html__('NOTICE (250)', 'wc1c-maincore'),
                esc_html__('This mode records events that notify of situations that you need to be aware of. For example, when changing settings or renaming a product during data exchange.', 'wc1c-maincore'),
                esc_html__('WARNING (300)', 'wc1c-maincore'),
                esc_html__('Recording warnings that occur during operation. Warnings are worth investigating and taking into account, but they are not errors.', 'wc1c-maincore'),
                esc_html__('ERROR (400)', 'wc1c-maincore'),
                esc_html__('Will be recorded only data on errors occurring during the processing of plugin algorithms.', 'wc1c-maincore'),
                esc_html__('Event logs always record data about critical errors in the code, regardless of the level configured.', 'wc1c-maincore')
            ),
            'default' => '250',
			'options' =>
			[
				'100' => esc_html__('DEBUG (100)', 'wc1c-maincore'),
				'200' => esc_html__('INFO (200)', 'wc1c-maincore'),
				'250' => esc_html__('NOTICE (250)', 'wc1c-maincore'),
				'300' => esc_html__('WARNING (300)', 'wc1c-maincore'),
				'400' => esc_html__('ERROR (400)', 'wc1c-maincore'),
			],
		];

		$fields['logger_files_max'] =
		[
			'title' => esc_html__('Maximum files', 'wc1c-maincore'),
			'type' => 'text',
			'description' => esc_html__('Log files created daily. This option on the maximum number of stored files. By default saved of the logs are for the last 30 days.', 'wc1c-maincore'),
			'default' => 30,
			'css' => 'min-width: 20px;',
		];

		$fields['logger_output'] =
		[
			'title' => esc_html__('Output on display', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Check the box if you want to enable this feature. Disabled by default.', 'wc1c-maincore'),
			'description' => sprintf
            (
                '%s<hr>%s',
                esc_html__('All entries event logs will be displayed on the screen.', 'wc1c-maincore'),
                esc_html__('It is used only by developers. If enable this setting just for fun, 1C will not be able to connect to the site.', 'wc1c-maincore')
            ),
			'default' => 'no'
		];

		$fields['logger_title_level'] =
		[
			'title' => esc_html__('Levels by context', 'wc1c-maincore'),
			'type' => 'title',
            'description' => sprintf
            (
                '%s %s',
                esc_html__('Event log settings based on context.', 'wc1c-maincore'),
                esc_html__('Logical distribution of event logs into contexts for flexible analysis.', 'wc1c-maincore')
            ),
		];

		$fields['logger_tools_level'] =
		[
			'title' => esc_html__('Tools', 'wc1c-maincore'),
			'type' => 'select',
			'description' => sprintf
            (
                '%s<hr>%s',
                esc_html__('All events of the selected level are recorded in the tools events log file. The higher the level, the less data is logged.', 'wc1c-maincore'),
                esc_html__('In this context, events are recorded when users work with tools, both global and for a specific configuration.', 'wc1c-maincore')
            ),
            'default' => 'logger_level',
			'options' =>
			[
				'logger_level' => esc_html__('Use level for main events', 'wc1c-maincore'),
				'100' => esc_html__('DEBUG (100)', 'wc1c-maincore'),
				'200' => esc_html__('INFO (200)', 'wc1c-maincore'),
				'250' => esc_html__('NOTICE (250)', 'wc1c-maincore'),
				'300' => esc_html__('WARNING (300)', 'wc1c-maincore'),
				'400' => esc_html__('ERROR (400)', 'wc1c-maincore'),
			],
		];

		$fields['logger_schemas_level'] =
		[
			'title' => esc_html__('Schemas', 'wc1c-maincore'),
			'type' => 'select',
            'description' => sprintf
            (
                '%s<hr>%s',
                esc_html__('All events of the selected level are recorded in the events log files for schemas. The higher the level, the less data is logged.', 'wc1c-maincore'),
                esc_html__('Only events that are processed in the schema algorithms are recorded. If the events are related to the user configuration, they are not written to schema events.', 'wc1c-maincore')
            ),
            'default' => 'logger_level',
			'options' =>
			[
				'logger_level' => esc_html__('Use level for main events', 'wc1c-maincore'),
				'100' => esc_html__('DEBUG (100)', 'wc1c-maincore'),
				'200' => esc_html__('INFO (200)', 'wc1c-maincore'),
				'250' => esc_html__('NOTICE (250)', 'wc1c-maincore'),
				'300' => esc_html__('WARNING (300)', 'wc1c-maincore'),
				'400' => esc_html__('ERROR (400)', 'wc1c-maincore'),
			],
		];

		$fields['logger_configurations_level'] =
		[
			'title' => esc_html__('Configurations', 'wc1c-maincore'),
			'type' => 'select',
			'description' => sprintf
            (
                '%s<hr>%s',
                esc_html__('All events of the selected level will be recorded the configurations events in the log file. The higher the level, the less data is recorded.', 'wc1c-maincore'),
                esc_html__('A personalized event log level can be set on the level of each configuration. The current value is taken for creating new configurations.', 'wc1c-maincore')
            ),
			'default' => 'logger_level',
			'options' =>
			[
				'logger_level' => esc_html__('Use level for main events', 'wc1c-maincore'),
				'100' => esc_html__('DEBUG (100)', 'wc1c-maincore'),
				'200' => esc_html__('INFO (200)', 'wc1c-maincore'),
				'250' => esc_html__('NOTICE (250)', 'wc1c-maincore'),
				'300' => esc_html__('WARNING (300)', 'wc1c-maincore'),
				'400' => esc_html__('ERROR (400)', 'wc1c-maincore'),
			],
		];

		return $fields;
	}
}