<?php namespace Wc1c\Main;

use Wc1c\Main\Schemas\Abstracts\SchemaAbstract;

defined('ABSPATH') || exit;

/**
 * Receiver
 *
 * @package Wc1c\Main
 */
final class Receiver
{
	/**
	 * Receiver constructor.
	 */
	public function __construct()
	{
		do_action(wc1c()->context()->getSlug() . '_receiver_loaded');
	}

	/**
	 * Receiver register.
	 *
	 * @return void
	 */
	public function register()
	{
		if(false === wc1c()->context()->isReceiver())
		{
			return;
		}

		add_filter('parse_request', [$this, 'handleRequests']);
	}

	/**
	 * Handle requests
	 */
	public function handleRequests()
	{
		$_wc1c_receiver = sanitize_text_field($_GET['wc1c-receiver']);

		$wc1c_receiver = wc1c()->getVar($_wc1c_receiver, false);

		wc1c()->log('receiver')->info(esc_html__('Received new request for Receiver.', 'wc1c-main'));
		wc1c()->log('receiver')->debug(esc_html__('Receiver request params.', 'wc1c-main'), ['GET' => $_GET, 'POST' => $_POST, 'SERVER' => $_SERVER]);

		wc1c()->define('WC1C_RECEIVER_REQUEST', true);

		if('yes' !== wc1c()->settings()->get('receiver', 'yes'))
		{
			wc1c()->log('receiver')->warning(esc_html__('Receiver is offline. Request reject.', 'wc1c-main'));
			die(esc_html__('Receiver is offline. Request reject.', 'wc1c-main'));
		}

		try
		{
			$configuration = new Configuration($wc1c_receiver);
		}
		catch(\Throwable $e)
		{
			wc1c()->log('receiver')->warning(esc_html__('Selected configuration for Receiver is unavailable.', 'wc1c-main'), ['exception' => $e]);
			die(esc_html__('Configuration for Receiver is unavailable.', 'wc1c-main'));
		}

		try
		{
			$schema = wc1c()->schemas()->init($configuration);
		}
		catch(\Throwable $e)
		{
			wc1c()->log('receiver')->error('Schema for configuration is not initialized.', ['exception' => $e]);
			die(esc_html__('Schema for configuration is not initialized.', 'wc1c-main'));
		}

		wc1c()->environment()->set('current_configuration_id', $wc1c_receiver);

        /**
         * Полноценная обработка по полным алгоритмам схемы
         */
		if(method_exists($schema, 'receiver'))
		{
			wc1c()->log('receiver')->info(esc_html__('The request was successfully submitted for processing in the schema for the selected configuration.', 'wc1c-main'), ['action' => 'receiver', 'schema' => $configuration->getSchema(), 'configuration_id' =>$configuration->getId()]);

			$schema->receiver();

			return;
		}

		if($configuration->isEnabled() === false)
		{
			wc1c()->log('receiver')->warning(esc_html__('Selected configuration is offline.', 'wc1c-main'));
			die(esc_html__('Selected configuration is offline.', 'wc1c-main'));
		}

		try
		{
			$configuration->setDateActivity(time());
			$configuration->save();
		}
		catch(\Throwable $e)
		{
			wc1c()->log('receiver')->error('Error saving configuration.', ['exception' => $e]);
			die(esc_html__('Error saving configuration.', 'wc1c-main'));
		}

		$action = false;
		$receiver_action = wc1c()->context()->getSlug() . '_receiver_' . $configuration->getSchema();

        /**
         * Обработка событий исходя из схемы
         */
		if(has_action($receiver_action))
		{
			$action = true;

			ob_start();
			nocache_headers();

			wc1c()->log('receiver')->notice(esc_html__('The request for processing actions for the selected configuration has been successfully submitted.', 'wc1c-main'), ['action' => $receiver_action, 'schema' => $configuration->getSchema(), 'configuration_id' => $configuration->getId()]);

            /**
             * @param Configuration $configuration Текущая конфигурация
             * @param SchemaAbstract $schema Текущая схема
             */
            do_action($receiver_action, $configuration, $schema);

			ob_end_clean();
		}

		if(false === $action)
		{
			wc1c()->log('receiver')->warning(esc_html__('Receiver request is very bad! Action not found in selected configuration.', 'wc1c-main'), ['action' => $receiver_action]);
			die(esc_html__('Receiver request is very bad! Action not found.', 'wc1c-main'));
		}
		die();
	}
}