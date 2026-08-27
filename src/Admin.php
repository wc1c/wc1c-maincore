<?php namespace Wc1c\Main;

// phpcs:disable WordPress.Security.NonceVerification.Recommended

defined('ABSPATH') || exit;

use Digiom\Wotices\Interfaces\ManagerInterface;
use Digiom\Wotices\Manager;
use Wc1c\Main\Admin\About;
use Wc1c\Main\Admin\Configurations;
use Wc1c\Main\Admin\Extensions;
use Wc1c\Main\Admin\Settings;
use Wc1c\Main\Admin\Tools;
use Wc1c\Main\Traits\SectionsTrait;
use Wc1c\Main\Traits\SingletonTrait;
use Wc1c\Main\Traits\UtilityTrait;

/**
 * Admin
 *
 * @package Wc1c\Main
 */
final class Admin
{
	use SingletonTrait;
	use SectionsTrait;
	use UtilityTrait;

	/**
	 * @var ManagerInterface|null Admin notices
	 */
	private $notices;

	/**
	 * Admin constructor.
	 */
	public function __construct()
	{
		// hook
		do_action('wc1c_admin_before_loading');

		$this->notices();

		if('yes' === wc1c()->settings('interface')->get('admin_interface', 'yes'))
		{
			Admin\Columns\Init::instance();
			Admin\Metaboxes\Init::instance();
		}

		add_action('admin_menu', [$this, 'addMenu'], 30);
        add_action('admin_enqueue_scripts', [$this, 'initGlobalStyles']);

		if(isset($_GET['page']) && 'wc1c' === sanitize_key(wp_unslash($_GET['page'])) && wc1c()->context()->isAdmin())
		{
			add_action('init', [$this, 'init'], 10);
			add_action('admin_enqueue_scripts', [$this, 'initStyles']);
			add_action('admin_enqueue_scripts', [$this, 'initScripts']);

			Admin\Helps\Init::instance();
			Admin\Wizards\Init::instance();
		}

		add_filter('plugin_action_links_' . wc1c()->environment()->get('plugin_basename'), [$this, 'linksLeft']);

		// hook
		do_action('wc1c_admin_after_loading');
	}

