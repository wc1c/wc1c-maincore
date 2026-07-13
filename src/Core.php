<?php namespace Wc1c\Main;

defined('ABSPATH') || exit;

use wpdb;
use Wc1c\Main\Abstracts\SettingsAbstract;
use Wc1c\Main\Log\StreamHandler;
use Digiom\Woplucore\Abstracts\CoreAbstract;
use Digiom\Woplucore\Traits\SingletonTrait;
use Psr\Log\LoggerInterface;
use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Log\Formatter;
use Wc1c\Main\Log\Handler;
use Wc1c\Main\Log\Logger;
use Wc1c\Main\Log\Processor;
use Wc1c\Main\Settings\InterfaceSettings;
use Wc1c\Main\Settings\LogsSettings;
use Wc1c\Main\Settings\MainSettings;

/**
 * Core
 *
 * @package Wc1c\Main
 */
final class Core extends CoreAbstract
{
	use SingletonTrait;

	/**
	 * @var array
	 */
	private $log = [];

	/**
	 * @var Timer
	 */
	private $timer;

    /**
     * @var Transliterator
     */
    private $transliterator;

	/**
	 * @var SettingsAbstract[]
	 */
	private $settings = [];

	/**
	 * @var Receiver
	 */
	private $receiver;

	/**
	 * Core constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		do_action('wc1c_core_loaded');
	}

	/**
	 * Initialization
	 */
	public function init()
	{
		// hook
		do_action($this->context()->getSlug() . '_before_init');

		$this->localization();

		try
		{
			$this->timer();
		}
		catch(\Throwable $e)
		{
			wc1c()->log()->emergency(__('Timer is not loaded. Further execution of algorithms without a timer is impossible.', 'wc1c-main'), ['exception' => $e]);
			return;
		}

		try
		{
			$this->extensions()->load();
		}
		catch(\Throwable $e)
		{
			wc1c()->log()->alert(__('Extensions is not loaded. Execution continued, but without all plugins.', 'wc1c-main'), ['exception' => $e]);
		}

		try
		{
			$this->extensions()->init();
		}
		catch(\Throwable $e)
		{
			wc1c()->log()->alert(__('Extensions is not initialized.', 'wc1c-main'), ['exception' => $e]);
		}

		try
		{
			$this->schemas()->load();
		}
		catch(\Throwable $e)
		{
			wc1c()->log()->alert(__('Schemas is not loaded.', 'wc1c-main'), ['exception' => $e]);
		}

		try
		{
			$this->tools()->load();
		}
		catch(\Throwable $e)
		{
			wc1c()->log()->alert(__('Tools is not loaded.', 'wc1c-main'), ['exception' => $e]);
		}

		if(false !== wc1c()->context()->isReceiver() || false !== wc1c()->context()->isAdmin())
		{
			try
			{
				$this->tools()->init();
			}
			catch(\Throwable $e)
			{
				wc1c()->log()->alert(__('Tools is not initialized.', 'wc1c-main'), ['exception' => $e]);
			}
		}

		if(false !== wc1c()->context()->isReceiver())
		{
			try
			{
				$this->loadReceiver();
			}
			catch(\Throwable $e)
			{
				wc1c()->log()->alert(__('Receiver is not loaded.', 'wc1c-main'), ['exception' => $e]);
			}
		}

		// hook
		do_action($this->context()->getSlug() . '_after_init');
	}

	/**
	 * Extensions
	 *
	 * @return Extensions\Core
	 */
	public function extensions(): Extensions\Core
	{
		return Extensions\Core::instance();
	}

    /**
     * Transliterator
     *
     * @return Transliterator
     */
    public function transliterator(): Transliterator
    {
        if(is_null($this->transliterator))
        {
            $this->transliterator = new Transliterator();
        }

        return $this->transliterator;
    }

	/**
	 * Filesystem
	 *
	 * @return Filesystem
	 */
	public function filesystem(): Filesystem
	{
		return Filesystem::instance();
	}

	/**
	 * Schemas
	 *
	 * @return Schemas\Core
	 */
	public function schemas(): Schemas\Core
	{
		return Schemas\Core::instance();
	}

	/**
	 * Environment
	 *
	 * @return Environment
	 */
	public function environment(): Environment
	{
		return Environment::instance();
	}

	/**
	 * Views
	 *
	 * @return Views
	 */
	public function views(): Views
	{
		return Views::instance()->setSlug('wc1c-main')->setPluginDir($this->environment()->get('plugin_directory_path'));
	}

	/**
	 * Tools
	 *
	 * @return Tools\Core
	 */
	public function tools(): Tools\Core
	{
		return Tools\Core::instance();
	}

