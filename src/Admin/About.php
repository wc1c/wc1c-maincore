<?php namespace Wc1c\Main\Admin;

defined('ABSPATH') || exit;

use Wc1c\Main\Traits\SectionsTrait;
use Wc1c\Main\Traits\SingletonTrait;
use Wc1c\Main\Admin\About\Main;
use Wc1c\Main\Admin\About\License;

/**
 * About
 *
 * @package Wc1c\Main\Admin
 */
class About
{
    use SingletonTrait;
    use SectionsTrait;

    /**
     * About constructor.
     */
    public function __construct()
    {
        // hook
        do_action('wc1c_admin_about_before_loading');

        $this->init();
        $this->route();

        // hook
        do_action('wc1c_admin_about_after_loading');
    }

    /**
     * Initialization
     */
    public function init()
    {
        // hook
        do_action('wc1c_admin_about_before_init');

        $default_sections['main'] =
        [
            'title' => esc_html__('Main', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [Main::class, 'instance']
        ];

        $default_sections['license'] =
        [
            'title' => esc_html__('License', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [License::class, 'instance']
        ];

        $this->initSections($default_sections);

        // hook
        do_action('wc1c_admin_about_after_init');
    }

    /**
     * Initializing current section
     *
     * @return string
     */
    public function initCurrentSection(): string
    {
        $current_section = isset($_GET['do_about']) ? sanitize_key(wp_unslash($_GET['do_about'])) : 'main'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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
        wc1c()->views()->getView('about/sections.php');
    }

    /**
     * Error
     */
    public function wrapError()
    {
        wc1c()->views()->getView('error.php');
    }
}