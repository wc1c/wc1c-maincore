<?php namespace Wc1c\Main\Admin;

defined('ABSPATH') || exit;

use Wc1c\Main\Admin\Settings\LogsForm;
use Wc1c\Main\Admin\Settings\MainForm;
use Wc1c\Main\Admin\Settings\InterfaceForm;
use Wc1c\Main\Traits\SectionsTrait;
use Wc1c\Main\Traits\SingletonTrait;

/**
 * Settings
 *
 * @package Wc1c\Main\Admin
 */
class Settings
{
    use SingletonTrait;
    use SectionsTrait;

    /**
     * Settings constructor.
     */
    public function __construct()
    {
        // hook
        do_action('wc1c_admin_settings_before_loading');

        $this->init();
        $this->route();

        // hook
        do_action('wc1c_admin_settings_after_loading');
    }

    /**
     * Initialization
     */
    public function init()
    {
        // hook
        do_action('wc1c_admin_settings_before_init');

        $default_sections['main'] =
        [
            'title' => esc_html__('Main', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [MainForm::class, 'instance']
        ];

        $default_sections['logs'] =
        [
            'title' => esc_html__('Event logs', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [LogsForm::class, 'instance']
        ];

        $default_sections['interface'] =
        [
            'title' => esc_html__('Interface', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [InterfaceForm::class, 'instance']
        ];

        $this->initSections($default_sections);

        // hook
        do_action('wc1c_admin_settings_after_init');
    }

    /**
     * Initializing current section
     *
     * @return string
     */
    public function initCurrentSection(): string
    {
        // Nonce не требуется, это только навигационный параметр.
        $current_section = isset($_GET['do_settings']) ? sanitize_key(wp_unslash($_GET['do_settings'])) : 'main'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $current_section = !empty($current_section) ? $current_section : 'main';

        if($current_section !== '')
        {
            $this->setCurrentSection($current_section);
        }

        return $this->getCurrentSection();
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
            add_action('wc1c_admin_show', [$this, 'wrapError']);
        }
        else
        {
            add_action('wc1c_admin_show', [$this, 'wrapSections'], 7);

            $callback = $sections[$current_section]['callback'];

            if(is_callable($callback, false, $callback_name))
            {
                $callback_name();
            }
        }
    }

    /**
     * Sections
     */
    public function wrapSections()
    {
        wc1c()->views()->getView('settings/sections.php');
    }

    /**
     * Error
     */
    public function wrapError()
    {
        wc1c()->views()->getView('settings/error.php');
    }
}