    /**
     * Logger
     *
     * @param string $channel
     * @param string $name
     * @param array $params
     * @return LoggerInterface
     */
	public function log(string $channel = 'main', string $name = '', array $params = [])
	{
		$channel = strtolower($channel);

		if(!isset($this->log[$channel]))
		{
            $default_params =
            [
                'hard_level' => null,
                'files_max' => null
            ];

            $params = array_merge($default_params, $params);

			if('' === $name)
			{
				$name = $channel;
			}

			$path = '';
			$max_files = $this->settings('logs')->get('logger_files_max', 30);

			$logger = new Logger($channel);

			switch($channel)
			{
				case 'tools':
					$path = $this->environment()->get('wc1c_tools_logs_directory') . '/' . $name . '.log';
					$level = $this->settings('logs')->get('logger_tools_level', 'logger_level');
					break;
				case 'schemas':
					$path = $this->environment()->get('wc1c_schemas_logs_directory') . '/' . $name . '.log';
					$level = $this->settings('logs')->get('logger_schemas_level', 'logger_level');
					break;
				case 'configurations':
					$path = $name . '.log';
					$level = $this->settings('logs')->get('logger_configurations_level', 'logger_level');
					break;
				default:
					$level = $this->settings('logs')->get('logger_level', 250);
			}

			if('logger_level' === $level)
			{
				$level = $this->settings('logs')->get('logger_level', 250);
			}

			if(!is_null($params['hard_level']))
			{
				$level = $params['hard_level'];
			}

            if(!is_null($params['files_max']))
            {
                $max_files = $params['files_max'];
            }

			if('' === $path)
			{
				$path = $this->environment()->get('wc1c_logs_directory') . '/main.log';
			}

			try
			{
				$uid_processor = new Processor();
				$formatter = new Formatter();

				$handler = new Handler($path, $max_files, $level);

				$handler->setFormatter($formatter);

				$logger->pushProcessor($uid_processor);

				$logger->pushHandler($handler);

				if('yes' === $this->settings('logs')->get('logger_output', 'no'))
				{
					$logger->pushHandler(new StreamHandler('php://output', $level));
				}
			}
			catch(\Throwable $e){}

			/**
			 * Внешние назначения для логгера
			 *
			 * @param LoggerInterface $logger Текущий логгер
			 *
			 * @return LoggerInterface
			 */
			if(has_filter('wc1c_log_load_before'))
			{
				$logger = apply_filters('wc1c_log_load_before', $logger);
			}

			$this->log[$channel] = $logger;
		}

		return $this->log[$channel];
	}

	/**
	 * Settings
	 *
	 * @param string $context
	 *
	 * @return SettingsAbstract
	 */
	public function settings(string $context = 'main')
	{
		if(!isset($this->settings[$context]))
		{
			switch($context)
			{
				case 'logs':
					$class = LogsSettings::class;
					break;
				case 'interface':
					$class = InterfaceSettings::class;
					break;
				default:
					$class = MainSettings::class;
			}

			$settings = new $class();

			try
			{
				$settings->init();
			}
			catch(\Throwable $e)
			{
				wc1c()->log()->error($e->getMessage(), ['exception' => $e]);
			}

			$this->settings[$context] = $settings;
		}

		return $this->settings[$context];
	}

	/**
	 * Timer
	 *
	 * @return Timer
	 */
	public function timer(): Timer
	{
		if(is_null($this->timer))
		{
			$timer = new Timer();

			$php_max_execution = $this->environment()->get('php_max_execution_time', 20);

			if($php_max_execution !== $this->settings()->get('php_max_execution_time', $php_max_execution))
			{
				$php_max_execution = $this->settings()->get('php_max_execution_time', $php_max_execution);
			}

			$timer->setMaximum($php_max_execution);

			$this->timer = $timer;
		}

		return $this->timer;
	}

	/**
	 * Get Receiver
	 *
	 * @return Receiver
	 */
	public function receiver(): Receiver
	{
		return $this->receiver;
	}

	/**
	 * Set Receiver
	 *
	 * @param Receiver $receiver
	 */
	public function setReceiver(Receiver $receiver)
	{
		$this->receiver = $receiver;
	}

	/**
	 * Receiver loading
	 *
	 * @return void
	 * @throws Exception
	 */
	private function loadReceiver()
	{
        wc1c()->log()->debug(__('Receiver loading.', 'wc1c-main'));

		$default_class_name = Receiver::class;

		$use_class_name = apply_filters(wc1c()->context()->getSlug() . '_receiver_loading_class_name', $default_class_name);

		if(false === class_exists($use_class_name))
		{
			wc1c()->log()->notice(__('Receiver loading: class is not exists, use is default.', 'wc1c-main'), ['context' => $use_class_name]);

            $use_class_name = $default_class_name;
		}

		$receiver = new $use_class_name();

		$receiver->register();

		$this->setReceiver($receiver);

        wc1c()->log()->debug(__('Receiver loading is completed.', 'wc1c-main'), ['class' => $use_class_name]);
	}

	/**
	 * Load localisation
	 */
	public function localization()
	{
        wc1c()->log()->debug(__('Localization loading.', 'default'));

		$locale = determine_locale();

		if(has_filter('plugin_locale'))
		{
			$locale = apply_filters('plugin_locale', $locale, 'wc1c-main');
		}

		load_textdomain('wc1c-main', WP_LANG_DIR . '/plugins/wc1c-main-' . $locale . '.mo');
		load_textdomain('wc1c-main', wc1c()->environment()->get('plugin_directory_path') . 'assets/languages/wc1c-main-' . $locale . '.mo');

		wc1c()->log()->debug(__('Localization loaded.', 'wc1c-main'), ['locale' => $locale]);
	}

	/**
	 * Use in plugin for DB queries
	 *
	 * @return wpdb
	 */
	public function database(): wpdb
	{
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Main instance of Admin
	 *
	 * @return Admin
	 */
	public function admin(): Admin
	{
		return Admin::instance();
	}

	/**
	 * Get data if set, otherwise return a default value or null
	 * Prevents notices when data is not set
	 *
	 * @param mixed $var variable
	 * @param mixed $default default value
	 *
	 * @return mixed
	 */
	public function getVar(&$var, $default = null)
	{
		return $var ?? $default;
	}

	/**
	 * Define constant if not already set
	 *
	 * @param string $name constant name
	 * @param string|bool $value constant value
	 */
	public function define(string $name, $value)
	{
		if(!defined($name))
		{
			define($name, $value);
		}
	}
}