	/**
	 * Admin notices
	 *
	 * @return ManagerInterface
	 */
	public function notices(): ManagerInterface
	{
		if(empty($this->notices))
		{
            $admin = isset($_GET['page']) && 'wc1c' === sanitize_key(wp_unslash($_GET['page'])) && wc1c()->context()->isAdmin(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			$args =
			[
				'auto_save' => true,
				'admin_notices' => !$admin,
				'user_admin_notices' => false,
				'network_admin_notices' => false
			];

			$this->notices = new Manager('wc1c_admin_notices', $args);
		}

		return $this->notices;
	}

	/**
	 * Init menu
	 *
	 * @return void
	 */
	public function addMenu(): void
	{
		add_submenu_page
		(
			'woocommerce',
			__('Integration with 1C', 'wc1c-maincore'),
			__('Integration with 1C', 'wc1c-maincore'),
			'manage_woocommerce',
            'wc1c',
			[$this, 'route']
		);
	}

	/**
	 * Initialization
	 *
	 * @return void
	 */
	public function init(): void
	{
		// hook
		do_action('wc1c_admin_before_init');

		$default_sections['configurations'] =
		[
			'title' => __('Configurations', 'wc1c-maincore'),
			'visible' => true,
			'callback' => [Configurations::class, 'instance']
		];

		$default_sections['tools'] =
		[
			'title' => __('Tools', 'wc1c-maincore'),
			'visible' => true,
			'callback' => [Tools::class, 'instance']
		];

		if(current_user_can('manage_options'))
		{
			$default_sections['settings'] =
			[
				'title' => __('Settings', 'wc1c-maincore'),
				'visible' => true,
				'callback' => [Settings::class, 'instance']
			];
		}

		if(current_user_can('edit_plugins') || current_user_can('install_plugins') || current_user_can('update_plugins'))
		{
			$default_sections['extensions'] =
			[
				'title' => __('Extensions', 'wc1c-maincore'),
				'visible' => true,
				'callback' => [Extensions::class, 'instance']
			];
		}

        $default_sections['about'] =
        [
            'title' => __('About plugin', 'wc1c-maincore'),
            'visible' => true,
            'callback' => [About::class, 'instance']
        ];

		$this->initSections($default_sections);
		$this->setCurrentSection('configurations');

		// hook
		do_action('wc1c_admin_after_init');
	}

	/**
	 * Styles
	 *
	 * @return void
	 */
	public function initStyles(): void
	{
		wp_enqueue_style
		(
			'wc1c_admin_main',
			wc1c()->environment()->get('plugin_directory_url') . 'assets/css/main.min.css',
			[],
			wc1c()->environment()->get('wc1c_version')
		);
	}

    /**
     * Global styles
     *
     * @return void
     */
    public function initGlobalStyles(): void
    {
        wp_enqueue_style
        (
            'wc1c_admin_global',
            wc1c()->environment()->get('plugin_directory_url') . 'assets/css/global.min.css',
            [],
            wc1c()->environment()->get('wc1c_version'),
            true
        );
    }

	/**
	 * Scripts
	 *
	 * @return void
	 */
	public function initScripts(): void
	{
        wp_enqueue_script
        (
			'wc1c_admin_bootstrap',
            wc1c()->environment()->get('plugin_directory_url') . 'assets/js/bootstrap/bootstrap.bundle.min.js',
			[],
			wc1c()->environment()->get('wc1c_version'),
            true
        );

        wp_enqueue_script
        (
			'wc1c_admin_tocbot',
			wc1c()->environment()->get('plugin_directory_url') . 'assets/js/tocbot/tocbot.min.js',
			[],
			wc1c()->environment()->get('wc1c_version'),
            true
        );

		wp_enqueue_script
		(
			'wc1c_admin_main',
			wc1c()->environment()->get('plugin_directory_url') . 'assets/js/admin.js',
			[],
			wc1c()->environment()->get('wc1c_version'),
            true
		);
	}

	/**
	 * Route sections
	 *
	 * @return void
	 */
	public function route(): void
	{
		$sections = $this->getSections();
		$current_section = $this->initCurrentSection();

		if(!array_key_exists($current_section, $sections) || !isset($sections[$current_section]['callback']))
		{
			add_action('wc1c_admin_show', [$this, 'wrapError']);
		}
		else
		{
			if(false === get_option('wc1c_wizard', false))
			{
				add_action('wc1c_admin_show', [$this, 'wrapHeader'], 3);
			}

			add_action('wc1c_admin_show', [$this, 'wrapSections'], 7);

			$callback = $sections[$current_section]['callback'];

			if(is_callable($callback, false, $callback_name))
			{
				$callback_name();
			}
		}

		wc1c()->views()->getView('wrap.php');
	}

	/**
	 * Error
	 *
	 * @return void
	 */
	public function wrapError(): void
	{
		wc1c()->views()->getView('error.php');
	}

	/**
	 * Header
	 *
	 * @return void
	 */
	public function wrapHeader(): void
	{
		$args['url_create'] = $this->utilityAdminConfigurationsGetUrl('create');

		wc1c()->views()->getView('header.php', $args);
	}

	/**
	 * Sections
	 *
	 * @return void
	 */
	public function wrapSections(): void
	{
		wc1c()->views()->getView('sections.php');
	}

	/**
	 * Setup left links
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function linksLeft(array $links): array
	{
		return array_merge(['site' => '<a href="' . esc_url(admin_url('admin.php?page=wc1c')) . '">' . __('Dashboard', 'wc1c-maincore') . '</a>'], $links);
	}
}
