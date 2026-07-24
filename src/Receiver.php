<?php namespace Wc1c\Main;

defined('ABSPATH') || exit;

use Wc1c\Main\Schemas\Abstracts\SchemaAbstract;

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
    public function register(): void
    {
        if(false === wc1c()->context()->isReceiver())
        {
            return;
        }

        add_filter('parse_request', [$this, 'handleRequests']);
    }

    /**
     * Handle requests
     *
     * @return void
     */
    public function handleRequests(): void
    {
        $config_id = filter_input(INPUT_GET, 'wc1c-receiver', FILTER_VALIDATE_INT);
        if ($config_id === false || $config_id <= 0)
        {
            $msg = esc_html__('Receiver request missing or invalid configuration ID.', 'wc1c-maincore');
            wc1c()->log('receiver')->warning($msg);
            die(esc_html($msg));
        }

        wc1c()->log('receiver')->info(esc_html__('Received new request for Receiver.', 'wc1c-maincore'));

        wc1c()->define('WC1C_RECEIVER_REQUEST', true);

        if('yes' !== wc1c()->settings()->get('receiver', 'yes'))
        {
            $msg = esc_html__('Receiver is offline. Request reject.', 'wc1c-maincore');
            wc1c()->log('receiver')->warning($msg);
            die(esc_html($msg));
        }

        try
        {
            $configuration = new Configuration($config_id);
        }
        catch(\Throwable $e)
        {
            $msg = esc_html__('Configuration for Receiver is unavailable.', 'wc1c-maincore');
            wc1c()->log('receiver')->warning($msg, ['exception' => $e]);
            die(esc_html($msg));
        }

        try
        {
            $schema = wc1c()->schemas()->init($configuration);
        }
        catch(\Throwable $e)
        {
            $msg = esc_html__('Schema for configuration is not initialized.', 'wc1c-maincore');
            wc1c()->log('receiver')->error($msg, ['exception' => $e]);
            die(esc_html($msg));
        }

        wc1c()->environment()->set('current_configuration_id', $config_id);

        /**
         * Полноценная обработка по полным алгоритмам схемы
         */
        if(method_exists($schema, 'receiver'))
        {
            wc1c()->log('receiver')->info
            (
                esc_html__('The request was successfully submitted for processing in the schema for the selected configuration.', 'wc1c-maincore'),
                [
                    'action'           => esc_html('receiver'),
                    'schema'           => esc_html($configuration->getSchema()),
                    'configuration_id' => esc_html($configuration->getId())
                ]
            );

            $schema->receiver();

            return;
        }

        if($configuration->isEnabled() === false)
        {
            $msg = esc_html__('Selected configuration is offline.', 'wc1c-maincore');
            wc1c()->log('receiver')->warning($msg);
            die(esc_html($msg));
        }

        try
        {
            $configuration->setDateActivity(time());
            $configuration->save();
        }
        catch(\Throwable $e)
        {
            $msg = esc_html__('Error saving configuration.', 'wc1c-maincore');
            wc1c()->log('receiver')->error($msg, ['exception' => $e]);
            die(esc_html($msg));
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

            wc1c()->log('receiver')->notice
            (
                esc_html__('The request for processing actions for the selected configuration has been successfully submitted.', 'wc1c-maincore'),
                [
                    'action' => esc_html($receiver_action),
                    'schema' => esc_html($configuration->getSchema()),
                    'configuration_id' => esc_html($configuration->getId())
                ]
            );

            /**
             * @param Configuration $configuration Текущая конфигурация
             * @param SchemaAbstract $schema Текущая схема
             *
             * todo: optimize via remove dynamic  hook
             */
            do_action($receiver_action, $configuration, $schema); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound

            ob_end_clean();
        }

        if(false === $action)
        {
            $msg = esc_html__('Receiver request is very bad! Action not found.', 'wc1c-maincore');
            wc1c()->log('receiver')->warning($msg, ['action' => esc_html($receiver_action)]);
            die(esc_html($msg));
        }
        die();
    }
}